<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentAuthorizeNetSimAuthorizeNetSim extends Controller {
    public function index() {
        $this->data = $this->load->language('payment/authorizenet_sim/authorizenet_sim');

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

        if ($invoice_info) {
            if ($this->config->get('authorizenet_sim_server') == 'test') {
                $this->data['action'] = 'https://sandbox.authorize.net/gateway/transact.dll';
            } else {
                $this->data['action'] = 'https://secure.authorize.net/gateway/transact.dll';
            }

            $this->data['x_login'] = $this->config->get('authorizenet_sim_merchant');
            $this->data['x_fp_sequence'] = $invoice_info['invoice_id'];
            $this->data['x_fp_timestamp'] = time();
            $this->data['x_amount'] = $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value'], false);
            $this->data['x_show_form'] = 'PAYMENT_FORM';
            $this->data['x_test_request'] = $this->config->get('authorizenet_sim_mode') == 'test' ? true : false;
            $this->data['x_type'] = $this->config->get('authorizenet_sim_method') == 'authorization' ? 'AUTH_ONLY' : 'AUTH_CAPTURE';
            $this->data['x_currency_code'] = $invoice_info['currency_code'];
            $this->data['x_invoice_num'] = $invoice_info['order_id'];
            $this->data['x_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
            $this->data['x_first_name'] = html_entity_decode($invoice_info['payment_firstname'] ? $invoice_info['payment_firstname'] : $invoice_info['firstname'], ENT_QUOTES, 'UTF-8');
            $this->data['x_last_name'] = html_entity_decode($invoice_info['payment_lastname'] ? $invoice_info['payment_lastname'] : $invoice_info['lastname'], ENT_QUOTES, 'UTF-8');
            $this->data['x_company'] = html_entity_decode($invoice_info['payment_company'] ? $invoice_info['payment_company'] : $invoice_info['company'], ENT_QUOTES, 'UTF-8');
            $this->data['x_address'] = html_entity_decode($invoice_info['payment_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($invoice_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
            $this->data['x_city'] = html_entity_decode($invoice_info['payment_city'], ENT_QUOTES, 'UTF-8');
            $this->data['x_state'] = html_entity_decode($invoice_info['payment_zone'], ENT_QUOTES, 'UTF-8');
            $this->data['x_zip'] = html_entity_decode($invoice_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
            $this->data['x_country'] = html_entity_decode($invoice_info['payment_country'], ENT_QUOTES, 'UTF-8');
            $this->data['x_customer_ip'] = $this->request->server['REMOTE_ADDR'];
            $this->data['x_email'] = $invoice_info['email'];
            $this->data['x_relay_response'] = 'true';

            $data['x_fp_hash'] = hash_hmac('md5', $data['x_login'] . '^' . $data['x_fp_sequence'] . '^' . $data['x_fp_timestamp'] . '^' . $data['x_amount'] . '^' . $data['x_currency_code'], $this->config->get('authorizenet_sim_transaction_key'));

            $this->response->setOutput($this->render('payment/authorizenet_sim/authorizenet_sim'));
        }
    }

    public function callback() {
        if (md5($this->config->get('authorizenet_sim_response_key') . $this->request->post['x_login'] . $this->request->post['x_trans_id'] . $this->request->post['x_amount']) == strtolower($this->request->post['x_MD5_Hash'])) {
            $this->load->model('billing/invoice');

            $invoice_info = $this->model_billing_invoice->getInvoice($this->request->post['x_invoice_num']);

            $status_id = $this->config->get('authorizenet_sim_denied_status_id');

            $message = '';

            if ($invoice_info && $this->request->post['x_response_code'] == '1') {
                $status_id = $this->config->get('authorizenet_sim_completed_status_id');

                if (isset($this->request->post['x_response_reason_text'])) {
                    $message .= 'Response Text: ' . $this->request->post['x_response_reason_text'] . "\n";
                }

                if (isset($this->request->post['exact_issname'])) {
                    $message .= 'Issuer: ' . $this->request->post['exact_issname'] . "\n";
                }

                if (isset($this->request->post['exact_issconf'])) {
                    $message .= 'Confirmation Number: ' . $this->request->post['exact_issconf'] . "\n";
                }

                if (isset($this->request->post['exact_ctr'])) {
                    $message .= 'Receipt: ' . $this->request->post['exact_ctr'] . "\n";
                }
            } else {
                $message .= 'Transaction failed.' . "\n";
            }

            $data = array(
                'status_id' => $status_id,
                'comment'   => $message
            );

            $this->model_billing_invoice->addHistory($invoice_info['invoice_id'], $data, true);

            $this->load->model('system/status');

            $status = $this->model_system_status->getStatus($status_id);

            $this->load->model('system/activity');

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $invoice_info['invoice_id'], $status['name']));

            $this->response->redirect($this->url->link('account/invoice/success', 'invoice_id=' . $invoice_info['invoice_id'], true));
        } else {
            $this->response->redirect($this->url->link('account/invoice', '', true));
        }
    }
}