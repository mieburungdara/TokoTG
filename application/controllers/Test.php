<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {

    public function index()
    {
        // Set specific page variables
        $this->dm->title = 'Test Page | ' . $this->config->item('dm_name');
        $this->dm->main_nav_active = 'be_pages_generic_blank'; // Assuming a link to a generic page for highlighting

        $this->render('test');
    }
}
