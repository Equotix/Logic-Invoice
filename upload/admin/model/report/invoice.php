<?php
defined('_PATH') or die('Restricted!');

class ModelReportInvoice extends Model {
    public function getInvoicesByGroup($data) {
        $sql = "SELECT MIN(i.date_issued) AS date_start, MAX(i.date_issued) AS date_end, COUNT(*) AS invoices, SUM((SELECT SUM(ii.quantity) FROM " . DB_PREFIX . "invoice_item ii WHERE ii.invoice_id = i.invoice_id GROUP BY ii.invoice_id)) AS items, SUM((SELECT SUM(it.value) FROM " . DB_PREFIX . "invoice_total it WHERE it.invoice_id = i.invoice_id AND it.code = 'tax' GROUP BY it.invoice_id)) AS tax, SUM(i.total) AS `total` FROM " . DB_PREFIX . "invoice i";

        if (!empty($data['filter_status_id'])) {
            $sql .= " WHERE i.status_id = '" . (int)$data['filter_status_id'] . "'";
        } else {
            $sql .= " WHERE i.status_id > '0'";
        }

        if (!empty($data['filter_date_issued_start'])) {
            $sql .= " AND DATE(i.date_issued) >= '" . $this->db->escape($data['filter_date_issued_start']) . "'";
        }

        if (!empty($data['filter_date_issued_end'])) {
            $sql .= " AND DATE(i.date_issued) <= '" . $this->db->escape($data['filter_date_issued_end']) . "'";
        }

        switch ($data['filter_group']) {
            case 'day';
                $sql .= " GROUP BY YEAR(i.date_issued), MONTH(i.date_issued), DAY(i.date_issued)";
                break;
            default:
            case 'week':
                $sql .= " GROUP BY YEAR(i.date_issued), WEEK(i.date_issued)";
                break;
            case 'month':
                $sql .= " GROUP BY YEAR(i.date_issued), MONTH(i.date_issued)";
                break;
            case 'year':
                $sql .= " GROUP BY YEAR(i.date_issued)";
                break;
        }

        $sql .= " ORDER BY i.date_issued DESC";

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

    public function getTotalInvoicesByGroup($data) {
        switch ($data['filter_group']) {
            case 'day';
                $sql = "SELECT COUNT(DISTINCT YEAR(date_issued), MONTH(date_issued), DAY(date_issued)) AS total FROM " . DB_PREFIX . "invoice";
                break;
            default:
            case 'week':
                $sql = "SELECT COUNT(DISTINCT YEAR(date_issued), WEEK(date_issued)) AS total FROM " . DB_PREFIX . "invoice";
                break;
            case 'month':
                $sql = "SELECT COUNT(DISTINCT YEAR(date_issued), MONTH(date_issued)) AS total FROM " . DB_PREFIX . "invoice";
                break;
            case 'year':
                $sql = "SELECT COUNT(DISTINCT YEAR(date_issued)) AS total FROM " . DB_PREFIX . "invoice";
                break;
        }

        if (!empty($data['filter_status_id'])) {
            $sql .= " WHERE status_id = '" . (int)$data['filter_status_id'] . "'";
        } else {
            $sql .= " WHERE status_id > '0'";
        }

        if (!empty($data['filter_date_issued_start'])) {
            $sql .= " AND DATE(date_issued) >= '" . $this->db->escape($data['filter_date_issued_start']) . "'";
        }

        if (!empty($data['filter_date_issued_end'])) {
            $sql .= " AND DATE(date_issued) <= '" . $this->db->escape($data['filter_date_issued_end']) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalInvoices($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice";

        $implode = array();

        if (!empty($data['filter_date_issued_start']) && !empty($data['filter_date_issued_end'])) {
            $implode[] = "DATE(date_issued) >= DATE('" . $this->db->escape($data['filter_date_issued_start']) . "') AND DATE(date_issued) <= DATE('" . $this->db->escape($data['filter_date_issued_end']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}