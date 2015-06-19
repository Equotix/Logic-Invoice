<?php
defined('_PATH') or die('Restricted!');

class ModelContentArticle extends Model {
    public function addArticle($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "article SET top = '" . (int)$data['top'] . "', parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status= '" . (int)$data['status'] . "'");

        $article_id = $this->db->getLastId();

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', title= '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        if ($data['url_alias']) {
            $this->load->model('system/url_alias');

            $this->model_system_url_alias->addUrlAlias('article_id=' . $article_id, $data['url_alias']);
        }

        $this->cache->delete('article');
    }

    public function editArticle($article_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "article SET top = '" . (int)$data['top'] . "', parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', status= '" . (int)$data['status'] . "' WHERE article_id = '" . (int)$article_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");

        foreach ($data['description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', title= '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        if ($data['url_alias']) {
            $this->load->model('system/url_alias');

            $this->model_system_url_alias->addUrlAlias('article_id=' . $article_id, $data['url_alias']);
        }

        $this->cache->delete('article');
    }

    public function deleteArticle($article_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "article WHERE article_id = '" . (int)$article_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");

        $this->cache->delete('article');
    }

    public function getArticle($article_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article WHERE article_id = '" . (int)$article_id . "'");

        $description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article_description WHERE article_id = '" . (int)$article_id . "'");

        $description = array();

        foreach ($description_query->rows as $result) {
            $description[$result['language_id']] = array(
                'title'       => $result['title'],
                'description' => $result['description']
            );
        }

        return array(
            'article_id'  => $query->row['article_id'],
            'top'         => $query->row['top'],
            'parent_id'   => $query->row['parent_id'],
            'sort_order'  => $query->row['sort_order'],
            'status'      => $query->row['status'],
            'description' => $description
        );
    }

    public function getArticles($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON ad.article_id = a.article_id WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sort_data = array(
                'title',
                'top',
                'sort_order',
                'status'
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

            $article_data = $query->rows;
        } else {
            $article_data = $this->cache->get('article.list.' . $this->config->get('config_language_id'));

            if (!$article_data) {
                $article_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "article a LEFT JOIN " . DB_PREFIX . "article_description ad ON ad.article_id = a.article_id WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ad.title");

                $article_data = $query->rows;

                $this->cache->set('article.list.' . $this->config->get('config_language_id'), $article_data);
            }
        }

        return $article_data;
    }

    public function getTotalArticles() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "article");

        return $query->row['total'];
    }
}