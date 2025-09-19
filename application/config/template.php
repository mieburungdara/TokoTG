<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Template Configuration
| -------------------------------------------------------------------|
| This file will contain the settings for the Dashmix template.
|
*/

$config['dm_name'] = 'Dashmix';
$config['dm_version'] = '5.10';
$config['dm_assets_folder'] = 'assets';
$config['dm_author'] = 'pixelcave';
$config['dm_robots'] = 'index, follow';
$config['dm_title'] = 'Dashmix - Bootstrap 5 Admin Template & UI Framework';
$config['dm_description'] = 'Dashmix - Bootstrap 5 Admin Template & UI Framework created by pixelcave';
$config['dm_og_url_site'] = '';
$config['dm_og_url_image'] = '';
$config['dm_theme'] = '';
$config['dm_page_loader'] = false;
$config['dm_remember_theme'] = true;
$config['dm_l_sidebar_left'] = true;
$config['dm_l_sidebar_mini'] = false;
$config['dm_l_sidebar_visible_desktop'] = true;
$config['dm_l_sidebar_visible_mobile'] = false;
$config['dm_l_sidebar_dark'] = true;
$config['dm_l_side_overlay_hoverable'] = false;
$config['dm_l_side_overlay_visible'] = false;
$config['dm_l_page_overlay'] = true;
$config['dm_l_side_scroll'] = true;
$config['dm_l_header_fixed'] = true;
$config['dm_l_header_style'] = 'light';
$config['dm_l_footer_fixed'] = false;
$config['dm_l_m_content'] = '';
$config['dm_main_nav_active'] = basename($_SERVER['PHP_SELF']);
$config['dm_main_nav'] = array(
    array(
        'name'  => 'Dashboard',
        'icon'  => 'fa fa-rocket',
        'badge' => array(3, 'primary'),
        'url'   => 'gs_backend.php'
    ),
    array(
        'name'  => 'Heading',
        'type'  => 'heading'
    ),
    array(
        'name'  => 'Dropdown',
        'icon'  => 'fa fa-puzzle-piece',
        'sub'   => array(
            array(
                'name'  => 'Link #1',
                'url'   => 'javascript:void(0)'
            ),
            array(
                'name'  => 'Link #2',
                'url'   => 'javascript:void(0)'
            )
        )
    ),
    array(
        'name'  => 'Test Page',
        'icon'  => 'fa fa-vial',
        'url'   => 'test'
    )
);
$config['dm_inc_side_overlay'] = 'partials/inc_side_overlay.php';
$config['dm_inc_sidebar'] = 'partials/inc_sidebar.php';
$config['dm_inc_header'] = 'partials/inc_header.php';
$config['dm_inc_footer'] = 'partials/inc_footer.php';
