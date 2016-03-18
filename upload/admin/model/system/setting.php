<?php
defined('_PATH') or die('Restricted!');

class ModelSystemSetting extends Model {
    public function editSetting($group, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");

        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
            }
        }
    }

    public function deleteSetting($group) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");
    }

    public function getSetting($group) {
        $data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $data[$result['key']] = $result['value'];
            } else {
                $data[$result['key']] = json_decode($result['value'], true);
            }
        }

        return $data;
    }

    public function addSettingValue($group = '', $key = '', $value = '') {
        if (!is_array($value)) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
        }
    }

    public function editSettingValue($group = '', $key = '', $value = '') {
        if (!is_array($value)) {
            $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND serialized = '1'");
        }
    }

    public function getSettingValue($group = '', $key = '') {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "'");

        if ($query->num_rows) {
            if ($query->row['serialized']) {
                return json_decode($query->row['value'], true);
            } else {
                return $query->row['value'];
            }
        } else {
            return false;
        }
    }
}