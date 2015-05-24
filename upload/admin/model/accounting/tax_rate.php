<?php
defined('_PATH') or die('Restricted!');

class ModelAccountingTaxRate extends Model {
    public function addTaxRate($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate SET name = '" . $this->db->escape($data['name']) . "', rate = '" . (float)$data['rate'] . "', type = '" . $this->db->escape($data['type']) . "'");

        $this->cache->delete('tax_rate');
    }

    public function editTaxRate($tax_rate_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "tax_rate SET name = '" . $this->db->escape($data['name']) . "', rate = '" . (float)$data['rate'] . "', type = '" . $this->db->escape($data['type']) . "' WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        $this->cache->delete('tax_rate');
    }

    public function deleteTaxRate($tax_rate_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        $this->cache->delete('tax_rate');
    }

    public function getTaxRate($tax_rate_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

        return $query->row;
    }

    public function getTaxRates($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "tax_rate";

            $sort_data = array(
                'name'
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

            $tax_rate_data = $query->rows;
        } else {
            $tax_rate_data = $this->cache->get('tax_rate');

            if (!$tax_rate_data) {
                $tax_rate_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate ORDER BY name");

                $tax_rate_data = $query->rows;

                $this->cache->set('tax_rate', $tax_rate_data);
            }
        }

        return $tax_rate_data;
    }

    public function getTotalTaxRates() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_rate");

        return $query->row['total'];
    }
}