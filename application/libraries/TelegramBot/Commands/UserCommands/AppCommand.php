<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class AppCommand extends UserCommand
{
    protected $name = 'app';
    protected $description = 'Launch the TokoTG Mini App';
    protected $usage = '/app';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();

        // --- Get User Roles ---
        $CI =& get_instance();
        $CI->load->model('User_model');
        $roles = $CI->User_model->get_user_roles($user_id);
        if (empty($roles)) {
            // Assign default role if user has no roles yet
            $CI->db->insert('user_roles', ['user_id' => $user_id, 'role_id' => 2]); // Assuming 2 is 'customer' role
            $roles = ['customer'];
        }

        // --- Build Keyboard based on Roles ---
        $keyboard_buttons = [];

        // Marketplace button (for everyone)
        $keyboard_buttons[] = ['text' => '🛍️ Marketplace', 'web_app' => ['url' => site_url('miniapp/marketplace')]];

        // My Orders button (for everyone logged in)
        $keyboard_buttons[] = ['text' => '🧾 My Orders', 'web_app' => ['url' => site_url('orders')]];

        // Seller Menu button
        if (in_array('seller', $roles)) {
            $keyboard_buttons[] = ['text' => '📦 My Products', 'web_app' => ['url' => site_url('seller/products')]];
        }

        // Admin Menu button
        if (in_array('admin', $roles)) {
            $keyboard_buttons[] = ['text' => '👑 Admin: Add Balance', 'web_app' => ['url' => site_url('admin/add_balance')]];
        }

        // The keyboard is an array of button rows, let's make it 2 columns
        $inline_keyboard = new InlineKeyboard(...array_chunk($keyboard_buttons, 2));

        $data = [
            'chat_id'      => $chat_id,
            'text'         => 'Welcome to the TokoTG Mini App! Please choose an option below:',
            'reply_markup' => $inline_keyboard,
        ];

        return Request::sendMessage($data);
    }
}
