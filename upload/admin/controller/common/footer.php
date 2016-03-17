<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonFooter extends Controller {
    public function index() {
        $this->load->language('common/footer');

        $this->data['text_powered'] = sprintf($this->language->get('text_powered'), date('Y'));

        if ($this->user->isLogged()) {
            $this->data['version'] = sprintf($this->language->get('text_version'), VERSION);
        } else {
            $this->data['version'] = '';
        }

        return $this->render('common/footer');
    }
}