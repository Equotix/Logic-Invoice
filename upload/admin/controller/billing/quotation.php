<?php
defined('_PATH') or die('Restricted!');

class ControllerBillingQuotation extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('billing/quotation');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $url = $this->build->url(array(
            'filter_quotation_id',
            'filter_name',
            'filter_total',
            'filter_status_id',
            'filter_date_due',
            'filter_date_issued',
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
            'href' => $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['filter_quotation_id'])) {
            $filter_quotation_id = $this->request->get['filter_quotation_id'];
        } else {
            $filter_quotation_id = '';
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_status_id'])) {
            $filter_status_id = $this->request->get['filter_status_id'];
        } else {
            $filter_status_id = null;
        }
		
        if (isset($this->request->get['filter_date_due'])) {
            $filter_date_due = $this->request->get['filter_date_due'];
        } else {
            $filter_date_due = '';
        }

        if (isset($this->request->get['filter_date_issued'])) {
            $filter_date_issued = $this->request->get['filter_date_issued'];
        } else {
            $filter_date_issued = '';
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
            $sort = 'quotation_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        $filter_data = array(
            'filter_quotation_id'  => $filter_quotation_id,
            'filter_name'          => $filter_name,
            'filter_total'         => $filter_total,
            'filter_status_id'     => $filter_status_id,
            'filter_date_due'      => $filter_date_due,
            'filter_date_issued'   => $filter_date_issued,
            'filter_date_modified' => $filter_date_modified,
            'start'                => $this->config->get('config_limit_admin') * ($page - 1),
            'limit'                => $this->config->get('config_limit_admin'),
            'sort'                 => $sort,
            'order'                => $order
        );

        $this->load->model('billing/quotation');

        $this->data['quotations'] = array();

        $quotations = $this->model_billing_quotation->getQuotations($filter_data);

        foreach ($quotations as $quotation) {
            $this->data['quotations'][] = array(
                'quotation_id'     => $quotation['quotation_id'],
                'customer'         => $this->url->link('billing/customer/form', 'token=' . $this->session->data['token'] . '&customer_id=' . $quotation['customer_id'], true),
                'name'             => $quotation['name'],
                'total'            => $this->currency->format($quotation['total'], $quotation['currency_code'], $quotation['currency_value']),
                'status_id'        => $quotation['status_id'],
                'invoice_href'     => $this->url->link('billing/invoice', 'token=' . $this->session->data['token'] . '&filter_quotation_id=' . $quotation['quotation_id'], true),
                'date_due'         => date($this->language->get('date_format_short'), strtotime($quotation['date_due'])),
                'date_issued'      => date($this->language->get('datetime_format_short'), strtotime($quotation['date_issued'])),
                'date_modified'    => date($this->language->get('datetime_format_short'), strtotime($quotation['date_modified'])),
                'edit'             => $this->url->link('billing/quotation/form', 'token=' . $this->session->data['token'] . $url . '&quotation_id=' . $quotation['quotation_id'], true),
                'quotation'        => $this->url->link('billing/quotation/quotation', 'token=' . $this->session->data['token'] . '&quotation_id=' . $quotation['quotation_id'], true),
                'view'             => $this->url->link('billing/quotation/view', 'token=' . $this->session->data['token'] . $url . '&quotation_id=' . $quotation['quotation_id'], true)
            );
        }

        $url = $this->build->url(array(
            'filter_quotation_id',
            'filter_name',
            'filter_total',
            'filter_status_id',
            'filter_date_due',
            'filter_date_issued',
            'filter_date_modified',
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_billing_quotation->getTotalQuotations($filter_data);
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['generate'] = $this->url->link('billing/quotation/generate', 'token=' . $this->session->data['token'], true);
        $this->data['delete'] = $this->url->link('billing/quotation/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('billing/quotation/form', 'token=' . $this->session->data['token'], true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

        $url = $this->build->url(array(
            'filter_quotation_id',
            'filter_name',
            'filter_total',
            'filter_status_id',
            'filter_date_due',
            'filter_date_issued',
            'filter_date_modified'
        ));

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_quotation_id'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url . '&sort=quotation_id&order=' . $order, true);
        $this->data['sort_name'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url . '&sort=name&order=' . $order, true);
        $this->data['sort_total'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url . '&sort=total&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url . '&sort=status&order=' . $order, true);
        $this->data['sort_date_issued'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url . '&sort=date_issued&order=' . $order, true);
        $this->data['sort_date_due'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url . '&sort=date_due&order=' . $order, true);
        $this->data['sort_date_modified'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url . '&sort=date_modified&order=' . $order, true);

        $this->data['filter_quotation_id'] = $filter_quotation_id;
        $this->data['filter_name'] = $filter_name;
        $this->data['filter_total'] = $filter_total;
        $this->data['filter_status_id'] = $filter_status_id;
        $this->data['filter_date_due'] = $filter_date_due;
        $this->data['filter_date_issued'] = $filter_date_issued;
        $this->data['filter_date_modified'] = $filter_date_modified;

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('billing/quotation_list'));
    }

    public function delete() {
        $this->load->language('billing/quotation');

        $this->load->model('billing/quotation');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $quotation_id) {
                $this->model_billing_quotation->deleteQuotation($quotation_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('billing/quotation', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('billing/quotation');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $url = $this->build->url(array(
            'filter_quotation_id',
            'filter_name',
            'filter_total',
            'filter_status_id',
            'filter_date_due',
            'filter_date_issued',
            'filter_date_modified',
            'sort',
            'order',
            'page',
            'quotation_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('billing/quotation');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['quotation_id'])) {
                $this->model_billing_quotation->editQuotation((int)$this->request->get['quotation_id'], $this->request->post);
            } else {
                $this->model_billing_quotation->addQuotation($this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['quotation_id'])) {
            $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id']);
        } else {
            $quotation_info = array();
        }

        $this->data['action'] = $this->url->link('billing/quotation/form', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['cancel'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        $this->data['quotation_id'] = $this->build->data('quotation_id', $this->request->post, $quotation_info);
        $this->data['customer_id'] = $this->build->data('customer_id', $this->request->post, $quotation_info);
        $this->data['customer'] = $this->build->data('customer', $this->request->post, $quotation_info);
        $this->data['firstname'] = $this->build->data('firstname', $this->request->post, $quotation_info);
        $this->data['lastname'] = $this->build->data('lastname', $this->request->post, $quotation_info);
        $this->data['company'] = $this->build->data('company', $this->request->post, $quotation_info);
        $this->data['website'] = $this->build->data('website', $this->request->post, $quotation_info);
        $this->data['email'] = $this->build->data('email', $this->request->post, $quotation_info);
        $this->data['payment_code'] = $this->build->data('payment_code', $this->request->post, $quotation_info);
        $this->data['payment_name'] = $this->build->data('payment_name', $this->request->post, $quotation_info);
        $this->data['payment_description'] = $this->build->data('payment_description', $this->request->post, $quotation_info);
        $this->data['currency_code'] = $this->build->data('currency_code', $this->request->post, $quotation_info, $this->config->get('config_currency'));
        $this->data['currency_value'] = $this->build->data('currency_value', $this->request->post, $quotation_info, '1.00');
        $this->data['comment'] = $this->build->data('comment', $this->request->post, $quotation_info);
        $this->data['status_id'] = $this->build->data('status_id', $this->request->post, $quotation_info);
        $this->data['date_due'] = $this->build->data('date_due', $this->request->post, $quotation_info);
        $this->data['items'] = $this->build->data('items', $this->request->post, $quotation_info, array());
        $this->data['totals'] = $this->build->data('totals', $this->request->post, $quotation_info, array());

        $this->load->model('extension/extension');

        $payments = $this->model_extension_extension->getInstalled('payment');

        $this->data['payments'] = array();

        foreach ($payments as $payment) {
            if ($this->config->get($payment . '_status')) {
                $this->load->language('payment/' . $payment . '/' . $payment);

                $this->data['payments'][] = array(
                    'name'       => $this->language->get('heading_title'),
                    'code'       => $payment,
                    'sort_order' => $this->config->get($payment . '_sort_order')
                );
            }
        }

        $sort_order = array();

        foreach ($this->data['payments'] as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $this->data['payments']);

        $this->load->model('accounting/currency');

        $this->data['currencies'] = $this->model_accounting_currency->getCurrencies();

        $this->data['default_currency_code'] = $this->config->get('config_currency');

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->load->model('accounting/tax_class');

        $this->data['tax_classes'] = $this->model_accounting_tax_class->getTaxClasses();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('billing/quotation_form'));
    }

    public function history() {
        $json = $this->load->language('billing/quotation');;

        $this->load->model('billing/quotation');

        if (isset($this->request->get['quotation_id'])) {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateHistory()) {
                $this->model_billing_quotation->addHistory((int)$this->request->get['quotation_id'], $this->request->post, true);

                $json['success'] = $this->language->get('text_success');
            }

            if (!isset($this->request->get['page'])) {
                $page = 1;
            } else {
                $page = (int)$this->request->get['page'];
            }

            $histories = $this->model_billing_quotation->getHistoriesByQuotation((int)$this->request->get['quotation_id'], ($page - 1) * $this->config->get('config_limit_admin'), $this->config->get('config_limit_admin'));

            $json['histories'] = array();

            foreach ($histories as $history) {
                $json['histories'][] = array(
                    'status'     => $history['status'],
                    'comment'    => $history['comment'],
                    'date_added' => date($this->language->get('datetime_format_short'), strtotime($history['date_added']))
                );
            }

            $pagination = new Pagination();
            $pagination->total = $this->model_billing_quotation->getTotalHistoriesByQuotation((int)$this->request->get['quotation_id']);
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_limit_admin');
            $pagination->url = '{page}';

            $json['pagination'] = $pagination->render();

            $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id']);

            $json['status_id'] = $quotation_info['status_id'];

            $this->load->model('system/status');

            $json['statuses'] = $this->model_system_status->getStatuses();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function quotation() {
        $this->data = $this->load->language('billing/quotation');

        $this->data['title'] = $this->language->get('heading_title');

        if ($this->request->server['HTTPS']) {
            $this->data['base'] = HTTPS_SERVER;
            $this->data['application'] = HTTPS_APPLICATION;
        } else {
            $this->data['base'] = HTTP_SERVER;
            $this->data['application'] = HTTP_APPLICATION;
        }

        $this->load->model('billing/quotation');

        $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id']);

        if ($quotation_info) {
            $this->data['system_company'] = $this->config->get('config_registered_name');
            $this->data['system_address'] = nl2br($this->config->get('config_address'));
            $this->data['system_email'] = $this->config->get('config_email');
            $this->data['system_telephone'] = $this->config->get('config_telephone');
            $this->data['system_fax'] = $this->config->get('config_fax');
            $this->data['quotation_prefix'] = $this->config->get('config_quotation_prefix');

            $this->data['quotation_id'] = $quotation_info['quotation_id'];
            $this->data['customer'] = $quotation_info['customer'];
            $this->data['firstname'] = $quotation_info['firstname'];
            $this->data['lastname'] = $quotation_info['lastname'];
            $this->data['company'] = $quotation_info['company'];
            $this->data['website'] = $quotation_info['website'];
            $this->data['email'] = $quotation_info['email'];
            $this->data['status'] = $quotation_info['status'];
            $this->data['date_issued'] = date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued']));
            $this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified']));

            $items = $this->build->data('items', $quotation_info, array());

            $this->data['items'] = array();

            $number = 1;

            foreach ($items as $item) {
                $this->data['items'][] = array(
                    'number'      => $number,
                    'title'       => html_entity_decode($item['title'], ENT_QUOTES),
                    'description' => html_entity_decode(nl2br($item['description']), ENT_QUOTES),
                    'quantity'    => $item['quantity'],
                    'price'       => $this->currency->format($item['price'], $quotation_info['currency_code'], $quotation_info['currency_value']),
                    'discount'    => (float)$item['discount'] ? $this->currency->format($item['discount'], $quotation_info['currency_code'], $quotation_info['currency_value']) : '-',
                    'total'       => $this->currency->format(($item['price'] - $item['discount']) * $item['quantity'], $quotation_info['currency_code'], $quotation_info['currency_value'])
                );

                $number++;
            }

            $totals = $this->build->data('totals', $quotation_info, array());

            $this->data['totals'] = array();

            foreach ($totals as $total) {
                $this->data['totals'][] = array(
                    'title' => html_entity_decode($total['title'], ENT_QUOTES),
                    'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value'])
                );
            }

            $this->response->setOutput($this->render('billing/quotation_quotation'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function generate() {
        $this->load->language('billing/quotation');

        $this->load->model('billing/quotation');

        if (isset($this->request->post['selected']) && $this->validateGenerate()) {
            foreach ($this->request->post['selected'] as $quotation_id) {
                $this->model_billing_quotation->generateInvoice($quotation_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('billing/invoice', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function update() {
        $this->load->language('billing/quotation');

        $json = array();

        $quotation_id = (int)$this->request->post['quotation_id'];
        $status_id = (int)$this->request->post['status_id'];

        if (!$this->user->hasPermission('modify', 'billing/quotation')) {
            $json['warning'] = $this->language->get('error_permission');
        } else {
            $this->load->model('billing/quotation');

            $data = array(
                'status_id' => $status_id,
                'comment'   => ''
            );

            $this->model_billing_quotation->addHistory($quotation_id, $data, true);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function view() {
        $this->data = $this->load->language('billing/quotation');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'filter_quotation_id',
            'filter_name',
            'filter_total',
            'filter_status_id',
            'filter_date_due',
            'filter_date_issued',
            'filter_date_modified',
            'sort',
            'order',
            'page',
            'quotation_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('billing/quotation');

        $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id']);

        if ($quotation_info) {
            $this->data['cancel'] = $this->url->link('billing/quotation', 'token=' . $this->session->data['token'] . $url, true);

            $this->data['quotation'] = $this->url->link('billing/quotation/quotation', 'token=' . $this->session->data['token'] . '&quotation_id=' . $quotation_info['quotation_id'], true);

            $this->data['token'] = $this->session->data['token'];

            $this->data['quotation_id'] = $quotation_info['quotation_id'];
            $this->data['customer'] = $this->url->link('billing/customer/form', 'token=' . $this->session->data['token'] . '&customer_id=' . $quotation_info['customer_id'], true);
            $this->data['firstname'] = $quotation_info['firstname'];
            $this->data['lastname'] = $quotation_info['lastname'];
            $this->data['company'] = $quotation_info['company'];
            $this->data['website'] = $quotation_info['website'];
            $this->data['email'] = $quotation_info['email'];
            $this->data['status'] = $quotation_info['status'];
            $this->data['currency_value'] = $quotation_info['currency_value'];
            $this->data['comment'] = nl2br($quotation_info['comment']);
            $this->data['date_due'] = $quotation_info['date_due'];

            $this->load->model('extension/extension');

            $payments = $this->model_extension_extension->getInstalled('payment');

            $this->data['payment_code'] = $quotation_info['payment_code'];

            foreach ($payments as $payment) {
                if ($this->config->get($payment . '_status') && $payment == $quotation_info['payment_code']) {
                    $this->load->language('payment/' . $payment . '/' . $payment);

                    $this->data['payment_code'] = $this->language->get('heading_title');
                }
            }

            $this->load->model('accounting/currency');

            $currencies = $this->model_accounting_currency->getCurrencies();

            $this->data['currency_code'] = $quotation_info['currency_code'];

            foreach ($currencies as $currency) {
                if ($currency['code'] == $quotation_info['currency_code']) {
                    $this->data['currency_code'] = $currency['title'];
                }
            }

            $items = $quotation_info['items'];

            $this->data['items'] = array();

            $number = 1;

            foreach ($items as $item) {
                $this->data['items'][] = array(
                    'number'      => $number,
                    'title'       => html_entity_decode($item['title'], ENT_QUOTES),
                    'description' => html_entity_decode(nl2br($item['description']), ENT_QUOTES),
                    'quantity'    => $item['quantity'],
                    'price'       => $this->currency->format($item['price'], $quotation_info['currency_code'], $quotation_info['currency_value']),
                    'discount'    => (float)$item['discount'] ? $this->currency->format($item['discount'], $quotation_info['currency_code'], $quotation_info['currency_value']) : '-',
                    'total'       => $this->currency->format(($item['price'] - $item['discount']) * $item['quantity'], $quotation_info['currency_code'], $quotation_info['currency_value'])
                );

                $number++;
            }

            $totals = $quotation_info['totals'];

            $this->data['totals'] = array();

            foreach ($totals as $total) {
                $this->data['totals'][] = array(
                    'title' => html_entity_decode($total['title'], ENT_QUOTES),
                    'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value'])
                );
            }

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('billing/quotation_view'));
        } else {
            return new Action('error/not_found');
        }
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'billing/quotation')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'billing/quotation')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateHistory() {
        if (!$this->user->hasPermission('modify', 'billing/quotation')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateGenerate() {
        if (!$this->user->hasPermission('modify', 'billing/quotation')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function validate_step_1() {
        $this->load->language('billing/quotation');

        $json = array();

        if (!$this->user->hasPermission('modify', 'billing/quotation')) {
            $json['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $json['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $json['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $json['email'] = $this->language->get('error_email');
        }

        if (!$this->request->post['date_due']) {
            $json['date_due'] = $this->language->get('error_date_due');
        }

        if (!$json) {
            $json['success'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validate_step_3() {
        $this->load->language('billing/quotation');

        $json = array();

        if (!$this->user->hasPermission('modify', 'billing/quotation')) {
            $json['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['currency_code']) {
            $json['currency_code'] = $this->language->get('error_currency_code');
        }

        if (!(float)$this->request->post['currency_value']) {
            $json['currency_value'] = $this->language->get('error_currency_value');
        }

        if (!$json) {
            $json['success'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validate_step_4() {
        $json = array();

        $this->load->model('accounting/tax_class');

        if (isset($this->request->post['items'])) {
            $taxes = array();
            $total = 0;

            $json['items'] = array();

            $number = 1;

            foreach ($this->request->post['items'] as $key => $item) {
                $tax = 0;

                $tax_class_info = $this->model_accounting_tax_class->getTaxClass($item['tax_class_id']);

                if ($tax_class_info) {
                    foreach ($tax_class_info['tax_rates'] as $tax_rate) {
                        if ($tax_rate['type'] == 'P') {
                            $tax = ((float)$item['price'] - (float)$item['discount']) / 100 * $tax_rate['rate'];
                        } else {
                            $tax = $tax_rate['rate'];
                        }

                        if (isset($taxes[$tax_rate['tax_rate_id']])) {
                            $value = $tax * (int)$item['quantity'] + $taxes[$tax_rate['tax_rate_id']]['value'];
                        } else {
                            $value = $tax * (int)$item['quantity'];
                        }

                        $taxes[$tax_rate['tax_rate_id']] = array(
                            'name'  => $tax_rate['name'],
                            'value' => $value
                        );
                    }
                }

                $total += ((float)$item['price'] - (float)$item['discount']) * (int)$item['quantity'];

                $json['items'][] = array(
                    'key'         => $key,
                    'number'      => $number,
                    'title'       => $item['title'],
                    'description' => nl2br($item['description']),
                    'quantity'    => $item['quantity'],
                    'tax'         => $tax,
                    'price'       => $this->currency->format((float)$item['price'], $this->request->post['currency_code'], (float)$this->request->post['currency_value']),
                    'discount'    => (float)$item['discount'] ? $this->currency->format((float)$item['discount'], $this->request->post['currency_code'], (float)$this->request->post['currency_value']) : '-',
                    'total'       => $this->currency->format((((float)$item['price'] - (float)$item['discount']) * (int)$item['quantity']), $this->request->post['currency_code'], (float)$this->request->post['currency_value'])
                );

                $number++;
            }

            $this->load->model('extension/extension');

            $sort_order = array();

            $results = $this->model_extension_extension->getInstalled('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            $total_data = array();

            foreach ($results as $result) {
                if ($this->config->get($result . '_status')) {
                    $this->load->model('total/' . $result . '/' . $result);

                    $this->{'model_total_' . $result . '_' . $result}->getTotal($total_data, $total, $taxes);
                }
            }

            $json['totals'] = array();

            foreach ($total_data as $total) {
                $json['totals'][] = array(
                    'code'       => $total['code'],
                    'title'      => $total['title'],
                    'text'       => $this->currency->format($total['value'], $this->request->post['currency_code'], $this->request->post['currency_value']),
                    'value'      => $total['value'],
                    'sort_order' => $total['sort_order']
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}