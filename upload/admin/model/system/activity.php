<?php
defined('_PATH') or die('Restricted!');

class ModelSystemActivity extends Model {
    public function addActivity($message) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "activity SET message = '" . $this->db->escape($message) . "', date_added = NOW()");
    }

    public function getActivities($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "activity ORDER BY activity_id DESC";

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

    public function deleteActivities() {
        $this->db->query("DELETE FROM " . DB_PREFIX . "activity");
    }

    public function getTotalActivities() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "activity");

        return $query->row['total'];
    }
}