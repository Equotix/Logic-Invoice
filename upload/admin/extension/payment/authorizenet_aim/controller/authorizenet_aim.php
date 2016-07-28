<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentAuthorizenetAimAuthorizenetAim extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('payment/authorizenet_aim/authorizenet_aim');

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
            'href' => $this->url->link('payment/authorizenet_aim/authorizenet_aim', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('authorizenet_aim', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }

        $this->data['action'] = $this->url->link('payment/authorizenet_aim/authorizenet_aim', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_login'] = $this->build->data('login', $this->error);
        $this->data['error_key'] = $this->build->data('key', $this->error);

        $setting = $this->model_system_setting->getSetting('authorizenet_aim');

        $this->data['authorizenet_aim_login'] = $this->build->data('authorizenet_aim_login', $this->request->post, $setting);
        $this->data['authorizenet_aim_key'] = $this->build->data('authorizenet_aim_key', $this->request->post, $setting);
        $this->data['authorizenet_aim_hash'] = $this->build->data('authorizenet_aim_hash', $this->request->post, $setting);
        $this->data['authorizenet_aim_server'] = $this->build->data('authorizenet_aim_server', $this->request->post, $setting);
        $this->data['authorizenet_aim_mode'] = $this->build->data('authorizenet_aim_mode', $this->request->post, $setting);
        $this->data['authorizenet_aim_method'] = $this->build->data('authorizenet_aim_method', $this->request->post, $setting);
        $this->data['authorizenet_aim_completed_status_id'] = $this->build->data('authorizenet_aim_completed_status_id', $this->request->post, $setting);
        $this->data['authorizenet_aim_denied_status_id'] = $this->build->data('authorizenet_aim_denied_status_id', $this->request->post, $setting);
        $this->data['authorizenet_aim_status'] = $this->build->data('authorizenet_aim_status', $this->request->post, $setting);
        $this->data['authorizenet_aim_sort_order'] = $this->build->data('authorizenet_aim_sort_order', $this->request->post, $setting);

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('payment/authorizenet_aim/authorizenet_aim'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/authorizenet_aim')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['authorizenet_aim_login'])) {
            $this->error['login'] = $this->language->get('error_login');
        }

        if (empty($this->request->post['authorizenet_aim_key'])) {
            $this->error['key'] = $this->language->get('error_key');
        }

        return !$this->error;
    }
}