<?php
defined('_PATH') or die('Restricted!');

class ModelAccountingInventory extends Model {
    public function addInventory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "inventory SET sku = '" . $this->db->escape($data['sku']) . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', image = '" . $this->db->escape($data['image']) . "', quantity = '" . (int)$data['quantity'] . "', cost = '" . (float)$data['cost'] . "', sell = '" . (float)$data['sell'] . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");

        return $this->db->getLastId();
    }

    public function editInventory($inventory_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "inventory SET sku = '" . $this->db->escape($data['sku']) . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', image = '" . $this->db->escape($data['image']) . "', quantity = '" . (int)$data['quantity'] . "', cost = '" . (float)$data['cost'] . "', sell = '" . (float)$data['sell'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE inventory_id = '" . (int)$inventory_id . "'");
    }

    public function deleteInventory($inventory_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "inventory WHERE inventory_id = '" . (int)$inventory_id . "'");
    }

    public function getInventory($inventory_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "inventory WHERE inventory_id = '" . (int)$inventory_id . "'");

        return $query->row;
    }

    public function getInventoryBySKU($sku) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "inventory WHERE LOWER(sku) = '" . $this->db->escape(utf8_strtolower($sku)) . "'");

        return $query->row;
    }

    public function getInventories($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "inventory";

        $implode = array();

        if (!empty($data['filter_sku'])) {
            $implode[] = "LOWER(sku) = '" . $this->db->escape(utf8_strtolower($data['filter_sku'])) . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "LOWER(name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $implode[] = "quantity = '" . (int)$data['filter_quantity'] . "'";
        }

        if (isset($data['filter_cost']) && !is_null($data['filter_cost'])) {
            $implode[] = "cost = '" . (float)$data['filter_cost'] . "'";
        }

        if (isset($data['filter_sell']) && !is_null($data['filter_sell'])) {
            $implode[] = "sell = '" . (float)$data['filter_sell'] . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int)$data['filter_status'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'sku',
            'name',
            'quantity',
            'cost',
            'sell',
            'status',
            'date_added',
            'date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY sku";
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
}