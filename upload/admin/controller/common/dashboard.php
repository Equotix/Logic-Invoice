<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonDashboard extends Controller {
    public function index() {
        $this->data = $this->load->language('common/dashboard');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['text_total_invoices'] = sprintf($this->language->get('text_total_invoices'), date('Y'));
        $this->data['text_total_journal_entries'] = sprintf($this->language->get('text_total_journal_entries'), date('Y'));

        $this->data['invoice'] = $this->url->link('billing/invoice', 'token=' . $this->session->data['token'], true);
        $this->data['journal'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'], true);
        $this->data['recurring'] = $this->url->link('billing/recurring', 'token=' . $this->session->data['token'], true);
        $this->data['customer'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'], true);

        $this->load->model('report/invoice');

        $filter_data = array(
            'filter_date_issued_start' => date('Y') . '-01-01',
            'filter_date_issued_end'   => date('Y') . '-12-31'
        );

        $this->data['total_invoices'] = number_format($this->model_report_invoice->getTotalInvoices($filter_data), 0, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

        $this->load->model('report/transaction');

        $filter_data = array(
            'filter_date_start' => date('Y') . '-01-01',
            'filter_date_end'   => date('Y') . '-12-31'
        );

        $this->data['total_journal_entries'] = number_format($this->model_report_transaction->getTotalTransactions($filter_data), 0, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

        $this->load->model('report/recurring');

        $filter_data = array(
            'filter_status' => 1
        );

        $this->data['total_recurring'] = number_format($this->model_report_recurring->getTotalRecurrings($filter_data), 0, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

        $this->load->model('report/customer');

        $filter_data = array(
            'filter_status' => 1
        );

        $this->data['total_customers'] = number_format($this->model_report_customer->getTotalCustomers($filter_data), 0, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

        $this->load->model('billing/invoice');

        $filter_data = array(
            'sort'  => 'date_issued',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10
        );

        $invoices = $this->model_billing_invoice->getInvoices($filter_data);

        $this->data['invoices'] = array();

        foreach ($invoices as $invoice) {
            $this->data['invoices'][] = array(
                'invoice_id' => $invoice['invoice_id'],
                'name'       => $invoice['firstname'] . ' ' . $invoice['lastname'],
                'total'      => $this->currency->format($invoice['total'], $invoice['currency_code'], $invoice['currency_value']),
                'status'     => $invoice['status'],
                'date_due'   => date($this->language->get('date_format_short'), strtotime($invoice['date_due'])),
                'view'       => $this->url->link('billing/invoice/view', 'token=' . $this->session->data['token'] . '&invoice_id=' . $invoice['invoice_id'], true),
                'invoice'    => $this->url->link('billing/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . $invoice['invoice_id'], true)
            );
        }

        $this->load->model('accounting/transaction');

        $filter_data = array(
            'sort'  => 'date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10
        );

        $transactions = $this->model_accounting_transaction->getTransactions($filter_data);

        $this->data['transactions'] = array();

        foreach ($transactions as $transaction) {
            $this->data['transactions'][] = array(
                'description' => utf8_strlen($transaction['description']) > 20 ? utf8_substr($transaction['description'], 0, 20) . '...' : $transaction['description'],
                'invoice_id'  => $transaction['invoice_id'] ? $transaction['invoice_id'] : $this->language->get('text_none'),
                'date'        => date($this->language->get('date_format_short'), strtotime($transaction['date'])),
                'date_added'  => date($this->language->get('datetime_format_short'), strtotime($transaction['date_added'])),
                'edit'        => $this->url->link('accounting/journal/form', 'token=' . $this->session->data['token'] . '&transaction_id=' . $transaction['transaction_id'], true)
            );
        }

        $this->load->model('system/activity');

        $filter_data = array(
            'start' => 0,
            'limit' => 10
        );

        $activities = $this->model_system_activity->getActivities($filter_data);

        $this->data['activities'] = array();

        foreach ($activities as $activity) {
            $this->data['activities'][] = array(
                'date_added' => date($this->language->get('datetime_format_short'), strtotime($activity['date_added'])),
                'message'    => $activity['message']
            );
        }

        if ($this->config->get('config_auto_update_currency')) {
            $this->load->model('accounting/currency');

            $this->model_accounting_currency->updateCurrencies(false);
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('common/dashboard'));
    }
}