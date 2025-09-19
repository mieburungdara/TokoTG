<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_bots()
    {
        $query = $this->db->get('bots');
        return $query->result_array();
    }

    public function get_bots_by_mode($mode)
    {
        $query = $this->db->get_where('bots', array('mode' => $mode));
        return $query->result_array();
    }

    public function get_bot_by_username($username)
    {
        $query = $this->db->get_where('bots', array('username' => $username));
        return $query->row_array();
    }

    public function add_bot($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('bots', $data);
    }

    public function update_bot($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('bots', $data);
    }

    public function delete_bot($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('bots');
    }

}
