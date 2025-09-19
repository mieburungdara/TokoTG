<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Miniapp extends MY_Controller {

    public function index()
    {
        $data['is_miniapp_page'] = true;
        $this->render('miniapp', $data);
    }

    public function get_miniapp_content()
    {
        $this->load->view('miniapp');
    }

    /**
     * Renders the marketplace page for the Mini App.
     */
    public function marketplace()
    {
        $data['is_miniapp_page'] = true;
        $this->render('marketplace/index', $data);
    }
}
