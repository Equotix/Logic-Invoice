<?php
defined('_PATH') or die('Restricted!');

class ModelSystemLanguage extends Model {
    public function addLanguage($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");

        $this->cache->delete('language');

        $language_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "status SET language_id = '" . (int)$language_id . "'");

        $this->cache->delete('status');
    }

    public function editLanguage($language_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('language');
    }

    public function deleteLanguage($language_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('language');

        $this->db->query("DELETE FROM " . DB_PREFIX . "status WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('status');
    }

    public function getLanguage($language_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        return $query->row;
    }

    public function getLanguages($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "language";

            $sort_data = array(
                'name',
                'code',
                'sort_order',
                'status'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY sort_order, name";
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
            $language_data = $this->cache->get('language');

            if (!$language_data) {
                $language_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

                foreach ($query->rows as $result) {
                    $language_data[$result['code']] = array(
                        'language_id' => $result['language_id'],
                        'name'        => $result['name'],
                        'code'        => $result['code'],
                        'locale'      => $result['locale'],
                        'image'       => $result['image'],
                        'directory'   => $result['directory'],
                        'sort_order'  => $result['sort_order'],
                        'status'      => $result['status']
                    );
                }

                $this->cache->set('language', $language_data);
            }

            return $language_data;
        }
    }

    public function getTotalLanguages() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language");

        return $query->row['total'];
    }
}