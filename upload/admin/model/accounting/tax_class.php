<?php
defined('_PATH') or die('Restricted!');

class ModelAccountingTaxClass extends Model {
    public function addTaxClass($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "tax_class SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "'");

        $tax_class_id = $this->db->getLastId();

        foreach ($data['tax_rates'] as $tax_rate) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_to_tax_class SET tax_rate_id = '" . (int)$tax_rate['tax_rate_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$tax_rate['priority'] . "'");
        }

        $this->cache->delete('tax_class');
    }

    public function editTaxClass($tax_class_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "tax_class SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "' WHERE tax_class_id = '" . (int)$tax_class_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");

        foreach ($data['tax_rates'] as $tax_rate) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_to_tax_class SET tax_rate_id = '" . (int)$tax_rate['tax_rate_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$tax_rate['priority'] . "'");
        }

        $this->cache->delete('tax_class');
    }

    public function deleteTaxClass($tax_class_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");

        $this->cache->delete('tax_class');
    }

    public function getTaxClass($tax_class_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");

        $tax_rate_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate_to_tax_class tr2tc LEFT JOIN " . DB_PREFIX . "tax_rate tr ON tr.tax_rate_id = tr2tc.tax_rate_id WHERE tax_class_id = '" . (int)$tax_class_id . "'");

        if ($query->num_rows) {
            return array(
                'tax_class_id' => $query->row['tax_class_id'],
                'name'         => $query->row['name'],
                'description'  => $query->row['description'],
                'tax_rates'    => $tax_rate_query->rows
            );
        } else {
            return false;
        }
    }

    public function getTaxClasses($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "tax_class";

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

            $tax_class_data = $query->rows;
        } else {
            $tax_class_data = $this->cache->get('tax_class');

            if (!$tax_class_data) {
                $tax_class_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class ORDER BY name");

                $tax_class_data = $query->rows;

                $this->cache->set('tax_class', $tax_class_data);
            }
        }

        return $tax_class_data;
    }

    public function getTotalTaxClasses() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_class");

        return $query->row['total'];
    }
}