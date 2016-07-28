<?php
defined('_PATH') or die('Restricted!');

class ModelContentBlogPost extends Model {
    public function getBlogPost($blog_post_id) {
        $blog_post_data = $this->cache->get('blog_post.' . $blog_post_id . '.' . $this->config->get('config_language_id'));

        if (!$blog_post_data) {
            $query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "user u WHERE u.user_id = bp.user_id) AS user FROM " . DB_PREFIX . "blog_post bp LEFT JOIN " . DB_PREFIX . "blog_post_description bpd ON bpd.blog_post_id = bp.blog_post_id WHERE bpd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND bp.blog_post_id = '" . (int)$blog_post_id . "' AND status = '1'");

            $blog_post_data = $query->row;

            $this->cache->set('blog_post.' . $blog_post_id . '.' . $this->config->get('config_language_id'), $blog_post_data);
        }

        return $blog_post_data;
    }

    public function getBlogPosts($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "blog_post bp";

        if (!empty($data['blog_category_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_to_blog_category bp2bc ON bp2bc.blog_post_id = bp.blog_post_id WHERE bp2bc.blog_category_id = '" . (int)$data['blog_category_id'] . "'";
        }

        $sort_data = array(
            'view',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY sort_order DESC, " . $data['sort'];
        } else {
            $sql .= " ORDER BY sort_order DESC, date_added";
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

        $blog_post_data = array();

        foreach ($query->rows as $result) {
            $blog_post_data[] = $this->getBlogPost($result['blog_post_id']);
        }

        return $blog_post_data;
    }

    public function getTotalBlogPosts($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_post bp";

        if (!empty($data['blog_category_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "blog_post_to_blog_category bp2bc ON bp2bc.blog_post_id = bp.blog_post_id WHERE bp2bc.blog_category_id = '" . (int)$blog_category_id . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function increaseView($blog_post_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET view = view + 1 WHERE blog_post_id = '" . (int)$blog_post_id . "'");
    }
}