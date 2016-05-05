<?php
defined('_PATH') or die('Restricted!');

class ControllerErrorNotFound extends Controller {
    public function index() {
        $this->data = $this->load->language('error/not_found');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        if (isset($this->request->get['load'])) {
            $url_data = $this->request->get;

            unset($url_data['_load_']);

            $load = $url_data['load'];

            unset($url_data['load']);

            $url = '';

            if ($url_data) {
                $url = '&' . urldecode(http_build_query($url_data, '', '&'));
            }

            if ($this->request->server['HTTPS']) {
                $connection = 'SSL';
            } else {
                $connection = 'NONSSL';
            }

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link($load, $url, $connection)
            );
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('error/not_found'));
    }
}