<?php
defined('_PATH') or die('Restricted!');

class ControllerReportSCI extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('report/sci');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/sci', 'token=' . $this->session->data['token'], true)
        );

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(str_replace('/', '-', $this->config->get('config_financial_year')) . '-' . date('Y') . ' -1 year + 1 day'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        $filter_data = array(
            'filter_date_start' => $filter_date_start,
            'filter_date_end'   => $filter_date_end
        );

        $this->load->model('accounting/account');
        $this->load->model('report/transaction');

        $revenue = array(
            'other_income',
            'revenue',
            'sale'
        );

        $accounts = $this->model_accounting_account->getAccountsByType($revenue);

        $this->data['revenue_accounts'] = array();

        $revenue_total = 0;

        foreach ($accounts as $account) {
            if ($account['status']) {
                $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                if (($transaction_total['credit'] - $transaction_total['debit']) != 0) {
                    $this->data['revenue_accounts'][] = array(
                        'name'  => $account['name'],
                        'total' => $this->currency->format($transaction_total['credit'] - $transaction_total['debit'], $this->config->get('config_currency'))
                    );

                    $revenue_total += $transaction_total['credit'] - $transaction_total['debit'];
                }
            }
        }

        $this->data['revenue_total'] = $this->currency->format($revenue_total, $this->config->get('config_currency'));

        $expense = array(
            'depreciation',
            'direct_cost',
            'expense',
            'overhead'
        );

        $accounts = $this->model_accounting_account->getAccountsByType($expense);

        $this->data['expense_accounts'] = array();

        $expense_total = 0;

        foreach ($accounts as $account) {
            if ($account['status']) {
                $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                if (($transaction_total['debit'] - $transaction_total['credit']) != 0) {
                    $this->data['expense_accounts'][] = array(
                        'name'  => $account['name'],
                        'total' => $this->currency->format($transaction_total['debit'] - $transaction_total['credit'], $this->config->get('config_currency'))
                    );

                    $expense_total += $transaction_total['debit'] - $transaction_total['credit'];
                }
            }
        }

        $this->data['expense_total'] = $this->currency->format($expense_total, $this->config->get('config_currency'));

        $this->data['net_profit'] = $this->currency->format($revenue_total - $expense_total, $this->config->get('config_currency'));

        $this->data['filter_date_start'] = $filter_date_start;
        $this->data['filter_date_end'] = $filter_date_end;
        $this->data['date_start'] = date('d M Y', strtotime($filter_date_start));
        $this->data['date_end'] = date('d M Y', strtotime($filter_date_end));

        $url = $this->build->url(array(
            'filter_date_start',
            'filter_date_end'
        ));

        $this->data['print'] = $this->url->link('report/sci', 'token=' . $this->session->data['token'] . '&print_version=1' . $url, true);

        if (isset($this->request->get['print_version'])) {
            $this->data['print_version'] = true;
        } else {
            $this->data['print_version'] = false;
        }

        $this->data['token'] = $this->session->data['token'];

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('report/sci'));
    }
}