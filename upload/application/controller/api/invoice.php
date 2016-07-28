<?php
defined('_PATH') or die('Restricted!');

class ControllerApiInvoice extends Controller {
    public function check() {
        $this->load->language('api/invoice');

        $json = array();

        $json['success'] = false;

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('billing/invoice');
            $this->load->model('system/activity');
            $this->load->model('system/status');

            // Overdue
            $status = $this->model_system_status->getStatus($this->config->get('config_default_overdue_status'));

            $invoices = $this->model_billing_invoice->getPendingInvoices();

            foreach ($invoices as $invoice) {
                if (strtotime($invoice['date_due']) <= time()) {
                    $data = array(
                        'status_id' => $this->config->get('config_default_overdue_status'),
                        'comment'   => ''
                    );

                    $this->model_billing_invoice->addHistory($invoice['invoice_id'], $data, true);

                    $this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $invoice['invoice_id'], $status['name'], $this->session->data['username']));
                }
            }

            // Void
            $status = $this->model_system_status->getStatus($this->config->get('config_default_void_status'));

            $invoices = $this->model_billing_invoice->getOverdueInvoices();

            foreach ($invoices as $invoice) {
                if (strtotime($invoice['date_due'] . ' +' . $this->config->get('config_invoice_void_days') . ' days') <= time()) {
                    $data = array(
                        'status_id' => $this->config->get('config_default_void_status'),
                        'comment'   => ''
                    );

                    $this->model_billing_invoice->addHistory($invoice['invoice_id'], $data, true);

                    $this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $invoice['invoice_id'], $status['name'], $this->session->data['username']));
                }
            }

            $json['success'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function add() {
        $this->load->language('api/invoice');

        $json = array();

        $json['success'] = false;

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('billing/customer');
            $this->load->model('billing/invoice');
            $this->load->model('system/activity');
            $this->load->model('system/status');

            $json = array_merge($json, $this->validate());

            if (empty($json['error'])) {
                $items = array();

                foreach ($this->request->post['items'] as $item) {
                    $items[] = array(
                        'title'        => isset($item['title']) ? $item['title'] : '',
                        'description'  => isset($item['description']) ? $item['description'] : '',
                        'tax_class_id' => isset($item['tax_class_id']) ? $item['tax_class_id'] : 0,
                        'quantity'     => isset($item['quantity']) ? $item['quantity'] : 1,
                        'price'        => isset($item['price']) ? $item['price'] : 0,
                        'tax'          => isset($item['tax']) ? $item['tax'] : 0,
                        'discount'     => isset($item['discount']) ? $item['discount'] : 0
                    );
                }

                $totals = array();

                foreach ($this->request->post['totals'] as $total) {
                    $totals[] = array(
                        'code'       => isset($total['code']) ? $total['code'] : '',
                        'title'      => isset($total['title']) ? $total['title'] : '',
                        'value'      => isset($total['value']) ? $total['value'] : 0,
                        'sort_order' => isset($total['sort_order']) ? $total['sort_order'] : 0,
                    );
                }

                $customer_info = $this->model_billing_customer->getCustomerByEmail($this->request->post['email']);

                if (!$customer_info) {
                    $data = array(
                        'firstname' => $this->request->post['firstname'],
                        'lastname'  => $this->request->post['lastname'],
                        'company'   => isset($this->request->post['company']) ? $this->request->post['company'] : '',
                        'website'   => isset($this->request->post['website']) ? $this->request->post['website'] : '',
                        'email'     => $this->request->post['email'],
                        'password'  => substr(sha1(uniqid(mt_rand(), true)), 0, 10)
                    );

                    $this->model_billing_customer->addCustomer($data);

                    $customer_info = $this->model_billing_customer->getCustomerByEmail($this->request->post['email']);
                }

                $data = array(
                    'recurring_id'        => '',
                    'customer_id'         => $customer_info['customer_id'],
                    'firstname'           => $customer_info['firstname'],
                    'lastname'            => $customer_info['lastname'],
                    'company'             => $customer_info['company'],
                    'website'             => $customer_info['website'],
                    'email'               => $customer_info['email'],
                    'payment_firstname'   => $this->request->post['firstname'],
                    'payment_lastname'    => $this->request->post['lastname'],
                    'payment_company'     => isset($this->request->post['company']) ? $this->request->post['company'] : '',
                    'payment_address_1'   => isset($this->request->post['address_1']) ? $this->request->post['address_1'] : '',
                    'payment_address_2'   => isset($this->request->post['address_2']) ? $this->request->post['address_2'] : '',
                    'payment_city'        => isset($this->request->post['city']) ? $this->request->post['city'] : '',
                    'payment_postcode'    => isset($this->request->post['postcode']) ? $this->request->post['postcode'] : '',
                    'payment_country'     => isset($this->request->post['country']) ? $this->request->post['country'] : '',
                    'payment_zone'        => isset($this->request->post['zone']) ? $this->request->post['zone'] : '',
                    'total'               => $this->request->post['total'],
                    'payment_code'        => isset($this->request->post['payment_code']) ? $this->request->post['payment_code'] : '',
                    'payment_name'        => $this->request->post['payment_name'],
                    'payment_description' => isset($this->request->post['payment_description']) ? $this->request->post['payment_description'] : '',
                    'currency_code'       => $this->request->post['currency_code'],
                    'currency_value'      => $this->request->post['currency_value'],
                    'comment'             => isset($this->request->post['company']) ? $this->request->post['company'] : '',
                    'status_id'           => $this->request->post['status_id'],
                    'date_due'            => $this->request->post['date_due'],
                    'items'               => $items,
                    'totals'              => $totals
                );

                $invoice_id = $this->model_billing_invoice->addInvoice($data);

                $this->model_system_activity->addActivity(sprintf($this->language->get('text_added'), $invoice_id, $this->session->data['username']));

                $json['success'] = true;
                $json['invoice_id'] = $invoice_id;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit() {
        $this->load->language('api/invoice');

        $json = array();

        $json['success'] = false;

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('billing/customer');
            $this->load->model('billing/invoice');
            $this->load->model('system/activity');
            $this->load->model('system/status');

            $json = array_merge($json, $this->validate());

            if (empty($this->request->post['invoice_id'])) {
                $json['error'][] = $this->langauge->get('error_invoice_id');
            }

            if (empty($json['error'])) {
                $items = array();

                foreach ($this->request->post['items'] as $item) {
                    $items[] = array(
                        'title'        => isset($item['title']) ? $item['title'] : '',
                        'description'  => isset($item['description']) ? $item['description'] : '',
                        'tax_class_id' => isset($item['tax_class_id']) ? $item['tax_class_id'] : 0,
                        'quantity'     => isset($item['quantity']) ? $item['quantity'] : 1,
                        'price'        => isset($item['price']) ? $item['price'] : 0,
                        'tax'          => isset($item['tax']) ? $item['tax'] : 0,
                        'discount'     => isset($item['discount']) ? $item['discount'] : 0
                    );
                }

                $totals = array();

                foreach ($this->request->post['totals'] as $total) {
                    $totals[] = array(
                        'code'       => isset($total['code']) ? $total['code'] : '',
                        'title'      => isset($total['title']) ? $total['title'] : '',
                        'value'      => isset($total['value']) ? $total['value'] : 0,
                        'sort_order' => isset($total['sort_order']) ? $total['sort_order'] : 0,
                    );
                }

                $customer_info = $this->model_billing_customer->getCustomerByEmail($this->request->post['email']);

                if (!$customer_info) {
                    $data = array(
                        'firstname' => $this->request->post['firstname'],
                        'lastname'  => $this->request->post['lastname'],
                        'company'   => isset($this->request->post['company']) ? $this->request->post['company'] : '',
                        'website'   => isset($this->request->post['website']) ? $this->request->post['website'] : '',
                        'email'     => $this->request->post['email'],
                        'password'  => substr(sha1(uniqid(mt_rand(), true)), 0, 10)
                    );

                    $this->model_billing_customer->addCustomer($data);

                    $customer_info = $this->model_billing_customer->getCustomerByEmail($this->request->post['email']);
                }

                $data = array(
                    'invoice_id'          => $this->request->post['invoice_id'],
                    'recurring_id'        => '',
                    'customer_id'         => $customer_info['customer_id'],
                    'firstname'           => $customer_info['firstname'],
                    'lastname'            => $customer_info['lastname'],
                    'company'             => $customer_info['company'],
                    'website'             => $customer_info['website'],
                    'email'               => $customer_info['email'],
                    'payment_firstname'   => $this->request->post['firstname'],
                    'payment_lastname'    => $this->request->post['lastname'],
                    'payment_company'     => isset($this->request->post['company']) ? $this->request->post['company'] : '',
                    'payment_address_1'   => isset($this->request->post['address_1']) ? $this->request->post['address_1'] : '',
                    'payment_address_2'   => isset($this->request->post['address_2']) ? $this->request->post['address_2'] : '',
                    'payment_city'        => isset($this->request->post['city']) ? $this->request->post['city'] : '',
                    'payment_postcode'    => isset($this->request->post['postcode']) ? $this->request->post['postcode'] : '',
                    'payment_country'     => isset($this->request->post['country']) ? $this->request->post['country'] : '',
                    'payment_zone'        => isset($this->request->post['zone']) ? $this->request->post['zone'] : '',
                    'total'               => $this->request->post['total'],
                    'payment_code'        => isset($this->request->post['payment_code']) ? $this->request->post['payment_code'] : '',
                    'payment_name'        => $this->request->post['payment_name'],
                    'payment_description' => isset($this->request->post['payment_description']) ? $this->request->post['payment_description'] : '',
                    'currency_code'       => $this->request->post['currency_code'],
                    'currency_value'      => $this->request->post['currency_value'],
                    'comment'             => isset($this->request->post['company']) ? $this->request->post['company'] : '',
                    'status_id'           => $this->request->post['status_id'],
                    'date_due'            => $this->request->post['date_due'],
                    'items'               => $items,
                    'totals'              => $totals
                );

                $this->model_billing_invoice->editInvoice($this->request->post['invoice_id'], $data);

                $this->model_system_activity->addActivity(sprintf($this->language->get('text_edited'), $this->request->post['invoice_id'], $this->session->data['username']));

                $json['success'] = true;
                $json['invoice_id'] = $this->request->post['invoice_id'];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        $json = array();

        if (!isset($this->request->post['email']) || (utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
            $json['error'][] = $this->language->get('error_email');
        }

        if (!isset($this->request->post['firstname']) || (utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $json['error'][] = $this->language->get('error_firstname');
        }

        if (!isset($this->request->post['lastname']) || (utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $json['error'][] = $this->language->get('error_lastname');
        }

        if (!isset($this->request->post['payment_name'])) {
            $json['error'][] = $this->language->get('error_payment_name');
        }

        if (!isset($this->request->post['currency_code']) || !$this->currency->has($this->request->post['currency_code'])) {
            $json['error'][] = $this->language->get('error_currency_code');
        }

        if (!isset($this->request->post['currency_value']) || $this->request->post['currency_value'] <= 0) {
            $json['error'][] = $this->language->get('error_currency_value');
        }

        if (!isset($this->request->post['status'])) {
            $json['error'][] = $this->language->get('error_status');
        } else {
            $status_info = $this->model_system_status->getStatusByName($this->request->post['status']);

            if ($status_info) {
                $this->request->post['status_id'] = $status_info['status_id'];
            } else {
                $json['error'][] = $this->language->get('error_status');
            }
        }

        if (!isset($this->request->post['date_due'])) {
            $json['error'][] = $this->language->get('error_date_due');
        }

        $order_total = 0;

        if (!isset($this->request->post['items']) || !is_array($this->request->post['items'])) {
            $json['error'][] = $this->language->get('error_items');
        } else {
            foreach ($this->request->post['items'] as $item) {
                $order_total += isset($item['tax']) ? $item['tax'] : 0;

                $order_total += (isset($item['price']) && isset($item['quantity']) && isset($item['discount'])) ? (((float)$item['price'] - (float)$item['discount']) * (int)$item['quantity']) : 0;
            }
        }

        if (!isset($this->request->post['totals']) || !is_array($this->request->post['totals'])) {
            $json['error'][] = $this->language->get('error_totals');
        }

        if (!isset($this->request->post['total']) || round($this->request->post['total'], 4) != round($order_total, 4)) {
            $json['error'][] = $this->language->get('error_total');
        }

        return $json;
    }
}