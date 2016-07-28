<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountInvoice extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/invoice', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/invoice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/invoice', '', true)
        );

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->load->model('billing/invoice');

        $filter_data = array(
            'start' => $this->config->get('config_limit_application') * ($page - 1),
            'limit' => $this->config->get('config_limit_application')
        );

        $invoices = $this->model_billing_invoice->getInvoices($filter_data);

        $this->data['invoices'] = array();

        foreach ($invoices as $invoice) {
            $this->data['invoices'][] = array(
                'invoice_id'  => $invoice['invoice_id'],
                'date_issued' => date($this->language->get('date_format_short'), strtotime($invoice['date_issued'])),
                'date_due'    => date($this->language->get('date_format_short'), strtotime($invoice['date_due'])),
                'total'       => $this->currency->format($invoice['total'], $invoice['currency_code'], $invoice['currency_value']),
                'status'      => $invoice['status'],
                'invoice'     => $this->url->link('account/invoice/invoice', 'invoice_id=' . $invoice['invoice_id'], true),
                'view'        => $this->url->link('account/invoice/view', 'invoice_id=' . $invoice['invoice_id'], true)
            );
        }

        $pagination = new Pagination();
        $pagination->total = $this->model_billing_invoice->getTotalInvoices();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_application');
        $pagination->url = $this->url->link('account/invoice', 'page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/invoice_list'));
    }

    public function invoice() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/invoice', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/invoice');

        $this->data['title'] = $this->language->get('heading_title');

        if ($this->request->server['HTTPS']) {
            $this->data['base'] = HTTPS_SERVER;
        } else {
            $this->data['base'] = HTTP_SERVER;
        }

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

        if ($invoice_info) {
            $this->data['system_company'] = $this->config->get('config_registered_name');
            $this->data['system_address'] = nl2br($this->config->get('config_address'));
            $this->data['system_email'] = $this->config->get('config_email');
            $this->data['system_telephone'] = $this->config->get('config_telephone');
            $this->data['system_fax'] = $this->config->get('config_fax');
            $this->data['invoice_prefix'] = $this->config->get('config_invoice_prefix');

            $this->data['invoice_id'] = $invoice_info['invoice_id'];
            $this->data['customer'] = $invoice_info['customer'];
            $this->data['firstname'] = $invoice_info['firstname'];
            $this->data['lastname'] = $invoice_info['lastname'];
            $this->data['company'] = $invoice_info['company'];
            $this->data['website'] = $invoice_info['website'];
            $this->data['email'] = $invoice_info['email'];
            $this->data['status'] = $invoice_info['status'];
            $this->data['payment_firstname'] = $invoice_info['payment_firstname'];
            $this->data['payment_lastname'] = $invoice_info['payment_lastname'];
            $this->data['payment_company'] = $invoice_info['payment_company'];
            $this->data['payment_address_1'] = $invoice_info['payment_address_1'];
            $this->data['payment_address_2'] = $invoice_info['payment_address_2'];
            $this->data['payment_city'] = $invoice_info['payment_city'];
            $this->data['payment_postcode'] = $invoice_info['payment_postcode'];
            $this->data['payment_country'] = $invoice_info['payment_country'];
            $this->data['payment_zone'] = $invoice_info['payment_zone'];
            $this->data['payment_name'] = $invoice_info['payment_name'];
            $this->data['payment_description'] = $invoice_info['payment_description'];
            $this->data['date_issued'] = sprintf($this->language->get('text_issued'), date($this->language->get('date_format_short'), strtotime($invoice_info['date_issued'])));
            $this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified']));

            $items = $invoice_info['items'];

            $this->data['items'] = array();

            $number = 1;

            foreach ($items as $item) {
                $this->data['items'][] = array(
                    'number'      => $number,
                    'title'       => html_entity_decode($item['title'], ENT_QUOTES),
                    'description' => html_entity_decode(nl2br($item['description']), ENT_QUOTES),
                    'quantity'    => $item['quantity'],
                    'price'       => $this->currency->format($item['price'], $invoice_info['currency_code'], $invoice_info['currency_value']),
                    'discount'    => (float)$item['discount'] ? $this->currency->format($item['discount'], $invoice_info['currency_code'], $invoice_info['currency_value']) : '-',
                    'total'       => $this->currency->format(($item['price'] - $item['discount']) * $item['quantity'], $invoice_info['currency_code'], $invoice_info['currency_value'])
                );

                $number++;
            }

            $totals = $invoice_info['totals'];

            $this->data['totals'] = array();

            foreach ($totals as $total) {
                $this->data['totals'][] = array(
                    'title' => html_entity_decode($total['title'], ENT_QUOTES),
                    'text'  => $this->currency->format($total['value'], $invoice_info['currency_code'], $invoice_info['currency_value'])
                );
            }

            $pending_status = is_array($this->config->get('config_pending_status')) ? $this->config->get('config_pending_status') : array();
            $overdue_status = is_array($this->config->get('config_overdue_status')) ? $this->config->get('config_overdue_status') : array();

            if (in_array($invoice_info['status_id'], $pending_status) || in_array($invoice_info['status_id'], $overdue_status)) {
                $this->data['payment_url'] = $this->url->link('account/invoice/payment', 'invoice_id=' . $invoice_info['invoice_id'], true);
            } else {
                $this->data['payment_url'] = '';
            }

            $this->response->setOutput($this->render('account/invoice_invoice'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function payment() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/invoice', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/invoice');

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

        if ($invoice_info) {
            $pending_status = is_array($this->config->get('config_pending_status')) ? $this->config->get('config_pending_status') : array();
            $overdue_status = is_array($this->config->get('config_overdue_status')) ? $this->config->get('config_overdue_status') : array();

            if (!(in_array($invoice_info['status_id'], $pending_status) || in_array($invoice_info['status_id'], $overdue_status))) {
                return new Action('error/not_found');
            }

            $this->document->setTitle($this->language->get('text_invoice_payment'));

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/invoice', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_invoice_payment'),
                'href' => $this->url->link('account/invoice/payment', 'invoice_id=' . $invoice_info['invoice_id'], true)
            );

            $this->data['text_invoice_info'] = sprintf($this->language->get('text_invoice_info'), $invoice_info['invoice_id']);

            $this->data['invoice_id'] = $invoice_info['invoice_id'];
            $this->data['payment_name'] = $invoice_info['payment_name'];
            $this->data['payment_description'] = $invoice_info['payment_description'];

            $items = $invoice_info['items'];

            $this->data['items'] = array();

            $number = 1;

            foreach ($items as $item) {
                $this->data['items'][] = array(
                    'number'      => $number,
                    'title'       => html_entity_decode($item['title'], ENT_QUOTES),
                    'description' => html_entity_decode(nl2br($item['description']), ENT_QUOTES),
                    'quantity'    => $item['quantity'],
                    'price'       => $this->currency->format($item['price'], $invoice_info['currency_code'], $invoice_info['currency_value']),
                    'discount'    => (float)$item['discount'] ? $this->currency->format($item['discount'], $invoice_info['currency_code'], $invoice_info['currency_value']) : '-',
                    'total'       => $this->currency->format(($item['price'] - $item['discount']) * $item['quantity'], $invoice_info['currency_code'], $invoice_info['currency_value'])
                );

                $number++;
            }

            $totals = $invoice_info['totals'];

            $this->data['totals'] = array();

            foreach ($totals as $total) {
                $this->data['totals'][] = array(
                    'title' => html_entity_decode($total['title'], ENT_QUOTES),
                    'text'  => $this->currency->format($total['value'], $invoice_info['currency_code'], $invoice_info['currency_value'])
                );
            }

            $this->load->model('extension/extension');

            $payments = $this->model_extension_extension->getInstalled('payment');

            $this->data['payments'] = array();

            foreach ($payments as $payment) {
                if ($this->config->get($payment . '_status')) {
                    if (!$invoice_info['payment_code'] || $invoice_info['payment_code'] == $payment) {
                        $this->load->language('payment/' . $payment . '/' . $payment);

                        $this->data['payments'][] = array(
                            'name'       => $this->language->get('heading_title'),
                            'code'       => $payment,
                            'sort_order' => $this->config->get($payment . '_sort_order')
                        );
                    }
                }
            }

            $sort_order = array();

            foreach ($this->data['payments'] as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $this->data['payments']);

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('account/invoice_payment'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function success() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/invoice', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['invoice_id'])) {
            $this->data = $this->load->language('account/invoice');

            $this->document->setTitle($this->language->get('text_invoice_success'));

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/invoice', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_invoice_payment'),
                'href' => $this->url->link('account/invoice/payment', 'invoice_id=' . $this->request->get['invoice_id'], true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_invoice_success'),
                'href' => $this->url->link('account/invoice/success', 'invoice_id=' . $this->request->get['invoice_id'], true)
            );

            $this->data['text_invoice_success_info'] = sprintf($this->language->get('text_invoice_success_info'), $this->request->get['invoice_id']);

            $this->data['continue'] = $this->url->link('account/invoice', '', true);

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('account/invoice_success'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function view() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/invoice', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/invoice');

        $this->load->model('billing/invoice');

        $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id'], $this->customer->getId());

        if ($invoice_info) {
            $this->document->setTitle(sprintf($this->language->get('text_invoice'), $invoice_info['invoice_id']));

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/invoice', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => sprintf($this->language->get('text_invoice'), $invoice_info['invoice_id']),
                'href' => $this->url->link('account/invoice/view', 'invoice_id=' . $invoice_info['invoice_id'], true)
            );

            $this->data['text_invoice'] = sprintf($this->language->get('text_invoice'), $invoice_info['invoice_id']);

            $this->data['invoice_id'] = $invoice_info['invoice_id'];
            $this->data['date_issued'] = sprintf($this->language->get('text_issued'), date($this->language->get('date_format_short'), strtotime($invoice_info['date_issued'])));
            $this->data['date_due'] = sprintf($this->language->get('text_due'), date($this->language->get('date_format_short'), strtotime($invoice_info['date_due'])));
            $this->data['status'] = $invoice_info['status'];
            $this->data['payment_name'] = $invoice_info['payment_name'];
            $this->data['payment_description'] = $invoice_info['payment_description'];

            $items = $invoice_info['items'];

            $this->data['items'] = array();

            $number = 1;

            foreach ($items as $item) {
                $this->data['items'][] = array(
                    'number'      => $number,
                    'title'       => html_entity_decode($item['title'], ENT_QUOTES),
                    'description' => html_entity_decode(nl2br($item['description']), ENT_QUOTES),
                    'quantity'    => $item['quantity'],
                    'price'       => $this->currency->format($item['price'], $invoice_info['currency_code'], $invoice_info['currency_value']),
                    'discount'    => (float)$item['discount'] ? $this->currency->format($item['discount'], $invoice_info['currency_code'], $invoice_info['currency_value']) : '-',
                    'total'       => $this->currency->format(($item['price'] - $item['discount']) * $item['quantity'], $invoice_info['currency_code'], $invoice_info['currency_value'])
                );

                $number++;
            }

            $totals = $invoice_info['totals'];

            $this->data['totals'] = array();

            foreach ($totals as $total) {
                $this->data['totals'][] = array(
                    'title' => html_entity_decode($total['title'], ENT_QUOTES),
                    'text'  => $this->currency->format($total['value'], $invoice_info['currency_code'], $invoice_info['currency_value'])
                );
            }

            $pending_status = is_array($this->config->get('config_pending_status')) ? $this->config->get('config_pending_status') : array();
            $overdue_status = is_array($this->config->get('config_overdue_status')) ? $this->config->get('config_overdue_status') : array();

            if (in_array($invoice_info['status_id'], $pending_status) || in_array($invoice_info['status_id'], $overdue_status)) {
                $this->data['payment_url'] = $this->url->link('account/invoice/payment', 'invoice_id=' . $invoice_info['invoice_id'], true);
            } else {
                $this->data['payment_url'] = '';
            }

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('account/invoice_view'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function history() {
        $json = $this->load->language('account/invoice');;

        $this->load->model('billing/invoice');

        if (isset($this->request->get['invoice_id'])) {
            $invoice_info = $this->model_billing_invoice->getInvoice((int)$this->request->get['invoice_id']);

            if ($invoice_info) {
                if (!isset($this->request->get['page'])) {
                    $page = 1;
                } else {
                    $page = (int)$this->request->get['page'];
                }

                $histories = $this->model_billing_invoice->getHistoriesByInvoice($invoice_info['invoice_id'], ($page - 1) * $this->config->get('config_limit_application'), $this->config->get('config_limit_application'));

                $json['histories'] = array();

                foreach ($histories as $history) {
                    $json['histories'][] = array(
                        'status'     => $history['status'],
                        'comment'    => $history['comment'],
                        'date_added' => date($this->language->get('datetime_format_short'), strtotime($history['date_added']))
                    );
                }

                $pagination = new Pagination();
                $pagination->total = $this->model_billing_invoice->getTotalHistoriesByInvoice($invoice_info['invoice_id']);
                $pagination->page = $page;
                $pagination->limit = $this->config->get('config_limit_application');
                $pagination->url = '{page}';

                $json['pagination'] = $pagination->render();
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}