<?php
defined('_PATH') or die('Restricted!');

class ModelTotalTaxTax extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/tax/tax');

        foreach ($taxes as $tax) {
            if ($tax['value'] > 0) {
                $total_data[] = array(
                    'code'       => 'tax',
                    'title'      => $tax['name'],
                    'value'      => $tax['value'],
                    'sort_order' => $this->config->get('tax_sort_order')
                );

                $total += $tax['value'];
            }
        }
    }
}