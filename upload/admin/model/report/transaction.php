<?php
defined('_PATH') or die('Restricted!');

class ModelReportTransaction extends Model {
    public function getTotalByAccount($account_id, $data) {
        $sql = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM " . DB_PREFIX . "transaction_account ta LEFT JOIN " . DB_PREFIX . "transaction t ON t.transaction_id = ta.transaction_id WHERE ta.account_id = '" . (int)$account_id . "'";

        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(t.date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(t.date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getTotalTransactions($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "transaction";

        $implode = array();

        if (!empty($data['filter_date_start']) && !empty($data['filter_date_end'])) {
            $implode[] = "DATE(date) >= DATE('" . $this->db->escape($data['filter_date_start']) . "') AND DATE(date) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}