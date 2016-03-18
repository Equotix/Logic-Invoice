<?php
class ControllerInstallStep1 extends Controller {
    public function index() {
        $this->data = $this->load->language('default');

        $this->data['base'] = HTTP_SERVER;
        $this->data['application'] = HTTP_APPLICATION;

        $this->data['license'] = file_get_contents(DIR_APPLICATION . 'license.txt');

        $this->response->setOutput($this->render('install/step_1'));
    }
}