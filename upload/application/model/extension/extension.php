<?php
defined('_PATH') or die('Restricted!');

class ModelExtensionExtension extends Model {
    public function getInstalled($type) {
        $extension_data = $this->cache->get('extension.' . $type);

        if (!$extension_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

            $extension_data = array();

            foreach ($query->rows as $result) {
                $extension_data[] = $result['code'];
            }

            $this->cache->set('extension', $extension_data);
        }

        return $extension_data;
    }
}