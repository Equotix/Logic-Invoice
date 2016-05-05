<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountPassword extends Controller {
    private $error = array();

    public function index() {
        $this->load->model('billing/customer');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/password', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/password');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_billing_customer->editPassword($this->customer->getEmail(), $this->request->post['password'], $this->request->server['REMOTE_ADDR']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('account/account', '', true));
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/password', '', true)
        );

        $this->data['action'] = $this->url->link('account/password', '', true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_password'] = $this->build->data('password', $this->error);
        $this->data['error_confirm'] = $this->build->data('confirm', $this->error);

        $this->data['verify'] = $this->build->data('verify', $this->request->post);
        $this->data['password'] = $this->build->data('password', $this->request->post);
        $this->data['confirm'] = $this->build->data('confirm', $this->request->post);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/password'));
    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['password']) < 6) || (utf8_strlen($this->request->post['password']) > 25)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ($this->request->post['confirm'] != $this->request->post['password']) {
            $this->error['confirm'] = $this->language->get('error_confirm');
        }

        if (!$this->model_billing_customer->getCustomerByPassword($this->request->post['verify'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}