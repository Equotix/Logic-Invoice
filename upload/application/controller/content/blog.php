<?php
defined('_PATH') or die('Restricted!');

class ControllerContentBlog extends Controller {
    public function index() {
        $this->data = $this->load->language('content/blog');

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/blog')
        );

        $this->load->model('content/blog_category');

        if (isset($this->request->get['blog_category_id'])) {
            $url = '';

            $parts = explode('_', (string)$this->request->get['blog_category_id']);

            foreach ($parts as $part) {
                $blog_category_info = $this->model_content_blog_category->getBlogCategory((int)$part);

                if ($blog_category_info) {
                    if ($url) {
                        $url .= '_' . $blog_category_info['blog_category_id'];
                    } else {
                        $url .= $blog_category_info['blog_category_id'];
                    }

                    $this->data['breadcrumbs'][] = array(
                        'text' => $blog_category_info['name'],
                        'href' => $this->url->link('content/blog', 'blog_category_id=' . $url)
                    );
                }
            }

            $blog_category_id = (int)end($parts);

            $blog_category_info = $this->model_content_blog_category->getBlogCategory($blog_category_id);

            if ($blog_category_info) {
                $this->document->setTitle($blog_category_info['meta_title']);
                $this->document->setDescription($blog_category_info['meta_description']);
                $this->document->setKeywords($blog_category_info['meta_keyword']);
                $this->document->addLink($this->url->link('content/blog', 'blog_category_id=' . $blog_category_info['blog_category_id']), 'canonical');

                $this->data['blog_category_id'] = $blog_category_info['blog_category_id'];

                $this->data['heading_title'] = $blog_category_info['name'];
            } else {
                return new Action('error/not_found');
            }
        } else {
            $blog_category_id = 0;

            $this->data['blog_category_id'] = 0;

            $this->document->setTitle($this->language->get('heading_title'));
        }

        $filter_data = array(
            'start'            => $this->config->get('config_limit_application') * ($page - 1),
            'limit'            => $this->config->get('config_limit_application'),
            'blog_category_id' => $blog_category_id,
            'order'            => 'DESC'
        );

        $this->load->model('content/blog_post');
        $this->load->model('tool/image');

        $url = $this->build->url(array(
            'blog_category_id',
            'page'
        ));

        $this->data['blog_posts'] = array();

        $blog_posts = $this->model_content_blog_post->getBlogPosts($filter_data);

        foreach ($blog_posts as $blog_post) {
            if ($blog_post['image']) {
                $image = $this->model_tool_image->resize($blog_post['image'], 800, 250);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 800, 250);
            }

            $this->data['blog_posts'][] = array(
                'image'             => $image,
                'title'             => $blog_post['title'],
                'short_description' => html_entity_decode($blog_post['short_description'], ENT_QUOTES, 'UTF-8'),
                'view'              => $blog_post['view'],
                'date_added'        => date($this->language->get('date_format_long'), strtotime($blog_post['date_added'])),
                'href'              => $this->url->link('content/blog/info', $url . '&blog_post_id=' . $blog_post['blog_post_id'])
            );
        }

        $url = $this->build->url(array(
            'blog_category_id'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_content_blog_post->getTotalBlogPosts();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_application');
        $pagination->url = $this->url->link('content/blog', $url . '&page={page}');

        $this->data['pagination'] = $pagination->render();

        $this->data['blog_categories'] = array();

        $blog_categories = $this->model_content_blog_category->getBlogCategories();

        foreach ($blog_categories as $blog_category) {
            $children = $this->model_content_blog_category->getBlogCategories($blog_category['blog_category_id']);

            $children_data = array();

            foreach ($children as $child) {
                $grandchildren = $this->model_content_blog_category->getBlogCategories($child['blog_category_id']);

                $grandchildren_data = array();

                foreach ($grandchildren as $grandchild) {
                    $grandchildren_data[] = array(
                        'blog_category_id' => $grandchild['blog_category_id'],
                        'name'             => $grandchild['name'],
                        'href'             => $this->url->link('content/blog', 'blog_category_id=' . $blog_category['blog_category_id'] . '_' . $child['blog_category_id'] . '_' . $grandchild['blog_category_id'])
                    );
                }

                $children_data[] = array(
                    'blog_category_id' => $child['blog_category_id'],
                    'name'             => $child['name'],
                    'href'             => $this->url->link('content/blog', 'blog_category_id=' . $blog_category['blog_category_id'] . '_' . $child['blog_category_id']),
                    'grandchildren'    => $grandchildren_data
                );
            }

            $this->data['blog_categories'][] = array(
                'blog_category_id' => $blog_category['blog_category_id'],
                'name'             => $blog_category['name'],
                'href'             => $this->url->link('content/blog', 'blog_category_id=' . $blog_category['blog_category_id']),
                'children'         => $children_data
            );
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/blog'));
    }

    public function info() {
        $this->data = $this->load->language('content/blog');

        if (isset($this->request->get['blog_post_id'])) {
            $blog_post_id = (int)$this->request->get['blog_post_id'];
        } else {
            $blog_post_id = 0;
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/blog')
        );

        $this->load->model('content/blog_category');

        if (isset($this->request->get['blog_category_id'])) {
            $url = '';

            $parts = explode('_', (string)$this->request->get['blog_category_id']);

            foreach ($parts as $part) {
                $blog_category_info = $this->model_content_blog_category->getBlogCategory((int)$part);

                if ($blog_category_info) {
                    if ($url) {
                        $url .= '_' . $blog_category_info['blog_category_id'];
                    } else {
                        $url .= $blog_category_info['blog_category_id'];
                    }

                    $this->data['breadcrumbs'][] = array(
                        'text' => $blog_category_info['name'],
                        'href' => $this->url->link('content/blog', 'blog_category_id=' . $url)
                    );
                }
            }

            $this->data['blog_category_id'] = (int)end($parts);
        } else {
            $this->data['blog_category_id'] = 0;
        }

        $filter_data = array(
            'blog_post_id' => $blog_post_id
        );

        $this->load->model('content/blog_post');
        $this->load->model('tool/image');

        $url = $this->build->url(array(
            'blog_category_id',
            'page'
        ));

        $blog_post_info = $this->model_content_blog_post->getBlogPost($blog_post_id);

        if ($blog_post_info) {
            $this->document->setTitle($blog_post_info['meta_title']);
            $this->document->setDescription($blog_post_info['meta_description']);
            $this->document->setKeywords($blog_post_info['meta_keyword']);
            $this->document->addLink($this->url->link('content/blog/info', 'blog_post_id=' . $blog_post_info['blog_post_id']), 'canonical');

            $this->data['heading_title'] = $blog_post_info['title'];

            $this->data['breadcrumbs'][] = array(
                'text' => $blog_post_info['title'],
                'href' => $this->url->link('content/blog/info', $url . '&blog_post_id=' . $blog_post_info['blog_post_id'])
            );

            if ($blog_post_info['image']) {
                $this->data['image'] = $this->model_tool_image->resize($blog_post_info['image'], 800, 250);
            } else {
                $this->data['image'] = $this->model_tool_image->resize('placeholder.png', 800, 250);
            }

            $this->data['title'] = $blog_post_info['title'];
            $this->data['description'] = html_entity_decode($blog_post_info['description'], ENT_QUOTES, 'UTF-8');
            $this->data['view'] = $blog_post_info['view'];
            $this->data['date_added'] = date($this->language->get('date_format_long'), strtotime($blog_post_info['date_added']));
            $this->data['user'] = $blog_post_info['user'];
            $this->data['tag'] = $blog_post_info['tag'];

            $this->model_content_blog_post->increaseView($blog_post_info['blog_post_id']);
        } else {
            return new Action('error/not_found');
        }

        $this->data['blog_categories'] = array();

        $blog_categories = $this->model_content_blog_category->getBlogCategories();

        foreach ($blog_categories as $blog_category) {
            $children = $this->model_content_blog_category->getBlogCategories($blog_category['blog_category_id']);

            $children_data = array();

            foreach ($children as $child) {
                $grandchildren = $this->model_content_blog_category->getBlogCategories($child['blog_category_id']);

                $grandchildren_data = array();

                foreach ($grandchildren as $grandchild) {
                    $grandchildren_data[] = array(
                        'blog_category_id' => $grandchild['blog_category_id'],
                        'name'             => $grandchild['name'],
                        'href'             => $this->url->link('content/blog', 'blog_category_id=' . $blog_category['blog_category_id'] . '_' . $child['blog_category_id'] . '_' . $grandchild['blog_category_id'])
                    );
                }

                $children_data[] = array(
                    'blog_category_id' => $child['blog_category_id'],
                    'name'             => $child['name'],
                    'href'             => $this->url->link('content/blog', 'blog_category_id=' . $blog_category['blog_category_id'] . '_' . $child['blog_category_id']),
                    'grandchildren'    => $grandchildren_data
                );
            }

            $this->data['blog_categories'][] = array(
                'blog_category_id' => $blog_category['blog_category_id'],
                'name'             => $blog_category['name'],
                'href'             => $this->url->link('content/blog', 'blog_category_id=' . $blog_category['blog_category_id']),
                'children'         => $children_data
            );
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/blog_info'));
    }
}