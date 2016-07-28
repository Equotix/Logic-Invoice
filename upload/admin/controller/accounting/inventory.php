<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountingInventory extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('accounting/inventory');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'filter_sku',
            'filter_name',
            'filter_quantity',
            'filter_cost',
            'filter_sell',
            'filter_status',
            'sort',
            'order',
            'page'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['filter_sku'])) {
            $filter_sku = $this->request->get['filter_sku'];
        } else {
            $filter_sku = '';
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = null;
        }

        if (isset($this->request->get['filter_cost'])) {
            $filter_cost = $this->request->get['filter_cost'];
        } else {
            $filter_cost = null;
        }

        if (isset($this->request->get['filter_sell'])) {
            $filter_sell = $this->request->get['filter_sell'];
        } else {
            $filter_sell = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'sku';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        $filter_data = array(
            'filter_sku'      => $filter_sku,
            'filter_name'     => $filter_name,
            'filter_quantity' => $filter_quantity,
            'filter_cost'     => $filter_cost,
            'filter_sell'     => $filter_sell,
            'filter_status'   => $filter_status,
            'start'           => $this->config->get('config_limit_admin') * ($page - 1),
            'limit'           => $this->config->get('config_limit_admin'),
            'sort'            => $sort,
            'order'           => $order
        );

        $this->load->model('accounting/inventory');
        $this->load->model('tool/image');

        $this->data['inventories'] = array();

        $inventories = $this->model_accounting_inventory->getInventories($filter_data);

        foreach ($inventories as $inventory) {
            $this->data['inventories'][] = array(
                'inventory_id'  => $inventory['inventory_id'],
                'image'         => $this->model_tool_image->resize($inventory['image'], 30, 30),
                'sku'           => $inventory['sku'],
                'name'          => $inventory['name'],
                'quantity'      => $inventory['quantity'],
                'cost'          => $this->currency->format($inventory['cost'], $this->config->get('config_currency')),
                'sell'          => $this->currency->format($inventory['sell'], $this->config->get('config_currency')),
                'cost_raw'      => $inventory['cost'],
                'sell_raw'      => $inventory['sell'],
                'status'        => $inventory['status'],
                'date_added'    => date($this->language->get('datetime_format_short'), strtotime($inventory['date_added'])),
                'date_modified' => date($this->language->get('datetime_format_short'), strtotime($inventory['date_modified'])),
                'edit'          => $this->url->link('accounting/inventory/form', 'token=' . $this->session->data['token'] . $url . '&inventory_id=' . $inventory['inventory_id'], true)
            );
        }

        $url = $this->build->url(array(
            'filter_sku',
            'filter_name',
            'filter_quantity',
            'filter_cost',
            'filter_sell',
            'filter_status',
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_accounting_inventory->getTotalInventories($filter_data);
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('accounting/inventory/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('accounting/inventory/form', 'token=' . $this->session->data['token'], true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

        $url = $this->build->url(array(
            'filter_sku',
            'filter_name',
            'filter_quantity',
            'filter_cost',
            'filter_sell',
            'filter_status',
        ));

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_sku'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=sku&order=' . $order, true);
        $this->data['sort_name'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=name&order=' . $order, true);
        $this->data['sort_quantity'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=quantity&order=' . $order, true);
        $this->data['sort_cost'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=cost&order=' . $order, true);
        $this->data['sort_sell'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=sell&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=status&order=' . $order, true);
        $this->data['sort_date_added'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=date_added&order=' . $order, true);
        $this->data['sort_date_modified'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url . '&sort=date_modified&order=' . $order, true);

        $this->data['filter_sku'] = $filter_sku;
        $this->data['filter_name'] = $filter_name;
        $this->data['filter_quantity'] = $filter_quantity;
        $this->data['filter_cost'] = $filter_cost;
        $this->data['filter_sell'] = $filter_sell;
        $this->data['filter_status'] = $filter_status;

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/inventory_list'));
    }

    public function delete() {
        $this->load->language('accounting/inventory');

        $this->load->model('accounting/inventory');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $inventory_id) {
                $this->model_accounting_inventory->deleteInventory($inventory_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/inventory', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('accounting/inventory');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'filter_sku',
            'filter_name',
            'filter_quantity',
            'filter_cost',
            'filter_sell',
            'filter_status',
            'sort',
            'order',
            'page',
            'inventory_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('accounting/inventory');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['inventory_id'])) {
                $this->model_accounting_inventory->editInventory((int)$this->request->get['inventory_id'], $this->request->post);
            } else {
                $this->model_accounting_inventory->addInventory($this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['inventory_id'])) {
            $inventory_info = $this->model_accounting_inventory->getInventory((int)$this->request->get['inventory_id']);
        } else {
            $inventory_info = array();
        }

        $this->data['action'] = $this->url->link('accounting/inventory/form', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['cancel'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_sku'] = $this->build->data('sku', $this->error);
        $this->data['error_name'] = $this->build->data('name', $this->error);

        $this->data['sku'] = $this->build->data('sku', $this->request->post, $inventory_info);
        $this->data['name'] = $this->build->data('name', $this->request->post, $inventory_info);
        $this->data['description'] = $this->build->data('description', $this->request->post, $inventory_info);
        $this->data['image'] = $this->build->data('image', $this->request->post, $inventory_info);
        $this->data['quantity'] = $this->build->data('quantity', $this->request->post, $inventory_info);
        $this->data['cost'] = $this->build->data('cost', $this->request->post, $inventory_info);
        $this->data['sell'] = $this->build->data('sell', $this->request->post, $inventory_info);
        $this->data['status'] = $this->build->data('status', $this->request->post, $inventory_info, '1');

        $this->load->model('tool/image');

        if ($this->data['image']) {
            $this->data['thumb'] = $this->model_tool_image->resize($this->data['image'], 100, 100);
        } else {
            $this->data['thumb'] = $this->model_tool_image->resize('placeholder.png', 100, 100);
        }

        $this->data['placeholder'] = $this->model_tool_image->resize('placeholder.png', 100, 100);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/inventory_form'));
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_sku'])) {
            if (isset($this->request->get['filter_sku'])) {
                $filter_sku = $this->request->get['filter_sku'];
            } else {
                $filter_sku = '';
            }

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $filter_data = array(
                'filter_sku'  => $filter_sku,
                'filter_name' => $filter_name,
                'sort'        => 'name',
                'start'       => 0,
                'limit'       => $this->config->get('config_limit_admin')
            );

            $this->load->model('accounting/inventory');

            $inventories = $this->model_accounting_inventory->getInventories($filter_data);

            foreach ($inventories as $inventory) {
                $json[] = $inventory;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'accounting/inventory')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'accounting/inventory')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['sku']) < 1) || (utf8_strlen($this->request->post['sku']) > 255)) {
            $this->error['sku'] = $this->language->get('error_sku');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }

    public function update() {
        $this->load->language('accounting/inventory');

        $json = array();

        $inventory_id = (int)$this->request->post['inventory_id'];
        $column = $this->request->post['column'];
        $value = $this->request->post['value'];

        if (!$this->user->hasPermission('modify', 'accounting/inventory')) {
            $json['warning'] = $this->language->get('error_permission');
        } else {
            if ($column == 'sku') {
                if ((utf8_strlen($value) < 1) || (utf8_strlen($value) > 255)) {
                    $json['warning'] = $this->language->get('error_sku');
                }
            } elseif ($column == 'name') {
                if ((utf8_strlen($value) < 3) || (utf8_strlen($value) > 255)) {
                    $json['warning'] = $this->language->get('error_name');
                }
            }

            if (!$json) {
                $this->load->model('accounting/inventory');

                $this->model_accounting_inventory->editInventoryData($inventory_id, $column, $value);

                $json['value'] = $value;

                if ($column == 'cost' || $column == 'sell') {
                    $json['value'] = $this->currency->format($value, $this->config->get('config_currency'));
                }

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}