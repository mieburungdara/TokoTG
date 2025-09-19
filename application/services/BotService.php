<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;

class BotService {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Bot_model');
        $this->CI->load->helper('url');
    }

    public function setWebhook($botId) {
        if ($botId === NULL) {
            return ['status' => 'error', 'message' => 'Bot ID is required.'];
        }

        $bot = $this->CI->db->get_where('bots', array('id' => $botId))->row_array();

        if (empty($bot)) {
            // In a real service, we might throw an exception here
            return ['status' => 'error', 'message' => 'Bot not found.'];
        }

        try {
            $telegram = new Telegram($bot['api_key'], $bot['username']);
            $webhook_url = base_url('telegram_webhook');
            $result = $telegram->setWebhook($webhook_url);

            if ($result->isOk()) {
                $this->CI->Bot_model->update_bot($botId, ['webhook_url' => $webhook_url]);
                return ['status' => 'success', 'message' => 'Webhook untuk ' . $bot['username'] . ' berhasil di-set: ' . $result->getDescription()];
            } else {
                return ['status' => 'error', 'message' => 'Gagal set webhook untuk ' . $bot['username'] . ': ' . $result->getDescription()];
            }
        } catch (TelegramException $e) {
            // Log the exception
            log_message('error', 'Error setting webhook for bot ' . $bot['username'] . ': ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error set webhook untuk ' . $bot['username'] . ': ' . $e->getMessage()];
        }
    }

    public function deleteWebhook($botId) {
        if ($botId === NULL) {
            return ['status' => 'error', 'message' => 'Bot ID is required.'];
        }

        $bot = $this->CI->db->get_where('bots', array('id' => $botId))->row_array();

        if (empty($bot)) {
            return ['status' => 'error', 'message' => 'Bot not found.'];
        }

        try {
            $telegram = new Telegram($bot['api_key'], $bot['username']);
            $result = $telegram->deleteWebhook();

            if ($result->isOk()) {
                $this->CI->Bot_model->update_bot($botId, ['webhook_url' => NULL]);
                return ['status' => 'success', 'message' => 'Webhook for ' . $bot['username'] . ' deleted successfully: ' . $result->getDescription()];
            } else {
                return ['status' => 'error', 'message' => 'Failed to delete webhook for ' . $bot['username'] . ': ' . $result->getDescription()];
            }
        } catch (TelegramException $e) {
            log_message('error', 'Error deleting webhook for bot ' . $bot['username'] . ': ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error deleting webhook for ' . $bot['username'] . ': ' . $e->getMessage()];
        }
    }
}
