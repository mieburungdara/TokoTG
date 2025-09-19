<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_message($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('messages', $data);
    }

    public function get_messages_by_user_id($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('messages');
        return $query->result_array();
    }

    public function get_message_by_telegram_message_id($telegram_message_id)
    {
        $this->db->where('telegram_message_id', $telegram_message_id);
        $query = $this->db->get('messages');
        return $query->row_array();
    }

}
