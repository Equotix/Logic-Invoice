<?php
defined('_PATH') or die('Restricted!');

class ModelSystemActivity extends Model {
    public function addActivity($message) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "activity SET message = '" . $this->db->escape($message) . "', date_added = NOW()");
    }
}