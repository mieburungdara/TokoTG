<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin_login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Superadmin_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->session->userdata('superadmin_logged_in'))
        {
            redirect('superadmin/dashboard');
        }

        $this->load->view('superadmin/login');
    }

    public function authenticate()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $superadmin = $this->Superadmin_model->get_superadmin_by_username($username);

        if ($superadmin && $this->Superadmin_model->verify_password($password, $superadmin->password))
        {
            $this->session->set_userdata('superadmin_logged_in', TRUE);
            $this->session->set_userdata('superadmin_username', $superadmin->username);
            redirect('superadmin/dashboard');
        }
        else
        {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('superadmin_login');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('superadmin_logged_in');
        $this->session->unset_userdata('superadmin_username');
        $this->session->sess_destroy();
        redirect('superadmin_login');
    }

}
