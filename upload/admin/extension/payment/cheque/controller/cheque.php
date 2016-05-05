<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentChequeCheque extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('payment/cheque/cheque');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payments'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/cheque/cheque', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('cheque', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }

        $this->data['action'] = $this->url->link('payment/cheque/cheque', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_payable'] = $this->build->data('payable', $this->error);

        $setting = $this->model_system_setting->getSetting('cheque');

        $this->data['cheque_payable'] = $this->build->data('cheque_payable', $this->request->post, $setting);
        $this->data['cheque_details'] = $this->build->data('cheque_details', $this->request->post, $setting, array());
        $this->data['cheque_completed_status_id'] = $this->build->data('cheque_completed_status_id', $this->request->post, $setting);
        $this->data['cheque_status'] = $this->build->data('cheque_status', $this->request->post, $setting);
        $this->data['cheque_sort_order'] = $this->build->data('cheque_sort_order', $this->request->post, $setting);

        $this->load->model('system/language');

        $this->data['languages'] = $this->model_system_language->getLanguages();

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('payment/cheque/cheque'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/cheque')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['cheque_payable'])) {
            $this->error['payable'] = $this->language->get('error_payable');
        }

        return !$this->error;
    }
}