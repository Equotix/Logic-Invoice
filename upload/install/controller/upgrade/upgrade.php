<?php
class ControllerUpgradeUpgrade extends Controller {
    public function index() {
        $this->data = $this->load->language('default');

        $this->data['base'] = HTTP_SERVER;
        $this->data['application'] = HTTP_APPLICATION;

        $this->data['admin'] = HTTP_APPLICATION . 'admin/';

        $this->response->setOutput($this->render('upgrade/upgrade'));
    }

    public function upgrade() {
        $json = array();

        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if (isset($this->request->get['step'])) {
            $step = $this->request->get['step'];
        } else {
            $step = 1;
        }

        $files = glob(DIR_APPLICATION . 'model/upgrade/*.php');

        if (isset($files[$step - 1])) {
            try {
                $this->load->model('upgrade/' . basename($files[$step - 1], '.php'));

                $this->{'model_upgrade_' . str_replace('.', '', basename($files[$step - 1], '.php'))}->upgrade($db);

                $json['success'] = sprintf($this->language->get('text_progress'), $step, count($files));

                $json['url'] = str_replace('&amp;', '&', $this->url->link('upgrade/upgrade/upgrade', 'step=' . ($step + 1)));
            } catch (Exception $exception) {
                $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
            }
        } else {
            $json['url'] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}