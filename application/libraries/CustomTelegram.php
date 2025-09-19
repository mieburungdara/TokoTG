<?php
// Placed in application/libraries/CustomTelegram.php

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

class CustomTelegram extends Telegram
{
    /**
     * Overridden method to log updates before processing.
     *
     * This method intercepts every update, logs it to the custom 'messages' table,
     * and then calls the parent method to continue with normal command execution.
     */
    public function processUpdate(Update $update)
    {
        $CI =& get_instance();
        $CI->load->model('TelegramMessageModel');
        $CI->load->model('Bot_model');

        // Get bot_id from the currently running bot's username
        $bot_username = $this->getBotUsername();
        
        // Note: You may need to create the 'get_bot_by_username' method in your Bot_model
        $bot_info = $CI->Bot_model->get_bot_by_username($bot_username);
        $bot_id = $bot_info ? $bot_info['bot_id'] : 0;

        $update_type = $update->getUpdateType();
        $message = $update->getUpdateContent();

        // Prepare data for logging, starting with universal data
        $data = [
            'bot_id'            => $bot_id,
            'direction'         => 'incoming',
            'type'              => $update_type,
            'raw_data'          => json_encode($update->getRawData()),
            'processing_status' => 'received',
        ];

        // Add fields that are common to message-like updates, checking for existence
        if (method_exists($message, 'getMessageId')) {
            $data['message_id'] = $message->getMessageId();
        }
        if (method_exists($message, 'getReplyToMessage') && $message->getReplyToMessage()) {
            $data['reply_to_message_id'] = $message->getReplyToMessage()->getMessageId();
        }
        if (method_exists($message, 'getFrom') && $message->getFrom()) {
            $data['user_id'] = $message->getFrom()->getId();
        }
        if (method_exists($message, 'getChat') && $message->getChat()) {
            $data['chat_id'] = $message->getChat()->getId();
        }
        if (method_exists($message, 'getText')) {
            $data['text'] = $message->getText();
        }
        if (method_exists($message, 'getMediaGroupId')) {
            $data['media_group_id'] = $message->getMediaGroupId();
        }
        if (method_exists($message, 'getDate') && $message->getDate()) {
            $data['created_at'] = gmdate('Y-m-d H:i:s', $message->getDate());
        }

        // Log the data to the database
        $CI->TelegramMessageModel->log_message($data);

        // IMPORTANT: Continue with the original processing
        return parent::processUpdate($update);
    }
}
