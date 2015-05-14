<?php
class ModelContentArticle extends Model {
    public function getArticle($article_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON ad.article_id = a.article_id WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.article_id = '" . (int)$article_id . "' AND status = '1'");

        return $query->row;
    }

    public function getArticles($parent_id = 0) {
        $article_data = $this->cache->get('article.' . $parent_id . '.' . $this->config->get('config_language_id'));

        if (!$article_data) {
            $article_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON ad.article_id = a.article_id WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND parent_id = '" . (int)$parent_id . "' AND status = '1' ORDER BY a.sort_order, ad.title");

            $article_data = $query->rows;

            $this->cache->set('article.' . $this->config->get('config_language_id'), $article_data);
        }

        return $article_data;
    }
}