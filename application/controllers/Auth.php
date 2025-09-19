<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function telegram_login() {
        log_message('debug', 'telegram_login method called.');
        // Handle CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }

        // It's better to get the raw input stream and decode it
        $auth_data_json = file_get_contents('php://input');
        $auth_data = json_decode($auth_data_json, true);

        if (!isset($auth_data['initData']) || !isset($auth_data['bot_id'])) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid data']));
            return;
        }

        $initData = $auth_data['initData'];
        $bot_id = $auth_data['bot_id'];
        log_message('debug', 'Received initData for bot ID: ' . $bot_id);

        if ($this->is_valid_telegram_data($initData, $bot_id)) {
            parse_str($initData, $data_array);
            $user_data = json_decode($data_array['user'], true);

            $user = $this->User_model->find_or_create_user($user_data);

            $this->session->set_userdata([
                'user_id' => $user['id'],
                'telegram_id' => $user['id'],
                'first_name' => $user['first_name'],
                'logged_in' => TRUE
            ]);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Login successful', 'user' => $user]));
        } else {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Authentication failed']));
        }
    }

    private function is_valid_telegram_data($initData, $bot_id)
    {
        $this->load->database();
        
        if (empty($bot_id)) {
            return false;
        }

        $bot = $this->db->get_where('bots', ['id' => $bot_id])->row();
        if (!$bot) {
            log_message('error', 'Validation failed: Bot with ID ' . $bot_id . ' not found.');
            return false;
        }

        $bot_token = $bot->api_key;

        //log_message('debug', 'Bot token from DB: ' . $bot_token);
        if (empty($bot_token)) {
            // Security: Do not proceed if the bot token is not configured
            log_message('error', 'Telegram bot token is not configured in the database.');
            return false;
        }

        parse_str($initData, $data_array);

        if (!isset($data_array['hash'])) {
            log_message('debug', 'Hash not found in data.');
            return false;
        }

        $hash = $data_array['hash'];
        //log_message('debug', 'Hash from data: ' . $hash);
        unset($data_array['hash']);
        
        $data_check_arr = [];
        foreach ($data_array as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        //log_message('debug', 'Data check string: ' . $data_check_string);

        $secret_key = hash_hmac('sha256', $bot_token, 'WebAppData', true);
        $calculated_hash = hash_hmac('sha256', $data_check_string, $secret_key);
        //log_message('debug', 'Calculated hash: ' . $calculated_hash);

        $is_equal = hash_equals($calculated_hash, $hash);
        //log_message('debug', 'Hash comparison result: ' . ($is_equal ? 'true' : 'false'));

        return $is_equal;
    }
}
