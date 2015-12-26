<?php
defined('_PATH') or die('Restricted!');

class ModelBillingRecurring extends Model {
    public function addRecurring($data) {
        if (empty($data['customer_id'])) {
            $this->load->model('billing/customer');

            if ($customer_info = $this->model_billing_customer->getCustomerByEmail($data['email'])) {
                $data['customer_id'] = $customer_info['customer_id'];
            } else {
                $temp_status = $data['status'];

                $data['status'] = 1;

                $data['customer_id'] = $this->model_billing_customer->addCustomer($data);

                $data['status'] = $temp_status;
            }
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "recurring SET customer_id = '" . (int)$data['customer_id'] . "', total = '" . (float)$data['total'] . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', payment_name = '" . $this->db->escape($data['payment_name']) . "', payment_description = '" . $this->db->escape($data['payment_description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status = '" . (int)$data['status'] . "', cycle = '" . $this->db->escape($data['cycle']) . "', date_added = NOW(), date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW()");

        $recurring_id = $this->db->getLastId();

        foreach ($data['items'] as $item) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "recurring_item SET recurring_id = '" . (int)$recurring_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
        }

        foreach ($data['totals'] as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "recurring_total SET recurring_id = '" . (int)$recurring_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
        }

        $recurring_info = $this->getRecurring($recurring_id);

        $this->load->model('billing/customer');

        $customer_info = $this->model_billing_customer->getCustomer($recurring_info['customer_id']);

        $this->load->model('content/email_template');

        $this->load->language('account/recurring');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $customer_info['customer_id'],
            'firstname'     => $customer_info['firstname'],
            'lastname'      => $customer_info['lastname'],
            'company'       => $customer_info['company'],
            'website'       => $customer_info['website'],
            'email'         => $customer_info['email'],
            'recurring_id'  => $recurring_info['recurring_id'],
            'comment'       => $recurring_info['comment'],
            'total'         => $this->currency->format($recurring_info['total'], $recurring_info['currency_code'], $recurring_info['currency_value']),
            'status'        => $recurring_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'cycle'         => $this->language->get('text_' . $recurring_info['cycle']),
            'payment_name'  => $recurring_info['payment_name'],
            'date_added'    => date($this->language->get('date_format_short'), strtotime($recurring_info['date_added'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($recurring_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($recurring_info['date_modified'])),
            'to_email'      => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'new_recurring_admin');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $customer_info['customer_id'],
            'firstname'     => $customer_info['firstname'],
            'lastname'      => $customer_info['lastname'],
            'company'       => $customer_info['company'],
            'website'       => $customer_info['website'],
            'email'         => $customer_info['email'],
            'recurring_id'  => $recurring_info['recurring_id'],
            'comment'       => $recurring_info['comment'],
            'total'         => $this->currency->format($recurring_info['total'], $recurring_info['currency_code'], $recurring_info['currency_value']),
            'status'        => $recurring_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'cycle'         => $this->language->get('text_' . $recurring_info['cycle']),
            'payment_name'  => $recurring_info['payment_name'],
            'date_added'    => date($this->language->get('date_format_short'), strtotime($recurring_info['date_added'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($recurring_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($recurring_info['date_modified'])),
            'to_email'      => $customer_info['email']
        );

        $this->model_content_email_template->send($email_data, 'new_recurring_customer');
    }

    public function updateRecurring($recurring_id, $date_due) {
        $this->db->query("UPDATE " . DB_PREFIX . "recurring SET date_due = '" . $this->db->escape($date_due) . "', date_modified = NOW() WHERE recurring_id = '" . (int)$recurring_id . "'");
    }

    public function cancelRecurring($recurring_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "recurring SET status = '0', date_modified = NOW() WHERE recurring_id = '" . (int)$recurring_id . "'");

        $recurring_info = $this->getRecurring($recurring_id);

        $this->load->model('billing/customer');

        $customer_info = $this->model_billing_customer->getCustomer($recurring_info['customer_id']);

        $this->load->model('content/email_template');

        $this->load->language('account/recurring');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $customer_info['customer_id'],
            'firstname'     => $customer_info['firstname'],
            'lastname'      => $customer_info['lastname'],
            'company'       => $customer_info['company'],
            'website'       => $customer_info['website'],
            'email'         => $customer_info['email'],
            'recurring_id'  => $recurring_info['recurring_id'],
            'comment'       => $recurring_info['comment'],
            'total'         => $this->currency->format($recurring_info['total'], $recurring_info['currency_code'], $recurring_info['currency_value']),
            'status'        => $recurring_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'cycle'         => $this->language->get('text_' . $recurring_info['cycle']),
            'payment_name'  => $recurring_info['payment_name'],
            'date_added'    => date($this->language->get('date_format_short'), strtotime($recurring_info['date_added'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($recurring_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($recurring_info['date_modified'])),
            'to_email'      => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'edit_recurring_admin');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $customer_info['customer_id'],
            'firstname'     => $customer_info['firstname'],
            'lastname'      => $customer_info['lastname'],
            'company'       => $customer_info['company'],
            'website'       => $customer_info['website'],
            'email'         => $customer_info['email'],
            'recurring_id'  => $recurring_info['recurring_id'],
            'comment'       => $recurring_info['comment'],
            'total'         => $this->currency->format($recurring_info['total'], $recurring_info['currency_code'], $recurring_info['currency_value']),
            'status'        => $recurring_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'cycle'         => $this->language->get('text_' . $recurring_info['cycle']),
            'payment_name'  => $recurring_info['payment_name'],
            'date_added'    => date($this->language->get('date_format_short'), strtotime($recurring_info['date_added'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($recurring_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($recurring_info['date_modified'])),
            'to_email'      => $customer_info['email']
        );

        $this->model_content_email_template->send($email_data, 'edit_recurring_customer');
    }

    public function getRecurring($recurring_id, $customer_id = false) {
        $sql = "SELECT * FROM " . DB_PREFIX . "recurring WHERE recurring_id = '" . (int)$recurring_id . "'";

        if ($customer_id) {
            $sql .= " AND customer_id = '" . (int)$this->customer->getId() . "'";
        }

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            $recurring_item_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring_item WHERE recurring_id = '" . (int)$query->row['recurring_id'] . "'");

            $recurring_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring_total WHERE recurring_id = '" . (int)$query->row['recurring_id'] . "' ORDER BY sort_order");

            return array(
                'recurring_id'        => $query->row['recurring_id'],
                'customer_id'         => $query->row['customer_id'],
                'total'               => $query->row['total'],
                'payment_code'        => $query->row['payment_code'],
                'payment_name'        => $query->row['payment_name'],
                'payment_description' => $query->row['payment_description'],
                'currency_code'       => $query->row['currency_code'],
                'currency_value'      => $query->row['currency_value'],
                'comment'             => $query->row['comment'],
                'status'              => $query->row['status'],
                'cycle'               => $query->row['cycle'],
                'date_added'          => $query->row['date_added'],
                'date_due'            => $query->row['date_due'],
                'items'               => $recurring_item_query->rows,
                'totals'              => $recurring_total_query->rows
            );
        } else {
            return false;
        }
    }

    public function getRecurrings($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "recurring WHERE customer_id = '" . (int)$this->customer->getId() . "' ORDER BY recurring_id DESC";

        if (isset($data['start']) && isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getDueRecurrings() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring WHERE DATE_SUB(date_due, INTERVAL " . (int)$this->config->get('config_recurring_invoice_days') . " DAY) <= NOW() AND status = '1'");

        return $query->rows;
    }

    public function getTotalRecurrings() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recurring WHERE customer_id = '" . (int)$this->customer->getId() . "'");

        return $query->row['total'];
    }
}