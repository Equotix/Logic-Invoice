<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonMaintenance extends Controller {
    public function index() {
        if ($this->config->get('config_maintenance')) {
            $load = '';

            if (isset($this->request->get['load'])) {
                $part = explode('/', $this->request->get['load']);

                if (isset($part[0])) {
                    $load .= $part[0];
                }
            }

            $this->load->library('user');

            $this->user = new User($this->registry);

            if ($load != 'api' && !$this->user->isLogged()) {
                return new Action('common/maintenance/info');
            }
        }
    }

    public function info() {
        $this->data = $this->load->language('common/maintenance');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server['SERVER_PROTOCOL'] == 'HTTP/1.1') {
            $this->response->addHeader('HTTP/1.1 503 Service Unavailable');
        } else {
            $this->response->addHeader('HTTP/1.0 503 Service Unavailable');
        }

        $this->response->addHeader('Retry-After: 3600');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/maintenance')
        );

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('common/maintenance'));
    }
}