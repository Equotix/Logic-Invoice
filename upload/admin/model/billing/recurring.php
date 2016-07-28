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
            'website_url'   => HTTPS_APPLICATION,
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
            'website_url'   => HTTPS_APPLICATION,
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

    public function editRecurring($recurring_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "recurring SET customer_id = '" . (int)$data['customer_id'] . "', total = '" . (float)$data['total'] . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', payment_name = '" . $this->db->escape($data['payment_name']) . "', payment_description = '" . $this->db->escape($data['payment_description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status = '" . (int)$data['status'] . "', cycle = '" . $this->db->escape($data['cycle']) . "', date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW() WHERE recurring_id = '" . (int)$recurring_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "recurring_item WHERE recurring_id = '" . (int)$recurring_id . "'");

        foreach ($data['items'] as $item) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "recurring_item SET recurring_id = '" . (int)$recurring_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "recurring_total WHERE recurring_id = '" . (int)$recurring_id . "'");

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
            'website_url'   => HTTPS_APPLICATION,
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
            'website_url'   => HTTPS_APPLICATION,
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

    public function editStatus($recurring_id, $status) {
        $this->db->query("UPDATE " . DB_PREFIX . "recurring SET status = '" . (int)$status . "', date_modified = NOW() WHERE recurring_id = '" . (int)$recurring_id . "'");

        $recurring_info = $this->getRecurring($recurring_id);

        $this->load->model('billing/customer');

        $customer_info = $this->model_billing_customer->getCustomer($recurring_info['customer_id']);

        $this->load->model('content/email_template');

        $this->load->language('account/recurring');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => HTTPS_APPLICATION,
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
            'website_url'   => HTTPS_APPLICATION,
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

    public function deleteRecurring($recurring_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "recurring WHERE recurring_id = '" . (int)$recurring_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "recurring_item WHERE recurring_id = '" . (int)$recurring_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "recurring_total WHERE recurring_id = '" . (int)$recurring_id . "'");
    }

    public function getRecurring($recurring_id) {
        $query = $this->db->query("SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS customer FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = r.customer_id WHERE recurring_id = '" . (int)$recurring_id . "'");

        if ($query->num_rows) {
            $recurring_item_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring_item WHERE recurring_id = '" . (int)$query->row['recurring_id'] . "'");

            $items = array();

            foreach ($recurring_item_query->rows as $item) {
                $items[] = array(
                    'recurring_item_id'  => $item['recurring_item_id'],
                    'recurring_id'       => $item['recurring_id'],
                    'title'              => $item['title'],
                    'description'        => $item['description'],
                    'tax_class_id'       => $item['tax_class_id'],
                    'quantity'           => $item['quantity'],
                    'price'              => $item['price'],
                    'tax'                => $item['tax'],
                    'converted_price'    => round($item['price'] * $query->row['currency_value'], 4),
                    'discount'           => $item['discount'],
                    'converted_discount' => round($item['discount'] * $query->row['currency_value'], 4)
                );
            }

            $recurring_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring_total WHERE recurring_id = '" . (int)$query->row['recurring_id'] . "' ORDER BY sort_order");

            return array(
                'recurring_id'        => $query->row['recurring_id'],
                'customer_id'         => $query->row['customer_id'],
                'customer'            => $query->row['customer'],
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
                'date_modified'       => $query->row['date_modified'],
                'items'               => $items,
                'totals'              => $recurring_total_query->rows
            );
        } else {
            return false;
        }
    }

    public function getRecurrings($data = array()) {
        $sql = "SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = r.customer_id) AS name FROM " . DB_PREFIX . "recurring r";

        $implode = array();

        if (!empty($data['filter_recurring_id'])) {
            $implode[] = "recurring_id = '" . (int)$data['filter_recurring_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_total'])) {
            $implode[] = "total = '" . (float)$data['filter_total'] . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int)$data['filter_status'] . "'";
        }

        if (!empty($data['filter_cycle'])) {
            $implode[] = "cycle = '" . $this->db->escape($data['filter_cycle']) . "'";
        }

        if (!empty($data['filter_date_due'])) {
            $implode[] = "DATE(date_due) = DATE('" . $this->db->escape($data['filter_date_due']) . "')";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'recurring_id',
            'name',
            'total',
            'status',
            'date_added',
            'date_due',
            'date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY recurring_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

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

    public function getTotalRecurrings($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recurring";

        $implode = array();

        if (!empty($data['filter_recurring_id'])) {
            $implode[] = "recurring_id = '" . (int)$data['filter_recurring_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_total'])) {
            $implode[] = "total = '" . (float)$data['filter_total'] . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int)$data['filter_status'] . "'";
        }

        if (!empty($data['filter_cycle'])) {
            $implode[] = "cycle = '" . $this->db->escape($data['filter_cycle']) . "'";
        }

        if (!empty($data['filter_date_due'])) {
            $implode[] = "DATE(date_due) = DATE('" . $this->db->escape($data['filter_date_due']) . "')";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}