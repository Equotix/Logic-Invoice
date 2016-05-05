<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountRegister extends Controller {
    private $error = array();

    public function index() {
        $this->load->model('billing/customer');

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', true));
        }

        if (!$this->config->get('config_registration')) {
            return new Action('error/not_found');
        }

        $this->data = $this->load->language('account/register');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->request->post['password'] = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

            $this->model_billing_customer->addCustomer($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->load->model('system/activity');

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_register'), $this->request->post['firstname'] . ' ' . $this->request->post['lastname']));

            $this->response->redirect($this->url->link('account/login', '', true));
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
            'href' => $this->url->link('account/register', '', true)
        );

        $this->data['action'] = $this->url->link('account/register', '', true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_firstname'] = $this->build->data('firstname', $this->error);
        $this->data['error_lastname'] = $this->build->data('lastname', $this->error);
        $this->data['error_email'] = $this->build->data('email', $this->error);
        $this->data['error_captcha'] = $this->build->data('captcha', $this->error);

        $this->data['firstname'] = $this->build->data('firstname', $this->request->post);
        $this->data['lastname'] = $this->build->data('lastname', $this->request->post);
        $this->data['company'] = $this->build->data('company', $this->request->post);
        $this->data['website'] = $this->build->data('website', $this->request->post);
        $this->data['email'] = $this->build->data('email', $this->request->post);
        $this->data['captcha'] = $this->build->data('captcha', $this->request->post);

        $this->data['captcha_image'] = $this->url->link('tool/captcha', '', true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/register'));
    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->model_billing_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if ($this->request->post['captcha'] != $this->session->data['captcha']) {
            $this->error['captcha'] = $this->language->get('error_captcha');
        }

        return !$this->error;
    }
}