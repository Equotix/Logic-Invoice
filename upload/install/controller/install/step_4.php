<?php
class ControllerInstallStep4 extends Controller {
    public function index() {
        $this->data = $this->load->language('default');

        $this->load->model('install/install');

        $this->model_install_install->database($this->request->post);

        $output = '<?php' . "\n";
        $output .= '// URL' . "\n";
        $output .= 'define(\'APP_URL\', \'' . HTTP_APPLICATION . '\');' . "\n";
        $output .= 'define(\'APP_SURL\', \'' . HTTP_APPLICATION . '\');' . "\n\n";

        $output .= '// Database' . "\n";
        $output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['database']) . '\');' . "\n";
        $output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['database_hostname']) . '\');' . "\n";
        $output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['database_username']) . '\');' . "\n";
        $output .= 'define(\'DB_PASSWORD\', \'' . addslashes($this->request->post['database_password']) . '\');' . "\n";
        $output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['database_name']) . '\');' . "\n";
        $output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['database_prefix']) . '\');' . "\n";

        $file = fopen(DIR_SOFTWARE . 'config.php', 'w');

        fwrite($file, $output);
        fclose($file);

        $this->data['admin'] = HTTP_APPLICATION . 'admin/';

        $this->response->setOutput($this->render('install/step_4'));
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