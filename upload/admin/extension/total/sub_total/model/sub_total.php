<?php
defined('_PATH') or die('Restricted!');

class ModelTotalSubTotalSubTotal extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/sub_total/sub_total');

        $total_data[] = array(
            'code'       => 'sub_total',
            'title'      => $this->language->get('heading_title'),
            'value'      => $total,
            'sort_order' => $this->config->get('sub_total_sort_order')
        );
    }
}