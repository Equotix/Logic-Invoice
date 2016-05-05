<?php
defined('_PATH') or die('Restricted!');

class ControllerSystemUser extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('system/user');

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
            'href' => $this->url->link('system/user', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'username';
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

        $this->load->model('system/user');

        $this->data['users'] = array();

        $users = $this->model_system_user->getUsers($filter_data);

        foreach ($users as $user) {
            $this->data['users'][] = array(
                'user_id'       => $user['user_id'],
                'name'          => $user['name'],
                'username'      => $user['username'],
                'user_group'    => $user['user_group'],
                'status'        => $user['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'date_added'    => date($this->language->get('date_format_short'), strtotime($user['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($user['date_modified'])),
                'edit'          => $this->url->link('system/user/form', 'token=' . $this->session->data['token'] . $url . '&user_id=' . $user['user_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_system_user->getTotalUsers();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('system/user', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('system/user/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('system/user/form', 'token=' . $this->session->data['token'], true);

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

        $this->data['sort_name'] = $this->url->link('system/user', 'token=' . $this->session->data['token'] . '&sort=u.name&order=' . $order, true);
        $this->data['sort_username'] = $this->url->link('system/user', 'token=' . $this->session->data['token'] . '&sort=username&order=' . $order, true);
        $this->data['sort_user_group'] = $this->url->link('system/user', 'token=' . $this->session->data['token'] . '&sort=ug.name&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('system/user', 'token=' . $this->session->data['token'] . '&sort=status&order=' . $order, true);
        $this->data['sort_date_added'] = $this->url->link('system/user', 'token=' . $this->session->data['token'] . '&sort=date_added&order=' . $order, true);
        $this->data['sort_date_modified'] = $this->url->link('system/user', 'token=' . $this->session->data['token'] . '&sort=date_modified&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/user_list'));
    }

    public function delete() {
        $this->load->language('system/user');

        $this->load->model('system/user');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $user_id) {
                $this->model_system_user->deleteUser($user_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/user', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('system/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'user_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/user', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/user/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('system/user');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['user_id'])) {
                $this->model_system_user->editUser((int)$this->request->get['user_id'], $this->request->post);
            } else {
                $this->model_system_user->addUser($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/user', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['user_id'])) {
            $user_info = $this->model_system_user->getUser((int)$this->request->get['user_id']);
        } else {
            $user_info = array();
        }

        $this->data['action'] = $this->url->link('system/user/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('system/user', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_email'] = $this->build->data('email', $this->error);
        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_username'] = $this->build->data('username', $this->error);
        $this->data['error_password'] = $this->build->data('password', $this->error);
        $this->data['error_confirm'] = $this->build->data('confirm', $this->error);

        $this->data['name'] = $this->build->data('name', $this->request->post, $user_info);
        $this->data['username'] = $this->build->data('username', $this->request->post, $user_info);
        $this->data['email'] = $this->build->data('email', $this->request->post, $user_info);
        $this->data['user_group_id'] = $this->build->data('user_group_id', $this->request->post, $user_info);
        $this->data['key'] = $this->build->data('key', $this->request->post, $user_info);
        $this->data['secret'] = $this->build->data('secret', $this->request->post, $user_info);
        $this->data['password'] = $this->build->data('password', $this->request->post);
        $this->data['confirm'] = $this->build->data('confirm', $this->request->post);
        $this->data['status'] = $this->build->data('status', $this->request->post, $user_info, '1');

        $this->load->model('system/user_group');

        $this->data['user_groups'] = $this->model_system_user_group->getUserGroups();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/user_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'system/user')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $user_id) {
                if ($this->user->getId() == $user_id) {
                    $this->error['warning'] = $this->language->get('error_user');

                    break;
                }
            }
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'system/user')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['username']) > 64)) {
            $this->error['name'] = $this->language->get('error_username');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 32)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        if (!isset($this->request->get['user_id'])) {
            if ($this->model_system_user->getTotalUsersByEmail($this->request->post['email'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }

            if ((utf8_strlen($this->request->post['password']) < 6) || (utf8_strlen($this->request->post['password']) > 25)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['confirm'] != $this->request->post['password']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        } else {
            if ($this->model_system_user->getTotalUsersByEmail($this->request->post['email']) > 1) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if (!empty($this->request->post['password'])) {
            if ((utf8_strlen($this->request->post['password']) < 6) || (utf8_strlen($this->request->post['password']) > 25)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['confirm'] != $this->request->post['password']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}