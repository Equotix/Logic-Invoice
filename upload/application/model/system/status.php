<?php
defined('_PATH') or die('Restricted!');

class ModelSystemStatus extends Model {
    public function getStatus($status_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "status WHERE status_id = '" . (int)$status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getStatusByName($name) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "status WHERE LOWER(name) = '" . $this->db->escape(utf8_strtolower($name)) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }
}