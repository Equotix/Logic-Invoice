<?php
defined('_PATH') or die('Restricted!');

class ModelBillingCustomer extends Model {
    public function addCustomer($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");

        $customer_id = $this->db->getLastId();

        if (empty($data['password'])) {
            $data['password'] = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
        }

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(sha1(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");

        $customer_info = $this->getCustomer($customer_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => HTTPS_APPLICATION,
            'customer_id'  => $customer_info['customer_id'],
            'firstname'    => $customer_info['firstname'],
            'lastname'     => $customer_info['lastname'],
            'company'      => $customer_info['company'],
            'website'      => $customer_info['website'],
            'email'        => $customer_info['email'],
            'status'       => $customer_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'to_email'     => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'new_customer_admin');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => HTTPS_APPLICATION,
            'customer_id'  => $customer_info['customer_id'],
            'firstname'    => $customer_info['firstname'],
            'lastname'     => $customer_info['lastname'],
            'company'      => $customer_info['company'],
            'website'      => $customer_info['website'],
            'email'        => $customer_info['email'],
            'status'       => $customer_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'password'     => $data['password'],
            'to_email'     => $customer_info['email']
        );

        $this->model_content_email_template->send($email_data, 'new_customer_customer');

        return $customer_id;
    }

    public function editCustomer($customer_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape($data['email']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE customer_id = '" . (int)$customer_id . "'");

        if (!empty($data['password'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");
        }

        $customer_info = $this->getCustomer($customer_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => HTTPS_APPLICATION,
            'customer_id'  => $customer_info['customer_id'],
            'firstname'    => $customer_info['firstname'],
            'lastname'     => $customer_info['lastname'],
            'company'      => $customer_info['company'],
            'website'      => $customer_info['website'],
            'email'        => $customer_info['email'],
            'status'       => $customer_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'to_email'     => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'edit_customer_admin');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => HTTPS_APPLICATION,
            'customer_id'  => $customer_info['customer_id'],
            'firstname'    => $customer_info['firstname'],
            'lastname'     => $customer_info['lastname'],
            'company'      => $customer_info['company'],
            'website'      => $customer_info['website'],
            'email'        => $customer_info['email'],
            'status'       => $customer_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'to_email'     => $customer_info['email']
        );

        $this->model_content_email_template->send($email_data, 'edit_customer_customer');
    }

    public function deleteCustomer($customer_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int)$customer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");

        $this->load->model('billing/invoice');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice WHERE customer_id = '" . (int)$customer_id . "'");

        foreach ($query->rows as $result) {
            $this->model_billing_invoice->deleteInvoice($result['invoice_id']);
        }

        $this->load->model('billing/recurring');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring WHERE customer_id = '" . (int)$customer_id . "'");

        foreach ($query->rows as $result) {
            $this->model_billing_recurring->deleteRecurring($result['recurring_id']);
        }
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row;
    }

    public function getCustomerByEmail($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getCustomers($data = array()) {
        $sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name, (SELECT SUM(amount) FROM " . DB_PREFIX . "customer_credit cc WHERE cc.customer_id = c.customer_id) AS credit, (SELECT SUM(total) FROM " . DB_PREFIX . "invoice i WHERE i.customer_id = c.customer_id) AS invoice, c.date_added AS date_added FROM " . DB_PREFIX . "customer c";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int)$data['filter_status'] . "'";
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
            'name',
            'email',
            'credit',
            'invoice',
            'status',
            'date_added',
            'date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

    public function getTotalCustomers($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_status'])) {
            $implode[] = "status = '" . (int)$data['filter_status'] . "'";
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

    public function editToken($customer_id, $token) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");
    }

    public function addCredit($customer_id, $data) {
        if (preg_match('/^\(.+\)$/', $data['amount'])) {
            $data['amount'] = preg_replace('/[^\d.-]/', '', $data['amount']);

            $data['amount'] = '-' . (float)$data['amount'];
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_credit SET customer_id = '" . (int)$customer_id . "', amount = '" . (float)$data['amount'] . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");

        $customer_info = $this->getCustomer($customer_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => HTTPS_APPLICATION,
            'customer_id'  => $customer_info['customer_id'],
            'firstname'    => $customer_info['firstname'],
            'lastname'     => $customer_info['lastname'],
            'company'      => $customer_info['company'],
            'website'      => $customer_info['website'],
            'email'        => $customer_info['email'],
            'status'       => $customer_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'amount'       => $this->currency->format($data['amount']),
            'description'  => $data['description'],
            'date_added'   => date($this->language->get('date_format_short')),
            'to_email'     => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'new_credit_admin');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => HTTPS_APPLICATION,
            'customer_id'  => $customer_info['customer_id'],
            'firstname'    => $customer_info['firstname'],
            'lastname'     => $customer_info['lastname'],
            'company'      => $customer_info['company'],
            'website'      => $customer_info['website'],
            'email'        => $customer_info['email'],
            'status'       => $customer_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
            'amount'       => $this->currency->format($data['amount']),
            'description'  => $data['description'],
            'date_added'   => date($this->language->get('date_format_short')),
            'to_email'     => $customer_info['email']
        );

        $this->model_content_email_template->send($email_data, 'new_credit_customer');
    }

    public function getCreditsByCustomer($customer_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalCreditsByCustomer($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['total'];
    }

    public function getCustomerTotalCredits($customer_id) {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['total'];
    }

    public function getIPsByCustomer($customer_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . $limit);

        return $query->rows;
    }

    public function getTotalIPsByCustomer($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['total'];
    }
}