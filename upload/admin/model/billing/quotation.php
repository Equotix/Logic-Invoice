<?php
defined('_PATH') or die('Restricted!');

class ModelBillingQuotation extends Model {
    public function addQuotation($data) {
        if (empty($data['customer_id'])) {
            $this->load->model('billing/customer');

            if ($customer_info = $this->model_billing_customer->getCustomerByEmail($data['email'])) {
                $data['customer_id'] = $customer_info['customer_id'];
            } else {
                $data['status'] = 1;

                $data['customer_id'] = $this->model_billing_customer->addCustomer($data);
            }
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "quotation SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', total = '" . (float)$data['total'] . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', payment_name = '" . $this->db->escape($data['payment_name']) . "', payment_description = '" . $this->db->escape($data['payment_description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status_id = '" . (int)$data['status_id'] . "', date_issued = NOW(), date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW()");

        $quotation_id = $this->db->getLastId();

        foreach ($data['items'] as $item) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_item SET quotation_id = '" . (int)$quotation_id . "', inventory_id = '" . (int)$item['inventory_id'] . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
        }

        foreach ($data['totals'] as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
        }

        $quotation_info = $this->getQuotation($quotation_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => HTTPS_APPLICATION,
            'customer_id'   => $quotation_info['customer_id'],
            'firstname'     => $quotation_info['firstname'],
            'lastname'      => $quotation_info['lastname'],
            'company'       => $quotation_info['company'],
            'website'       => $quotation_info['website'],
            'email'         => $quotation_info['email'],
            'quotation_id'  => $quotation_info['quotation_id'],
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
            'website_url'   => HTTPS_APPLICATION,
            'customer_id'   => $quotation_info['customer_id'],
            'firstname'     => $quotation_info['firstname'],
            'lastname'      => $quotation_info['lastname'],
            'company'       => $quotation_info['company'],
            'website'       => $quotation_info['website'],
            'email'         => $quotation_info['email'],
            'quotation_id'  => $quotation_info['quotation_id'],
            'comment'       => $quotation_info['comment'],
            'total'         => $this->currency->format($quotation_info['total'], $quotation_info['currency_code'], $quotation_info['currency_value']),
            'status'        => $quotation_info['status'],
            'date_issued'   => date($this->language->get('date_format_short'), strtotime($quotation_info['date_issued'])),
            'date_due'      => date($this->language->get('date_format_short'), strtotime($quotation_info['date_due'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified'])),
            'to_email'      => $quotation_info['email']
        );

        $this->model_content_email_template->send($email_data, 'new_quotation_customer');
    }

    public function editQuotation($quotation_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "quotation SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', total = '" . (float)$data['total'] . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', payment_name = '" . $this->db->escape($data['payment_name']) . "', payment_description = '" . $this->db->escape($data['payment_description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', comment = '" . $this->db->escape($data['comment']) . "', status_id = '" . (int)$data['status_id'] . "', transaction = '0', date_due = '" . $this->db->escape($data['date_due']) . "', date_modified = NOW() WHERE quotation_id = '" . (int)$quotation_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation_item WHERE quotation_id = '" . (int)$quotation_id . "'");

        foreach ($data['items'] as $item) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_item SET quotation_id = '" . (int)$quotation_id . "', inventory_id = '" . (int)$item['inventory_id'] . "', title = '" . $this->db->escape($item['title']) . "', description = '" . $this->db->escape($item['description']) . "', tax_class_id = '" . (int)$item['tax_class_id'] . "', quantity = '" . (int)$item['quantity'] . "', price = '" . (float)$item['price'] . "', tax = '" . (float)$item['tax'] . "', discount = '" . (float)$item['discount'] . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation_total WHERE quotation_id = '" . (int)$quotation_id . "'");

        foreach ($data['totals'] as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', value = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
        }

        $quotation_info = $this->getQuotation($quotation_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => HTTPS_APPLICATION,
            'customer_id'   => $quotation_info['customer_id'],
            'firstname'     => $quotation_info['firstname'],
            'lastname'      => $quotation_info['lastname'],
            'company'       => $quotation_info['company'],
            'website'       => $quotation_info['website'],
            'email'         => $quotation_info['email'],
            'quotation_id'  => $quotation_info['quotation_id'],
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
            'website_url'   => HTTPS_APPLICATION,
            'customer_id'   => $quotation_info['customer_id'],
            'firstname'     => $quotation_info['firstname'],
            'lastname'      => $quotation_info['lastname'],
            'company'       => $quotation_info['company'],
            'website'       => $quotation_info['website'],
            'email'         => $quotation_info['email'],
            'quotation_id'  => $quotation_info['quotation_id'],
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

    public function deleteQuotation($quotation_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation WHERE quotation_id = '" . (int)$quotation_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation_history WHERE quotation_id = '" . (int)$quotation_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation_item WHERE quotation_id = '" . (int)$quotation_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "quotation_total WHERE quotation_id = '" . (int)$quotation_id . "'");
    }

    public function getQuotation($quotation_id) {
        $query = $this->db->query("SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS customer, i.firstname AS firstname, i.lastname AS lastname, i.company AS company, i.website AS website, i.email AS email, i.date_modified AS date_modified, (SELECT name FROM " . DB_PREFIX . "status s WHERE s.status_id = i.status_id) AS status FROM " . DB_PREFIX . "quotation i LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = i.customer_id WHERE quotation_id = '" . (int)$quotation_id . "'");

        if ($query->num_rows) {
            $quotation_item_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_item WHERE quotation_id = '" . (int)$query->row['quotation_id'] . "'");

            $items = array();

            foreach ($quotation_item_query->rows as $item) {
                $items[] = array(
                    'quotation_item_id'  => $item['quotation_item_id'],
                    'quotation_id'       => $item['quotation_id'],
                    'inventory_id'       => $item['inventory_id'],
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
                'items'               => $items,
                'totals'              => $quotation_total_query->rows
            );
        } else {
            return false;
        }
    }

    public function getQuotations($data = array()) {
        $sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name, (SELECT name FROM " . DB_PREFIX . "status s WHERE s.status_id = i.status_id AND s.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM " . DB_PREFIX . "quotation i";

        $implode = array();

        if (!empty($data['filter_quotation_id'])) {
            $implode[] = "quotation_id = '" . (int)$data['filter_quotation_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_total'])) {
            $implode[] = "total = '" . (float)$data['filter_total'] . "'";
        }

        if (isset($data['filter_status_id']) && !is_null($data['filter_status_id'])) {
            $implode[] = "status_id = '" . (int)$data['filter_status_id'] . "'";
        }

        if (!empty($data['filter_date_due'])) {
            $implode[] = "DATE(date_due) = DATE('" . $this->db->escape($data['filter_date_due']) . "')";
        }

        if (!empty($data['filter_date_issued'])) {
            $implode[] = "DATE(date_issued) = DATE('" . $this->db->escape($data['filter_date_issued']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'quotation_id',
            'name',
            'total',
            'status',
            'date_issued',
            'date_due',
            'date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY quotation_id";
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

    public function getTotalQuotations($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quotation";

        $implode = array();

        if (!empty($data['filter_quotation_id'])) {
            $implode[] = "quotation_id = '" . (int)$data['filter_quotation_id'] . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_total'])) {
            $implode[] = "total = '" . (float)$data['filter_total'] . "'";
        }

        if (isset($data['filter_status_id']) && !is_null($data['filter_status_id'])) {
            $implode[] = "status_id = '" . (int)$data['filter_status_id'] . "'";
        }

        if (!empty($data['filter_date_due'])) {
            $implode[] = "DATE(date_due) = DATE('" . $this->db->escape($data['filter_date_due']) . "')";
        }

        if (!empty($data['filter_date_issued'])) {
            $implode[] = "DATE(date_issued) = DATE('" . $this->db->escape($data['filter_date_issued']) . "')";
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

    public function addHistory($quotation_id, $data, $notify = false) {
        $this->db->query("UPDATE " . DB_PREFIX . "quotation SET status_id = '" . (int)$data['status_id'] . "', date_modified = NOW() WHERE quotation_id = '" . (int)$quotation_id . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "quotation_history SET quotation_id = '" . (int)$quotation_id . "', status_id = '" . (int)$data['status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_added = NOW()");

        if ($notify) {
            $quotation_info = $this->getQuotation($quotation_id);

            $this->load->model('content/email_template');

            $email_data = array(
                'website_name'    => $this->config->get('config_name'),
                'website_url'     => HTTPS_APPLICATION,
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

    public function generateInvoice($quotation_id) {
        $quotation_info = $this->getQuotation($quotation_id);
        
        $this->load->model('billing/invoice');
        
        $data = array(
            'quotation_id'          => $quotation_info['quotation_id'],
            'customer_id'           => $quotation_info['customer_id'],
            'firstname'             => $quotation_info['firstname'],
            'lastname'              => $quotation_info['lastname'],
            'company'               => $quotation_info['company'],
            'website'               => $quotation_info['website'],
            'email'                 => $quotation_info['email'],
            'payment_firstname'     => $quotation_info['firstname'],
            'payment_lastname'      => $quotation_info['lastname'],
            'payment_company'       => $quotation_info['company'],
            'payment_address_1'     => '',
            'payment_address_2'     => '',
            'payment_city'          => '',
            'payment_postcode'      => '',
            'payment_country'       => '',
            'payment_zone'          => '',
            'total'                 => $quotation_info['total'],
            'payment_code'          => $quotation_info['payment_code'],
            'payment_name'          => $quotation_info['payment_name'],
            'payment_description'   => $quotation_info['payment_description'],
            'currency_code'         => $quotation_info['currency_code'],
            'currency_value'        => $quotation_info['currency_value'],
            'comment'               => $quotation_info['comment'],
            'status_id'             => $quotation_info['status_id'],
            'date_due'              => $quotation_info['date_due'],
            'items'                 => $quotation_info['items'],
            'totals'                => $quotation_info['totals']
        );
        
        $this->model_billing_invoice->addInvoice($data);
    }
}