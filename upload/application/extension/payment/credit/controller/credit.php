<?php
defined('_PATH') or die('Restricted!');

class ControllerPaymentCreditCredit extends Controller {
    public function index() {
        $this->data = $this->load->language('payment/credit/credit');

        $this->load->model('billing/invoice');
        $this->load->model('billing/customer');

        $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

        if ($invoice_info) {
            $credit = $this->model_billing_customer->getCustomerTotalCredits($this->customer->getId());

            $this->data['credit'] = sprintf($this->language->get('text_credit'), $this->currency->format($credit));

            if ($credit >= $invoice_info['total']) {
                $this->data['action'] = $this->url->link('payment/credit/credit/confirm', 'invoice_id=' . $invoice_info['invoice_id'], true);
            } else {
                $this->data['warning'] = $this->language->get('error_insufficient');
            }

            $this->response->setOutput($this->render('payment/credit/credit'));
        }
    }

    public function confirm() {
        $this->load->language('payment/credit/credit');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/invoice', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['invoice_id'])) {
            $invoice_id = (int)$this->request->get['invoice_id'];
        } else {
            $invoice_id = 0;
        }

        $this->load->model('billing/invoice');
        $this->load->model('billing/customer');

        $invoice_info = $this->model_billing_invoice->getInvoice($invoice_id, $this->customer->getId());

        if ($invoice_info) {
            $credit = $this->model_billing_customer->getCustomerTotalCredits($this->customer->getId());

            $pending_status = is_array($this->config->get('config_pending_status')) ? $this->config->get('config_pending_status') : array();
            $overdue_status = is_array($this->config->get('config_overdue_status')) ? $this->config->get('config_overdue_status') : array();

            if ($credit < $invoice_info['total'] || !(in_array($invoice_info['status_id'], $pending_status) || in_array($invoice_info['status_id'], $overdue_status))) {
                return new Action('error/not_found');
            }

            $data = array(
                'status_id' => $this->config->get('credit_completed_status_id'),
                'comment'   => ''
            );

            $this->model_billing_invoice->addHistory($invoice_id, $data, true);

            $data = array(
                'customer_id' => $invoice_info['customer_id'],
                'amount'      => -$invoice_info['total'],
                'description' => sprintf($this->language->get('text_invoice'), $invoice_info['invoice_id'])
            );

            $this->model_billing_customer->addCredit($data);

            $this->load->model('system/status');

            $status = $this->model_system_status->getStatus($this->config->get('credit_completed_status_id'));

            $this->load->model('system/activity');

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $invoice_info['invoice_id'], $status['name']));
        }

        $this->response->redirect($this->url->link('account/invoice/success', 'invoice_id=' . $invoice_id, true));
    }
}