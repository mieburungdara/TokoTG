<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_superadmin_by_username($username)
    {
        $query = $this->db->get_where('superadmin', array('username' => $username));
        return $query->row();
    }

    public function verify_password($password, $hashed_password)
    {
        return password_verify($password, $hashed_password);
    }

}
