<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['z/(:num)'] = 'miniapp_auth/index/$1'; // Route for Mini App Auth

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['miniapp'] = 'miniapp';
$route['test'] = 'test';
$route['superadmin_login'] = 'Superadmin_login';
$route['superadmin_login/authenticate'] = 'Superadmin_login/authenticate';
$route['superadmin_login/logout'] = 'Superadmin_login/logout';
$route['superadmin/dashboard'] = 'Superadmin/dashboard';
$route['superadmin/bots'] = 'Superadmin/bots';
$route['superadmin/bots/add'] = 'Superadmin/add_bot';
$route['superadmin/bots/edit/(:num)'] = 'Superadmin/edit_bot/$1';
$route['superadmin/bots/delete/(:num)'] = 'Superadmin/delete_bot/$1';
$route['superadmin/user'] = 'User';
$route['superadmin/switch_bot_mode/(:num)'] = 'Superadmin/switch_bot_mode/$1';
$route['telegram_webhook'] = 'Telegram_webhook';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;