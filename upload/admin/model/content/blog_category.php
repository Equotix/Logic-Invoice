<?php
defined('_PATH') or die('Restricted!');

class ModelContentBlogCategory extends Model {
    public function addBlogCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status= '" . (int)$data['status'] . "'");

        $blog_category_id = $this->db->getLastId();

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_description SET blog_category_id = '" . (int)$blog_category_id . "', language_id = '" . (int)$language_id . "', name= '" . $this->db->escape($value['name']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if ($data['url_alias']) {
            $this->load->model('system/url_alias');

            $this->model_system_url_alias->addUrlAlias('blog_category_id=' . $blog_category_id, $data['url_alias']);
        }

        $this->cache->delete('blog_category');
    }

    public function editBlogCategory($blog_category_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status= '" . (int)$data['status'] . "' WHERE blog_category_id = '" . (int)$blog_category_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_description WHERE blog_category_id = '" . (int)$blog_category_id . "'");

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_description SET blog_category_id = '" . (int)$blog_category_id . "', language_id = '" . (int)$language_id . "', name= '" . $this->db->escape($value['name']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if ($data['url_alias']) {
            $this->load->model('system/url_alias');

            $this->model_system_url_alias->addUrlAlias('blog_category_id=' . $blog_category_id, $data['url_alias']);
        }

        $this->cache->delete('blog_category');
    }

    public function deleteBlogCategory($blog_category_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category WHERE blog_category_id = '" . (int)$blog_category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_description WHERE blog_category_id = '" . (int)$blog_category_id . "'");

        $this->cache->delete('blog_category');
    }

    public function getBlogCategory($blog_category_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category WHERE blog_category_id = '" . (int)$blog_category_id . "'");

        $description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_description WHERE blog_category_id = '" . (int)$blog_category_id . "'");

        $description = array();

        foreach ($description_query->rows as $result) {
            $description[$result['language_id']] = array(
                'name'             => $result['name'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword']
            );
        }

        return array(
            'blog_category_id' => $query->row['blog_category_id'],
            'parent_id'        => $query->row['parent_id'],
            'sort_order'       => $query->row['sort_order'],
            'status'           => $query->row['status'],
            'description'      => $description
        );
    }

    public function getBlogCategories($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "blog_category bc LEFT JOIN " . DB_PREFIX . "blog_category_description bcd ON bcd.blog_category_id = bc.blog_category_id WHERE bcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sort_data = array(
                'name',
                'sort_order',
                'status'
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

            $blog_category_data = $query->rows;
        } else {
            $blog_category_data = $this->cache->get('blog_category.all.' . $this->config->get('config_language_id'));

            if (!$blog_category_data) {
                $blog_category_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category bc LEFT JOIN " . DB_PREFIX . "blog_category_description bcd ON bcd.blog_category_id = bc.blog_category_id WHERE bcd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bcd.name");

                $blog_category_data = $query->rows;

                $this->cache->set('blog_category.' . $this->config->get('config_language_id'), $blog_category_data);
            }
        }

        return $blog_category_data;
    }

    public function getTotalBlogCategories() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_category");

        return $query->row['total'];
    }
}