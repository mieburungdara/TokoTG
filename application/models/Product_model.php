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

    /**
     * Get all products for a specific seller.
     * @param int $seller_id The seller's ID from the 'sellers' table.
     * @return array An array of product records.
     */
    public function get_products_by_seller($seller_id)
    {
        $this->db->where('seller_id', $seller_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('products');
        return $query->result_array();
    }

    /**
     * Adds a new product for a specific seller.
     * @param int $seller_id The seller's ID.
     * @param array $product_data The product data to insert.
     * @return int|false The new product ID on success, or false on failure.
     */
    public function add_product($seller_id, $product_data)
    {
        // Combine seller_id with the rest of the product data
        $data_to_insert = array_merge(
            ['seller_id' => $seller_id],
            $product_data
        );

        if ($this->db->insert('products', $data_to_insert)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Updates a product for a specific seller.
     * @param int $product_id The ID of the product to update.
     * @param int $seller_id The ID of the seller who owns the product.
     * @param array $product_data The data to update.
     * @return bool True on success, false on failure.
     */
    public function update_product($product_id, $seller_id, $product_data)
    {
        $this->db->where('id', $product_id);
        $this->db->where('seller_id', $seller_id);
        return $this->db->update('products', $product_data);
    }

    /**
     * Deletes a product for a specific seller.
     * @param int $product_id The ID of the product to delete.
     * @param int $seller_id The ID of the seller who owns the product.
     * @return bool True if a row was deleted, false otherwise.
     */
    public function delete_product($product_id, $seller_id)
    {
        $this->db->where('id', $product_id);
        $this->db->where('seller_id', $seller_id);
        $this->db->delete('products');
        return $this->db->affected_rows() > 0;
    }
}
