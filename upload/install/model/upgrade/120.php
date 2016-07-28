<?php
class ModelUpgrade120 extends Model {
    // Upgrading to Logic Invoice version 1.2.0 database
    public function upgrade($db) {
        // Drop 'directory' column
        $query = $db->query("SHOW COLUMNS FROM " . DB_PREFIX . "language");

        $exists = false;

        foreach ($query->rows as $result) {
            if ($result['Field'] == 'directory') {
                $exists = true;

                break;
            }
        }

        if ($exists) {
            $db->query("ALTER TABLE " . DB_PREFIX . "language DROP directory");
        }

        // Update language code
        $db->query("UPDATE " . DB_PREFIX . "language SET code = 'en-gb' WHERE code = 'en'");

        $db->query("UPDATE " . DB_PREFIX . "setting SET `value` = 'en-gb' WHERE `value` = 'en' AND `key` = 'config_language'");
    }
}
