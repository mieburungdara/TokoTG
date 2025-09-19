<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TelegramMessageModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log a message/update to the database.
     *
     * @param array $data The prepared data to insert into the 'messages' table.
     * @return int The insert ID.
     */
    public function log_message(array $data): ?int
    {
        if ($this->db->insert('messages', $data)) {
            return $this->db->insert_id();
        }
        return null;
    }
}
