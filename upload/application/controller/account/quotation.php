<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountQuotation extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/quotation', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/quotation');

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
            'href' => $this->url->link('account/quotation', '', true)
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

        $this->load->model('billing/quotation');

        $filter_data = array(
            'start' => $this->config->get('config_limit_application') * ($page - 1),
            'limit' => $this->config->get('config_limit_application')
        );

        $quotations = $this->model_billing_quotation->getQuotations($filter_data);

        $this->data['quotations'] = array();

        foreach ($quotations as $quotation) {
            $this->data['quotations'][] = array(
                'quotation_id'  => $quotation['quotation_id'],
                'date_issued' => date($this->language->get('date_format_short'), strtotime($quotation['date_issued'])),
                'date_due'    => date($this->language->get('date_format_short'), strtotime($quotation['date_due'])),
                'total'       => $this->currency->format($quotation['total'], $quotation['currency_code'], $quotation['currency_value']),
                'status'      => $quotation['status'],
                'quotation'     => $this->url->link('account/quotation/quotation', 'quotation_id=' . $quotation['quotation_id'], true),
                'view'        => $this->url->link('account/quotation/view', 'quotation_id=' . $quotation['quotation_id'], true)
            );
        }

        $pagination = new Pagination();
        $pagination->total = $this->model_billing_quotation->getTotalQuotations();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_application');
        $pagination->url = $this->url->link('account/quotation', 'page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/quotation_list'));
    }

    public function quotation() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/quotation', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/quotation');

        $this->data['title'] = $this->language->get('heading_title');

        if ($this->request->server['HTTPS']) {
            $this->data['base'] = HTTPS_SERVER;
        } else {
            $this->data['base'] = HTTP_SERVER;
        }

        $this->load->model('billing/quotation');

        $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id'], $this->customer->getId());

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
            $this->data['payment_firstname'] = $quotation_info['payment_firstname'];
            $this->data['payment_lastname'] = $quotation_info['payment_lastname'];
            $this->data['payment_company'] = $quotation_info['payment_company'];
            $this->data['payment_address_1'] = $quotation_info['payment_address_1'];
            $this->data['payment_address_2'] = $quotation_info['payment_address_2'];
            $this->data['payment_city'] = $quotation_info['payment_city'];
            $this->data['payment_postcode'] = $quotation_info['payment_postcode'];
            $this->data['payment_country'] = $quotation_info['payment_country'];
            $this->data['payment_zone'] = $quotation_info['payment_zone'];
            $this->data['payment_name'] = $quotation_info['payment_name'];
            $this->data['payment_description'] = $quotation_info['payment_description'];
            $this->data['date_issued'] = sprintf($this->language->get('text_issued'), date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])));
            $this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified']));

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

            $pending_status = is_array($this->config->get('config_pending_status')) ? $this->config->get('config_pending_status') : array();
            $overdue_status = is_array($this->config->get('config_overdue_status')) ? $this->config->get('config_overdue_status') : array();

            if (in_array($quotation_info['status_id'], $pending_status) || in_array($quotation_info['status_id'], $overdue_status)) {
                $this->data['payment_url'] = $this->url->link('account/quotation/payment', 'quotation_id=' . $quotation_info['quotation_id'], true);
            } else {
                $this->data['payment_url'] = '';
            }

            $this->response->setOutput($this->render('account/quotation_quotation'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function payment() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/quotation', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/quotation');

        $this->load->model('billing/quotation');

        $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id'], $this->customer->getId());

        if ($quotation_info) {
            $pending_status = is_array($this->config->get('config_pending_status')) ? $this->config->get('config_pending_status') : array();
            $overdue_status = is_array($this->config->get('config_overdue_status')) ? $this->config->get('config_overdue_status') : array();

            if (!(in_array($quotation_info['status_id'], $pending_status) || in_array($quotation_info['status_id'], $overdue_status))) {
                return new Action('error/not_found');
            }

            $this->document->setTitle($this->language->get('text_quotation_payment'));

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
                'href' => $this->url->link('account/quotation', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_quotation_payment'),
                'href' => $this->url->link('account/quotation/payment', 'quotation_id=' . $quotation_info['quotation_id'], true)
            );

            $this->data['text_quotation_info'] = sprintf($this->language->get('text_quotation_info'), $quotation_info['quotation_id']);

            $this->data['quotation_id'] = $quotation_info['quotation_id'];
            $this->data['payment_name'] = $quotation_info['payment_name'];
            $this->data['payment_description'] = $quotation_info['payment_description'];

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

            $this->load->model('extension/extension');

            $payments = $this->model_extension_extension->getInstalled('payment');

            $this->data['payments'] = array();

            foreach ($payments as $payment) {
                if ($this->config->get($payment . '_status')) {
                    if (!$quotation_info['payment_code'] || $quotation_info['payment_code'] == $payment) {
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

            $this->response->setOutput($this->render('account/quotation_payment'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function success() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/quotation', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['quotation_id'])) {
            $this->data = $this->load->language('account/quotation');

            $this->document->setTitle($this->language->get('text_quotation_success'));

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
                'href' => $this->url->link('account/quotation', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_quotation_payment'),
                'href' => $this->url->link('account/quotation/payment', 'quotation_id=' . $this->request->get['quotation_id'], true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_quotation_success'),
                'href' => $this->url->link('account/quotation/success', 'quotation_id=' . $this->request->get['quotation_id'], true)
            );

            $this->data['text_quotation_success_info'] = sprintf($this->language->get('text_quotation_success_info'), $this->request->get['quotation_id']);

            $this->data['continue'] = $this->url->link('account/quotation', '', true);

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('account/quotation_success'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function view() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/quotation', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/quotation');

        $this->load->model('billing/quotation');

        $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id'], $this->customer->getId());

        if ($quotation_info) {
            $this->document->setTitle(sprintf($this->language->get('text_quotation'), $quotation_info['quotation_id']));

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
                'href' => $this->url->link('account/quotation', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => sprintf($this->language->get('text_quotation'), $quotation_info['quotation_id']),
                'href' => $this->url->link('account/quotation/view', 'quotation_id=' . $quotation_info['quotation_id'], true)
            );

            $this->data['text_quotation'] = sprintf($this->language->get('text_quotation'), $quotation_info['quotation_id']);

            $this->data['quotation_id'] = $quotation_info['quotation_id'];
            $this->data['date_issued'] = sprintf($this->language->get('text_issued'), date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])));
            $this->data['date_due'] = sprintf($this->language->get('text_due'), date($this->language->get('date_format_short'), strtotime($quotation_info['date_due'])));
            $this->data['status'] = $quotation_info['status'];
            $this->data['payment_name'] = $quotation_info['payment_name'];
            $this->data['payment_description'] = $quotation_info['payment_description'];

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

            $pending_status = is_array($this->config->get('config_pending_status')) ? $this->config->get('config_pending_status') : array();
            $overdue_status = is_array($this->config->get('config_overdue_status')) ? $this->config->get('config_overdue_status') : array();

            if (in_array($quotation_info['status_id'], $pending_status) || in_array($quotation_info['status_id'], $overdue_status)) {
                $this->data['payment_url'] = $this->url->link('account/quotation/payment', 'quotation_id=' . $quotation_info['quotation_id'], true);
            } else {
                $this->data['payment_url'] = '';
            }

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('account/quotation_view'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function history() {
        $json = $this->load->language('account/quotation');;

        $this->load->model('billing/quotation');

        if (isset($this->request->get['quotation_id'])) {
            $quotation_info = $this->model_billing_quotation->getQuotation((int)$this->request->get['quotation_id']);

            if ($quotation_info) {
                if (!isset($this->request->get['page'])) {
                    $page = 1;
                } else {
                    $page = (int)$this->request->get['page'];
                }

                $histories = $this->model_billing_quotation->getHistoriesByQuotation($quotation_info['quotation_id'], ($page - 1) * $this->config->get('config_limit_application'), $this->config->get('config_limit_application'));

                $json['histories'] = array();

                foreach ($histories as $history) {
                    $json['histories'][] = array(
                        'status'     => $history['status'],
                        'comment'    => $history['comment'],
                        'date_added' => date($this->language->get('datetime_format_short'), strtotime($history['date_added']))
                    );
                }

                $pagination = new Pagination();
                $pagination->total = $this->model_billing_quotation->getTotalHistoriesByQuotation($quotation_info['quotation_id']);
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