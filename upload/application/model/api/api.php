<?php
defined('_PATH') or die('Restricted!');

class ModelApiApi extends Model {
	public function getApiUserByKey($key) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `key` = '" . $this->db->escape($key) . "' AND status = '1'");

		if ($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
	}
}