<?php
defined('_PATH') or die('Restricted!');

class ControllerApiInventory extends Controller {
    public function post() {
        $this->load->language('api/inventory');

        $json = array();

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('accounting/inventory');
            $this->load->model('system/activity');

            if (isset($this->request->post['sku']) && isset($this->request->post['name'])) {
                if ((utf8_strlen($this->request->post['sku']) < 1) || (utf8_strlen($this->request->post['sku']) > 255)) {
                    $json['error'] = $this->language->get('error_sku');
                }

                if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
                    $json['error'] = $this->language->get('error_name');
                }

                if (empty($json['error'])) {
                    $data = array(
                        'image'       => '',
                        'sku'         => $this->request->post['sku'],
                        'name'        => $this->request->post['name'],
                        'description' => isset($this->request->post['description']) ? $this->request->post['description'] : '',
                        'quantity'    => isset($this->request->post['quantity']) ? $this->request->post['quantity'] : '',
                        'cost'        => isset($this->request->post['cost']) ? $this->request->post['cost'] : '',
                        'sell'        => isset($this->request->post['sell']) ? $this->request->post['sell'] : '',
                        'status'      => isset($this->request->post['status']) ? $this->request->post['status'] : ''
                    );

                    $inventory_info = $this->model_accounting_inventory->getInventoryBySKU($this->request->post['sku']);;

                    if ($inventory_info) {
                        $inventory_id = $inventory_info['inventory_id'];

                        $this->model_accounting_inventory->editInventory($inventory_id, $data);

                        $this->model_system_activity->addActivity(sprintf($this->language->get('text_edited'), $this->request->post['name'], $inventory_id, $this->session->data['username']));
                    } else {
                        $inventory_id = $this->model_accounting_inventory->addInventory($data);

                        $this->model_system_activity->addActivity(sprintf($this->language->get('text_added'), $this->request->post['name'], $inventory_id, $this->session->data['username']));
                    }

                    $json = $this->model_accounting_inventory->getInventory($inventory_id);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete() {
        $this->load->language('api/inventory');

        $json = array();

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('accounting/inventory');
            $this->load->model('system/activity');

            if (isset($this->request->post['inventory_id'])) {
                $this->model_accounting_inventory->deleteInventory($this->request->post['inventory_id']);

                $this->model_system_activity->addActivity(sprintf($this->language->get('text_deleted'), $this->request->post['inventory_id'], $this->session->data['username']));

                $json['success'] = sprintf($this->language->get('text_deleted'), $this->request->post['inventory_id'], $this->session->data['username']);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function inventory() {
        $json = array();

        if (isset($this->request->post['api_key']) && isset($this->session->data['api_key']) && $this->request->post['api_key'] == $this->session->data['api_key']) {
            $this->load->model('accounting/inventory');
            $this->load->model('system/activity');

            if (isset($this->request->post['sku'])) {
                $filter_sku = $this->request->post['sku'];
            } else {
                $filter_sku = '';
            }

            if (isset($this->request->post['name'])) {
                $filter_name = $this->request->post['name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->post['page'])) {
                $page = $this->request->post['page'];
            } else {
                $page = 1;
            }

            $filter_data = array(
                'filter_sku'  => $filter_sku,
                'filter_name' => $filter_name,
                'sort'        => 'name',
                'start'       => 30 * ($page - 1),
                'limit'       => 30
            );

            $inventories = $this->model_accounting_inventory->getInventories($filter_data);

            foreach ($inventories as $inventory) {
                $json[] = $inventory;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}