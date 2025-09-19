<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MY_Log extends CI_Log {

    protected $_logger;

    public function __construct() {
        parent::__construct();

        $this->_logger = new Logger('tokotg');
        $this->_logger->pushHandler(new StreamHandler(config_item('log_path').'log-'.date('Y-m-d').'.php', Logger::DEBUG));
    }

    public function write_log($level, $msg) {
        // Convert CI log level to Monolog log level
        $level = strtoupper($level);
        switch ($level) {
            case 'ERROR':
                $this->_logger->error($msg);
                break;
            case 'DEBUG':
                $this->_logger->debug($msg);
                break;
            case 'INFO':
                $this->_logger->info($msg);
                break;
            default:
                $this->_logger->info($msg);
                break;
        }
    }
}