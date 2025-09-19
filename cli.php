<?php
// Simplified CLI entry point for the bot

// Set the environment
define('ENVIRONMENT', 'development');

// Set main paths
$system_path = 'system';
$application_folder = 'application';

// --- Simplified Path Resolution ---
if (realpath($system_path) !== FALSE)
{
    $system_path = realpath($system_path).'/';
}
$system_path = rtrim($system_path, '/').'/';

if ( ! is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo "Your system folder path does not appear to be set correctly. Please open the following file and correct this: " . pathinfo(__FILE__, PATHINFO_BASENAME);
    exit(3);
}

// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the system directory
define('BASEPATH', $system_path);

// Path to the front controller (this file) directory
define('FCPATH', __DIR__ . '/');

// Name of the "system" directory
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

// The path to the "application" directory
define('APPPATH', $application_folder.'/');

// Path to the "views" directory
define('VIEWPATH', $application_folder.'/views/');

// --- End Path Resolution ---

// Override session config for CLI
$assign_to_config['sess_driver'] = 'files';
$assign_to_config['sess_save_path'] = sys_get_temp_dir();

// Load the CI core
require_once BASEPATH.'core/CodeIgniter.php';