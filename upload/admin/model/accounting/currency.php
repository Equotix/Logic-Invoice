<?php
defined('_PATH') or die('Restricted!');

class ModelAccountingCurrency extends Model {
    public function addCurrency($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . (int)$data['decimal_place'] . "', value = '" . (float)$data['value'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW()");

        $this->cache->delete('currency');
    }

    public function editCurrency($currency_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . (int)$data['decimal_place'] . "', value = '" . (float)$data['value'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }

    public function deleteCurrency($currency_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }

    public function getCurrency($currency_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        return $query->row;
    }

    public function getCurrencyByCode($code) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function getCurrencies($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "currency";

            $sort_data = array(
                'title',
                'code',
                'value',
                'status',
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
        } else {
            $currency_data = $this->cache->get('currency');

            if (!$currency_data) {
                $currency_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title");

                foreach ($query->rows as $result) {
                    $currency_data[$result['code']] = array(
                        'currency_id'   => $result['currency_id'],
                        'title'         => $result['title'],
                        'code'          => $result['code'],
                        'symbol_left'   => $result['symbol_left'],
                        'symbol_right'  => $result['symbol_right'],
                        'decimal_place' => $result['decimal_place'],
                        'value'         => $result['value'],
                        'status'        => $result['status'],
                        'date_modified' => $result['date_modified']
                    );
                }

                $this->cache->set('currency', $currency_data);
            }

            return $currency_data;
        }
    }

    public function getTotalCurrencies() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");

        return $query->row['total'];
    }

    public function updateCurrencies($force = false) {
        $data = array();

        if ($force) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "'");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified < DATE_SUB(NOW(), INTERVAL 1 DAY)");
        }

        foreach ($query->rows as $result) {
            $data[] = $this->config->get('config_currency') . $result['code'] . '=X';
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://api.fixer.io/latest?base='.$this->config->get('config_currency'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        if(curl_errno($curl) == 0){
            $json = json_decode($result);
            foreach($json->rates as $currency => $value){
                if ((float)$value) {
                    $this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$value . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($currency) . "'");
                }
            }
        }
        else{
            error_log('Unable to update currency => '.curl_error($curl));
        }
        
        curl_close($curl);

        $this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '1.00000', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");

        $this->cache->delete('currency');
    }
}
