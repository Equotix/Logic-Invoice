<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonFooter extends Controller {
    public function index() {
        $this->load->language('common/footer');

        $this->data['text_powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y'));

        return $this->render('common/footer');
    }
}