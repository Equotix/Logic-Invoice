<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountAccount extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $this->data = $this->load->language('account/account');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $this->data['update'] = $this->url->link('account/update', '', 'SSL');
        $this->data['password'] = $this->url->link('account/password', '', 'SSL');
        $this->data['invoice'] = $this->url->link('account/invoice', '', 'SSL');
        $this->data['recurring'] = $this->url->link('account/recurring', '', 'SSL');
        $this->data['credit'] = $this->url->link('account/credit', '', 'SSL');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/account/account.tpl')) {
            $this->response->setOutput($this->render($this->config->get('config_theme') . '/template/account/account.tpl'));
        } else {
            $this->response->setOutput($this->render('default/template/account/account.tpl'));
        }
    }
}