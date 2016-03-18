<?php
class ModelUpgrade110 extends Model {
    public function upgrade($db) {
        // Update serialized settings data to json
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE serialized = '1'");
		
		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['value'])) {
				$value = unserialize($result['value']);
				
				$db->query("UPDATE " . DB_PREFIX . "setting SET value = '" . $db->escape(json_encode($value)) . "' WHERE setting_id = '" . (int)$result['setting_id'] . "'");
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
    }
}
