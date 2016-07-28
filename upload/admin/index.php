<?php
// Configuration
if (file_exists('../config.php')) {
    require_once('../config.php');

    define('_URL', APP_URL . basename(__DIR__) . '/');
    define('_SURL', APP_SURL . basename(__DIR__) . '/');
    define('_PATH', __DIR__);
    define('_FRONT', false);

    require_once('../defined.php');
}

// Install 
if (!defined('DIR_APPLICATION')) {
    header('Location: ../install/index.php');
    exit;
}

// vQmod
require_once('../vqmod/vqmod.php');
VQMod::bootup();

// Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");

foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        $config->set($setting['key'], $setting['value']);
    } else {
        $config->set($setting['key'], json_decode($setting['value'], true));
    }
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
    global $log, $config;

    if (error_reporting() === 0) {
        return false;
    }

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    if ($config->get('config_error_display')) {
        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }

    if ($config->get('config_error_log')) {
        $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }

    return true;
}

function fatal_handler() {
    global $log, $config;

    $error = error_get_last();

    if ($error !== null) {
        $errno = $error['type'];
        $errfile = $error['file'];
        $errline = $error['line'];
        $errstr = $error['message'];

        if ($config->get('config_error_display')) {
            echo '<b>Fatal Error</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
        }

        if ($config->get('config_error_log')) {
            $log->write('PHP Fatal Error:  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
        }
    }
}

// Error Handler
set_error_handler('error_handler');
register_shutdown_function('fatal_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Cache
$cache = new Cache($config->get('config_cache'));
$registry->set('cache', $cache);

// Session
$session = new Session();
$registry->set('session', $session);

// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $db->escape($config->get('config_language')) . "' AND status = '1'");

$config->set('config_language_id', $query->row['language_id']);

$language = new Language($config->get('config_language'));
$language->load('default');
$registry->set('language', $language);

// Build
$registry->set('build', new Build($registry));

// Document
$registry->set('document', new Document());

// User
$registry->set('user', new User($registry));

// Currency
$registry->set('currency', new Currency($registry));

// Front Controller 
$controller = new Front($registry);

// Login
$controller->addPreAction(new Action('common/login/check'));

// Permission
$controller->addPreAction(new Action('common/permission/check'));

// Action
if (isset($request->get['load'])) {
    $action = new Action($request->get['load']);
} else {
    $action = new Action('common/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();