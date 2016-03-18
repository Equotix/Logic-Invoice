<?php
class ModelInstallInstall extends Model {
    public function database($data) {
        $db = new DB($data['database'], $data['database_hostname'], $data['database_username'], $data['database_password'], $data['database_name']);

        $file = DIR_APPLICATION . 'logic_invoice.sql';

        if (!file_exists($file)) {
            exit('Could not load sql file: ' . $file);
        }

        $lines = file($file);

        if ($lines) {
            $sql = '';

            foreach ($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql .= $line;

                    if (preg_match('/;\s*$/', $line)) {
                        $sql = str_replace("DROP TABLE IF EXISTS `li_", "DROP TABLE IF EXISTS `" . $data['database_prefix'], $sql);
                        $sql = str_replace("CREATE TABLE IF NOT EXISTS `li_", "CREATE TABLE IF NOT EXISTS `" . $data['database_prefix'], $sql);
                        $sql = str_replace("INSERT INTO `li_", "INSERT INTO `" . $data['database_prefix'], $sql);

                        $db->query($sql);

                        $sql = '';
                    }
                }
            }

            $db->query("SET CHARACTER SET utf8");

            $db->query("SET @@session.sql_mode = 'MYSQL40'");

            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            // Main User
            $api_key = '';
            $api_secret = '';

            for ($i = 0; $i < 256; $i++) {
                $api_key .= $characters[rand(0, strlen($characters) - 1)];
                $api_secret .= $characters[rand(0, strlen($characters) - 1)];
            }

            $db->query("INSERT INTO `" . $data['database_prefix'] . "user` SET user_id = '1', user_group_id = '1', `key` = '" . $db->escape($api_key) . "', secret = '" . $db->escape($api_secret) . "', name = 'Logic Invoice', email= '" . $db->escape($data['admin_email']) . "', username = '" . $db->escape($data['admin_username']) . "', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($data['admin_password'])))) . "', status = '1', date_added = NOW(), date_modified = NOW()");

            // Cron User
            $api_key = '';
            $api_secret = '';

            for ($i = 0; $i < 256; $i++) {
                $api_key .= $characters[rand(0, strlen($characters) - 1)];
                $api_secret .= $characters[rand(0, strlen($characters) - 1)];
            }

            $db->query("INSERT INTO `" . $data['database_prefix'] . "user` SET user_id = '2', user_group_id = '2', `key` = '" . $db->escape($api_key) . "', secret = '" . $db->escape($api_secret) . "', name = 'System User', email= '" . $db->escape($data['admin_email']) . "', username = 'Cron', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1(substr(md5(uniqid(rand(), true)), 0, 15))))) . "', status = '1', date_added = NOW(), date_modified = NOW()");

            // System Email
            $db->query("DELETE FROM `" . $data['database_prefix'] . "setting` WHERE `key` = 'config_email'");
            $db->query("INSERT INTO `" . $data['database_prefix'] . "setting` SET `group` = 'config', `key` = 'config_email', value = '" . $db->escape($data['admin_email']) . "'");
        }
    }
}
