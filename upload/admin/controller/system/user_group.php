<?php
defined('_PATH') or die('Restricted!');

class ControllerSystemUserGroup extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('system/user_group');

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
            'href' => $this->url->link('system/user_group', 'token=' . $this->session->data['token'] . $url, true)
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

        $this->load->model('system/user_group');

        $this->data['user_groups'] = array();

        $user_groups = $this->model_system_user_group->getUserGroups($filter_data);

        foreach ($user_groups as $user_group) {
            $this->data['user_groups'][] = array(
                'user_group_id' => $user_group['user_group_id'],
                'name'          => $user_group['name'],
                'edit'          => $this->url->link('system/user_group/form', 'token=' . $this->session->data['token'] . $url . '&user_group_id=' . $user_group['user_group_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_system_user_group->getTotalUserGroups();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('system/user_group', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('system/user_group/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('system/user_group/form', 'token=' . $this->session->data['token'], true);

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

        $this->data['sort_name'] = $this->url->link('system/user_group', 'token=' . $this->session->data['token'] . '&sort=name&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/user_group_list'));
    }

    public function delete() {
        $this->load->language('system/user_group');

        $this->load->model('system/user_group');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $user_group_id) {
                $this->model_system_user_group->deleteUserGroup($user_group_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/user_group', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('system/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'user_group_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/user_group', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/user_group/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('system/user_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['user_group_id'])) {
                $this->model_system_user_group->editUserGroup((int)$this->request->get['user_group_id'], $this->request->post);
            } else {
                $this->model_system_user_group->addUserGroup($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/user_group', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['user_group_id'])) {
            $user_group_info = $this->model_system_user_group->getUserGroup((int)$this->request->get['user_group_id']);
        } else {
            $user_group_info = array();
        }

        $this->data['action'] = $this->url->link('system/user_group/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('system/user_group', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);
        $this->data['error_name'] = $this->build->data('name', $this->error);

        $this->data['name'] = $this->build->data('name', $this->request->post, $user_group_info);

        $ignore = array(
            'common/login',
            'common/logout',
            'common/forgotten',
            'common/reset',
            'error/not_found',
            'common/permission',
            'common/footer',
            'common/header'
        );

        $this->data['permissions'] = array();

        $files = glob(DIR_APPLICATION . 'controller/*/*.php');

        foreach ($files as $file) {
            $part = explode('/', dirname($file));

            $permission = end($part) . '/' . basename($file, '.php');

            if (!in_array($permission, $ignore)) {
                $this->data['permissions'][] = $permission;
            }
        }

        $files = glob(DIR_EXTENSION . 'module/*', GLOB_ONLYDIR);

        foreach ($files as $file) {
            $this->data['permissions'][] = 'module/' . basename($file);
        }

        $files = glob(DIR_EXTENSION . 'payment/*', GLOB_ONLYDIR);

        foreach ($files as $file) {
            $this->data['permissions'][] = 'payment/' . basename($file);
        }

        $files = glob(DIR_EXTENSION . 'total/*', GLOB_ONLYDIR);

        foreach ($files as $file) {
            $this->data['permissions'][] = 'total/' . basename($file);
        }

        if (isset($this->request->post['permission']['access'])) {
            $this->data['access'] = $this->request->post['permission']['access'];
        } elseif (isset($user_group_info['permission']['access'])) {
            $this->data['access'] = $user_group_info['permission']['access'];
        } else {
            $this->data['access'] = array();
        }

        if (isset($this->request->post['permission']['modify'])) {
            $this->data['modify'] = $this->request->post['permission']['modify'];
        } elseif (isset($user_group_info['permission']['modify'])) {
            $this->data['modify'] = $user_group_info['permission']['modify'];
        } else {
            $this->data['modify'] = array();
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/user_group_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'system/user_group')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->model_system_user_group->getTotalUserGroups() <= 1) {
            $this->error['warning'] = $this->language->get('error_user_group');
        }

        $this->load->model('system/user');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $user_group_id) {
                if ($this->model_system_user->getTotalUsersByUserGroup($user_group_id)) {
                    $this->error['warning'] = $this->language->get('error_user');

                    break;
                }
            }
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'system/user_group')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}