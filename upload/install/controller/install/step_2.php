<?php
class ControllerInstallStep2 extends Controller {
    public function index() {
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
			'gd'                => array(
                $this->language->get('text_on'),
                extension_loaded('gd') ? $this->language->get('text_on') : $this->language->get('text_off'),
                extension_loaded('gd') ? true : false
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

        $this->response->setOutput($this->render('install/step_2'));
    }
}