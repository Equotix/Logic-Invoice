<?php
defined('_PATH') or die('Restricted!');

class ModelContentBlogPost extends Model {
    public function addBlogPost($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post SET user_id = '" . (int)$this->user->getId() . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");

        $blog_post_id = $this->db->getLastId();

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_description SET blog_post_id = '" . (int)$blog_post_id . "', language_id = '" . (int)$language_id . "', image = '" . $this->db->escape($value['image']) . "', title= '" . $this->db->escape($value['title']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', short_description = '" . $this->db->escape($value['short_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
        }

        if (isset($data['blog_category'])) {
            foreach ($data['blog_category'] as $blog_category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_blog_category SET blog_post_id = '" . (int)$blog_post_id . "', blog_category_id = '" . (int)$blog_category_id . "'");
            }
        }

        if ($data['url_alias']) {
            $this->load->model('system/url_alias');

            $this->model_system_url_alias->addUrlAlias('blog_post_id=' . $blog_post_id, $data['url_alias']);
        }

        $this->cache->delete('blog_post');
    }

    public function editBlogPost($blog_post_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE blog_post_id = '" . (int)$blog_post_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_description WHERE blog_post_id = '" . (int)$blog_post_id . "'");

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_description SET blog_post_id = '" . (int)$blog_post_id . "', language_id = '" . (int)$language_id . "', image = '" . $this->db->escape($value['image']) . "', title= '" . $this->db->escape($value['title']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', short_description = '" . $this->db->escape($value['short_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_blog_category WHERE blog_post_id = '" . (int)$blog_post_id . "'");

        if (isset($data['blog_category'])) {
            foreach ($data['blog_category'] as $blog_category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "blog_post_to_blog_category SET blog_post_id = '" . (int)$blog_post_id . "', blog_category_id = '" . (int)$blog_category_id . "'");
            }
        }

        if ($data['url_alias']) {
            $this->load->model('system/url_alias');

            $this->model_system_url_alias->addUrlAlias('blog_post_id=' . $blog_post_id, $data['url_alias']);
        }

        $this->cache->delete('blog_post');
    }

    public function deleteBlogPost($blog_post_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post WHERE blog_post_id = '" . (int)$blog_post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_description WHERE blog_post_id = '" . (int)$blog_post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog_post_to_blog_category WHERE blog_post_id = '" . (int)$blog_post_id . "'");

        $this->cache->delete('blog_post');
    }

    public function getBlogPost($blog_post_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post WHERE blog_post_id = '" . (int)$blog_post_id . "'");

        $description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_description WHERE blog_post_id = '" . (int)$blog_post_id . "'");

        $description = array();

        foreach ($description_query->rows as $result) {
            $description[$result['language_id']] = array(
                'image'             => $result['image'],
                'title'             => $result['title'],
                'meta_title'        => $result['meta_title'],
                'meta_description'  => $result['meta_description'],
                'meta_keyword'      => $result['meta_keyword'],
                'short_description' => $result['short_description'],
                'description'       => $result['description'],
                'tag'               => $result['tag']
            );
        }

        $blog_category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_post_to_blog_category WHERE blog_post_id = '" . (int)$blog_post_id . "'");

        $blog_category_data = array();

        foreach ($blog_category_query->rows as $result) {
            $blog_category_data[] = $result['blog_category_id'];
        }

        return array(
            'blog_post_id'  => $query->row['blog_post_id'],
            'view'          => $query->row['view'],
            'sort_order'    => $query->row['sort_order'],
            'status'        => $query->row['status'],
            'date_added'    => $query->row['date_added'],
            'date_modified' => $query->row['date_modified'],
            'description'   => $description,
            'blog_category' => $blog_category_data
        );
    }

    public function getBlogPosts($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "blog_post bp LEFT JOIN " . DB_PREFIX . "blog_post_description bpd ON bpd.blog_post_id = bp.blog_post_id WHERE bpd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'title',
            'view',
            'sort_order',
            'status',
            'date_added',
            'date_modified'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY title";
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

    public function getTotalBlogPosts() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_post");

        return $query->row['total'];
    }
}