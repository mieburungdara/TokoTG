<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all active products.
     * @return array An array of active product records.
     */
    public function get_active_products()
    {
        $this->db->where('is_active', 1);
        $query = $this->db->get('products');
        return $query->result_array();
    }

    /**
     * Get a single product by its ID.
     * @param int $product_id The product ID.
     * @return array|null The product record or null if not found.
     */
    public function get_product_by_id($product_id)
    {
        $query = $this->db->get_where('products', array('id' => $product_id, 'is_active' => 1));
        return $query->row_array();
    }
}
