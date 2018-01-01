<?php
class ModelUpgrade110 extends Model {
    // Upgrading to Logic Invoice version 1.1.0 database
    public function upgrade($db) {
        // Update serialized settings data to json
        $query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE serialized = '1'");

        foreach ($query->rows as $result) {
            if (preg_match('/^(a:)/', $result['value'])) {
                $value = unserialize($result['value']);

                $db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $db->escape(json_encode($value)) . "' WHERE setting_id = '" . (int)$result['setting_id'] . "'");
            }
        }

        // Update serialized user permission to json
        $query = $db->query("SELECT * FROM " . DB_PREFIX . "user_group");

        foreach ($query->rows as $result) {
            if (preg_match('/^(a:)/', $result['permission'])) {
                $permission = unserialize($result['permission']);

                $db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $db->escape(json_encode($permission)) . "' WHERE user_group_id = '" . (int)$result['user_group_id'] . "'");
            }
        }

        // Add new database tables
        $db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "inventory` (
		  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
		  `sku` varchar(255) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `description` text NOT NULL,
		  `image` varchar(255) NOT NULL,
		  `quantity` int(11) NOT NULL,
		  `cost` decimal(15,4) NOT NULL,
		  `sell` decimal(15,4) NOT NULL,
		  `status` tinyint(1) NOT NULL,
		  `date_added` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  PRIMARY KEY (`inventory_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    }
}
