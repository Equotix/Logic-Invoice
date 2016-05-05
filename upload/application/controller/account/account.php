<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountAccount extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
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
            'href' => $this->url->link('account/account', '', true)
        );

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $this->data['update'] = $this->url->link('account/update', '', true);
        $this->data['password'] = $this->url->link('account/password', '', true);
        $this->data['invoice'] = $this->url->link('account/invoice', '', true);
        $this->data['recurring'] = $this->url->link('account/recurring', '', true);
        $this->data['credit'] = $this->url->link('account/credit', '', true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/account'));
    }
}