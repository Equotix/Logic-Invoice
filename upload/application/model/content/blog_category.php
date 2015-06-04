<?php
defined('_PATH') or die('Restricted!');

class ModelContentBlogCategory extends Model {
    public function getBlogCategory($blog_category_id) {
        $blog_category_data = $this->cache->get('blog_category.' . $blog_category_id . '.' . $this->config->get('config_language_id'));

        if (!$blog_category_data) {
            $blog_category_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category bc LEFT JOIN " . DB_PREFIX . "blog_category_description bcd ON bcd.blog_category_id = bc.blog_category_id WHERE bcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND bc.blog_category_id = '" . (int)$blog_category_id . "' AND status = '1'");

            $blog_category_data = $query->row;

            $this->cache->set('blog_category.' . $blog_category_id . '.' . $this->config->get('config_language_id'), $blog_category_data);
        }

        return $blog_category_data;
    }

    public function getBlogCategories($parent_id = 0) {
        $blog_category_data = $this->cache->get('blog_category.parent.' . $parent_id . '.' . $this->config->get('config_language_id'));

        if (!$blog_category_data) {
            $blog_category_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category bc LEFT JOIN " . DB_PREFIX . "blog_category_description bcd ON bcd.blog_category_id = bc.blog_category_id WHERE bcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND parent_id = '" . (int)$parent_id . "' AND status = '1' ORDER BY bc.sort_order, bcd.name");

            $blog_category_data = $query->rows;

            $this->cache->set('blog_category.parent.' . $parent_id . '.' . $this->config->get('config_language_id'), $blog_category_data);
        }

        return $blog_category_data;
    }
}