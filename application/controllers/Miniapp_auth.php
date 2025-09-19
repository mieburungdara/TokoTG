<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Miniapp_auth extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index($bot_id = NULL)
    {
        // This page contains the JS to perform the auth
        $data['bot_id'] = $bot_id;
        $this->load->view('miniapp_auth/index', $data);
    }

    public function dashboard()
    {
        // Protect this page, only logged-in users can see it
        if (!$this->session->userdata('logged_in')) {
            redirect('miniapp_auth/invalid_access');
            return;
        }

        // Set page title and load the view within the template
        $this->dm->title = 'Member Dashboard';
        $data['first_name'] = $this->session->userdata('first_name');
        $this->render('miniapp_auth/dashboard', $data);
    }

    public function invalid_access()
    {
        // Set page title and load the view within the template
        $this->dm->title = 'Invalid Access';
        $this->render('miniapp_auth/invalid');
    }
}
