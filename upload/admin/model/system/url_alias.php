<?php
defined('_PATH') or die('Restricted!');

class ModelSystemUrlAlias extends Model {
    public function addUrlAlias($query, $keywords) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($query) . "'");

        foreach ($keywords as $language_id => $keyword) {
            if ($keyword) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET language_id = '" . (int)$language_id . "', query = '" . $this->db->escape($query) . "', keyword = '" . $this->db->escape($keyword) . "'");
            }
        }
    }

    public function getUrlAliasByQuery($query) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($query) . "'");

        $url_alias_data = array();

        foreach ($query->rows as $url_alias) {
            $url_alias_data[$url_alias['language_id']] = $url_alias['keyword'];
        }

        return $url_alias_data;
    }

    public function getUrlAliasByKeyword($language_id, $keyword) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE language_id = '" . (int)$language_id . "' AND LOWER(keyword) = '" . $this->db->escape(utf8_strtolower($keyword)) . "'");

        if ($query->num_rows) {
            return $query->row['query'];
        } else {
            return false;
        }
    }
}