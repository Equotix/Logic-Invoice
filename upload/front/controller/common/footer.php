<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonFooter extends Controller {
    public function index() {
        $this->load->language('common/footer');

        $this->data['text_powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y'));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/common/footer.tpl')) {
			return $this->render($this->config->get('config_theme') . '/template/common/footer.tpl');
		} else {
			return $this->render('default/template/common/footer.tpl');
		}
    }
}