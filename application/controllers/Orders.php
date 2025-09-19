<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');

        // Protect this page, only logged-in users can see it
        if (!$this->session->userdata('logged_in')) {
            // You can redirect to a login page or show an error
            show_error('You must be logged in to view your orders.', 401, 'Unauthorized Access');
            exit;
        }
    }

    /**
     * Display the user's order history.
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $data['orders'] = $this->Order_model->get_orders_by_user($user_id);
        
        $this->dm->title = 'My Orders';
        $this->render('orders/index', $data);
    }
}
