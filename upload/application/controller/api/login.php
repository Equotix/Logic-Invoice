<?php
defined('_PATH') or die('Restricted!');

class ControllerApiLogin extends Controller {
    public function index() {
        $this->load->language('api/login');

        $json = array();

        $json['success'] = false;

        unset($this->session->data['api_key']);

        $this->load->model('api/api');
        $this->load->model('system/activity');

        if (isset($this->request->post['key']) && isset($this->request->post['secret'])) {
            $api_info = $this->model_api_api->login($this->request->post['key'], $this->request->post['secret']);

            if ($api_info) {
                $json['success'] = true;

                $this->session->data['api_key'] = md5(mt_rand());
                $this->session->data['username'] = $api_info['username'];

                $json['api_key'] = $this->session->data['api_key'];

                $json['cookie'] = $this->session->getId();

                $this->model_system_activity->addActivity(sprintf($this->language->get('text_login'), $this->session->data['username']));
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}