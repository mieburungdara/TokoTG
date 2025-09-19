<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;

class Getupdates extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // This controller can be run from the CLI only
        if (!$this->input->is_cli_request()) {
            show_error('Direct access is not allowed');
            return;
        }

        $this->load->model('Bot_model');
        require_once APPPATH . 'vendor/autoload.php';
    }

    public function index()
    {
        echo "Starting getUpdates in loop mode...\n";
        echo "Press Ctrl+C to stop.\n";

        while (true) {
            try {
                // Fetch all bots from the database
                $bots = $this->Bot_model->get_bots_by_mode('longpolling');

                if (empty($bots)) {
                    echo "No bots configured for long polling. Waiting...\n";
                    sleep(10); // Wait longer if no bots are configured
                    continue;
                }

                // Initialize Telegram object with the first bot
                $first_bot = $bots[0];
                $telegram = new Telegram($first_bot['api_key'], $first_bot['username']);

                // Enable MySQL
                $telegram->enableMySql([
                    'host'     => $this->db->hostname,
                    'user'     => $this->db->username,
                    'password' => $this->db->password,
                    'database' => $this->db->database,
                ]);

                // Add other bots
                for ($i = 1; $i < count($bots); $i++) {
                    $bot = $bots[$i];
                    $telegram->addBot($bot['api_key'], $bot['username']);
                }

                // Add commands paths
                $telegram->addCommandsPath(APPPATH . 'libraries/TelegramBot/Commands');

                // Handle getUpdates request
                $server_response = $telegram->handleGetUpdates();

                if ($server_response->isOk()) {
                    $update_count = count($server_response->getResult());
                    if ($update_count > 0) {
                        echo date('Y-m-d H:i:s') . ' - Updates processed: ' . $update_count . "\n";
                    }
                } else {
                    echo date('Y-m-d H:i:s') . ' - Failed to fetch updates: ' . $server_response->getDescription() . "\n";
                }

            } catch (TelegramException $e) {
                // Log all Telegram errors
                log_message('error', $e->getMessage());
                echo date('Y-m-d H:i:s') . ' - TelegramException: ' . $e->getMessage() . "\n";
            } catch (Exception $e) {
                // Catch any other generic exceptions
                log_message('error', $e->getMessage());
                echo date('Y-m-d H:i:s') . ' - Exception: ' . $e->getMessage() . "\n";
            }

            // Wait for 1 second before the next poll
            sleep(1);
        }
    }
}
