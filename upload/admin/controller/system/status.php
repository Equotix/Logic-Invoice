<?php
defined('_PATH') or die('Restricted!');

class ControllerSystemStatus extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('system/status');

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
            'href' => $this->url->link('system/status', 'token=' . $this->session->data['token'] . $url, true)
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

        $this->load->model('system/status');

        $this->data['statuses'] = array();

        $statuses = $this->model_system_status->getStatuses($filter_data);

        foreach ($statuses as $status) {
            $this->data['statuses'][] = array(
                'status_id' => $status['status_id'],
                'name'      => $status['name'],
                'edit'      => $this->url->link('system/status/form', 'token=' . $this->session->data['token'] . $url . '&status_id=' . $status['status_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_system_status->getTotalStatuses();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('system/status', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('system/status/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('system/status/form', 'token=' . $this->session->data['token'], true);

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

        $this->data['sort_name'] = $this->url->link('system/status', 'token=' . $this->session->data['token'] . '&sort=name&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/status_list'));
    }

    public function delete() {
        $this->load->language('system/status');

        $this->load->model('system/status');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $status_id) {
                $this->model_system_status->deleteStatus($status_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/status', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('system/status');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'status_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/status', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/status/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('system/status');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['status_id'])) {
                $this->model_system_status->editStatus((int)$this->request->get['status_id'], $this->request->post);
            } else {
                $this->model_system_status->addStatus($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/status', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['status_id'])) {
            $status_info = $this->model_system_status->getStatus((int)$this->request->get['status_id']);
        } else {
            $status_info = array();
        }

        $this->data['action'] = $this->url->link('system/status/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('system/status', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);
        $this->data['error_name'] = $this->build->data('name', $this->error);

        $this->data['name'] = $this->build->data('name', $this->request->post, $status_info);

        $this->load->model('system/language');

        $this->data['languages'] = $this->model_system_language->getLanguages();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/status_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'system/status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'system/status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['name'] as $language_id => $name) {
            if ((utf8_strlen($name) < 3) || (utf8_strlen($name) > 32)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}