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
            $db->query("ALTER TABLE " . DB_PREFIX . "invoice_item ADD inventory_id int(11) NOT NULL");
        }

		// Add missing settings config
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'config' AND `key` = 'config_auto_subtract_inventory'");
		
		if (!$query->num_rows) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'config', `key` = 'config_auto_subtract_inventory', `value` = '1', `serialized` = '0'");
		}
	}
}
