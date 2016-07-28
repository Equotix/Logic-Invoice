<?php
defined('_PATH') or die('Restricted!');

class ControllerReportSFP extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('report/sfp');

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
            'href' => $this->url->link('report/sfp', 'token=' . $this->session->data['token'], true)
        );

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        $filter_data = array(
            'filter_date_start' => false,
            'filter_date_end'   => $filter_date_end
        );

        $this->load->model('accounting/account');
        $this->load->model('report/transaction');

        $current_asset = array(
            'current_asset',
            'prepayment'
        );

        $accounts = $this->model_accounting_account->getAccountsByType($current_asset);

        $this->data['current_asset_accounts'] = array();

        $current_asset_total = 0;

        foreach ($accounts as $account) {
            if ($account['status']) {
                $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                if (($transaction_total['debit'] - $transaction_total['credit']) != 0) {
                    $this->data['current_asset_accounts'][] = array(
                        'name'  => $account['name'],
                        'total' => $this->currency->format($transaction_total['debit'] - $transaction_total['credit'], $this->config->get('config_currency'))
                    );

                    $current_asset_total += $transaction_total['debit'] - $transaction_total['credit'];
                }
            }
        }

        $this->data['current_asset_total'] = $this->currency->format($current_asset_total, $this->config->get('config_currency'));

        $non_current_asset = array(
            'fixed_asset',
            'non_current_asset'
        );

        $accounts = $this->model_accounting_account->getAccountsByType($non_current_asset);

        $this->data['non_current_asset_accounts'] = array();

        $non_current_asset_total = 0;

        foreach ($accounts as $account) {
            if ($account['status']) {
                $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                if (($transaction_total['debit'] - $transaction_total['credit']) != 0) {
                    $this->data['non_current_asset_accounts'][] = array(
                        'name'  => $account['name'],
                        'total' => $this->currency->format($transaction_total['debit'] - $transaction_total['credit'], $this->config->get('config_currency'))
                    );

                    $non_current_asset_total += $transaction_total['debit'] - $transaction_total['credit'];
                }
            }
        }

        $this->data['non_current_asset_total'] = $this->currency->format($non_current_asset_total, $this->config->get('config_currency'));

        $this->data['asset_total'] = $this->currency->format($current_asset_total + $non_current_asset_total, $this->config->get('config_currency'));

        $expense = array(
            'depreciation',
            'direct_cost',
            'expense',
            'overhead'
        );

        $revenue = array(
            'other_income',
            'revenue',
            'sale'
        );

        $equity = array(
            'equity'
        );

        $accounts = $this->model_accounting_account->getAccountsByType($equity);

        $this->data['equity_accounts'] = array();

        $equity_total = 0;

        foreach ($accounts as $account) {
            if ($account['status']) {
                if ($account['retained_earnings']) {
                    $revenue_accounts = $this->model_accounting_account->getAccountsByType($revenue);

                    $revenue = 0;

                    foreach ($revenue_accounts as $revenue_account) {
                        $transaction_total = $this->model_report_transaction->getTotalByAccount($revenue_account['account_id'], $filter_data);

                        $revenue += ($transaction_total['credit'] - $transaction_total['debit']);
                    }

                    $expense_accounts = $this->model_accounting_account->getAccountsByType($expense);

                    $expense = 0;

                    foreach ($expense_accounts as $expense_account) {
                        $transaction_total = $this->model_report_transaction->getTotalByAccount($expense_account['account_id'], $filter_data);

                        $expense += ($transaction_total['debit'] - $transaction_total['credit']);
                    }

                    $equity_total += $revenue - $expense;

                    $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                    $equity_total += $transaction_total['credit'] - $transaction_total['debit'];

                    if ($equity_total > 0 || $equity_total < 0) {
                        $this->data['equity_accounts'][] = array(
                            'name'  => $account['name'],
                            'total' => $this->currency->format($equity_total, $this->config->get('config_currency'))
                        );
                    }
                } else {
                    $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                    if (($transaction_total['credit'] - $transaction_total['debit']) != 0) {
                        $this->data['equity_accounts'][] = array(
                            'name'  => $account['name'],
                            'total' => $this->currency->format($transaction_total['credit'] - $transaction_total['debit'], $this->config->get('config_currency'))
                        );

                        $equity_total += $transaction_total['credit'] - $transaction_total['debit'];
                    }
                }
            }
        }

        $this->data['equity_total'] = $this->currency->format($equity_total, $this->config->get('config_currency'));

        $current_liability = array(
            'current_liability',
            'liability'
        );

        $accounts = $this->model_accounting_account->getAccountsByType($current_liability);

        $this->data['current_liability_accounts'] = array();

        $current_liability_total = 0;

        foreach ($accounts as $account) {
            if ($account['status']) {
                $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                if (($transaction_total['credit'] - $transaction_total['debit']) != 0) {
                    $this->data['current_liability_accounts'][] = array(
                        'name'  => $account['name'],
                        'total' => $this->currency->format($transaction_total['credit'] - $transaction_total['debit'], $this->config->get('config_currency'))
                    );

                    $current_liability_total += $transaction_total['credit'] - $transaction_total['debit'];
                }
            }
        }

        $this->data['current_liability_total'] = $this->currency->format($current_liability_total, $this->config->get('config_currency'));

        $non_current_liability = array(
            'non_current_liability'
        );

        $accounts = $this->model_accounting_account->getAccountsByType($non_current_liability);

        $this->data['non_current_liability_accounts'] = array();

        $non_current_liability_total = 0;

        foreach ($accounts as $account) {
            if ($account['status']) {
                if (($transaction_total['credit'] - $transaction_total['debit']) != 0) {
                    $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $filter_data);

                    $this->data['non_current_liability_accounts'][] = array(
                        'name'  => $account['name'],
                        'total' => $this->currency->format($transaction_total['credit'] - $transaction_total['debit'], $this->config->get('config_currency'))
                    );

                    $non_current_liability_total += $transaction_total['credit'] - $transaction_total['debit'];
                }
            }
        }

        $this->data['non_current_liability_total'] = $this->currency->format($non_current_liability_total, $this->config->get('config_currency'));

        $this->data['liability_total'] = $this->currency->format($current_liability_total + $non_current_liability_total, $this->config->get('config_currency'));

        $this->data['liability_equity_total'] = $this->currency->format($current_liability_total + $non_current_liability_total + $equity_total, $this->config->get('config_currency'));

        $this->data['filter_date_end'] = $filter_date_end;
        $this->data['date_end'] = date('d M Y', strtotime($filter_date_end));

        $url = $this->build->url(array(
            'filter_date_end'
        ));

        $this->data['print'] = $this->url->link('report/sfp', 'token=' . $this->session->data['token'] . '&print_version=1' . $url, true);

        if (isset($this->request->get['print_version'])) {
            $this->data['print_version'] = true;
        } else {
            $this->data['print_version'] = false;
        }

        $this->data['token'] = $this->session->data['token'];

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('report/sfp'));
    }
}