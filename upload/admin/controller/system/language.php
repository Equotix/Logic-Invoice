<?php
defined('_PATH') or die('Restricted!');

class ControllerSystemLanguage extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('system/language');

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
            'href' => $this->url->link('system/language', 'token=' . $this->session->data['token'] . $url, true)
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

        $this->load->model('system/language');

        $this->data['languages'] = array();

        $languages = $this->model_system_language->getLanguages($filter_data);

        foreach ($languages as $language) {
            $this->data['languages'][] = array(
                'language_id' => $language['language_id'],
                'name'        => $language['name'],
                'code'        => $language['code'],
                'sort_order'  => $language['sort_order'],
                'status'      => $language['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit'        => $this->url->link('system/language/form', 'token=' . $this->session->data['token'] . $url . '&language_id=' . $language['language_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_system_language->getTotalLanguages();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('system/language', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('system/language/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('system/language/form', 'token=' . $this->session->data['token'], true);

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

        $this->data['sort_name'] = $this->url->link('system/language', 'token=' . $this->session->data['token'] . '&sort=name&order=' . $order, true);
        $this->data['sort_code'] = $this->url->link('system/language', 'token=' . $this->session->data['token'] . '&sort=code&order=' . $order, true);
        $this->data['sort_sort_order'] = $this->url->link('system/language', 'token=' . $this->session->data['token'] . '&sort=sort_order&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('system/language', 'token=' . $this->session->data['token'] . '&sort=status&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/language_list'));
    }

    public function delete() {
        $this->load->language('system/language');

        $this->load->model('system/language');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $language_id) {
                $this->model_system_language->deleteLanguage($language_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/language', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('system/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'language_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/language', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/language/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('system/language');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['language_id'])) {
                $this->model_system_language->editLanguage((int)$this->request->get['language_id'], $this->request->post);
            } else {
                $this->model_system_language->addLanguage($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('system/language', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['language_id'])) {
            $language_info = $this->model_system_language->getLanguage((int)$this->request->get['language_id']);
        } else {
            $language_info = array();
        }

        $this->data['action'] = $this->url->link('system/language/form', 'token=' . $this->session->data['token'] . $url, true);

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('system/language', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_code'] = $this->build->data('code', $this->error);
        $this->data['error_locale'] = $this->build->data('locale', $this->error);
        $this->data['error_image'] = $this->build->data('image', $this->error);

        $this->data['name'] = $this->build->data('name', $this->request->post, $language_info);
        $this->data['code'] = $this->build->data('code', $this->request->post, $language_info);
        $this->data['locale'] = $this->build->data('locale', $this->request->post, $language_info);
        $this->data['image'] = $this->build->data('image', $this->request->post, $language_info);
        $this->data['sort_order'] = $this->build->data('sort_order', $this->request->post, $language_info);
        $this->data['status'] = $this->build->data('status', $this->request->post, $language_info, '1');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/language_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'system/language')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->model_system_language->getTotalLanguages() <= 1) {
            $this->error['warning'] = $this->language->get('error_language');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'system/language')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (utf8_strlen($this->request->post['code']) < 2) {
            $this->error['code'] = $this->language->get('error_code');
        }

        if (!$this->request->post['locale']) {
            $this->error['locale'] = $this->language->get('error_locale');
        }

        if ((utf8_strlen($this->request->post['image']) < 3) || (utf8_strlen($this->request->post['image']) > 32)) {
            $this->error['image'] = $this->language->get('error_image');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}