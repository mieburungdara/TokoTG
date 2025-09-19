<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');

        // Check if superadmin is logged in
        if (!$this->session->userdata('superadmin_logged_in'))
        {
            redirect('superadmin_login');
        }
    }

    public function index()
    {
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('superadmin/user/list', $data);
    }
}
