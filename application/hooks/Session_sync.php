<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_sync {

    public function sync() {
        $CI =& get_instance();
        $CI->load->library('session');

        if ($CI->session->userdata('logged_in')) {
            $user_id = $CI->session->userdata('user_id');
            
            $CI->load->model('User_model');
            $user = $CI->User_model->get_user_by_id($user_id);

            if ($user) {
                $session_data = array(
                    'user_id' => $user['id'],
                    'telegram_id' => $user['id'],
                    'first_name' => $user['first_name'],
                    'logged_in' => TRUE
                );
                $CI->session->set_userdata($session_data);
            } else {
                // User not found in DB, destroy session
                $CI->session->sess_destroy();
            }
        }
    }
}
