<?php
defined('_PATH') or die('Restricted!');

class ModelContentArticle extends Model {
    public function getArticle($article_id) {
        $article_data = $this->cache->get('article.' . $article_id . '.' . $this->config->get('config_language_id'));

        if (!$article_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON ad.article_id = a.article_id WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.article_id = '" . (int)$article_id . "' AND a.status = '1'");

            $article_data = $query->row;

            $this->cache->set('article.' . $article_id . '.' . $this->config->get('config_language_id'), $article_data);
        }

        return $article_data;
    }

    public function getArticles($parent_id = 0) {
        $article_data = $this->cache->get('article.parent.' . $parent_id . '.' . $this->config->get('config_language_id'));

        if (!$article_data) {
            $article_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON ad.article_id = a.article_id WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.parent_id = '" . (int)$parent_id . "' AND a.status = '1' ORDER BY a.sort_order, ad.title");

            $article_data = $query->rows;

            $this->cache->set('article.parent.' . $parent_id . '.' . $this->config->get('config_language_id'), $article_data);
        }

        return $article_data;
    }
}