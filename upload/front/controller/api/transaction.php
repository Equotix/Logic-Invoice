<?php
defined('_PATH') or die('Restricted!');

class ControllerApiTransaction extends Controller {
    public function add() {
        $this->load->language('api/transaction');

        $json = array();

        $json['success'] = false;

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('accounting/transaction');
            $this->load->model('system/activity');

            if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen($this->request->post['description']) > 1000)) {
                $json['error'][] = $this->language->get('error_description');
            }

            if (!(float)$this->request->post['currency_value']) {
                $json['error'][] = $this->language->get('error_currency_value');
            }

            if (empty($this->request->post['date'])) {
                $json['error'][] = $this->language->get('error_date');
            }

            $debit = 0;
            $credit = 0;

            foreach ($this->request->post['transaction_accounts'] as $account) {
                if (!isset($account['account_id'])) {
                    $json['error'][] = $this->language->get('error_account_id');
                }

                if (preg_match('/^\(.+\)$/', $account['debit'])) {
                    $account['debit'] = preg_replace('/[^\d.-]/', '', $account['debit']);

                    $account['debit'] = '-' . (float)$account['debit'];
                }

                if (preg_match('/^\(.+\)$/', $account['credit'])) {
                    $account['credit'] = preg_replace('/[^\d.-]/', '', $account['credit']);

                    $account['credit'] = '-' . (float)$account['credit'];
                }

                $debit += $account['debit'];
                $credit += $account['credit'];
            }

            if (round($debit, 4) != round($credit, 4)) {
                $json['error'][] = $this->language->get('error_account');
            }

            if (empty($json['error'])) {
                $data = array(
                    'description'          => $this->request->post['description'],
                    'currency_code'        => $this->request->post['currency_code'],
                    'currency_value'       => $this->request->post['currency_value'],
                    'invoice_id'           => isset($this->request->post['invoice_id']) ? $this->request->post['invoice_id'] : 0,
                    'date'                 => $this->request->post['date'],
                    'transaction_accounts' => $this->request->post['transaction_accounts']
                );

                $this->model_accounting_transaction->addTransaction($this->request->post);

                $this->model_system_activity->addActivity(sprintf($this->language->get('text_transaction'), $this->request->post['date'], $this->session->data['username']));

                $json['success'] = true;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}