<?php
defined('_PATH') or die('Restricted!');

class ModelAccountingAccount extends Model {
    public function addAccount($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "account SET account_id = '" . (int)$data['account_id'] . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', type = '" . $this->db->escape($data['type']) . "', parent_id = '" . (int)$data['parent_id'] . "', status = '" . (int)$data['status'] . "'");

        $this->cache->delete('account');
    }

    public function editAccount($account_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "account SET account_id = '" . (int)$data['account_id'] . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', type = '" . $this->db->escape($data['type']) . "', parent_id = '" . (int)$data['parent_id'] . "', status = '" . (int)$data['status'] . "' WHERE account_id = '" . (int)$account_id . "'");

        $this->cache->delete('account');
    }

    public function deleteAccount($account_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "account WHERE account_id = '" . (int)$account_id . "'");

        $this->cache->delete('account');
    }

    public function getAccount($account_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "account WHERE account_id = '" . (int)$account_id . "'");

        return $query->row;
    }

    public function getAccounts($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "account";

            $implode = array();

            if (isset($data['filter_type'])) {
                $implode[] = "type = '" . $this->db->escape($data['filter_type']) . "'";
            }

            if (isset($data['filter_parent_id'])) {
                $implode[] = "parent_id = '" . (int)$data['filter_parent_id'] . "'";
            }

            if ($implode) {
                $sql .= " WHERE " . implode(" AND ", $implode);
            }

            $sort_data = array(
                'account_id',
                'name',
                'description',
                'type',
                'parent_id',
                'status'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                if ($data['sort'] == 'account_id') {
                    $sql .= " ORDER BY RPAD(account_id, 15, '0')";
                } else {
                    $sql .= " ORDER BY " . $data['sort'];
                }
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

            return $query->rows;
        } else {
            $account_data = $this->cache->get('account');

            if (!$account_data) {
                $account_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "account WHERE parent_id = '0' ORDER BY RPAD(account_id, 15, '0')");

                foreach ($query->rows as $result) {
                    $account_data[] = array(
                        'account_id'        => $result['account_id'],
                        'name'              => $result['name'],
                        'description'       => $result['description'],
                        'type'              => $result['type'],
                        'parent_id'         => $result['parent_id'],
                        'status'            => $result['status'],
                        'retained_earnings' => $result['retained_earnings']
                    );
                }

                $this->cache->set('account', $account_data);
            }

            return $account_data;
        }
    }

    public function getTotalAccounts() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "account");

        return $query->row['total'];
    }

    public function getAccountsByType($types) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "account WHERE type IN ('" . implode('\',\'', $types) . "')");

        return $query->rows;
    }
}