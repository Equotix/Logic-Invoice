<?php
defined('_PATH') or die('Restricted!');

class ControllerApiRecurring extends Controller {
    public function check() {
        $this->load->language('api/recurring');

        $json = array();

        $json['success'] = false;

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('billing/recurring');
            $this->load->model('billing/customer');
            $this->load->model('billing/invoice');
            $this->load->model('system/activity');

            // Due
            $recurrings = $this->model_billing_recurring->getDueRecurrings();

            foreach ($recurrings as $recurring) {
                $recurring_info = $this->model_billing_recurring->getRecurring($recurring['recurring_id']);

                if ($recurring_info) {
                    $customer_info = $this->model_billing_customer->getCustomer($recurring_info['customer_id']);

                    if ($customer_info) {
                        $data = array(
                            'recurring_id'        => $recurring_info['recurring_id'],
                            'customer_id'         => $recurring_info['customer_id'],
                            'firstname'           => $customer_info['firstname'],
                            'lastname'            => $customer_info['lastname'],
                            'company'             => $customer_info['company'],
                            'website'             => $customer_info['website'],
                            'email'               => $customer_info['email'],
                            'payment_firstname'   => '',
                            'payment_lastname'    => '',
                            'payment_company'     => '',
                            'payment_address_1'   => '',
                            'payment_address_2'   => '',
                            'payment_city'        => '',
                            'payment_postcode'    => '',
                            'payment_country'     => '',
                            'payment_zone'        => '',
                            'total'               => $recurring_info['total'],
                            'payment_code'        => $recurring_info['payment_code'],
                            'payment_name'        => $recurring_info['payment_name'],
                            'payment_description' => $recurring_info['payment_description'],
                            'currency_code'       => $recurring_info['currency_code'],
                            'currency_value'      => $recurring_info['currency_value'],
                            'comment'             => $recurring_info['comment'],
                            'status_id'           => $this->config->get('config_recurring_default_status'),
                            'date_due'            => $recurring_info['date_due'],
                            'items'               => $recurring_info['items'],
                            'totals'              => $recurring_info['totals']
                        );

                        $invoice_id = $this->model_billing_invoice->addInvoice($data);

                        $this->model_system_activity->addActivity(sprintf($this->language->get('text_invoice'), $invoice_id, $this->session->data['username']));

                        if ($recurring_info['cycle'] == 'monthly') {
                            $date_due = date('Y-m-d', strtotime($recurring_info['date_due'] . ' +1 month'));
                        } elseif ($recurring_info['cycle'] == 'quarterly') {
                            $date_due = date('Y-m-d', strtotime($recurring_info['date_due'] . ' +3 months'));
                        } elseif ($recurring_info['cycle'] == 'semi_annually') {
                            $date_due = date('Y-m-d', strtotime($recurring_info['date_due'] . ' +6 months'));
                        } elseif ($recurring_info['cycle'] == 'annually') {
                            $date_due = date('Y-m-d', strtotime($recurring_info['date_due'] . ' +1 year'));
                        } elseif ($recurring_info['cycle'] == 'biennally') {
                            $date_due = date('Y-m-d', strtotime($recurring_info['date_due'] . ' +2 years'));
                        } else {
                            $date_due = date('Y-m-d', strtotime($recurring_info['date_due'] . ' +3 years'));
                        }

                        $this->model_billing_recurring->updateRecurring($recurring['recurring_id'], $date_due);

                        $this->model_system_activity->addActivity(sprintf($this->language->get('text_updated'), $recurring['recurring_id'], $date_due, $this->session->data['username']));
                    }
                }
            }

            // Void
            $invoices = $this->model_billing_invoice->getOverdueInvoices();

            foreach ($invoices as $invoice) {
                if (strtotime($invoice['date_due'] . ' +' . $this->config->get('config_recurring_disable_days') . ' days') <= time()) {
                    $this->model_billing_recurring->cancelRecurring($invoice['recurring_id']);

                    $this->model_system_activity->addActivity(sprintf($this->language->get('text_cancelled'), $invoice['recurring_id'], $this->session->data['username']));
                }
            }

            $json['success'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function add() {
        $this->load->language('api/recurring');

        $json = array();

        $json['success'] = false;

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('billing/recurring');
            $this->load->model('system/activity');
            $this->load->model('system/status');

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

            if (!isset($this->request->post['currency_value']) || $this->request->post['currency_code'] < 0) {
                $json['error'][] = $this->language->get('error_currency_value');
            }

            if (!isset($this->request->post['status'])) {
                $json['error'][] = $this->language->get('error_status');
            }

            if (!isset($this->request->post['cycle'])) {
                $json['error'][] = $this->language->get('error_cycle');
            }

            if (!isset($this->request->post['date_due'])) {
                $json['error'][] = $this->language->get('error_date_due');
            }

            $total = 0;
            $tax = 0;

            if (!isset($this->request->post['items']) || !is_array($this->request->post['items'])) {
                $json['error'][] = $this->language->get('error_items');
            } else {
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

                    $tax += isset($item['tax']) ? $item['tax'] : 0;

                    $total += (isset($item['price']) && isset($item['quantity']) && isset($item['discount'])) ? (((float)$item['price'] - (float)$item['discount']) * (int)$item['quantity']) : 0;
                }
            }

            if (!isset($this->request->post['totals']) || !is_array($this->request->post['totals'])) {
                $json['error'][] = $this->language->get('error_totals');
            } else {
                $totals = array();

                foreach ($this->request->post['totals'] as $total) {
                    $totals[] = array(
                        'code'       => isset($total['code']) ? $total['code'] : '',
                        'title'      => isset($total['title']) ? $total['title'] : '',
                        'value'      => isset($total['value']) ? $total['value'] : 0,
                        'sort_order' => isset($total['sort_order']) ? $total['sort_order'] : 0,
                    );
                }
            }

            $total += $tax;

            if (!isset($this->request->post['total']) || $this->request->post['total'] != $total) {
                $json['error'][] = $this->language->get('error_total');
            }

            if (empty($json['error'])) {
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
                    'customer_id'         => $customer_info['customer_id'],
                    'total'               => $this->request->post['total'],
                    'payment_code'        => isset($this->request->post['payment_code']) ? $this->request->post['payment_code'] : '',
                    'payment_name'        => $this->request->post['payment_name'],
                    'payment_description' => isset($this->request->post['payment_description']) ? $this->request->post['payment_description'] : '',
                    'currency_code'       => $this->request->post['currency_code'],
                    'currency_value'      => $this->request->post['currency_value'],
                    'comment'             => isset($this->request->post['comment']) ? $this->request->post['comment'] : '',
                    'status'              => $this->request->post['status'],
                    'cycle'               => $this->request->post['cycle'],
                    'date_due'            => $this->request->post['date_due'],
                    'items'               => $items,
                    'totals'              => $totals
                );

                $recurring_id = $this->model_billing_recurring->addRecurring($data);

                $this->model_system_activity->addActivity(sprintf($this->language->get('text_recurring'), $recurring_id, $this->session->data['username']));

                $json['success'] = true;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}