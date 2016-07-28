<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentAuthorizenetSimAuthorizenetSim extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('payment/authorizenet_sim/authorizenet_sim');

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
            'href' => $this->url->link('payment/authorizenet_sim/authorizenet_sim', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('authorizenet_sim', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }

        $this->data['action'] = $this->url->link('payment/authorizenet_sim/authorizenet_sim', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_merchant'] = $this->build->data('merchant', $this->error);
        $this->data['error_key'] = $this->build->data('key', $this->error);

        $setting = $this->model_system_setting->getSetting('authorizenet_sim');

        $this->data['authorizenet_sim_merchant'] = $this->build->data('authorizenet_sim_merchant', $this->request->post, $setting);
        $this->data['authorizenet_sim_key'] = $this->build->data('authorizenet_sim_key', $this->request->post, $setting);
        $this->data['authorizenet_sim_hash'] = $this->build->data('authorizenet_sim_hash', $this->request->post, $setting);
        $this->data['authorizenet_sim_server'] = $this->build->data('authorizenet_sim_server', $this->request->post, $setting);
        $this->data['authorizenet_sim_mode'] = $this->build->data('authorizenet_sim_mode', $this->request->post, $setting);
        $this->data['authorizenet_sim_method'] = $this->build->data('authorizenet_sim_method', $this->request->post, $setting);
        $this->data['authorizenet_sim_completed_status_id'] = $this->build->data('authorizenet_sim_completed_status_id', $this->request->post, $setting);
        $this->data['authorizenet_sim_denied_status_id'] = $this->build->data('authorizenet_sim_denied_status_id', $this->request->post, $setting);
        $this->data['authorizenet_sim_status'] = $this->build->data('authorizenet_sim_status', $this->request->post, $setting);
        $this->data['authorizenet_sim_sort_order'] = $this->build->data('authorizenet_sim_sort_order', $this->request->post, $setting);

        $this->data['response_url'] = HTTPS_APPLICATION . 'index.php?load=payment/authorizenet_sim/authorizenet_sim/confirm';

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('payment/authorizenet_sim/authorizenet_sim'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/authorizenet_sim')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['authorizenet_sim_merchant'])) {
            $this->error['merchant'] = $this->language->get('error_merchant');
        }

        if (empty($this->request->post['authorizenet_sim_key'])) {
            $this->error['key'] = $this->language->get('error_key');
        }

        return !$this->error;
    }
}