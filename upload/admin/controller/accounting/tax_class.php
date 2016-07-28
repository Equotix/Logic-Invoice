<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountingTaxClass extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('accounting/tax_class');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
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
            'href' => $this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        $filter_data = array(
            'start' => $this->config->get('config_limit_admin') * ($page - 1),
            'limit' => $this->config->get('config_limit_admin'),
            'sort'  => $sort,
            'order' => $order
        );

        $this->load->model('accounting/tax_class');

        $this->data['tax_classs'] = array();

        $tax_classs = $this->model_accounting_tax_class->getTaxClasses($filter_data);

        foreach ($tax_classs as $tax_class) {
            $this->data['tax_classs'][] = array(
                'tax_class_id' => $tax_class['tax_class_id'],
                'name'         => $tax_class['name'],
                'edit'         => $this->url->link('accounting/tax_class/form', 'token=' . $this->session->data['token'] . $url . '&tax_class_id=' . $tax_class['tax_class_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_accounting_tax_class->getTotalTaxClasses();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('accounting/tax_class/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('accounting/tax_class/form', 'token=' . $this->session->data['token'], true);

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_name'] = $this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'] . '&sort=name&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/tax_class_list'));
    }

    public function delete() {
        $this->load->language('accounting/tax_class');

        $this->load->model('accounting/tax_class');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $tax_class_id) {
                $this->model_accounting_tax_class->deleteTaxClass($tax_class_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('accounting/tax_class');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'tax_class_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('accounting/tax_class/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('accounting/tax_class');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['tax_class_id'])) {
                $this->model_accounting_tax_class->editTaxClass((int)$this->request->get['tax_class_id'], $this->request->post);
            } else {
                $this->model_accounting_tax_class->addTaxClass($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['tax_class_id'])) {
            $tax_class_info = $this->model_accounting_tax_class->getTaxClass((int)$this->request->get['tax_class_id']);
        } else {
            $tax_class_info = array();
        }

        $this->data['action'] = $this->url->link('accounting/tax_class/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);
        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_description'] = $this->build->data('description', $this->error);

        $this->data['name'] = $this->build->data('name', $this->request->post, $tax_class_info);
        $this->data['description'] = $this->build->data('description', $this->request->post, $tax_class_info);
        $this->data['tax_rates'] = $this->build->data('tax_rates', $this->request->post, $tax_class_info, array());

        $this->load->model('accounting/tax_rate');

        $this->data['rates'] = $this->model_accounting_tax_rate->getTaxRates();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('accounting/tax_class_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'accounting/tax_class')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'accounting/tax_class')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen($this->request->post['description']) > 255)) {
            $this->error['description'] = $this->language->get('error_description');
        }

        if (empty($this->request->post['tax_rates'])) {
            $this->error['warning'] = $this->language->get('error_tax_rate');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}