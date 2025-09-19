<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller extends MY_Controller {

    private $seller_info;

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');

        // Protect all methods in this controller
        $this->_check_seller_privileges();
    }

    /**
     * Checks if the current user is a seller. If not, shows an error and exits.
     * Also stores the seller's profile data.
     */
    private function _check_seller_privileges()
    {
        $user_id = $this->session->userdata('user_id');

        // 1. Must be logged in
        if (!$user_id) {
            show_error('You must be logged in to access this page.', 401, 'Unauthorized Access');
            exit;
        }

        // 2. Must have the 'seller' role
        if (!$this->User_model->has_role($user_id, 'seller')) {
            show_error('You do not have permission to access this page. Become a seller to continue.', 403, 'Forbidden Access');
            exit;
        }

        // 3. Get and store seller profile for later use
        $this->seller_info = $this->User_model->get_seller_by_user_id($user_id);
        if (!$this->seller_info) {
            show_error('Could not find your seller profile. Please contact support.', 500, 'Internal Error');
            exit;
        }
    }

    /**
     * Default method, shows the seller's product list.
     */
    public function index()
    {
        $this->products();
    }

    /**
     * Renders the page for managing seller's products.
     */
    public function products()
    {
        $seller_id = $this->seller_info['id'];
        $data['products'] = $this->Product_model->get_products_by_seller($seller_id);
        
        $this->dm->title = 'My Products';
        $this->render('seller/products_index', $data);
    }

    /**
     * Renders the page for adding a new product.
     */
    public function add_product()
    {
        $this->dm->title = 'Add New Product';
        $this->render('seller/add_product');
    }

    /**
     * Renders the page for editing an existing product.
     */
    public function edit_product($product_id = null)
    {
        if (empty($product_id)) {
            show_404();
        }

        $product = $this->Product_model->get_product_by_id($product_id);

        // Ensure product exists and belongs to the logged-in seller
        if (!$product || $product['seller_id'] != $this->seller_info['id']) {
            show_error('You do not have permission to edit this product.', 403, 'Forbidden Access');
            exit;
        }

        $data['product'] = $product;
        $this->dm->title = 'Edit Product';
        $this->render('seller/edit_product', $data);
    }
}
