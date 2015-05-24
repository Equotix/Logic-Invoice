<?php
defined('_PATH') or die('Restricted!');

class ModelSystemStatus extends Model {
    public function addStatus($data) {
        foreach ($data['name'] as $language_id => $name) {
            if (isset($status_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "status SET status_id = '" . (int)$status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($name) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($name) . "'");

                $status_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('status');
    }

    public function editStatus($status_id, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "status WHERE status_id = '" . (int)$status_id . "'");

        foreach ($data['name'] as $language_id => $name) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "status SET status_id = '" . (int)$status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($name) . "'");
        }

        $this->cache->delete('status');
    }

    public function deleteStatus($status_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "status WHERE status_id = '" . (int)$status_id . "'");

        $this->cache->delete('status');
    }

    public function getStatus($status_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "status WHERE status_id = '" . (int)$status_id . "'");

        $name = array();

        foreach ($query->rows as $result) {
            $name[$result['language_id']] = $result['name'];
        }

        return array(
            'status_id' => $result['status_id'],
            'name'      => $name
        );
    }

    public function getStatuses($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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

            $status_data = $query->rows;
        } else {
            $status_data = $this->cache->get('status.' . $this->config->get('config_language_id'));

            if (!$status_data) {
                $status_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

                $status_data = $query->rows;

                $this->cache->set('status.' . $this->config->get('config_language_id'), $status_data);
            }
        }

        return $status_data;
    }

    public function getTotalStatuses() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }
}