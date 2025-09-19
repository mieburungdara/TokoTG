<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Order_model');
        $this->load->model('Balance_model');
        $this->load->model('User_model');
        $this->load->library('session');
        
        // Set CORS headers to allow requests from any origin
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    /**
     * Handle OPTIONS requests for CORS preflight
     */
    public function _handle_options()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }

    /**
     * API endpoint to get all active products.
     */
    public function products()
    {
        $this->_handle_options(); // Handle CORS preflight

        $products = $this->Product_model->get_active_products();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($products));
    }

    /**
     * API endpoint to get a single product by ID.
     * Example URL: /api/product/123
     */
    public function product($product_id = null)
    {
        $this->_handle_options(); // Handle CORS preflight

        if (empty($product_id)) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Product ID is required.']));
            return;
        }

        $product = $this->Product_model->get_product_by_id($product_id);

        if ($product) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($product));
        } else {
            $this->output->set_status_header(404)->set_output(json_encode(['error' => 'Product not found.']));
        }
    }

    /**
     * API endpoint to handle the checkout process.
     */
    public function checkout()
    {
        $this->_handle_options(); // Handle CORS preflight

        // This endpoint only accepts POST requests
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->output->set_status_header(405)->set_output(json_encode(['error' => 'Method Not Allowed']));
            return;
        }

        // 1. Check for user session
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->output->set_status_header(401)->set_output(json_encode(['error' => 'Unauthorized: User not logged in.']));
            return;
        }

        // 2. Get and decode POST data
        $request_body = file_get_contents('php://input');
        $request_data = json_decode($request_body, true);

        $cart_items = $request_data['cart'] ?? [];
        $payment_method = $request_data['payment_method'] ?? 'unknown';

        // 3. Validate input
        if (empty($cart_items) || !is_array($cart_items)) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Bad Request: Cart data is missing or invalid.']));
            return;
        }

        // 4. Call the model to create the order
        $order_id = $this->Order_model->create_order($user_id, $cart_items, $payment_method);

        // 5. Respond
        if ($order_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'order_id' => $order_id]));
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Failed to create order.']));
        }
    }

    /**
     * API endpoint to get the current user's balance.
     */
    public function balance()
    {
        $this->_handle_options(); // Handle CORS preflight

        // This endpoint only accepts GET requests
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->output->set_status_header(405)->set_output(json_encode(['error' => 'Method Not Allowed']));
            return;
        }

        // 1. Check for user session
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->output->set_status_header(401)->set_output(json_encode(['error' => 'Unauthorized: User not logged in.']));
            return;
        }

        // 2. Get balance from the model
        $balance_data = $this->Balance_model->get_user_balance($user_id);

        // 3. Respond
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($balance_data));
    }

    /**
     * ADMIN-ONLY endpoint to add balance to a user.
     */
    public function admin_add_balance()
    {
        $this->_handle_options(); // Handle CORS preflight

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->output->set_status_header(405)->set_output(json_encode(['error' => 'Method Not Allowed']));
            return;
        }

        // 1. Check for admin session
        $admin_user_id = $this->session->userdata('user_id');
        if (!$admin_user_id) {
            $this->output->set_status_header(401)->set_output(json_encode(['error' => 'Unauthorized: You are not logged in.']));
            return;
        }

        // 2. Authorize: Check if the logged-in user is an admin
        if (!$this->User_model->has_role($admin_user_id, 'admin')) {
            $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Forbidden: You do not have admin privileges.']));
            return;
        }

        // 3. Get and decode POST data
        $request_body = file_get_contents('php://input');
        $request_data = json_decode($request_body, true);

        $target_user_id = $request_data['target_user_id'] ?? null;
        $amount = $request_data['amount'] ?? 0;
        $description = $request_data['description'] ?? 'Deposit by admin #' . $admin_user_id;

        // 4. Validate input
        if (empty($target_user_id) || !is_numeric($target_user_id) || !is_numeric($amount) || $amount <= 0) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Bad Request: Invalid target_user_id or amount.']));
            return;
        }

        // 5. Call the model to add the transaction
        $success = $this->Balance_model->add_transaction($target_user_id, 'deposit', $amount, $admin_user_id, $description);

        // 6. Respond
        if ($success) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Balance added successfully.']));
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Failed to add balance.']));
        }
    }

    /**
     * SELLER-ONLY endpoint to create a new product.
     */
    public function create_product()
    {
        $this->_handle_options(); // Handle CORS preflight

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->output->set_status_header(405)->set_output(json_encode(['error' => 'Method Not Allowed']));
            return;
        }

        // 1. Check for user session
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $this->output->set_status_header(401)->set_output(json_encode(['error' => 'Unauthorized: You are not logged in.']));
            return;
        }

        // 2. Authorize: Check if the logged-in user is a seller
        if (!$this->User_model->has_role($user_id, 'seller')) {
            $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Forbidden: You are not a seller.']));
            return;
        }

        // 3. Get seller profile
        $seller = $this->User_model->get_seller_by_user_id($user_id);
        if (!$seller) {
            $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Forbidden: Seller profile not found.']));
            return;
        }
        $seller_id = $seller['id'];

        // 4. Get and decode POST data
        $request_body = file_get_contents('php://input');
        $product_data = json_decode($request_body, true);

        // 5. Basic validation
        if (empty($product_data['name']) || empty($product_data['product_code']) || !isset($product_data['price']) || !isset($product_data['stock_quantity'])) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Bad Request: Missing required product fields.']));
            return;
        }
        
        // 6. Call the model to create the product
        $product_id = $this->Product_model->add_product($seller_id, $product_data);

        // 7. Respond
        if ($product_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'product_id' => $product_id]));
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Failed to create product.']));
        }
    }

    /**
     * SELLER-ONLY endpoint to update an existing product.
     */
    public function update_product($product_id = null)
    {
        $this->_handle_options(); // Handle CORS preflight

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->output->set_status_header(405)->set_output(json_encode(['error' => 'Method Not Allowed']));
            return;
        }

        // 1. Auth and Authorization
        $user_id = $this->session->userdata('user_id');
        if (!$user_id || !$this->User_model->has_role($user_id, 'seller')) {
            $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Forbidden']));
            return;
        }

        // 2. Get Seller Profile
        $seller = $this->User_model->get_seller_by_user_id($user_id);
        if (!$seller) {
            $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Forbidden: Seller profile not found.']));
            return;
        }
        $seller_id = $seller['id'];

        // 3. Get and decode POST data
        $request_body = file_get_contents('php://input');
        $product_data = json_decode($request_body, true);

        // 4. Basic validation
        if (empty($product_id) || empty($product_data['name']) || !isset($product_data['price']) || !isset($product_data['stock_quantity'])) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Bad Request: Missing required fields.']));
            return;
        }

        // 5. Call the model to update the product (crucially, passing seller_id for ownership check)
        $success = $this->Product_model->update_product($product_id, $seller_id, $product_data);

        // 6. Respond
        if ($success) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Product updated successfully.']));
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Failed to update product. It might not exist or you may not be the owner.']));
        }
    }

    /**
     * SELLER-ONLY endpoint to delete a product.
     */
    public function delete_product($product_id = null)
    {
        $this->_handle_options(); // Handle CORS preflight

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->output->set_status_header(405)->set_output(json_encode(['error' => 'Method Not Allowed']));
            return;
        }

        // 1. Auth and Authorization
        $user_id = $this->session->userdata('user_id');
        if (!$user_id || !$this->User_model->has_role($user_id, 'seller')) {
            $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Forbidden']));
            return;
        }

        // 2. Get Seller Profile
        $seller = $this->User_model->get_seller_by_user_id($user_id);
        if (!$seller) {
            $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Forbidden: Seller profile not found.']));
            return;
        }
        $seller_id = $seller['id'];

        // 3. Basic validation
        if (empty($product_id)) {
            $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Bad Request: Missing product ID.']));
            return;
        }

        // 4. Call the model to delete the product (crucially, passing seller_id for ownership check)
        $success = $this->Product_model->delete_product($product_id, $seller_id);

        // 5. Respond
        if ($success) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']));
        } else {
            $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Failed to delete product. It might not exist or you may not be the owner.']));
        }
    }
}
