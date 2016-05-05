<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonPermission extends Controller {
    public function index() {
        $this->data = $this->load->language('common/permission');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->request->get['load'], 'token=' . $this->session->data['token'], true)
        );

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('common/permission'));
    }

    public function check() {
        if (isset($this->request->get['load'])) {
            $load = '';

            $part = explode('/', $this->request->get['load']);

            if (isset($part[0])) {
                $load .= $part[0];
            }

            if (isset($part[1])) {
                $load .= '/' . $part[1];
            }

            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'common/permission'
            );

            if (!in_array($load, $ignore) && !$this->user->hasPermission('access', $load)) {
                return new Action('common/permission');
            }
        }
    }
}