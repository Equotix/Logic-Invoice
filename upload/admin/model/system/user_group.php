<?php
defined('_PATH') or die('Restricted!');

class ModelSystemUserGroup extends Model {
    public function addUserGroup($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . $this->db->escape(isset($data['permission']) ? json_encode($data['permission']) : '') . "'");

        $this->cache->delete('user_group');
    }

    public function editUserGroup($user_group_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . $this->db->escape(isset($data['permission']) ? json_encode($data['permission']) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");

        $this->cache->delete('user_group');
    }

    public function deleteUserGroup($user_group_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        $this->cache->delete('user_group');
    }

    public function getUserGroup($user_group_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        $user_group = array();

        if ($query->num_rows) {
            $user_group = array(
                'name'       => $query->row['name'],
                'permission' => json_decode($query->row['permission'], true)
            );
        }

        return $user_group;
    }

    public function getUserGroups($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "user_group";

            $sort_data = array(
                'name'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY name";
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

            $user_group_data = $query->rows;
        } else {
            $user_group_data = $this->cache->get('user_group');

            if (!$user_group_data) {
                $user_group_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_group ORDER BY name");

                foreach ($query->rows as $result) {
                    $user_group_data[] = array(
                        'user_group_id' => $result['user_group_id'],
                        'name'          => $result['name'],
                        'permission'    => json_decode($result['permission'], true)
                    );
                }

                $this->cache->set('user_group', $user_group_data);
            }
        }

        return $user_group_data;
    }

    public function getTotalUserGroups() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

        return $query->row['total'];
    }

    public function addPermission($user_id, $type, $page) {
        $user_query = $this->db->query("SELECT DISTINCT user_group_id FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");

        if ($user_query->num_rows) {
            $user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

            if ($user_group_query->num_rows) {
                $data = json_decode($user_group_query->row['permission'], true);

                $data[$type][] = $page;

                $this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . json_encode($data) . "' WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
            }
        }
    }

    public function removePermission($user_id, $type, $page) {
        $user_query = $this->db->query("SELECT DISTINCT user_group_id FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");

        if ($user_query->num_rows) {
            $user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

            if ($user_group_query->num_rows) {
                $data = json_decode($user_group_query->row['permission'], true);

                $data[$type] = array_diff($data[$type], array($page));

                $this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . json_encode($data) . "' WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
            }
        }
    }
}