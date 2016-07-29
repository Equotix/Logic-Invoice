<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountLogin extends Controller {
    private $error = array();

    public function index() {
        $this->load->model('billing/customer');

        if (!empty($this->request->get['token'])) {
            $this->customer->logout();

            $customer_info = $this->model_billing_customer->getCustomerByToken($this->request->get['token']);

            if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
                $this->response->redirect($this->url->link('account/account', '', true));
            }
        }

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', true));
        }

        $this->data = $this->load->language('account/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
                $this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
            } else {
                $this->response->redirect($this->url->link('account/account', '', true));
            }
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
            'href' => $this->url->link('account/login', '', true)
        );

        $this->data['action'] = $this->url->link('account/login', '', true);

        if ($this->config->get('config_forgotten_application') && $this->config->get('config_registration')) {
            $this->data['text_forgotten'] = sprintf($this->language->get('text_register_forgotten'), $this->url->link('account/register', '', true), $this->url->link('account/forgotten', '', true));
        } elseif ($this->config->get('config_forgotten_application')) {
            $this->data['text_forgotten'] = sprintf($this->language->get('text_forgotten'), $this->url->link('account/register', '', true), $this->url->link('account/forgotten', '', true));
        } elseif ($this->config->get('config_registration')) {
            $this->data['text_forgotten'] = sprintf($this->language->get('text_register'), $this->url->link('account/register', '', true));
        } else {
            $this->data['text_forgotten'] = false;
        }

        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
            $this->data['redirect'] = $this->request->post['redirect'];
        } elseif (isset($this->session->data['redirect'])) {
            $this->data['redirect'] = $this->session->data['redirect'];

            unset($this->session->data['redirect']);
        } else {
            $this->data['redirect'] = '';
        }

        $this->data['email'] = $this->build->data('email', $this->request->post);
        $this->data['password'] = $this->build->data('password', $this->request->post);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/login'));
    }

    protected function validate() {
        if (empty($this->request->post['email']) || empty($this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        if (!$this->error && !$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        return !$this->error;
    }
}