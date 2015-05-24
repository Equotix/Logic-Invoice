<?php
defined('_PATH') or die('Restricted!');

class ModelReportRecurring extends Model {
    public function getRecurringsByGroup($data) {
        $sql = "SELECT MIN(r.date_added) AS date_start, MAX(r.date_added) AS date_end, r.cycle AS cycle, COUNT(*) AS recurrings, SUM((SELECT SUM(ri.quantity) FROM " . DB_PREFIX . "recurring_item ri WHERE ri.recurring_id = r.recurring_id GROUP BY ri.recurring_id)) AS items, SUM((SELECT SUM(rt.value) FROM " . DB_PREFIX . "recurring_total rt WHERE rt.recurring_id = r.recurring_id AND rt.code = 'tax' GROUP BY rt.recurring_id)) AS tax, SUM(r.total) AS `total` FROM " . DB_PREFIX . "recurring r";

        $implode = array();

        if (!is_null($data['filter_status'])) {
            $implode[] = "r.status = '" . (int)$data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added_start'])) {
            $implode[] = "DATE(r.date_added) >= '" . $this->db->escape($data['filter_date_added_start']) . "'";
        }

        if (!empty($data['filter_date_added_end'])) {
            $implode[] = "DATE(r.date_added) <= '" . $this->db->escape($data['filter_date_added_end']) . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        switch ($data['filter_group']) {
            case 'day';
                $sql .= " GROUP BY YEAR(r.date_added), MONTH(r.date_added), DAY(r.date_added), r.cycle";
                break;
            default:
            case 'week':
                $sql .= " GROUP BY YEAR(r.date_added), WEEK(r.date_added), r.cycle";
                break;
            case 'month':
                $sql .= " GROUP BY YEAR(r.date_added), MONTH(r.date_added), r.cycle";
                break;
            case 'year':
                $sql .= " GROUP BY YEAR(r.date_added), r.cycle";
                break;
        }

        $sql .= " ORDER BY r.date_added DESC";

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

    public function getTotalRecurringsByGroup($data) {
        switch ($data['filter_group']) {
            case 'day';
                $sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added), DAY(date_added), cycle) AS total FROM " . DB_PREFIX . "recurring";
                break;
            default:
            case 'week':
                $sql = "SELECT COUNT(DISTINCT YEAR(date_added), WEEK(date_added), cycle) AS total FROM " . DB_PREFIX . "recurring";
                break;
            case 'month':
                $sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added), cycle) AS total FROM " . DB_PREFIX . "recurring";
                break;
            case 'year':
                $sql = "SELECT COUNT(DISTINCT YEAR(date_added), cycle) AS total FROM " . DB_PREFIX . "recurring";
                break;
        }

        if (!is_null($data['filter_status'])) {
            $sql .= " WHERE status = '" . (int)$data['filter_status'] . "'";
        } else {
            $sql .= " WHERE status > '0'";
        }

        if (!empty($data['filter_date_added_start'])) {
            $sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_added_start']) . "'";
        }

        if (!empty($data['filter_date_added_end'])) {
            $sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_added_end']) . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalRecurrings($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recurring";

        $implode = array();

        if (!is_null($data['filter_status'])) {
            $implode[] = " status = '" . (int)$data['filter_status'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}