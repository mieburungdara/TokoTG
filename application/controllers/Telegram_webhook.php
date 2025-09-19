<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;

class Telegram_webhook extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->config('config');

        // Load Composer Autoload
        require_once FCPATH . 'vendor/autoload.php';

        $this->load->model('User_model');
        $this->load->model('Message_model');
        $this->load->model('Bot_model'); // Load the new Bot_model
    }

    public function index()
    {
        try {
            // Fetch all bots from the database
            $bots = $this->Bot_model->get_all_bots();

            if (empty($bots)) {
                log_message('error', 'No bots configured in the database.');
                return;
            }

            // Initialize Telegram object with the first bot (required for multi-bot setup)
            $first_bot = $bots[0];
            $telegram = new Telegram($first_bot['api_key'], $first_bot['username']);

            // Add other bots
            for ($i = 1; $i < count($bots); $i++) {
                $bot = $bots[$i];
                $telegram->addBot($bot['api_key'], $bot['username']);
            }

            // Add commands paths
            $telegram->addCommandsPath(APPPATH . 'libraries/TelegramBot/Commands');

            // Handle telegram webhook request
            $telegram->handle();

        } catch (TelegramException $e) {
            // Log all Telegram errors
            log_message('error', $e->getMessage());
        }
    }

    public function setwebhook()
    {
        try {
            // Fetch all bots from the database
            $bots = $this->Bot_model->get_all_bots();

            if (empty($bots)) {
                echo 'No bots configured in the database.';
                return;
            }

            $results = [];
            foreach ($bots as $bot_config) {
                $telegram = new Telegram($bot_config['api_key'], $bot_config['username']);
                $result = $telegram->setWebhook(base_url('telegram_webhook'));
                $results[] = ['username' => $bot_config['username'], 'result' => $result->getDescription()];
            }

            echo '<pre>' . print_r($results, true) . '</pre>';

        } catch (TelegramException $e) {
            echo $e->getMessage();
        }
    }

    public function unsetwebhook()
    {
        try {
            // Fetch all bots from the database
            $bots = $this->Bot_model->get_all_bots();

            if (empty($bots)) {
                echo 'No bots configured in the database.';
                return;
            }

            $results = [];
            foreach ($bots as $bot_config) {
                $telegram = new Telegram($bot_config['api_key'], $bot_config['username']);
                $result = $telegram->deleteWebhook();
                $results[] = ['username' => $bot_config['username'], 'result' => $result->getDescription()];
            }

            echo '<pre>' . print_r($results, true) . '</pre>';

        } catch (TelegramException $e) {
            echo $e->getMessage();
        }
    }
}