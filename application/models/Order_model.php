<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Product_model');
    }

    /**
     * Creates a new order in the database.
     * This method uses a transaction to ensure data integrity.
     *
     * @param int $user_id The ID of the user placing the order.
     * @param array $cart_items An array of items in the cart, e.g., [['product_id' => 1, 'quantity' => 2], ...]
     * @param string $payment_method The payment method chosen.
     * @return int|false The new order ID on success, or false on failure.
     */
    public function create_order($user_id, $cart_items, $payment_method)
    {
        // 1. Recalculate total amount on the backend to ensure price integrity
        $total_amount = 0;
        $order_items_data = [];

        foreach ($cart_items as $item) {
            $product = $this->Product_model->get_product_by_id($item['product_id']);
            if (!$product) {
                return false; // Or throw an exception if a product is not found
            }
            $price_at_purchase = (float) $product['price'];
            $total_amount += $price_at_purchase * $item['quantity'];
            
            $order_items_data[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_at_purchase' => $price_at_purchase
            ];
        }

        // 2. Start a database transaction
        $this->db->trans_start();

        // 3. Insert into 'orders' table
        $order_data = [
            'user_id' => $user_id,
            'status' => 'pending', // Default status for a new order
            'total_amount' => $total_amount,
            'payment_method' => $payment_method
        ];
        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        // 4. Insert into 'order_items' table
        foreach ($order_items_data as &$item_data) {
            $item_data['order_id'] = $order_id;
        }
        $this->db->insert_batch('order_items', $order_items_data);

        // 5. Complete the transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Transaction failed
            return false;
        }

        return $order_id;
    }

    /**
     * Get all orders for a specific user.
     * @param int $user_id The user's Telegram ID.
     * @return array An array of order records.
     */
    public function get_orders_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('orders');
        return $query->result_array();
    }
}
