<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;

class Superadmin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();


        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Bot_model');
        $this->load->library('form_validation');
        $this->load->library('services/BotService', null, 'bot_service');

        // Check if superadmin is logged in
        if (!$this->session->userdata('superadmin_logged_in'))
        {
            redirect('superadmin_login');
        }


    }

    public function dashboard()
    {
        $this->load->view('superadmin/dashboard');
    }

    public function bots()
    {
        $data['bots'] = $this->Bot_model->get_all_bots();
        $this->load->view('superadmin/bots/list', $data);
    }

    public function add_bot()
    {
        $this->form_validation->set_rules('username', 'Bot Username', 'required|is_unique[bots.username]');
        $this->form_validation->set_rules('api_key', 'Bot API Key', 'required|is_unique[bots.api_key]');
        $this->form_validation->set_rules('mode', 'Mode', 'required|in_list[webhook,longpolling]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('superadmin/bots/add');
        }
        else
        {
            $data = array(
                'username' => $this->input->post('username'),
                'api_key' => $this->input->post('api_key'),
                'mode' => $this->input->post('mode'),
            );


            if ($this->Bot_model->add_bot($data))
            {
                $this->session->set_flashdata('success', 'Bot added successfully!');
                redirect('superadmin/bots');
            }
            else
            {
                $this->session->set_flashdata('error', 'Failed to add bot.');
                redirect('superadmin/bots/add');
            }
        }
    }

    public function edit_bot($id = NULL)
    {
        if ($id === NULL) {
            redirect('superadmin/bots');
        }

        $bot = $this->db->get_where('bots', array('id' => $id))->row_array();

        if (empty($bot)) {
            show_404();
        }

        // Validate uniqueness only if username/api_key is changed
        $username_rule = 'required';
        if ($this->input->post('username') !== $bot['username']) {
            $username_rule .= '|is_unique[bots.username]';
        }
        $api_key_rule = 'required';
        if ($this->input->post('api_key') !== $bot['api_key']) {
            $api_key_rule .= '|is_unique[bots.api_key]';
        }

        $this->form_validation->set_rules('username', 'Bot Username', $username_rule);
        $this->form_validation->set_rules('api_key', 'Bot API Key', $api_key_rule);
        $this->form_validation->set_rules('mode', 'Mode', 'required|in_list[webhook,longpolling]');

        if ($this->form_validation->run() === FALSE)
        {
            $data['bot'] = $bot;
            $this->load->view('superadmin/bots/edit', $data);
        }
        else
        {
            $data = array(
                'username' => $this->input->post('username'),
                'api_key' => $this->input->post('api_key'),
                'mode' => $this->input->post('mode'),
            );

            if ($this->Bot_model->update_bot($id, $data))
            {
                $this->session->set_flashdata('success', 'Bot updated successfully!');
                redirect('superadmin/bots');
            }
            else
            {
                $this->session->set_flashdata('error', 'Failed to update bot.');
                redirect('superadmin/bots/edit/' . $id);
            }
        }
    }

    public function delete_bot($id = NULL)
    {
        if ($id === NULL) {
            redirect('superadmin/bots');
        }

        if ($this->Bot_model->delete_bot($id))
        {
            $this->session->set_flashdata('success', 'Bot deleted successfully!');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to delete bot.');
        }
        redirect('superadmin/bots');
    }

    public function set_bot_webhook($id = NULL)
    {
        if ($id === NULL) {
            redirect('superadmin/bots');
            return;
        }

        $result = $this->bot_service->setWebhook($id);

        if ($result['status'] === 'success') {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('superadmin/bots');
    }


    public function delete_bot_webhook($id = NULL)
    {
        if ($id === NULL) {
            redirect('superadmin/bots');
            return;
        }

        $result = $this->bot_service->deleteWebhook($id);

        if ($result['status'] === 'success') {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('superadmin/bots');
    }

    public function switch_bot_mode($id = NULL)
    {
        if ($id === NULL) {
            redirect('superadmin/bots');
        }

        $bot = $this->db->get_where('bots', array('id' => $id))->row_array();

        if (empty($bot)) {
            show_404();
        }

        $new_mode = ($bot['mode'] == 'webhook') ? 'longpolling' : 'webhook';

        if ($this->Bot_model->update_bot($id, array('mode' => $new_mode)))
        {
            $this->session->set_flashdata('success', 'Bot mode switched successfully!');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to switch bot mode.');
        }
        redirect('superadmin/bots');
    }


}
