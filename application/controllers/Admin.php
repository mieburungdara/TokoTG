<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');

        // Protect all methods in this controller
        $this->_check_admin_privileges();
    }

    /**
     * Checks if the current user is an admin. If not, shows an error and exits.
     */
    private function _check_admin_privileges()
    {
        $user_id = $this->session->userdata('user_id');

        if (!$user_id || !$this->User_model->has_role($user_id, 'admin')) {
            // You can create a more elaborate "Access Denied" view
            show_error('You do not have permission to access this page.', 403, 'Forbidden Access');
            exit;
        }
    }

    /**
     * Default method, shows the add balance page.
     */
    public function index()
    {
        $this->add_balance();
    }

    /**
     * Renders the page for adding balance to a user.
     */
    public function add_balance()
    {
        $this->dm->title = 'Admin: Add Balance';
        $this->render('admin/add_balance');
    }
}
