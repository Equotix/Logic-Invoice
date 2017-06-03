<?php
defined('_PATH') or die('Restricted!');

class ModelBillingQuotation extends Model {
    public function addQuotation($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "quotation SET recurring_id = '" . (int)$data['recurring_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', total = '" . (float)$data['total'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status_id = '" . (int)$data['status_id'] . "', date_issued = NOW(), date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW()");

        $quotation_id = $this->db->getLastId();

        foreach ($data['items'] as $item) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_item SET quotation_id = '" . (int)$quotation_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
        }

        foreach ($data['totals'] as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
        }

        $quotation_info = $this->getQuotation($quotation_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $quotation_info['customer_id'],
            'firstname'     => $quotation_info['firstname'],
            'lastname'      => $quotation_info['lastname'],
            'company'       => $quotation_info['company'],
            'website'       => $quotation_info['website'],
            'email'         => $quotation_info['email'],
            'quotation_id'    => $quotation_info['quotation_id'],
            'comment'       => $quotation_info['comment'],
            'total'         => $this->currency->format($quotation_info['total'], $quotation_info['currency_code'], $quotation_info['currency_value']),
            'status'        => $quotation_info['status'],
            'date_issued'   => date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($quotation_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified'])),
            'to_email'      => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'new_quotation_admin');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => $this->config->get('config_url'),
            'customer_id'   => $quotation_info['customer_id'],
            'firstname'     => $quotation_info['firstname'],
            'lastname'      => $quotation_info['lastname'],
            'company'       => $quotation_info['company'],
            'website'       => $quotation_info['website'],
            'email'         => $quotation_info['email'],
            'quotation_id'    => $quotation_info['quotation_id'],
            'comment'       => $quotation_info['comment'],
            'total'         => $this->currency->format($quotation_info['total'], $quotation_info['currency_code'], $quotation_info['currency_value']),
            'status'        => $quotation_info['status'],
            'date_issued'   => date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($quotation_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified'])),
            'to_email'      => $quotation_info['email']
        );

        $this->model_content_email_template->send($email_data, 'new_quotation_customer');

        return $quotation_id;
    }

    public function editQuotation($quotation_id, $data, $notify = false) {
        $this->db->query("UPDATE " . DB_PREFIX . "quotation SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', total = '" . (float)$data['total'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status_id = '" . (int)$data['status_id'] . "', transaction = '0', date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW() WHERE quotation_id = '" . (int)$quotation_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation_item WHERE quotation_id = '" . (int)$quotation_id . "'");

        foreach ($data['items'] as $item) {
            if (isset($item['quotation_item_id'])) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_item SET quotation_item_id = '" . (int)$item['quotation_item_id'] . "', quotation_id = '" . (int)$quotation_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_item SET quotation_id = '" . (int)$quotation_id . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation_total WHERE quotation_id = '" . (int)$quotation_id . "'");

        foreach ($data['totals'] as $total) {
            if (isset($total['quotation_total_id'])) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_total_id = '" . (int)$total['quotation_total_id'] . "', quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            }
        }

        if ($notify) {
            $quotation_info = $this->getQuotation($quotation_id);

            $this->load->model('content/email_template');

            $email_data = array(
                'website_name'  => $this->config->get('config_name'),
                'website_url'   => $this->config->get('config_url'),
                'customer_id'   => $quotation_info['customer_id'],
                'firstname'     => $quotation_info['firstname'],
                'lastname'      => $quotation_info['lastname'],
                'company'       => $quotation_info['company'],
                'website'       => $quotation_info['website'],
                'email'         => $quotation_info['email'],
                'quotation_id'    => $quotation_info['quotation_id'],
                'comment'       => $quotation_info['comment'],
                'total'         => $this->currency->format($quotation_info['total'], $quotation_info['currency_code'], $quotation_info['currency_value']),
                'status'        => $quotation_info['status'],
                'date_issued'   => date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])),
                'date_due'      => date($this->language->get('date_format_short'), strtotime($quotation_info['date_due'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified'])),
                'to_email'      => $this->config->get('config_email')
            );

            $this->model_content_email_template->send($email_data, 'edit_quotation_admin');

            $email_data = array(
                'website_name'  => $this->config->get('config_name'),
                'website_url'   => $this->config->get('config_url'),
                'customer_id'   => $quotation_info['customer_id'],
                'firstname'     => $quotation_info['firstname'],
                'lastname'      => $quotation_info['lastname'],
                'company'       => $quotation_info['company'],
                'website'       => $quotation_info['website'],
                'email'         => $quotation_info['email'],
                'quotation_id'    => $quotation_info['quotation_id'],
                'comment'       => $quotation_info['comment'],
                'total'         => $this->currency->format($quotation_info['total'], $quotation_info['currency_code'], $quotation_info['currency_value']),
                'status'        => $quotation_info['status'],
                'date_issued'   => date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])),
                'date_due'      => date($this->language->get('date_format_short'), strtotime($quotation_info['date_due'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified'])),
                'to_email'      => $quotation_info['email']
            );

            $this->model_content_email_template->send($email_data, 'edit_quotation_customer');
        }
    }

    public function getQuotation($quotation_id, $customer_id = false) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS customer, i.firstname AS firstname, i.lastname AS lastname, i.company AS company, i.website AS website, i.email AS email, i.date_modified AS date_modified, (SELECT name FROM " . DB_PREFIX . "status s WHERE s.status_id = i.status_id) AS status FROM " . DB_PREFIX . "quotation i LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = i.customer_id WHERE i.quotation_id = '" . (int)$quotation_id . "' AND i.status_id > 0";

        if ($customer_id) {
            $sql .= " AND i.customer_id = '" . (int)$this->customer->getId() . "'";
        }

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            $quotation_item_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_item WHERE quotation_id = '" . (int)$query->row['quotation_id'] . "'");

            $quotation_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_total WHERE quotation_id = '" . (int)$query->row['quotation_id'] . "' ORDER BY sort_order");

            return array(
                'quotation_id'          => $query->row['quotation_id'],
                'customer_id'         => $query->row['customer_id'],
                'customer'            => $query->row['customer'],
                'firstname'           => $query->row['firstname'],
                'lastname'            => $query->row['lastname'],
                'company'             => $query->row['company'],
                'website'             => $query->row['website'],
                'email'               => $query->row['email'],
                'total'               => $query->row['total'],
                'currency_code'       => $query->row['currency_code'],
                'currency_value'      => $query->row['currency_value'],
                'comment'             => $query->row['comment'],
                'status_id'           => $query->row['status_id'],
                'status'              => $query->row['status'],
                'date_issued'         => $query->row['date_issued'],
                'date_due'            => $query->row['date_due'],
                'date_modified'       => $query->row['date_modified'],
                'items'               => $quotation_item_query->rows,
                'totals'              => $quotation_total_query->rows
            );
        } else {
            return false;
        }
    }

    public function getQuotations($data = array()) {
        $sql = "SELECT *, (SELECT s.name FROM " . DB_PREFIX . "status s WHERE s.status_id = i.status_id AND s.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM " . DB_PREFIX . "quotation i WHERE customer_id = '" . (int)$this->customer->getId() . "' AND status_id > 0 ORDER BY i.quotation_id DESC";

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

    public function getTotalQuotations() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quotation WHERE customer_id = '" . (int)$this->customer->getId() . "' AND status_id > 0");

        return $query->row['total'];
    }

    public function getPendingQuotations() {
        $implode = array();

        $statuses = $this->config->get('config_pending_status');

        foreach ($statuses as $status_id) {
            $implode[] = $status_id;
        }

        if ($implode) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation WHERE status_id IN (" . implode(',', $implode) . ")");

            return $query->rows;
        } else {
            return;
        }
    }

    public function getOverdueQuotations() {
        $implode = array();

        $statuses = $this->config->get('config_overdue_status');

        foreach ($statuses as $status_id) {
            $implode[] = $status_id;
        }

        if ($implode) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation WHERE status_id IN (" . implode(',', $implode) . ")");

            return $query->rows;
        } else {
            return;
        }
    }

    public function addHistory($quotation_id, $data, $notify = false) {
        $this->db->query("UPDATE " . DB_PREFIX . "quotation SET status_id = '" . (int)$data['status_id'] . "', transaction = '0', date_modified = NOW() WHERE quotation_id = '" . (int)$quotation_id . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_history SET quotation_id = '" . (int)$quotation_id . "', status_id = '" . (int)$data['status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_added = NOW()");

        if ($notify) {
            $quotation_info = $this->getQuotation($quotation_id);

            $this->load->model('content/email_template');

            $email_data = array(
                'website_name'    => $this->config->get('config_name'),
                'website_url'     => $this->config->get('config_url'),
                'customer_id'     => $quotation_info['customer_id'],
                'firstname'       => $quotation_info['firstname'],
                'lastname'        => $quotation_info['lastname'],
                'company'         => $quotation_info['company'],
                'website'         => $quotation_info['website'],
                'email'           => $quotation_info['email'],
                'quotation_id'      => $quotation_info['quotation_id'],
                'comment'         => $quotation_info['comment'],
                'history_comment' => $data['comment'],
                'total'           => $this->currency->format($quotation_info['total'], $quotation_info['currency_code'], $quotation_info['currency_value']),
                'status'          => $quotation_info['status'],
                'date_issued'     => date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])),
                'date_due'        => date($this->language->get('date_format_short'), strtotime($quotation_info['date_due'])),
                'date_modified'   => date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified'])),
                'to_email'        => $quotation_info['email']
            );

            $this->model_content_email_template->send($email_data, 'status_' . $data['status_id']);
        }
    }

    public function getHistoriesByQuotation($quotation_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "status s WHERE s.status_id = ih.status_id AND s.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM " . DB_PREFIX . "quotation_history ih WHERE ih.quotation_id = '" . (int)$quotation_id . "' ORDER BY ih.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalHistoriesByQuotation($quotation_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quotation_history WHERE quotation_id = '" . (int)$quotation_id . "'");

        return $query->row['total'];
    }
}