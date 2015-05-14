<?php
class ControllerPaymentBankTransfer extends Controller {
    public function index() {
        $this->data = $this->load->language('payment/bank_transfer');

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

        if ($invoice_info) {
            $details = $this->config->get('bank_transfer_details');

            $this->data['details'] = html_entity_decode(nl2br($details[$this->config->get('config_language_id')], ENT_QUOTES));

            $this->data['action'] = $this->url->link('payment/bank_transfer/confirm', 'invoice_id=' . $invoice_info['invoice_id'], 'SSL');

            $this->response->setOutput($this->render('payment/bank_transfer.tpl'));
        }
    }

    public function confirm() {
        $this->load->language('payment/bank_transfer');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/invoice', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        if (isset($this->request->get['invoice_id'])) {
            $invoice_id = (int)$this->request->get['invoice_id'];
        } else {
            $invoice_id = 0;
        }

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice($invoice_id, $this->customer->getId());

        if ($invoice_info) {
            if (!(in_array($invoice_info['status_id'], $this->config->get('config_pending_status')) || in_array($invoice_info['status_id'], $this->config->get('config_overdue_status')))) {
                return new Action('error/not_found');
            }

            $data = array(
                'status_id' => $this->config->get('bank_transfer_completed_status_id'),
                'comment'   => ''
            );

            $this->model_billing_invoice->addHistory($invoice_id, $data, true);

            $this->load->model('system/status');

            $status = $this->model_system_status->getStatus($this->config->get('bank_transfer_completed_status_id'));

            $this->load->model('system/activity');

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $invoice_info['invoice_id'], $status['name']));
        }

        $this->response->redirect($this->url->link('account/invoice/success', 'invoice_id=' . $invoice_id, 'SSL'));
    }
}