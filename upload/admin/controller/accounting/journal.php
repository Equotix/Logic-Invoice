<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountingJournal extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('accounting/journal');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $url = $this->build->url(array(
            'filter_description',
            'flter_invoice_id',
            'filter_date',
            'filter_date_added',
            'filter_date_modified',
            'sort',
            'order',
            'page'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['filter_description'])) {
            $filter_description = $this->request->get['filter_description'];
        } else {
            $filter_description = '';
        }

        if (isset($this->request->get['filter_invoice_id'])) {
            $filter_invoice_id = $this->request->get['filter_invoice_id'];
        } else {
            $filter_invoice_id = null;
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = '';
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = '';
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = '';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'date';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        $filter_data = array(
            'filter_description'   => $filter_description,
            'filter_invoice_id'    => $filter_invoice_id,
            'filter_date'          => $filter_date,
            'filter_date_added'    => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'start'                => $this->config->get('config_limit_admin') * ($page - 1),
            'limit'                => $this->config->get('config_limit_admin'),
            'sort'                 => $sort,
            'order'                => $order
        );

        $this->load->model('accounting/transaction');

        $this->data['transactions'] = array();

        $transactions = $this->model_accounting_transaction->getTransactions($filter_data);

        foreach ($transactions as $transaction) {
            $this->data['transactions'][] = array(
                'transaction_id' => $transaction['transaction_id'],
                'description'    => utf8_strlen($transaction['description']) > 20 ? utf8_substr($transaction['description'], 0, 20) . '...' : $transaction['description'],
                'invoice_id'     => $transaction['invoice_id'],
                'invoice'        => $transaction['invoice_id'] ? $this->url->link('billing/invoice/view', 'token=' . $this->session->data['token'] . '&invoice_id=' . $transaction['invoice_id'], true) : false,
                'amount'         => $this->currency->format($transaction['amount'], $transaction['currency_code'], $transaction['currency_value']),
                'date'           => date($this->language->get('date_format_short'), strtotime($transaction['date'])),
                'date_added'     => date($this->language->get('datetime_format_short'), strtotime($transaction['date_added'])),
                'date_modified'  => date($this->language->get('datetime_format_short'), strtotime($transaction['date_modified'])),
                'edit'           => $this->url->link('accounting/journal/form', 'token=' . $this->session->data['token'] . $url . '&transaction_id=' . $transaction['transaction_id'], true)
            );
        }

        $url = $this->build->url(array(
            'filter_description',
            'flter_invoice_id',
            'filter_date',
            'filter_date_added',
            'filter_date_modified',
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_accounting_transaction->getTotalTransactions($filter_data);
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('accounting/journal/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('accounting/journal/form', 'token=' . $this->session->data['token'], true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

        $url = $this->build->url(array(
            'filter_description',
            'flter_invoice_id',
            'filter_date',
            'filter_date_added',
            'filter_date_modified'
        ));

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_description'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url . '&sort=description&order=' . $order, true);
        $this->data['sort_invoice_id'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url . '&sort=invoice_id&order=' . $order, true);
        $this->data['sort_amount'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url . '&sort=amount&order=' . $order, true);
        $this->data['sort_date'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url . '&sort=date&order=' . $order, true);
        $this->data['sort_date_added'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url . '&sort=date_added&order=' . $order, true);
        $this->data['sort_date_modified'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url . '&sort=date_modified&order=' . $order, true);

        $this->data['filter_description'] = $filter_description;
        $this->data['filter_invoice_id'] = $filter_invoice_id;
        $this->data['filter_date'] = $filter_date;
        $this->data['filter_date_added'] = $filter_date_added;
        $this->data['filter_date_modified'] = $filter_date_modified;

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/journal_list'));
    }

    public function delete() {
        $this->load->language('accounting/journal');

        $this->load->model('accounting/transaction');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $transaction_id) {
                $this->model_accounting_transaction->deleteTransaction($transaction_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/journal', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('accounting/journal');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $url = $this->build->url(array(
            'filter_description',
            'flter_invoice_id',
            'filter_date',
            'filter_date_added',
            'filter_date_modified',
            'sort',
            'order',
            'page',
            'transaction_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('accounting/transaction');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['transaction_id'])) {
                $this->model_accounting_transaction->editTransaction((int)$this->request->get['transaction_id'], $this->request->post);
            } else {
                $this->model_accounting_transaction->addTransaction($this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['transaction_id'])) {
            $transaction_info = $this->model_accounting_transaction->getTransaction((int)$this->request->get['transaction_id']);
        } else {
            $transaction_info = array();
        }

        $this->data['action'] = $this->url->link('accounting/journal/form', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['cancel'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_description'] = $this->build->data('description', $this->error);
        $this->data['error_currency_value'] = $this->build->data('currency_value', $this->error);
        $this->data['error_date'] = $this->build->data('date', $this->error);

        $this->data['description'] = $this->build->data('description', $this->request->post, $transaction_info);
        $this->data['currency_code'] = $this->build->data('currency_code', $this->request->post, $transaction_info, $this->config->get('config_currency'));
        $this->data['currency_value'] = $this->build->data('currency_value', $this->request->post, $transaction_info, '1.00');
        $this->data['invoice_id'] = $this->build->data('invoice_id', $this->request->post, $transaction_info);
        $this->data['date'] = $this->build->data('date', $this->request->post, $transaction_info, date('Y-m-d'));
        $this->data['transaction_accounts'] = $this->build->data('transaction_accounts', $this->request->post, $transaction_info, array());

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
                        $grandchildren_data[] = array(
                            'account_id' => $grandchild['account_id'],
                            'name'       => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $grandchild['name'],
                            'type'       => $grandchild['type']
                        );
                    }
                }

                if ($child['status']) {
                    $children_data[] = array(
                        'account_id'    => $child['account_id'],
                        'name'          => '&nbsp;&nbsp;&nbsp;&nbsp;' . $child['name'],
                        'type'          => $child['type'],
                        'grandchildren' => $grandchildren_data
                    );
                }
            }

            if ($account['status']) {
                $this->data['accounts'][] = array(
                    'account_id' => $account['account_id'],
                    'name'       => $account['name'],
                    'type'       => $account['type'],
                    'children'   => $children_data
                );
            }
        }

        $this->load->model('accounting/currency');

        $this->data['currencies'] = $this->model_accounting_currency->getCurrencies();

        $this->data['default_currency_code'] = $this->config->get('config_currency');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/journal_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'accounting/journal')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'accounting/journal')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen($this->request->post['description']) > 1000)) {
            $this->error['description'] = $this->language->get('error_description');
        }

        if (!(float)$this->request->post['currency_value']) {
            $this->error['currency_value'] = $this->language->get('error_currency_value');
        }

        if (empty($this->request->post['date'])) {
            $this->error['date'] = $this->language->get('error_date');
        }

        $debit = 0;
        $credit = 0;

        if (isset($this->request->post['transaction_accounts'])) {
            foreach ($this->request->post['transaction_accounts'] as $account) {
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
        } else {
            $this->error['warning'] = $this->language->get('error_form');
        }

        if (round($debit, 4) != round($credit, 4)) {
            $this->error['warning'] = $this->language->get('error_account');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}