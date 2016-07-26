<?php
defined('_PATH') or die('Restricted!');

class ControllerReportChartOfAccounts extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('report/chart_of_accounts');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/chart_of_accounts', 'token=' . $this->session->data['token'], true)
        );

        $restricted = array(
            'filter_date_start' => date('Y-m-d', strtotime(str_replace('/', '-', $this->config->get('config_financial_year')) . '-' . date('Y') . ' -1 year + 1 day')),
            'filter_date_end'   => date('Y-m-d')
        );

        $unrestricted = array(
            'filter_date_start' => false,
            'filter_date_end'   => date('Y-m-d')
        );

        $this->data['column_ytd'] = sprintf($this->language->get('column_ytd'), date('d M Y'));

        $this->data['asset'] = array(
            'current_asset',
            'fixed_asset',
            'non_current_asset',
            'prepayment'
        );

        $this->data['equity'] = array(
            'equity'
        );

        $this->data['expense'] = array(
            'depreciation',
            'direct_cost',
            'expense',
            'overhead'
        );

        $this->data['liability'] = array(
            'current_liability',
            'liability',
            'non_current_liability'
        );

        $this->data['revenue'] = array(
            'other_income',
            'revenue',
            'sale'
        );

        $this->data['accounts'] = array();

        $this->load->model('accounting/account');
        $this->load->model('report/transaction');

        $accounts = $this->model_accounting_account->getAccounts();

        foreach ($accounts as $account) {
            $children_data = array();

            $filter_data = array(
                'filter_parent_id' => $account['account_id']
            );

            $children = $this->model_accounting_account->getAccounts($filter_data);

            foreach ($children as $child) {
                $grandchildren_data = array();

                $filter_data = array(
                    'filter_parent_id' => $child['account_id']
                );

                $grandchildren = $this->model_accounting_account->getAccounts($filter_data);

                foreach ($grandchildren as $grandchild) {
                    if ($grandchild['status']) {
                        $ytd = 0;

                        if (in_array($grandchild['type'], $this->data['expense']) || in_array($grandchild['type'], $this->data['revenue'])) {
                            $transaction_total = $this->model_report_transaction->getTotalByAccount($grandchild['account_id'], $restricted);
                        } else {
                            $transaction_total = $this->model_report_transaction->getTotalByAccount($grandchild['account_id'], $unrestricted);
                        }

                        if (in_array($grandchild['type'], $this->data['asset']) || in_array($grandchild['type'], $this->data['expense'])) {
                            $ytd = $this->currency->format($transaction_total['debit'] - $transaction_total['credit'], $this->config->get('config_currency'));
                        } else {
                            $ytd = $this->currency->format($transaction_total['credit'] - $transaction_total['debit'], $this->config->get('config_currency'));
                        }

                        $grandchildren_data[] = array(
                            'account_id'     => $grandchild['account_id'],
                            'name'           => $grandchild['name'],
                            'description'    => nl2br($grandchild['description']),
                            'type'           => $grandchild['type'],
                            'formatted_type' => $this->language->get('text_' . $grandchild['type']),
                            'ytd'            => $ytd
                        );
                    }
                }

                if ($child['status']) {
                    $ytd = 0;

                    if (!$grandchildren_data) {
                        if (in_array($child['type'], $this->data['expense']) || in_array($child['type'], $this->data['revenue'])) {
                            $transaction_total = $this->model_report_transaction->getTotalByAccount($child['account_id'], $restricted);
                        } else {
                            $transaction_total = $this->model_report_transaction->getTotalByAccount($child['account_id'], $unrestricted);
                        }

                        if (in_array($child['type'], $this->data['asset']) || in_array($child['type'], $this->data['expense'])) {
                            $ytd = $this->currency->format($transaction_total['debit'] - $transaction_total['credit'], $this->config->get('config_currency'));
                        } else {
                            $ytd = $this->currency->format($transaction_total['credit'] - $transaction_total['debit'], $this->config->get('config_currency'));
                        }
                    }

                    $children_data[] = array(
                        'account_id'     => $child['account_id'],
                        'name'           => $child['name'],
                        'description'    => nl2br($child['description']),
                        'type'           => $child['type'],
                        'formatted_type' => $this->language->get('text_' . $child['type']),
                        'ytd'            => $ytd,
                        'grandchildren'  => $grandchildren_data
                    );
                }
            }

            if ($account['status']) {
                $ytd = 0;

                if (!$children_data) {
                    if (in_array($account['type'], $this->data['expense']) || in_array($account['type'], $this->data['revenue'])) {
                        $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $restricted);
                    } else {
                        $transaction_total = $this->model_report_transaction->getTotalByAccount($account['account_id'], $unrestricted);
                    }

                    if (in_array($account['type'], $this->data['asset']) || in_array($account['type'], $this->data['expense'])) {
                        $ytd = $this->currency->format($transaction_total['debit'] - $transaction_total['credit'], $this->config->get('config_currency'));
                    } else {
                        $ytd = $this->currency->format($transaction_total['credit'] - $transaction_total['debit'], $this->config->get('config_currency'));
                    }
                }

                if ($account['retained_earnings']) {
                    $revenue_accounts = $this->model_accounting_account->getAccountsByType($this->data['revenue']);

                    $revenue = 0;

                    foreach ($revenue_accounts as $revenue_account) {
                        $transaction_total = $this->model_report_transaction->getTotalByAccount($revenue_account['account_id'], $unrestricted);

                        $revenue += ($transaction_total['credit'] - $transaction_total['debit']);
                    }

                    $expense_accounts = $this->model_accounting_account->getAccountsByType($this->data['expense']);

                    $expense = 0;

                    foreach ($expense_accounts as $expense_account) {
                        $transaction_total = $this->model_report_transaction->getTotalByAccount($expense_account['account_id'], $unrestricted);

                        $expense += ($transaction_total['debit'] - $transaction_total['credit']);
                    }

                    $ytd = $this->currency->format($revenue - $expense, $this->config->get('config_currency'));
                }

                $this->data['accounts'][] = array(
                    'account_id'     => $account['account_id'],
                    'name'           => $account['name'],
                    'description'    => nl2br($account['description']),
                    'type'           => $account['type'],
                    'formatted_type' => $this->language->get('text_' . $account['type']),
                    'ytd'            => $ytd,
                    'children'       => $children_data
                );
            }
        }

        $url = $this->build->url(array(
            'filter_date_end'
        ));

        $this->data['print'] = $this->url->link('report/chart_of_accounts', 'token=' . $this->session->data['token'] . '&print_version=1' . $url, true);

        if (isset($this->request->get['print_version'])) {
            $this->data['print_version'] = true;
        } else {
            $this->data['print_version'] = false;
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('report/chart_of_accounts'));
    }
}