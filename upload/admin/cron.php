<?php
// Configuration
if (file_exists('../config.php')) {
    require_once('../config.php');

    define('_URL', APP_URL);
    define('_SURL', APP_SURL);
    define('_PATH', __DIR__ . '/../application');
    define('_FRONT', true);

    require_once('../defined.php');
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

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

$config->set('config_url', HTTP_SERVER);
$config->set('config_ssl', HTTPS_SERVER);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Url
$url = new Url($config->get('config_url'), $config->get('config_use_ssl') ? $config->get('config_ssl') : $config->get('config_url'));
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

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");

foreach ($query->rows as $result) {
    $languages[$result['code']] = $result;
}

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) {
    $browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

    foreach ($browser_languages as $browser_language) {
        foreach ($languages as $key => $value) {
            if ($value['status']) {
                $locale = explode(',', $value['locale']);

                if (in_array($browser_language, $locale)) {
                    $detect = $key;
                }
            }
        }
    }
}

if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
    $code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
    $code = $request->cookie['language'];
} elseif ($detect) {
    $code = $detect;
} else {
    $code = $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
    $session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
    setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

$language = new Language($languages[$code]['directory']);
$language->load('default');
$registry->set('language', $language);

// Build
$registry->set('build', new Build($registry));

// Document
$registry->set('document', new Document());

// Customer
$registry->set('customer', new Customer($registry));

// Currency
$registry->set('currency', new Currency($registry));

// Front Controller 
$controller = new Front($registry);

// Cron
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'config' AND `key` = 'config_cron_user_id'");

if ($query->num_rows) {
    $user_query = $db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$query->row['value'] . "'");

    if ($user_query->num_rows) {
        $session->data['api_key'] = md5(mt_rand());
        $session->data['username'] = $user_query->row['username'];

        $request->post['api_key'] = $session->data['api_key'];

        $error = new Action('error/not_found');

        $controller->dispatch(new Action('api/recurring/check'), $error);
        $controller->dispatch(new Action('api/invoice/check'), $error);
    }
}