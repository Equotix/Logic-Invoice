<?php
defined('_PATH') or die('Restricted!');

class ModelSystemUser extends Model {
    public function addUser($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET user_group_id = '" . (int)$data['user_group_id'] . "', `key` = '" . $this->db->escape($data['key']) . "', secret = '" . $this->db->escape($data['secret']) . "', name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', username = '" . $this->db->escape($data['username']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");
    }

    public function editUser($user_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET user_group_id = '" . (int)$data['user_group_id'] . "', `key` = '" . $this->db->escape($data['key']) . "', secret = '" . $this->db->escape($data['secret']) . "', name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', username = '" . $this->db->escape($data['username']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE user_id = '" . (int)$user_id . "'");

        if ($data['password']) {
            $this->editPassword($user_id, $data['password']);
        }
    }

    public function editPassword($user_id, $password) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
    }

    public function editCode($email, $code, $ip) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        $this->load->model('content/email_template');

        $email_data = array(
            'website_name' => $this->config->get('config_name'),
            'website_url'  => HTTPS_APPLICATION,
            'email'        => $email,
            'reset_link'   => $this->url->link('common/reset', 'code=' . $code, 'SSL'),
            'ip'           => $ip,
            'to_email'     => $email
        );

        $this->model_content_email_template->send($email_data, 'forgotten_password_admin');
    }

    public function deleteUser($user_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
    }

    public function getUser($user_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");

        return $query->row;
    }

    public function getUserByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = array()) {
        $sql = "SELECT *, u.name AS name, ug.name AS user_group FROM `" . DB_PREFIX . "user` u LEFT JOIN " . DB_PREFIX . "user_group ug ON ug.user_group_id = u.user_group_id";

        $sort_data = array(
            'u.name',
            'username',
            'ug.name',
            'status',
            'date_added',
            'date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY username";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) && isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUsers() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");

        return $query->row['total'];
    }

    public function getTotalUsersByUserGroup($user_group_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }
}