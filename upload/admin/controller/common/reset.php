<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonReset extends Controller {
    private $error = array();

    public function index() {
        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('common/dashboard', '', true));
        }

        if (!$this->config->get('config_forgotten_admin')) {
            $this->response->redirect($this->url->link('common/login', '', true));
        }

        if (isset($this->request->get['code'])) {
            $code = $this->request->get['code'];
        } else {
            $code = '';
        }

        $this->load->model('system/user');

        $user_info = $this->model_system_user->getUserByCode($code);

        if ($user_info) {
            $this->data = $this->load->language('common/reset');

            $this->document->setTitle($this->language->get('heading_title'));

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->model_system_user->editPassword($user_info['user_id'], $this->request->post['password']);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('common/login', '', true));
            }

            $this->data['error_warning'] = $this->build->data('warning', $this->error);

            $this->data['action'] = $this->url->link('common/reset', 'code=' . $code, true);

            $this->data['password'] = $this->build->data('password', $this->request->post);
            $this->data['confirm'] = $this->build->data('confirm', $this->request->post);

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('common/reset'));
        } else {
            $this->load->model('system/setting');

            $this->model_system_setting->editSettingValue('config', 'config_forgotten_admin', '0');

            $this->response->redirect($this->url->link('common/login', '', true));
        }
    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['password']) < 6) || (utf8_strlen($this->request->post['password']) > 25)) {
            $this->error['warning'] = $this->language->get('error_password');
        }

        if ($this->request->post['confirm'] != $this->request->post['password']) {
            $this->error['warning'] = $this->language->get('error_confirm');
        }

        return !$this->error;
    }
}