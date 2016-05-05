<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonForgotten extends Controller {
    private $error = array();

    public function index() {
        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('common/dashboard', '', true));
        }

        if (!$this->config->get('config_forgotten_admin')) {
            $this->response->redirect($this->url->link('common/login', '', true));
        }

        $this->data = $this->load->language('common/forgotten');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('system/user');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $code = sha1(uniqid(mt_rand(), true));

            $this->model_system_user->editCode($this->request->post['email'], $code, $this->request->server['REMOTE_ADDR']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('common/login', '', true));
        }

        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        $this->data['action'] = $this->url->link('common/forgotten', '', true);

        $this->data['email'] = $this->build->data('email', $this->request->post);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('common/forgotten'));
    }

    protected function validate() {
        if (!isset($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        } elseif (!$this->model_system_user->getTotalUsersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }
}
