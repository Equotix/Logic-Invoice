<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountingAccount extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('accounting/account');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
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
            'href' => $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'account_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        $filter_data = array(
            'start' => $this->config->get('config_limit_admin') * ($page - 1),
            'limit' => $this->config->get('config_limit_admin'),
            'sort'  => $sort,
            'order' => $order
        );

        $this->load->model('accounting/account');

        $this->data['accounts'] = array();

        $accounts = $this->model_accounting_account->getAccounts($filter_data);

        foreach ($accounts as $account) {
            $parent = $this->model_accounting_account->getAccount($account['parent_id']);

            $this->data['accounts'][] = array(
                'account_id'     => $account['account_id'],
                'name'           => $account['name'],
                'description'    => nl2br($account['description']),
                'type'           => $account['type'],
                'formatted_type' => $this->language->get('text_' . $account['type']),
                'parent'         => $parent ? $parent['name'] : $this->language->get('text_none'),
                'status'         => $account['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit'           => $this->url->link('accounting/account/form', 'token=' . $this->session->data['token'] . $url . '&account_id=' . $account['account_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_accounting_account->getTotalAccounts();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('accounting/account/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('accounting/account/form', 'token=' . $this->session->data['token'], true);

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

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

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_account_id'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=account_id&order=' . $order, true);
        $this->data['sort_name'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=name&order=' . $order, true);
        $this->data['sort_description'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=description&order=' . $order, true);
        $this->data['sort_type'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=type&order=' . $order, true);
        $this->data['sort_parent'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=parent_id&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . '&sort=status&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/account_list'));
    }

    public function delete() {
        $this->load->language('accounting/account');

        $this->load->model('accounting/account');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $account_id) {
                $this->model_accounting_account->deleteAccount($account_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/account', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('accounting/account');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'account_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/account', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/account/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('accounting/account');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['account_id'])) {
                $this->model_accounting_account->editAccount((int)$this->request->get['account_id'], $this->request->post);
            } else {
                $this->model_accounting_account->addAccount($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['account_id'])) {
            $account_info = $this->model_accounting_account->getAccount((int)$this->request->get['account_id']);
        } else {
            $account_info = array();
        }

        $this->data['action'] = $this->url->link('accounting/account/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_account_id'] = $this->build->data('account_id', $this->error);
        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_parent'] = $this->build->data('parent', $this->error);

        $this->data['account_id'] = $this->build->data('account_id', $this->request->post, $account_info);
        $this->data['name'] = $this->build->data('name', $this->request->post, $account_info);
        $this->data['description'] = $this->build->data('description', $this->request->post, $account_info);
        $this->data['type'] = $this->build->data('type', $this->request->post, $account_info);
        $this->data['parent_id'] = $this->build->data('parent_id', $this->request->post, $account_info);
        $this->data['status'] = $this->build->data('status', $this->request->post, $account_info, '1');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/account_form'));
    }

    public function getaccounts() {
        $json = array();

        $this->load->model('accounting/account');

        $filter_data = array(
            'filter_type' => $this->request->get['type']
        );

        $json['account'] = $this->model_accounting_account->getAccounts($filter_data);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'accounting/account')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'accounting/account')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->get['account_id']) || isset($this->request->get['account_id']) && $this->request->get['account_id'] != $this->request->post['account_id']) {
            if ($this->model_accounting_account->getAccount((int)$this->request->post['account_id'])) {
                $this->error['account_id'] = $this->language->get('error_account_id');
            }
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (isset($this->request->get['account_id']) && $this->request->post['parent_id'] == $this->request->get['account_id']) {
            $this->error['parent'] = $this->language->get('error_parent');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}