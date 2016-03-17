<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonHome extends Controller {
    public function index() {
        $title = $this->config->get('config_meta_title');

        $this->document->setTitle($title[$this->config->get('config_language_id')]);

        $description = $this->config->get('config_meta_description');

        $this->document->setDescription($description[$this->config->get('config_language_id')]);

        $home = $this->config->get('config_home');

        $this->data['home'] = html_entity_decode($home[$this->config->get('config_language_id')], ENT_QUOTES);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('common/home'));
    }
}