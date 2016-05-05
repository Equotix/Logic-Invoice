<?php
defined('_PATH') or die('Restricted!');

class ControllerContentBlogPost extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('content/blog_post');

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
            'href' => $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'title';
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

        $this->load->model('content/blog_post');

        $this->data['blog_posts'] = array();

        $blog_posts = $this->model_content_blog_post->getBlogPosts($filter_data);

        foreach ($blog_posts as $blog_post) {
            $this->data['blog_posts'][] = array(
                'blog_post_id'  => $blog_post['blog_post_id'],
                'title'         => $blog_post['title'],
                'view'          => $blog_post['view'],
                'sort_order'    => $blog_post['sort_order'],
                'status'        => $blog_post['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'date_added'    => date($this->language->get('date_format_short'), strtotime($blog_post['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($blog_post['date_modified'])),
                'edit'          => $this->url->link('content/blog_post/form', 'token=' . $this->session->data['token'] . $url . '&blog_post_id=' . $blog_post['blog_post_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_content_blog_post->getTotalBlogPosts();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('content/blog_post/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('content/blog_post/form', 'token=' . $this->session->data['token'], true);

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

        $this->data['sort_title'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . '&sort=title&order=' . $order, true);
        $this->data['sort_view'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . '&sort=view&order=' . $order, true);
        $this->data['sort_sort_order'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . '&sort=sort_order&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . '&sort=status&order=' . $order, true);
        $this->data['sort_date_added'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . '&sort=date_added&order=' . $order, true);
        $this->data['sort_date_modified'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . '&sort=date_modified&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/blog_post_list'));
    }

    public function delete() {
        $this->load->language('content/blog_post');

        $this->load->model('content/blog_post');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $blog_post_id) {
                $this->model_content_blog_post->deleteBlogPost($blog_post_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('content/blog_post', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('content/blog_post');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/summernote/summernote.min.js');
        $this->document->addStyle('view/javascript/summernote/summernote.css');

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'blog_post_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/blog_post', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/blog_post/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('content/blog_post');
        $this->load->model('system/url_alias');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['blog_post_id'])) {
                $this->model_content_blog_post->editBlogPost((int)$this->request->get['blog_post_id'], $this->request->post);
            } else {
                $this->model_content_blog_post->addBlogPost($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['blog_post_id'])) {
            $blog_post_info = $this->model_content_blog_post->getBlogPost((int)$this->request->get['blog_post_id']);
        } else {
            $blog_post_info = array();
        }

        $this->data['action'] = $this->url->link('content/blog_post/form', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);
        $this->data['error_title'] = $this->build->data('title', $this->error);
        $this->data['error_meta_title'] = $this->build->data('meta_title', $this->error);
        $this->data['error_short_description'] = $this->build->data('short_description', $this->error);
        $this->data['error_description'] = $this->build->data('description', $this->error);
        $this->data['error_url_alias'] = $this->build->data('url_alias', $this->error);

        $this->load->model('tool/image');

        $description = $this->build->data('description', $this->request->post, $blog_post_info, array());

        $this->data['description'] = array();

        foreach ($description as $language_id => $value) {
            if ($value['image']) {
                $thumb = $this->model_tool_image->resize($value['image'], 100, 100);
            } else {
                $thumb = $this->model_tool_image->resize('placeholder.png', 100, 100);
            }

            $this->data['description'][$language_id] = array(
                'thumb'             => $thumb,
                'image'             => $value['image'],
                'title'             => $value['title'],
                'meta_title'        => $value['meta_title'],
                'meta_description'  => $value['meta_description'],
                'meta_keyword'      => $value['meta_keyword'],
                'short_description' => $value['short_description'],
                'description'       => $value['description'],
                'tag'               => $value['tag']
            );
        }

        $this->data['sort_order'] = $this->build->data('sort_order', $this->request->post, $blog_post_info);
        $this->data['blog_category'] = $this->build->data('blog_category', $this->request->post, $blog_post_info, array());
        $this->data['status'] = $this->build->data('status', $this->request->post, $blog_post_info, '1');

        if (isset($this->request->post['url_alias'])) {
            $this->data['url_alias'] = $this->request->post['url_alias'];
        } elseif ($blog_post_info) {
            $this->data['url_alias'] = $this->model_system_url_alias->getUrlAliasByQuery('blog_post_id=' . $blog_post_info['blog_post_id']);
        } else {
            $this->data['url_alias'] = array();
        }

        $this->data['placeholder'] = $this->model_tool_image->resize('placeholder.png', 100, 100);

        $this->load->model('content/blog_category');

        $this->data['blog_categories'] = $this->model_content_blog_category->getBlogCategories();

        $this->load->model('system/language');

        $this->data['languages'] = $this->model_system_language->getLanguages();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/blog_post_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'content/blog_post')) {
            $this->session->data['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'content/blog_post')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['description'] as $language_id => $value) {
            if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 255)) {
                $this->error['title'][$language_id] = $this->language->get('error_title');
            }

            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }

            if (utf8_strlen($value['short_description']) < 10) {
                $this->error['short_description'][$language_id] = $this->language->get('error_short_description');
            }

            if (utf8_strlen($value['description']) < 50) {
                $this->error['description'][$language_id] = $this->language->get('error_description');
            }
        }

        if (!empty($this->request->post['url_alias'])) {
            foreach ($this->request->post['url_alias'] as $language_id => $keyword) {
                if ($keyword) {
                    $query = $this->model_system_url_alias->getUrlAliasByKeyword($language_id, $keyword);

                    if (isset($this->request->get['blog_post_id'])) {
                        if ($query && $query != 'blog_post_id=' . $this->request->get['blog_post_id']) {
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