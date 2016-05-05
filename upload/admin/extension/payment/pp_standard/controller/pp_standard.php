<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentPPStandardPPStandard extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('payment/pp_standard/pp_standard');

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
            'href' => $this->url->link('payment/pp_standard/pp_standard', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('pp_standard', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }

        $this->data['action'] = $this->url->link('payment/pp_standard/pp_standard', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_email'] = $this->build->data('email', $this->error);

        $setting = $this->model_system_setting->getSetting('pp_standard');

        $this->data['pp_standard_email'] = $this->build->data('pp_standard_email', $this->request->post, $setting);
        $this->data['pp_standard_sandbox'] = $this->build->data('pp_standard_sandbox', $this->request->post, $setting);
        $this->data['pp_standard_transaction'] = $this->build->data('pp_standard_transaction', $this->request->post, $setting);
        $this->data['pp_standard_debug'] = $this->build->data('pp_standard_debug', $this->request->post, $setting);
        $this->data['pp_standard_cancelled_reversal'] = $this->build->data('pp_standard_cancelled_reversal', $this->request->post, $setting);
        $this->data['pp_standard_completed'] = $this->build->data('pp_standard_completed', $this->request->post, $setting);
        $this->data['pp_standard_denied'] = $this->build->data('pp_standard_denied', $this->request->post, $setting);
        $this->data['pp_standard_expired'] = $this->build->data('pp_standard_expired', $this->request->post, $setting);
        $this->data['pp_standard_failed'] = $this->build->data('pp_standard_failed', $this->request->post, $setting);
        $this->data['pp_standard_pending'] = $this->build->data('pp_standard_pending', $this->request->post, $setting);
        $this->data['pp_standard_processed'] = $this->build->data('pp_standard_processed', $this->request->post, $setting);
        $this->data['pp_standard_refunded'] = $this->build->data('pp_standard_refunded', $this->request->post, $setting);
        $this->data['pp_standard_reversed'] = $this->build->data('pp_standard_reversed', $this->request->post, $setting);
        $this->data['pp_standard_voided'] = $this->build->data('pp_standard_voided', $this->request->post, $setting);
        $this->data['pp_standard_status'] = $this->build->data('pp_standard_status', $this->request->post, $setting);
        $this->data['pp_standard_sort_order'] = $this->build->data('pp_standard_sort_order', $this->request->post, $setting);

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('payment/pp_standard/pp_standard'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/pp_standard')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['pp_standard_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['pp_standard_email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        return !$this->error;
    }
}