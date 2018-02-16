<?php
class ModelUpgrade130 extends Model {
    // Upgrading to Logic Invoice version 1.3.0 database
    public function upgrade($db) {
        // Add 'inventory_id' column
        $query = $db->query("SHOW COLUMNS FROM " . DB_PREFIX . "invoice_item");

        $exists = false;

        foreach ($query->rows as $result) {
            if ($result['Field'] == 'inventory_id') {
                $exists = true;

                break;
            }
        }

        if (!$exists) {
            $db->query("ALTER TABLE " . DB_PREFIX . "quotation_item ADD inventory_id int(11) NOT NULL");
        }
        
        $query = $db->query("SHOW COLUMNS FROM " . DB_PREFIX . "quotation_item");

        $exists = false;

        foreach ($query->rows as $result) {
            if ($result['Field'] == 'inventory_id') {
                $exists = true;

                break;
            }
        }

        if (!$exists) {
            $db->query("ALTER TABLE " . DB_PREFIX . "quotation_item ADD inventory_id int(11) NOT NULL");
        }

		// Add missing settings config
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'config' AND `key` = 'config_auto_subtract_inventory'");
		
		if (!$query->num_rows) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'config', `key` = 'config_auto_subtract_inventory', `value` = '1', `serialized` = '0'");
		}
		
		// Add new database tables
        $db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quotation` (
		  `quotation_id` int(11) NOT NULL,
		  `recurring_id` int(11) NOT NULL,
		  `customer_id` int(11) NOT NULL,
		  `firstname` varchar(32) NOT NULL,
		  `lastname` varchar(32) NOT NULL,
		  `company` varchar(255) NOT NULL,
		  `website` varchar(255) NOT NULL,
		  `email` varchar(96) NOT NULL,
		  `total` decimal(15,4) NOT NULL,
		  `currency_code` varchar(3) NOT NULL,
		  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
		  `comment` text NOT NULL,
		  `status_id` int(11) NOT NULL DEFAULT '0',
		  `date_due` date NOT NULL,
		  `date_issued` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  PRIMARY KEY (`quotation_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		
		$db->query("CREATE TABLE `" . DB_PREFIX . "quotation_history` (
		  `quotation_history_id` int(11) NOT NULL,
		  `quotation_id` int(11) NOT NULL,
		  `status_id` int(11) NOT NULL,
		  `comment` text NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`quotation_history_id`),
		  KEY `quotation_id` (`quotation_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		
		$db->query("CREATE TABLE `" . DB_PREFIX . "quotation_item` (
		  `quotation_item_id` int(11) NOT NULL,
		  `quotation_id` int(11) NOT NULL,
		  `inventory_id` int(11) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  `description` text NOT NULL,
		  `tax_class_id` int(11) NOT NULL,
		  `quantity` int(11) NOT NULL,
		  `price` decimal(15,4) NOT NULL,
		  `tax` decimal(15,4) NOT NULL,
		  `discount` decimal(15,4) NOT NULL,
		  PRIMARY KEY (`quotation_item_id`),
		  KEY `quotation_id` (`quotation_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
		
		$db->query("CREATE TABLE `" . DB_PREFIX . "quotation_total` (
		  `quotation_total_id` int(11) NOT NULL,
		  `quotation_id` int(11) NOT NULL,
		  `code` varchar(32) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  `value` decimal(15,4) NOT NULL,
		  `sort_order` int(3) NOT NULL,
		  PRIMARY KEY (`quotation_total_id`),
		  KEY `quotation_id` (`quotation_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}
}
