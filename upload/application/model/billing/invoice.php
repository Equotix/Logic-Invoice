<?php
defined('_PATH') or die('Restricted!');

class ModelBillingInvoice extends Model {
    public function addInvoice($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "invoice SET recurring_id = '" . (int)$data['recurring_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', total = '" . (float)$data['total'] . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', payment_name = '" . $this->db->escape($data['payment_name']) . "', payment_description = '" . $this->db->escape($data['payment_description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status_id = '" . (int)$data['status_id'] . "', date_issued = NOW(), date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW()");

        $invoice_id = $this->db->getLastId();

        foreach ($data['items'] as $item) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_item SET invoice_id = '" . (int)$invoice_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
        }

        foreach ($data['totals'] as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_total SET invoice_id = '" . (int)$invoice_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
        }

        $invoice_info = $this->getInvoice($invoice_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $invoice_info['customer_id'],
            'firstname'     => $invoice_info['firstname'],
            'lastname'      => $invoice_info['lastname'],
            'company'       => $invoice_info['company'],
            'website'       => $invoice_info['website'],
            'email'         => $invoice_info['email'],
            'invoice_id'    => $invoice_info['invoice_id'],
            'comment'       => $invoice_info['comment'],
            'total'         => $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value']),
            'status'        => $invoice_info['status'],
            'payment_name'  => $invoice_info['payment_name'],
            'date_issued'   => date($this->language->get('date_format_short'), strtotime($invoice_info['date_issued'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($invoice_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified'])),
            'to_email'      => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'new_invoice_admin');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $invoice_info['customer_id'],
            'firstname'     => $invoice_info['firstname'],
            'lastname'      => $invoice_info['lastname'],
            'company'       => $invoice_info['company'],
            'website'       => $invoice_info['website'],
            'email'         => $invoice_info['email'],
            'invoice_id'    => $invoice_info['invoice_id'],
            'comment'       => $invoice_info['comment'],
            'total'         => $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value']),
            'status'        => $invoice_info['status'],
            'payment_name'  => $invoice_info['payment_name'],
            'date_issued'   => date($this->language->get('date_format_short'), strtotime($invoice_info['date_issued'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($invoice_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified'])),
            'to_email'      => $invoice_info['email']
        );

        $this->model_content_email_template->send($email_data, 'new_invoice_customer');

        return $invoice_id;
    }

    public function editInvoice($invoice_id, $data, $notify = false) {
        $this->db->query("UPDATE " . DB_PREFIX . "invoice SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', total = '" . (float)$data['total'] . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', payment_name = '" . $this->db->escape($data['payment_name']) . "', payment_description = '" . $this->db->escape($data['payment_description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status_id = '" . (int)$data['status_id'] . "', transaction = '0', date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW() WHERE invoice_id = '" . (int)$invoice_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "invoice_item WHERE invoice_id = '" . (int)$invoice_id . "'");

        foreach ($data['items'] as $item) {
            if (isset($item['invoice_item_id'])) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_item SET invoice_item_id = '" . (int)$item['invoice_item_id'] . "', invoice_id = '" . (int)$invoice_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_item SET invoice_id = '" . (int)$invoice_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "invoice_total WHERE invoice_id = '" . (int)$invoice_id . "'");

        foreach ($data['totals'] as $total) {
            if (isset($total['invoice_total_id'])) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_total SET invoice_total_id = '" . (int)$total['invoice_total_id'] . "', invoice_id = '" . (int)$invoice_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_total SET invoice_id = '" . (int)$invoice_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            }
        }

        if ($notify) {
            $invoice_info = $this->getInvoice($invoice_id);

            $this->load->model('content/email_template');

            $email_data = array(
                'website_name'  => $this->config->get('config_name'),
                'website_url'   => $this->config->get('config_url'),
                'customer_id'   => $invoice_info['customer_id'],
                'firstname'     => $invoice_info['firstname'],
                'lastname'      => $invoice_info['lastname'],
                'company'       => $invoice_info['company'],
                'website'       => $invoice_info['website'],
                'email'         => $invoice_info['email'],
                'invoice_id'    => $invoice_info['invoice_id'],
                'comment'       => $invoice_info['comment'],
                'total'         => $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value']),
                'status'        => $invoice_info['status'],
                'payment_name'  => $invoice_info['payment_name'],
                'date_issued'   => date($this->language->get('date_format_short'), strtotime($invoice_info['date_issued'])),
                'date_due'      => date($this->language->get('date_format_short'), strtotime($invoice_info['date_due'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified'])),
                'to_email'      => $this->config->get('config_email')
            );

            $this->model_content_email_template->send($email_data, 'edit_invoice_admin');

            $email_data = array(
                'website_name'  => $this->config->get('config_name'),
                'website_url'   => $this->config->get('config_url'),
                'customer_id'   => $invoice_info['customer_id'],
                'firstname'     => $invoice_info['firstname'],
                'lastname'      => $invoice_info['lastname'],
                'company'       => $invoice_info['company'],
                'website'       => $invoice_info['website'],
                'email'         => $invoice_info['email'],
                'invoice_id'    => $invoice_info['invoice_id'],
                'comment'       => $invoice_info['comment'],
                'total'         => $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value']),
                'status'        => $invoice_info['status'],
                'payment_name'  => $invoice_info['payment_name'],
                'date_issued'   => date($this->language->get('date_format_short'), strtotime($invoice_info['date_issued'])),
                'date_due'      => date($this->language->get('date_format_short'), strtotime($invoice_info['date_due'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified'])),
                'to_email'      => $invoice_info['email']
            );

            $this->model_content_email_template->send($email_data, 'edit_invoice_customer');
        }
    }

    public function getInvoice($invoice_id, $customer_id = false) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS customer, i.firstname AS firstname, i.lastname AS lastname, i.company AS company, i.website AS website, i.email AS email, i.date_modified AS date_modified, (SELECT name FROM " . DB_PREFIX . "status s WHERE s.status_id = i.status_id) AS status FROM " . DB_PREFIX . "invoice i LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = i.customer_id WHERE i.invoice_id = '" . (int)$invoice_id . "' AND i.status_id > 0";

        if ($customer_id) {
            $sql .= " AND i.customer_id = '" . (int)$this->customer->getId() . "'";
        }

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            $invoice_item_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_item WHERE invoice_id = '" . (int)$query->row['invoice_id'] . "'");

            $invoice_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_total WHERE invoice_id = '" . (int)$query->row['invoice_id'] . "' ORDER BY sort_order");

            return array(
                'invoice_id'          => $query->row['invoice_id'],
                'customer_id'         => $query->row['customer_id'],
                'customer'            => $query->row['customer'],
                'firstname'           => $query->row['firstname'],
                'lastname'            => $query->row['lastname'],
                'company'             => $query->row['company'],
                'website'             => $query->row['website'],
                'email'               => $query->row['email'],
                'payment_firstname'   => $query->row['payment_firstname'],
                'payment_lastname'    => $query->row['payment_lastname'],
                'payment_company'     => $query->row['payment_company'],
                'payment_address_1'   => $query->row['payment_address_1'],
                'payment_address_2'   => $query->row['payment_address_2'],
                'payment_city'        => $query->row['payment_city'],
                'payment_postcode'    => $query->row['payment_postcode'],
                'payment_country'     => $query->row['payment_country'],
                'payment_zone'        => $query->row['payment_zone'],
                'total'               => $query->row['total'],
                'payment_code'        => $query->row['payment_code'],
                'payment_name'        => $query->row['payment_name'],
                'payment_description' => $query->row['payment_description'],
                'currency_code'       => $query->row['currency_code'],
                'currency_value'      => $query->row['currency_value'],
                'comment'             => $query->row['comment'],
                'status_id'           => $query->row['status_id'],
                'status'              => $query->row['status'],
                'date_issued'         => $query->row['date_issued'],
                'date_due'            => $query->row['date_due'],
                'date_modified'       => $query->row['date_modified'],
                'items'               => $invoice_item_query->rows,
                'totals'              => $invoice_total_query->rows
            );
        } else {
            return false;
        }
    }

    public function getInvoices($data = array()) {
        $sql = "SELECT *, (SELECT s.name FROM " . DB_PREFIX . "status s WHERE s.status_id = i.status_id AND s.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM " . DB_PREFIX . "invoice i WHERE customer_id = '" . (int)$this->customer->getId() . "' AND status_id > 0 ORDER BY i.invoice_id DESC";

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

    public function getTotalInvoices() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice WHERE customer_id = '" . (int)$this->customer->getId() . "' AND status_id > 0");

        return $query->row['total'];
    }

    public function getPendingInvoices() {
        $implode = array();

        $statuses = $this->config->get('config_pending_status');

        foreach ($statuses as $status_id) {
            $implode[] = $status_id;
        }

        if ($implode) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice WHERE status_id IN (" . implode(',', $implode) . ")");

            return $query->rows;
        } else {
            return;
        }
    }

    public function getOverdueInvoices() {
        $implode = array();

        $statuses = $this->config->get('config_overdue_status');

        foreach ($statuses as $status_id) {
            $implode[] = $status_id;
        }

        if ($implode) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice WHERE status_id IN (" . implode(',', $implode) . ")");

            return $query->rows;
        } else {
            return;
        }
    }

    public function getOverdueRecurringPayments() {
        $implode = array();

        $statuses = $this->config->get('config_overdue_status');

        foreach ($statuses as $status_id) {
            $implode[] = $status_id;
        }

        $statuses = $this->config->get('config_void_status');

        foreach ($statuses as $status_id) {
            $implode[] = $status_id;
        }

        if ($implode) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice i LEFT JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = i.recurring_id WHERE i.status_id IN (" . implode(',', $implode) . ") AND i.recurring_id != '0' AND r.status = '1'");

            return $query->rows;
        } else {
            return;
        }
    }

    public function addHistory($invoice_id, $data, $notify = false) {
        $this->db->query("UPDATE " . DB_PREFIX . "invoice SET status_id = '" . (int)$data['status_id'] . "', transaction = '0', date_modified = NOW() WHERE invoice_id = '" . (int)$invoice_id . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "invoice_history SET invoice_id = '" . (int)$invoice_id . "', status_id = '" . (int)$data['status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_added = NOW()");

        if ($notify) {
            $invoice_info = $this->getInvoice($invoice_id);

            $this->load->model('content/email_template');

            $email_data = array(
                'website_name'    => $this->config->get('config_name'),
                'website_url'     => $this->config->get('config_url'),
                'customer_id'     => $invoice_info['customer_id'],
                'firstname'       => $invoice_info['firstname'],
                'lastname'        => $invoice_info['lastname'],
                'company'         => $invoice_info['company'],
                'website'         => $invoice_info['website'],
                'email'           => $invoice_info['email'],
                'invoice_id'      => $invoice_info['invoice_id'],
                'comment'         => $invoice_info['comment'],
                'history_comment' => $data['comment'],
                'total'           => $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value']),
                'status'          => $invoice_info['status'],
                'payment_name'    => $invoice_info['payment_name'],
                'date_issued'     => date($this->language->get('date_format_short'), strtotime($invoice_info['date_issued'])),
                'date_due'        => date($this->language->get('date_format_short'), strtotime($invoice_info['date_due'])),
                'date_modified'   => date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified'])),
                'to_email'        => $invoice_info['email']
            );

            $this->model_content_email_template->send($email_data, 'status_' . $data['status_id']);
        }
    }

    public function getHistoriesByInvoice($invoice_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "status s WHERE s.status_id = ih.status_id AND s.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM " . DB_PREFIX . "invoice_history ih WHERE ih.invoice_id = '" . (int)$invoice_id . "' ORDER BY ih.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalHistoriesByInvoice($invoice_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice_history WHERE invoice_id = '" . (int)$invoice_id . "'");

        return $query->row['total'];
    }
}