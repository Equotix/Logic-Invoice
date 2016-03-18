<?php
// Configuration
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_APPLICATION', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');
define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SOFTWARE', str_replace('\'', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_SYSTEM', str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/system/');
define('DIR_DATABASE', DIR_SYSTEM . 'library/database/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/');
define('_FRONT', false);

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Url
$url = new Url(HTTP_SERVER);
$registry->set('url', $url);

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Session
$session = new Session();
$registry->set('session', $session);

// Language
$language = new Language('english');
$language->load('default');
$registry->set('language', $language);

// Front Controller 
$controller = new Front($registry);

// Upgrade
$upgrade = false;

if (file_exists('../config.php')) {
    if (filesize('../config.php') > 0) {
		require_once('../config.php');
		
        $upgrade = true;
    }
}

// Action
if (isset($request->get['load'])) {
    $action = new Action($request->get['load']);
} elseif ($upgrade) {
    $action = new Action('upgrade/upgrade');
} else {
    $action = new Action('install/step_1');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();