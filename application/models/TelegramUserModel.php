<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TelegramUserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Save or update a user based on their user_id.
     *
     * @param array $user_data User data from Telegram object.
     * @return string 'inserted', 'updated', or 'no_change'
     */
    public function save_user(array $user_data) {
        // Prepare data for insertion/update
        $data = [
            'user_id'       => $user_data['id'],
            'username'      => isset($user_data['username']) ? $user_data['username'] : null,
            'first_name'    => isset($user_data['first_name']) ? $user_data['first_name'] : '',
            'last_name'     => isset($user_data['last_name']) ? $user_data['last_name'] : null,
            'language_code' => isset($user_data['language_code']) ? $user_data['language_code'] : null,
        ];

        // Build the ON DUPLICATE KEY UPDATE part of the query
        $update_fields = [];
        foreach ($data as $key => $value) {
            // Don't update user_id on duplicate
            if ($key === 'user_id') continue;
            $update_fields[] = $this->db->protect_identifiers($key) . ' = ' . $this->db->escape($value);
        }
        $update_string = implode(', ', $update_fields);

        // Build the full query
        $sql = $this->db->insert_string('users', $data) . ' ON DUPLICATE KEY UPDATE ' . $update_string;

        // Execute the query
        $this->db->query($sql);

        // Check if a new row was inserted or an existing one was updated
        // MySQL with ON DUPLICATE KEY UPDATE returns:
        // 1 for a new row insert
        // 2 for an update of an existing row
        // 0 if an existing row was not updated (all values were the same)
        $affected_rows = $this->db->affected_rows();

        if ($affected_rows === 1) {
            return 'inserted';
        } elseif ($affected_rows === 2) {
            return 'updated';
        }
        
        return 'no_change';
    }
}
