<?php
defined('_PATH') or die('Restricted!');

class ModelAccountingTransaction extends Model {
    public function addTransaction($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "transaction SET description = '" . $this->db->escape($data['description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', invoice_id = '" . (int)$data['invoice_id'] . "', date = '" . $this->db->escape($data['date']) . "', date_added = NOW(), date_modified = NOW()");

        $transaction_id = $this->db->getLastId();

        if (isset($data['transaction_accounts'])) {
            foreach ($data['transaction_accounts'] as $transaction_account) {
                if ($transaction_account['debit'] || $transaction_account['credit']) {
                    if (preg_match('/^\(.+\)$/', $transaction_account['debit'])) {
                        $transaction_account['debit'] = preg_replace('/[^\d.-]/', '', $transaction_account['debit']);

                        $transaction_account['debit'] = '-' . (float)$transaction_account['debit'];
                    }

                    if (preg_match('/^\(.+\)$/', $transaction_account['credit'])) {
                        $transaction_account['credit'] = preg_replace('/[^\d.-]/', '', $transaction_account['credit']);

                        $transaction_account['credit'] = '-' . (float)$transaction_account['credit'];
                    }

                    $this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$transaction_account['account_id'] . "', debit = '" . (float)$transaction_account['debit'] . "', credit = '" . (float)$transaction_account['credit'] . "'");
                }
            }
        }

        $this->db->query("UPDATE " . DB_PREFIX . "invoice SET transaction = '1' WHERE invoice_id = '" . (int)$data['invoice_id'] . "'");

        $transaction_info = $this->getTransaction($transaction_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => HTTPS_APPLICATION,
            'invoice_id'    => $transaction_info['invoice_id'],
            'date'          => date($this->language->get('date_format_short'), strtotime($transaction_info['date'])),
            'date_added'    => date($this->language->get('date_format_short'), strtotime($transaction_info['date_added'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($transaction_info['date_modified'])),
            'to_email'      => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'new_transaction_admin');
    }

    public function editTransaction($transaction_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "transaction SET description = '" . $this->db->escape($data['description']) . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', invoice_id = '" . (int)$data['invoice_id'] . "', date = '" . $this->db->escape($data['date']) . "', date_modified = NOW() WHERE transaction_id = '" . (int)$transaction_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "transaction_account WHERE transaction_id = '" . (int)$transaction_id . "'");

        if (isset($data['transaction_accounts'])) {
            foreach ($data['transaction_accounts'] as $transaction_account) {
                if ($transaction_account['debit'] || $transaction_account['credit']) {
                    if (preg_match('/^\(.+\)$/', $transaction_account['debit'])) {
                        $transaction_account['debit'] = preg_replace('/[^\d.-]/', '', $transaction_account['debit']);

                        $transaction_account['debit'] = '-' . (float)$transaction_account['debit'];
                    }

                    if (preg_match('/^\(.+\)$/', $transaction_account['credit'])) {
                        $transaction_account['credit'] = preg_replace('/[^\d.-]/', '', $transaction_account['credit']);

                        $transaction_account['credit'] = '-' . (float)$transaction_account['credit'];
                    }

                    $this->db->query("INSERT INTO " . DB_PREFIX . "transaction_account SET transaction_id = '" . (int)$transaction_id . "', account_id = '" . (int)$transaction_account['account_id'] . "', debit = '" . (float)$transaction_account['debit'] . "', credit = '" . (float)$transaction_account['credit'] . "'");
                }
            }
        }

        $this->db->query("UPDATE " . DB_PREFIX . "invoice SET transaction = '1' WHERE invoice_id = '" . (int)$data['invoice_id'] . "'");

        $transaction_info = $this->getTransaction($transaction_id);

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name'  => $this->config->get('config_name'),
            'website_url'   => HTTPS_APPLICATION,
            'invoice_id'    => $transaction_info['invoice_id'],
            'date'          => date($this->language->get('date_format_short'), strtotime($transaction_info['date'])),
            'date_added'    => date($this->language->get('date_format_short'), strtotime($transaction_info['date_added'])),
            'date_modified' => date($this->language->get('date_format_short'), strtotime($transaction_info['date_modified'])),
            'to_email'      => $this->config->get('config_email')
        );

        $this->model_content_email_template->send($email_data, 'edit_transaction_admin');
    }

    public function deleteTransaction($transaction_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "transaction WHERE transaction_id = '" . (int)$transaction_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "transaction_account WHERE transaction_id = '" . (int)$transaction_id . "'");
    }

    public function getTransaction($transaction_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction WHERE transaction_id = '" . (int)$transaction_id . "'");

        if ($query->num_rows) {
            $transaction_account_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transaction_account WHERE transaction_id = '" . (int)$query->row['transaction_id'] . "'");

            $transaction_accounts = array();

            foreach ($transaction_account_query->rows as $transaction) {
                $converted_debit = round($transaction['debit'] * $query->row['currency_value'], 4);
                $converted_credit = round($transaction['credit'] * $query->row['currency_value'], 4);

                if ($transaction['debit'] < 0) {
                    $transaction['debit'] = '(' . substr($transaction['debit'], 1) . ')';
                }

                if ($transaction['credit'] < 0) {
                    $transaction['credit'] = '(' . substr($transaction['credit'], 1) . ')';
                }

                $transaction_accounts[$transaction['account_id']] = array(
                    'account_id'       => $transaction['account_id'],
                    'debit'            => $transaction['debit'],
                    'converted_debit'  => $converted_debit,
                    'credit'           => $transaction['credit'],
                    'converted_credit' => $converted_credit
                );
            }

            return array(
                'transaction_id'       => $query->row['transaction_id'],
                'description'          => $query->row['description'],
                'currency_code'        => $query->row['currency_code'],
                'currency_value'       => $query->row['currency_value'],
                'invoice_id'           => $query->row['invoice_id'],
                'date'                 => $query->row['date'],
                'date_added'           => $query->row['date_added'],
                'date_modified'        => $query->row['date_modified'],
                'transaction_accounts' => $transaction_accounts
            );
        } else {
            return false;
        }
    }

    public function getTransactions($data = array()) {
        $sql = "SELECT *, (SELECT SUM(debit) FROM " . DB_PREFIX . "transaction_account ta WHERE ta.transaction_id = t.transaction_id) AS amount FROM " . DB_PREFIX . "transaction t";

        $implode = array();

        if (!empty($data['filter_description'])) {
            $implode[] = "LOWER(description) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_description'])) . "%'";
        }

        if (isset($data['filter_invoice_id']) && !is_null($data['filter_invoice_id'])) {
            $implode[] = "invoice_id = '" . (int)$data['filter_invoice_id'] . "'";
        }

        if (!empty($data['filter_date'])) {
            $implode[] = "DATE(date) = '" . $this->db->escape($data['filter_date']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(date_modified) = '" . $this->db->escape($data['filter_date_modified']) . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'description',
            'invoice_id',
            'amount',
            'date',
            'date_added',
            'date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY date";
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

    public function getTotalTransactions($data = array()) {
        $sql = "SELECT COUNT(*) AS total, (SELECT SUM(debit) FROM " . DB_PREFIX . "transaction_account ta WHERE ta.transaction_id = t.transaction_id) AS amount FROM " . DB_PREFIX . "transaction t";

        $implode = array();

        if (!empty($data['filter_description'])) {
            $implode[] = "LOWER(description) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_description'])) . "%'";
        }

        if (isset($data['filter_invoice_id']) && !is_null($data['filter_invoice_id'])) {
            $implode[] = "invoice_id = '" . (int)$data['filter_invoice_id'] . "'";
        }

        if (!empty($data['filter_date'])) {
            $implode[] = "DATE(date) = '" . $this->db->escape($data['filter_date']) . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (!empty($data['filter_date_modified'])) {
            $implode[] = "DATE(date_modified) = '" . $this->db->escape($data['filter_date_modified']) . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}