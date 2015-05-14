<?php
class ControllerSystemError extends Controller {
    public function index() {
        $this->data = $this->load->language('system/error');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/error', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);

        $this->data['clear'] = $this->url->link('system/error/clear', 'token=' . $this->session->data['token'], 'SSL');

        $file = DIR_LOGS . $this->config->get('config_error_filename');

        if (file_exists($file)) {
            $this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
        } else {
            $this->data['log'] = '';
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/error.tpl'));
    }

    public function clear() {
        $this->load->language('system/error');

        if (!$this->user->hasPermission('modify', 'system/error')) {
            $this->session->data['warning'] = $this->language->get('error_permission');
        } else {
            $file = DIR_LOGS . $this->config->get('config_error_filename');

            $handle = fopen($file, 'w+');

            fclose($handle);

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->redirect($this->url->link('system/error', 'token=' . $this->session->data['token'], 'SSL'));
    }
}