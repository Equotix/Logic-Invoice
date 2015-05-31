<?php
defined('_PATH') or die('Restricted!');

class ControllerApiCustomer extends Controller {
    public function login() {
        $json = array();

        $json['success'] = false;

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            if (isset($this->request->post['email']) && isset($this->request->post['password'])) {
                if ($this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                    $json['success'] = true;
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}