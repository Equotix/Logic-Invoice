<?php
// Version
define('VERSION', '0.1.0');

// Configuration
require_once('config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Session
$session = new Session();

// Request
$request = new Request();

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Cron
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'config' AND `key` = 'config_cron_user_id'");

if ($query->num_rows) {
    $user_query = $db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$query->row['value'] . "'");

    if ($user_query->num_rows) {
        $cron_key = $user_query->row['key'];
        $cron_secret = $user_query->row['secret'];
    } else {
        exit();
    }
} else {
    exit();
}

$data = array(
    'key'    => $cron_key,
    'secret' => $cron_secret
);

$curl = curl_init();

if (substr(HTTPS_APPLICATION, 0, 5) == 'https') {
    curl_setopt($curl, CURLOPT_PORT, 443);
}

curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
curl_setopt($curl, CURLOPT_USERAGENT, $request->server['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, HTTPS_APPLICATION . 'index.php?load=api/login');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

$response = curl_exec($curl);

$data = json_decode($response, true);

if (isset($data['cookie'])) {
    $cookie = $data['cookie'];
    $api_key = $data['api_key'];
}

curl_close($curl);

if (isset($cookie)) {
    $data = array(
        'api_key' => $api_key
    );

    // Recurring
    $curl = curl_init();

    if (substr(HTTPS_APPLICATION, 0, 5) == 'https') {
        curl_setopt($curl, CURLOPT_PORT, 443);
    }

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_USERAGENT, $request->server['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, HTTPS_APPLICATION . 'index.php?load=api/recurring/check');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $cookie . ';');
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);

    curl_exec($curl);

    // Invoice
    $curl = curl_init();

    if (substr(HTTPS_APPLICATION, 0, 5) == 'https') {
        curl_setopt($curl, CURLOPT_PORT, 443);
    }

    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_USERAGENT, $request->server['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, HTTPS_APPLICATION . 'index.php?load=api/invoice/check');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $cookie . ';');
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);

    curl_exec($curl);
}