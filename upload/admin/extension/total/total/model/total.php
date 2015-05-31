<?php
defined('_PATH') or die('Restricted!');

class ModelTotalTotalTotal extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/total/total');

        $total_data[] = array(
            'code'       => 'total',
            'title'      => $this->language->get('heading_title'),
            'value'      => max(0, $total),
            'sort_order' => $this->config->get('total_sort_order')
        );
    }
}