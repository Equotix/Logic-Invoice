<?php
defined('_PATH') or die('Restricted!');

define('VERSION', '1.2.0');
define('HTTP_SERVER', _URL);
define('HTTP_APPLICATION', APP_URL);
define('HTTPS_SERVER', _SURL);
define('HTTPS_APPLICATION', APP_SURL);
define('DIR_APPLICATION', _PATH . '/');
define('DIR_EXTENSION', _PATH . '/extension/');
define('DIR_IMAGE', __DIR__ . '/image/');
define('DIR_LANGUAGE', _PATH . '/language/');
define('DIR_SYSTEM', __DIR__ . '/system/');
define('DIR_TEMPLATE', _PATH . '/view/');