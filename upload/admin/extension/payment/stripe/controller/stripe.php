<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentStripeStripe extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('payment/stripe/stripe');

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
            'href' => $this->url->link('payment/stripe/stripe', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('stripe', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }

        $this->data['action'] = $this->url->link('payment/stripe/stripe', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        $setting = $this->model_system_setting->getSetting('stripe');

        $this->data['stripe_testmode'] = $this->build->data('stripe_testmode', $this->request->post, $setting);
        $this->data['stripe_status'] = $this->build->data('stripe_status', $this->request->post, $setting);

        $this->data['stripe_test_public_key'] = $this->build->data('stripe_test_public_key', $this->request->post, $setting);
        $this->data['stripe_test_secret_key'] = $this->build->data('stripe_test_secret_key', $this->request->post, $setting);
        $this->data['stripe_public_key'] = $this->build->data('stripe_public_key', $this->request->post, $setting);
        $this->data['stripe_secret_key'] = $this->build->data('stripe_secret_key', $this->request->post, $setting);
        $this->data['stripe_sort_order'] = $this->build->data('stripe_sort_order', $this->request->post, $setting);
        
        $this->data['stripe_success'] = $this->build->data('stripe_success', $this->request->post, $setting);
        $this->data['stripe_carderror'] = $this->build->data('stripe_carderror', $this->request->post, $setting);
        $this->data['stripe_invalidrequest'] = $this->build->data('stripe_invalidrequest', $this->request->post, $setting);
        $this->data['stripe_authentication'] = $this->build->data('stripe_authentication', $this->request->post, $setting);
        $this->data['stripe_apiconnection'] = $this->build->data('stripe_apiconnection', $this->request->post, $setting);
        $this->data['stripe_genericerror'] = $this->build->data('stripe_genericerror', $this->request->post, $setting);
        $this->data['stripe_other'] = $this->build->data('stripe_other', $this->request->post, $setting);

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('payment/stripe/stripe'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/stripe')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}