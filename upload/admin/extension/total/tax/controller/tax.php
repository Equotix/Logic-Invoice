<?php
defined('_PATH') or die('Restricted!');

class ControllerTotalTaxTax extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('total/tax/tax');

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
            'href' => $this->url->link('total/tax/tax', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('tax', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['action'] = $this->url->link('total/tax/tax', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        $setting = $this->model_system_setting->getSetting('tax');

        $this->data['tax_status'] = $this->build->data('tax_status', $this->request->post, $setting);
        $this->data['tax_sort_order'] = $this->build->data('tax_sort_order', $this->request->post, $setting);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('total/tax/tax'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'total/tax')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}