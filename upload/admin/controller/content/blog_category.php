<?php
defined('_PATH') or die('Restricted!');

class ControllerContentBlogCategory extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('content/blog_category');

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
            'href' => $this->url->link('content/blog_category', 'token=' . $this->session->data['token'] . $url, true)
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

        $this->load->model('content/blog_category');

        $this->data['blog_categories'] = array();

        $blog_categories = $this->model_content_blog_category->getBlogCategories($filter_data);

        foreach ($blog_categories as $blog_category) {
            $this->data['blog_categories'][] = array(
                'blog_category_id' => $blog_category['blog_category_id'],
                'name'             => $blog_category['name'],
                'sort_order'       => $blog_category['sort_order'],
                'status'           => $blog_category['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit'             => $this->url->link('content/blog_category/form', 'token=' . $this->session->data['token'] . $url . '&blog_category_id=' . $blog_category['blog_category_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_content_blog_category->getTotalBlogCategories();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('content/blog_category', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('content/blog_category/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('content/blog_category/form', 'token=' . $this->session->data['token'], true);

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

        $this->data['sort_name'] = $this->url->link('content/blog_category', 'token=' . $this->session->data['token'] . '&sort=name&order=' . $order, true);
        $this->data['sort_sort_order'] = $this->url->link('content/blog_category', 'token=' . $this->session->data['token'] . '&sort=sort_order&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('content/blog_category', 'token=' . $this->session->data['token'] . '&sort=status&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/blog_category_list'));
    }

    public function delete() {
        $this->load->language('content/blog_category');

        $this->load->model('content/blog_category');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $blog_category_id) {
                $this->model_content_blog_category->deleteBlogCategory($blog_category_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('content/blog_category', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('content/blog_category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/summernote/summernote.min.js');
        $this->document->addStyle('view/javascript/summernote/summernote.css');

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'blog_category_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/blog_category', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/blog_category/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('content/blog_category');
        $this->load->model('system/url_alias');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['blog_category_id'])) {
                $this->model_content_blog_category->editBlogCategory((int)$this->request->get['blog_category_id'], $this->request->post);
            } else {
                $this->model_content_blog_category->addBlogCategory($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('content/blog_category', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['blog_category_id'])) {
            $blog_category_info = $this->model_content_blog_category->getBlogCategory((int)$this->request->get['blog_category_id']);
        } else {
            $blog_category_info = array();
        }

        $this->data['action'] = $this->url->link('content/blog_category/form', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('content/blog_category', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);
        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_meta_title'] = $this->build->data('meta_title', $this->error);
        $this->data['error_url_alias'] = $this->build->data('url_alias', $this->error);

        $this->data['description'] = $this->build->data('description', $this->request->post, $blog_category_info, array());
        $this->data['parent_id'] = $this->build->data('parent_id', $this->request->post, $blog_category_info);
        $this->data['sort_order'] = $this->build->data('sort_order', $this->request->post, $blog_category_info);
        $this->data['status'] = $this->build->data('status', $this->request->post, $blog_category_info, '1');

        if (isset($this->request->post['url_alias'])) {
            $this->data['url_alias'] = $this->request->post['url_alias'];
        } elseif ($blog_category_info) {
            $this->data['url_alias'] = $this->model_system_url_alias->getUrlAliasByQuery('blog_category_id=' . $blog_category_info['blog_category_id']);
        } else {
            $this->data['url_alias'] = array();
        }

        $this->data['blog_categories'] = $this->model_content_blog_category->getBlogCategories();

        $this->load->model('system/language');

        $this->data['languages'] = $this->model_system_language->getLanguages();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/blog_category_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'content/blog_category')) {
            $this->session->data['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'content/blog_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_name');
            }
        }

        if (!empty($this->request->post['url_alias'])) {
            foreach ($this->request->post['url_alias'] as $language_id => $keyword) {
                if ($keyword) {
                    $query = $this->model_system_url_alias->getUrlAliasByKeyword($language_id, $keyword);

                    if (isset($this->request->get['blog_category_id'])) {
                        if ($query && $query != 'blog_category_id=' . $this->request->get['blog_category_id']) {
                            $this->error['url_alias'][$language_id] = $this->language->get('error_url_alias');
                        }
                    } elseif ($query) {
                        $this->error['url_alias'][$language_id] = $this->language->get('error_url_alias');
                    }
                }
            }
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}