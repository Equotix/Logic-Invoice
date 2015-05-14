<?php
class ModelSystemUrlAlias extends Model {
    public function addUrlAlias($query, $keyword) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($query) . "'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($query) . "', keyword = '" . $this->db->escape($keyword) . "'");
    }

    public function getUrlAliasByQuery($query) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($query) . "'");

        if ($query->num_rows) {
            return $query->row['keyword'];
        } else {
            return false;
        }
    }

    public function getUrlAliasByKeyword($keyword) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "'");

        if ($query->num_rows) {
            return $query->row['query'];
        } else {
            return false;
        }
    }
}