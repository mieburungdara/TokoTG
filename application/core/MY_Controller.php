<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $dm;

    public function __construct()
    {
        parent::__construct();

        // Load the template library and config
        $this->load->library('template');
        $this->config->load('template');

        // Create the $dm object
        $this->dm = new Template(
            $this->config->item('dm_name'),
            $this->config->item('dm_version'),
            $this->config->item('dm_assets_folder')
        );

        // Set the properties of the $dm object from the config file
        $this->dm->author = $this->config->item('dm_author');
        $this->dm->robots = $this->config->item('dm_robots');
        $this->dm->title = $this->config->item('dm_title');
        $this->dm->description = $this->config->item('dm_description');
        $this->dm->og_url_site = $this->config->item('dm_og_url_site');
        $this->dm->og_url_image = $this->config->item('dm_og_url_image');
        $this->dm->theme = $this->config->item('dm_theme');
        $this->dm->page_loader = $this->config->item('dm_page_loader');
        $this->dm->remember_theme = $this->config->item('dm_remember_theme');
        $this->dm->l_sidebar_left = $this->config->item('dm_l_sidebar_left');
        $this->dm->l_sidebar_mini = $this->config->item('dm_l_sidebar_mini');
        $this->dm->l_sidebar_visible_desktop = $this->config->item('dm_l_sidebar_visible_desktop');
        $this->dm->l_sidebar_visible_mobile = $this->config->item('dm_l_sidebar_visible_mobile');
        $this->dm->l_sidebar_dark = $this->config->item('dm_l_sidebar_dark');
        $this->dm->l_side_overlay_hoverable = $this->config->item('dm_l_side_overlay_hoverable');
        $this->dm->l_side_overlay_visible = $this->config->item('dm_l_side_overlay_visible');
        $this->dm->l_page_overlay = $this->config->item('dm_l_page_overlay');
        $this->dm->l_side_scroll = $this->config->item('dm_l_side_scroll');
        $this->dm->l_header_fixed = $this->config->item('dm_l_header_fixed');
        $this->dm->l_header_style = $this->config->item('dm_l_header_style');
        $this->dm->l_footer_fixed = $this->config->item('dm_l_footer_fixed');
        $this->dm->l_m_content = $this->config->item('dm_l_m_content');
        $this->dm->main_nav_active = $this->config->item('dm_main_nav_active');
        $this->dm->main_nav = $this->config->item('dm_main_nav');
        $this->dm->inc_side_overlay = $this->config->item('dm_inc_side_overlay');
        $this->dm->inc_sidebar = $this->config->item('dm_inc_sidebar');
        $this->dm->inc_header = $this->config->item('dm_inc_header');
        $this->dm->inc_footer = $this->config->item('dm_inc_footer');
    }

    public function render($view, $data = array())
    {
        $data['dm'] = $this->dm;
        $data['main_content'] = $this->load->view($view, $data, TRUE);
        $this->load->view('backend_template', $data);
    }
}
