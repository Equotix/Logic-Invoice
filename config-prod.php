<?php
// URL
define('APP_URL', getenv('LI_URL'));
define('APP_SURL', getenv('LI_SURL'));

// Database
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'db');
define('DB_USERNAME', getenv('MYSQL_USER'));
define('DB_PASSWORD', getenv('MYSQL_PASSWORD'));
define('DB_DATABASE', getenv('MYSQL_DATABASE'));
define('DB_PREFIX', 'li_');