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

        $telegram_id = $user_data['id'];

        // Check if user exists in our 'users' table
        $this->db->where('user_id', $telegram_id);
        $query = $this->db->get('users');
        $existing_user = $query->row_array();

        // Prepare data from Telegram to be inserted or updated
        $data_to_upsert = [
            'first_name'    => $user_data['first_name'] ?? '',
            'last_name'     => $user_data['last_name'] ?? null,
            'username'      => $user_data['username'] ?? null,
            'language_code' => $user_data['language_code'] ?? null,
        ];

        if ($existing_user) {
            // User found, update their data
            $this->db->where('user_id', $telegram_id);
            $this->db->update('users', $data_to_upsert);
        } else {
            // User not found, create a new user
            $data_to_insert = array_merge(
                ['user_id' => $telegram_id],
                $data_to_upsert
            );
            $this->db->insert('users', $data_to_insert);
        }

        // Return the final user record from our database
        $this->db->where('user_id', $telegram_id);
        return $this->db->get('users')->row_array();
    }

    /**
     * Get all users.
     * @return array An array of user records.
     */
    public function get_all_users()
    {
        $query = $this->db->get('users');
        return $query->result_array();
    }

    /**
     * Get a user by their internal auto-increment ID.
     * @param int $id The internal ID.
     * @return array|null The user record or null if not found.
     */
    public function get_user_by_id($id)
    {
        $query = $this->db->get_where('users', array('id' => $id));
        return $query->row_array();
    }

    /**
     * Update user information by their internal auto-increment ID.
     * @param int $id The internal user ID.
     * @param array $data The data to update.
     * @return bool True on success, false on failure.
     */
    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Check if a user has a specific role.
     * @param int $user_id The user's Telegram ID.
     * @param string $role_name The name of the role to check.
     * @return bool True if the user has the role, false otherwise.
     */
    public function has_role($user_id, $role_name)
    {
        $this->db->select('1');
        $this->db->from('user_roles ur');
        $this->db->join('roles r', 'ur.role_id = r.id');
        $this->db->where('ur.user_id', $user_id);
        $this->db->where('r.name', $role_name);
        $query = $this->db->get();

        return $query->num_rows() > 0;
    }
}
