<?php
defined('_PATH') or die('Restricted!');

class ControllerTotalSubTotalSubTotal extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('total/sub_total/sub_total');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_totals'),
            'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('total/sub_total/sub_total', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('sub_total', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['action'] = $this->url->link('total/sub_total/sub_total', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        $setting = $this->model_system_setting->getSetting('sub_total');

        $this->data['sub_total_status'] = $this->build->data('sub_total_status', $this->request->post, $setting);
        $this->data['sub_total_sort_order'] = $this->build->data('sub_total_sort_order', $this->request->post, $setting);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('total/sub_total/sub_total'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'total/sub_total')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}