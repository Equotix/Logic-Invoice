<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountingCurrency extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('accounting/currency');

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
            'href' => $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'title';
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

        $this->load->model('accounting/currency');

        $this->data['currencies'] = array();

        $currencies = $this->model_accounting_currency->getCurrencies($filter_data);

        foreach ($currencies as $currency) {
            $this->data['currencies'][] = array(
                'currency_id'   => $currency['currency_id'],
                'title'         => $currency['title'],
                'code'          => $currency['code'],
                'value'         => $currency['value'],
                'status'        => $currency['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'date_modified' => date($this->language->get('datetime_format_short'), strtotime($currency['date_modified'])),
                'edit'          => $this->url->link('accounting/currency/form', 'token=' . $this->session->data['token'] . $url . '&currency_id=' . $currency['currency_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_accounting_currency->getTotalCurrencies();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('accounting/currency/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('accounting/currency/form', 'token=' . $this->session->data['token'], true);
        $this->data['refresh'] = $this->url->link('accounting/currency/refresh', 'token=' . $this->session->data['token'], true);

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_title'] = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . '&sort=title&order=' . $order, true);
        $this->data['sort_code'] = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . '&sort=code&order=' . $order, true);
        $this->data['sort_value'] = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . '&sort=value&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . '&sort=status&order=' . $order, true);
        $this->data['sort_date_modified'] = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . '&sort=date_modified&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/currency_list'));
    }

    public function delete() {
        $this->load->language('accounting/currency');

        $this->load->model('accounting/currency');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $currency_id) {
                $this->model_accounting_currency->deleteCurrency($currency_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/currency', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('accounting/currency');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'currency_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/currency', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/currency/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('accounting/currency');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['currency_id'])) {
                $this->model_accounting_currency->editCurrency((int)$this->request->get['currency_id'], $this->request->post);
            } else {
                $this->model_accounting_currency->addCurrency($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['currency_id'])) {
            $currency_info = $this->model_accounting_currency->getCurrency((int)$this->request->get['currency_id']);
        } else {
            $currency_info = array();
        }

        $this->data['action'] = $this->url->link('accounting/currency/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_title'] = $this->build->data('title', $this->error);
        $this->data['error_code'] = $this->build->data('code', $this->error);
        $this->data['error_decimal_place'] = $this->build->data('decimal_place', $this->error);
        $this->data['error_value'] = $this->build->data('value', $this->error);

        $this->data['title'] = $this->build->data('title', $this->request->post, $currency_info);
        $this->data['code'] = $this->build->data('code', $this->request->post, $currency_info);
        $this->data['symbol_left'] = $this->build->data('symbol_left', $this->request->post, $currency_info);
        $this->data['symbol_right'] = $this->build->data('symbol_right', $this->request->post, $currency_info);
        $this->data['decimal_place'] = $this->build->data('decimal_place', $this->request->post, $currency_info);
        $this->data['value'] = $this->build->data('value', $this->request->post, $currency_info);
        $this->data['status'] = $this->build->data('status', $this->request->post, $currency_info, '1');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/currency_form'));
    }

    public function currency() {
        $json = array();

        if (isset($this->request->get['code'])) {
            $this->load->model('accounting/currency');

            $currency_info = $this->model_accounting_currency->getCurrencyByCode($this->request->get['code']);

            $json = $currency_info;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function refresh() {
        $this->load->language('accounting/currency');

        $this->load->model('accounting/currency');

        if ($this->user->hasPermission('modify', 'accounting/currency')) {
            $this->model_accounting_currency->updateCurrencies(true);

            $this->session->data['success'] = $this->language->get('text_refresh');

            $this->response->redirect($this->url->link('accounting/currency', 'token=' . $this->session->data['token'], true));
        } else {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->index();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'accounting/currency')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (count($this->request->post['selected']) >= $this->model_accounting_currency->getTotalCurrencies()) {
            $this->error['warning'] = $this->language->get('error_currency');
        }

        foreach ($this->request->post['selected'] as $currency_id) {
            $currency_info = $this->model_accounting_currency->getCurrency($currency_id);

            if ($currency_info['code'] == $this->config->get('config_currency')) {
                $this->error['warning'] = $this->language->get('error_default_currency');
            }
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'accounting/currency')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 32)) {
            $this->error['title'] = $this->language->get('error_title');
        }

        if (!$this->request->post['code'] || utf8_strlen($this->request->post['code']) > 3) {
            $this->error['code'] = $this->language->get('error_code');
        }

        if (utf8_strlen($this->request->post['decimal_place']) != 1) {
            $this->error['decimal_place'] = $this->language->get('error_decimal_place');
        }

        if (!$this->request->post['value']) {
            $this->error['value'] = $this->language->get('error_value');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}