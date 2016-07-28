<?php
defined('_PATH') or die('Restricted!');

class ModelBillingCustomer extends Model {
    public function addCustomer($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape(trim($data['email'])) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', date_added = NOW(), date_modified = NOW()");

        $customer_id = $this->db->getLastId();

        $customer_info = $this->getCustomer($customer_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => $this->config->get('config_url'),
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
            'website_url'  => $this->config->get('config_url'),
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
    }

    public function editCustomer($data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', email = '" . $this->db->escape(trim($data['email'])) . "', date_modified = NOW() WHERE customer_id = '" . (int)$this->customer->getId() . "'");

        $customer_info = $this->getCustomer($this->customer->getId());

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => $this->config->get('config_url'),
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
            'website_url'  => $this->config->get('config_url'),
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

    public function editPassword($email, $password, $ip, $forgotten = false) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE email = '" . $this->db->escape($email) . "'");

        if ($forgotten) {
            $customer_info = $this->getCustomerByEmail($email);

            $this->load->model('content/email_template');

            $email_data = array(
                'website_name' => $this->config->get('config_name'),
                'website_url'  => $this->config->get('config_url'),
                'firstname'    => $customer_info['firstname'],
                'lastname'     => $customer_info['lastname'],
                'email'        => $customer_info['email'],
                'password'     => $password,
                'ip'           => $ip,
                'to_email'     => $customer_info['email']
            );

            $this->model_content_email_template->send($email_data, 'forgotten_password_customer');
        }
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row;
    }

    public function getCustomerByEmail($email) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower(trim($email))) . "'");

        return $query->row;
    }

    public function getCustomerByPassword($password) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) AND customer_id = '" . (int)$this->customer->getId() . "'");

        return $query->row;
    }

    public function getCustomerByToken($token) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

        $this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

        return $query->row;
    }

    public function getTotalCustomersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower(trim($email))) . "'");

        return $query->row['total'];
    }

    public function addCredit($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_credit SET customer_id = '" . (int)$data['customer_id'] . "', amount = '" . (float)$data['amount'] . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");
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
}