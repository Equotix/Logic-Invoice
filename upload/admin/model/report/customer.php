<?php
defined('_PATH') or die('Restricted!');

class ModelReportCustomer extends Model {
    public function getTotalCustomers($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

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