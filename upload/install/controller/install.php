<?php
class ControllerInstall extends Controller {
    public function index() {
        $this->data = $this->load->language('default');

        $this->data['base'] = HTTP_SERVER;
        $this->data['application'] = HTTP_APPLICATION;

        $this->data['license'] = file_get_contents(DIR_APPLICATION . 'license.txt');

        $this->response->setOutput($this->render('install.tpl'));
    }

    public function requirement() {
        $this->data = $this->load->language('default');

        $this->data['requirements'] = array(
            'php_version'        => array(
                '5.3',
                phpversion(),
                phpversion() >= 5.3
            ),
            'register_globals'   => array(
                $this->language->get('text_off'),
                ini_get('register_globals') ? $this->language->get('text_on') : $this->language->get('text_off'),
                ini_get('register_globals') ? false : true
            ),
            'magic_quotes_gpc'   => array(
                $this->language->get('text_off'),
                ini_get('magic_quotes_gpc') ? $this->language->get('text_on') : $this->language->get('text_off'),
                ini_get('magic_quotes_gpc') ? false : true
            ),
            'file_uploads'       => array(
                $this->language->get('text_on'),
                ini_get('file_uploads') ? $this->language->get('text_on') : $this->language->get('text_off'),
                ini_get('file_uploads') ? true : false
            ),
            'session_auto_start' => array(
                $this->language->get('text_off'),
                ini_get('session_auto_start') ? $this->language->get('text_on') : $this->language->get('text_off'),
                ini_get('session_auto_start') ? false : true
            ),
            'curl'               => array(
                $this->language->get('text_on'),
                extension_loaded('curl') ? $this->language->get('text_on') : $this->language->get('text_off'),
                extension_loaded('curl') ? true : false
            ),
            'zlib'               => array(
                $this->language->get('text_on'),
                extension_loaded('zlib') ? $this->language->get('text_on') : $this->language->get('text_off'),
                extension_loaded('zlib') ? true : false
            ),
            'zip'                => array(
                $this->language->get('text_on'),
                extension_loaded('zip') ? $this->language->get('text_on') : $this->language->get('text_off'),
                extension_loaded('zip') ? true : false
            ),
            'iconv'              => array(
                $this->language->get('text_on'),
                function_exists('iconv') ? $this->language->get('text_on') : $this->language->get('text_off'),
                function_exists('iconv') ? true : false
            ),
            'mbstring'           => array(
                $this->language->get('text_on'),
                extension_loaded('mbstring') ? $this->language->get('text_on') : $this->language->get('text_off'),
                extension_loaded('mbstring') ? true : false
            ),
            'db'                 => array(
                $this->language->get('text_on'),
                array_filter(array(
                    'mysql',
                    'mysqli'
                ), 'extension_loaded') ? $this->language->get('text_on') : $this->language->get('text_off'),
                array_filter(array(
                    'mysql',
                    'mysqli'
                ), 'extension_loaded') ? true : false
            ),
            'config'             => array(
                $this->language->get('text_yes'),
                (is_writable(DIR_SOFTWARE) && is_writable(DIR_SOFTWARE . 'admin/')) ? $this->language->get('text_yes') : $this->language->get('text_no'),
                (is_writable(DIR_SOFTWARE) && is_writable(DIR_SOFTWARE . 'admin/')) ? true : false
            ),
            'cache'              => array(
                $this->language->get('text_yes'),
                is_writable(DIR_SYSTEM . 'cache/') ? $this->language->get('text_yes') : $this->language->get('text_no'),
                is_writable(DIR_SYSTEM . 'cache/') ? true : false
            ),
            'logs'               => array(
                $this->language->get('text_yes'),
                is_writable(DIR_SYSTEM . 'logs/') ? $this->language->get('text_yes') : $this->language->get('text_no'),
                is_writable(DIR_SYSTEM . 'logs/') ? true : false
            )
        );

        $this->response->setOutput($this->render('requirement.tpl'));
    }

    public function configure() {
        $this->data = $this->load->language('default');

        $this->data['databases'] = array(
            'mysqli',
            'mysql'
        );

        $this->data['prefix'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5) . '_';

        $this->response->setOutput($this->render('configure.tpl'));
    }

    public function validate_configure() {
        $json = array();

        if ($this->request->post['database'] == 'mysqli') {
            $connection = @new mysqli($this->request->post['database_host'], $this->request->post['database_username'], $this->request->post['database_password'], $this->request->post['database_name']);

            if ($connection->connect_error) {
                $json['error'] = $connection->connect_error;
            } else {
                $connection->close();
            }
        }

        if ($this->request->post['database'] == 'mysql') {
            $connection = @mysql_connect($this->request->post['database_host'], $this->request->post['database_username'], $this->request->post['database_password']);

            if (!$connection) {
                $json['error'] = $this->language->get('error_connection');
            } else {
                if (!@mysql_select_db($this->request->post['database_name'], $connection)) {
                    $json['error'] = $this->language->get('error_database');
                }

                mysql_close($connection);
            }
        }

        if ((utf8_strlen($this->request->post['admin_username']) < 3) || (utf8_strlen($this->request->post['admin_username']) > 32)) {
            $json['error'] = $this->language->get('error_username');
        }

        if ((utf8_strlen($this->request->post['admin_password']) < 6) || (utf8_strlen($this->request->post['admin_password']) > 25)) {
            $json['error'] = $this->language->get('error_password');
        }

        if ((utf8_strlen($this->request->post['admin_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['admin_email'])) {
            $json['error'] = $this->language->get('error_email');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install() {
        $this->data = $this->load->language('default');

        $this->load->model('install');

        $this->model_install->database($this->request->post);

        $output = '<?php' . "\n";
        $output .= '// HTTP' . "\n";
        $output .= 'define(\'HTTP_SERVER\', \'' . HTTP_APPLICATION . '\');' . "\n\n";

        $output .= '// HTTPS' . "\n";
        $output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_APPLICATION . '\');' . "\n\n";

        $output .= '// DIR' . "\n";
        $output .= 'define(\'DIR_APPLICATION\', \'' . DIR_SOFTWARE . 'application/\');' . "\n";
        $output .= 'define(\'DIR_CACHE\', \'' . DIR_SOFTWARE . 'system/cache/\');' . "\n";
        $output .= 'define(\'DIR_SYSTEM\', \'' . DIR_SOFTWARE . 'system/\');' . "\n";
        $output .= 'define(\'DIR_DATABASE\', \'' . DIR_SOFTWARE . 'system/library/database/\');' . "\n";
        $output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_SOFTWARE . 'application/language/\');' . "\n";
        $output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_SOFTWARE . 'application/view/template/\');' . "\n";
        $output .= 'define(\'DIR_LOGS\', \'' . DIR_SOFTWARE . 'system/logs/\');' . "\n\n";

        $output .= '// DB' . "\n";
        $output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['database']) . '\');' . "\n";
        $output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['database_host']) . '\');' . "\n";
        $output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['database_username']) . '\');' . "\n";
        $output .= 'define(\'DB_PASSWORD\', \'' . addslashes($this->request->post['database_password']) . '\');' . "\n";
        $output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['database_name']) . '\');' . "\n";
        $output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['database_prefix']) . '\');' . "\n";

        $file = fopen(DIR_SOFTWARE . 'config.php', 'w');

        fwrite($file, $output);

        fclose($file);

        $output = '<?php' . "\n";
        $output .= '// HTTP' . "\n";
        $output .= 'define(\'HTTP_SERVER\', \'' . HTTP_APPLICATION . 'admin/\');' . "\n";
        $output .= 'define(\'HTTP_APPLICATION\', \'' . HTTP_APPLICATION . '\');' . "\n\n";

        $output .= '// HTTPS' . "\n";
        $output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_APPLICATION . 'admin/\');' . "\n";
        $output .= 'define(\'HTTPS_APPLICATION\', \'' . HTTP_APPLICATION . '\');' . "\n\n";

        $output .= '// DIR' . "\n";
        $output .= 'define(\'DIR_APPLICATION\', \'' . DIR_SOFTWARE . 'admin/\');' . "\n";
        $output .= 'define(\'DIR_CACHE\', \'' . DIR_SOFTWARE . 'system/cache/\');' . "\n";
        $output .= 'define(\'DIR_SYSTEM\', \'' . DIR_SOFTWARE . 'system/\');' . "\n";
        $output .= 'define(\'DIR_DATABASE\', \'' . DIR_SOFTWARE . 'system/library/database/\');' . "\n";
        $output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_SOFTWARE . 'admin/language/\');' . "\n";
        $output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_SOFTWARE . 'admin/view/template/\');' . "\n";
        $output .= 'define(\'DIR_LOGS\', \'' . DIR_SOFTWARE . 'system/logs/\');' . "\n\n";

        $output .= '// DB' . "\n";
        $output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['database']) . '\');' . "\n";
        $output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['database_host']) . '\');' . "\n";
        $output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['database_username']) . '\');' . "\n";
        $output .= 'define(\'DB_PASSWORD\', \'' . addslashes($this->request->post['database_password']) . '\');' . "\n";
        $output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['database_name']) . '\');' . "\n";
        $output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['database_prefix']) . '\');' . "\n";

        $file = fopen(DIR_SOFTWARE . 'admin/config.php', 'w');

        fwrite($file, $output);

        fclose($file);

        $this->data['admin'] = HTTP_APPLICATION . 'admin/';

        $this->response->setOutput($this->render('complete.tpl'));
    }

    public function remove() {
        $iterator = new RecursiveDirectoryIterator(DIR_APPLICATION, RecursiveDirectoryIterator::SKIP_DOTS);

        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->isDir()) {
                @rmdir($file->getRealPath());
            } else {
                @unlink($file->getRealPath());
            }
        }

        @rmdir(DIR_APPLICATION);
    }
}