<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Finds a user by their Telegram ID, or creates a new one if not found.
     * @param array $user_data The user data object from Telegram's initData
     * @return array The user record from the database
     */
    public function find_or_create_user($user_data) {
        if (!isset($user_data['id'])) {
            return null; // Or handle error appropriately
        }

        $id = $user_data['id'];

        $query = $this->db->get_where('user', array('id' => $id));
        $user = $query->row_array();

        if ($user) {
            // User found, return user data
            return $user;
        } else {
            // User not found, create a new user
            $new_user_data = array(
                'id' => $id,
                'first_name'  => isset($user_data['first_name']) ? $user_data['first_name'] : '',
                'last_name'   => isset($user_data['last_name']) ? $user_data['last_name'] : null,
                'username'    => isset($user_data['username']) ? $user_data['username'] : null,
                'chat_id'     => isset($user_data['chat_id']) ? $user_data['chat_id'] : null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            );

            $this->db->insert('user', $new_user_data);

            // Return the newly created user's data
            $query = $this->db->get_where('user', array('id' => $id));
            return $query->row_array();
        }
    }

    /**
     * Get all users.
     * @return array An array of user records.
     */
    public function get_all_users()
    {
        $query = $this->db->get('user');
        return $query->result_array();
    }

    /**
     * Get a user by ID.
     * @param int $id The user ID.
     * @return array|null The user record or null if not found.
     */
    public function get_user_by_id($id)
    {
        $query = $this->db->get_where('user', array('id' => $id));
        return $query->row_array();
    }

    /**
     * Update user information.
     * @param int $id The user ID.
     * @param array $data The data to update.
     * @return bool True on success, false on failure.
     */
    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
    }
}
