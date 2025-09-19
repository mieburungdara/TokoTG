<?php
/**
 * This is an example file for handling Telegram webhook requests.
 */

// Define paths to the CodeIgniter framework
define('APPPATH', __DIR__ . '/application/');
define('BASEPATH', __DIR__ . '/system/');
define('ENVIRONMENT', 'production'); // Set to 'production' for live webhooks

// --- Bootstrap CodeIgniter --- 
// This is a simplified bootstrap. For a full implementation, you might need more from index.php
require_once BASEPATH . 'core/CodeIgniter.php';
// --- End Bootstrap ---

// Load Composer autoloader, Longman library, and our custom class
require_once APPPATH . 'vendor/autoload.php';
require_once APPPATH . 'libraries/CustomTelegram.php';

use Longman\TelegramBot\Exception\TelegramException;

/**
 * --------------------------------------------------------------------------
 * IMPORTANT: DYNAMIC BOT LOADING
 * --------------------------------------------------------------------------
 * A webhook URL should be unique per bot, e.g., /webhook.php?bot=my_bot
 * The code should use the 'bot' parameter to fetch the correct API key
 * from the database.
 * 
 * For this example, we will use a hardcoded placeholder.
 * REPLACE THIS with your actual dynamic loading logic.
 */

// 1. Get the CodeIgniter instance
$ci = &get_instance();

// 2. Load the Bot_model
$ci->load->model('Bot_model');

// 3. Get bot username from URL parameter (e.g., webhook.php?bot=your_bot)
$bot_username = $_GET['bot'] ?? '';

if (empty($bot_username)) {
    // Or handle error appropriately
    die('Bot username not specified in URL.');
}

// 4. Fetch bot details from the database
$bot_info = $ci->Bot_model->get_bot_by_username($bot_username);

if (!$bot_info) {
    die('Bot not found in database.');
}

$bot_api_key = $bot_info['api_key'];

try {
    // Create Telegram object using our custom class
    $telegram = new CustomTelegram($bot_api_key, $bot_username);

    // Add commands path
    $telegram->addCommandsPath(APPPATH . 'libraries/TelegramBot/Commands', false);

    // Handle the webhook request
    $telegram->handle();

} catch (TelegramException $e) {
    // Log telegram errors
    log_message('error', '[Webhook Error] ' . $e->getMessage());
} catch (Exception $e) {
    // Log other errors
    log_message('error', '[Webhook Error] ' . $e->getMessage());
}
