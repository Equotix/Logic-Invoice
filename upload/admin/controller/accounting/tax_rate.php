<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountingTaxRate extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('accounting/tax_rate');

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
            'href' => $this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
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

        $this->load->model('accounting/tax_rate');

        $this->data['tax_rates'] = array();

        $tax_rates = $this->model_accounting_tax_rate->getTaxRates($filter_data);

        foreach ($tax_rates as $tax_rate) {
            $this->data['tax_rates'][] = array(
                'tax_rate_id' => $tax_rate['tax_rate_id'],
                'name'        => $tax_rate['name'],
                'edit'        => $this->url->link('accounting/tax_rate/form', 'token=' . $this->session->data['token'] . $url . '&tax_rate_id=' . $tax_rate['tax_rate_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_accounting_tax_rate->getTotalTaxRates();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('accounting/tax_rate/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('accounting/tax_rate/form', 'token=' . $this->session->data['token'], true);

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

        $this->data['sort_name'] = $this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'] . '&sort=name&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/tax_rate_list'));
    }

    public function delete() {
        $this->load->language('accounting/tax_rate');

        $this->load->model('accounting/tax_rate');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $tax_rate_id) {
                $this->model_accounting_tax_rate->deleteTaxRate($tax_rate_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('accounting/tax_rate');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'tax_rate_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/tax_rate/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('accounting/tax_rate');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['tax_rate_id'])) {
                $this->model_accounting_tax_rate->editTaxRate((int)$this->request->get['tax_rate_id'], $this->request->post);
            } else {
                $this->model_accounting_tax_rate->addTaxRate($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['tax_rate_id'])) {
            $tax_rate_info = $this->model_accounting_tax_rate->getTaxRate((int)$this->request->get['tax_rate_id']);
        } else {
            $tax_rate_info = array();
        }

        $this->data['action'] = $this->url->link('accounting/tax_rate/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);
        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_rate'] = $this->build->data('rate', $this->error);

        $this->data['name'] = $this->build->data('name', $this->request->post, $tax_rate_info);
        $this->data['rate'] = $this->build->data('rate', $this->request->post, $tax_rate_info);
        $this->data['type'] = $this->build->data('type', $this->request->post, $tax_rate_info);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/tax_rate_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'accounting/tax_rate')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'accounting/tax_rate')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!(float)$this->request->post['rate']) {
            $this->error['rate'] = $this->language->get('error_rate');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}