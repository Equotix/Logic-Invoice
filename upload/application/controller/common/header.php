<?php
class ControllerCommonHeader extends Controller {
    public function index() {
        $this->data = $this->load->language('common/header');

        $this->data['title'] = $this->document->getTitle();
        $this->data['styles'] = $this->document->getStyles();
        $this->data['scripts'] = $this->document->getScripts();
        $this->data['links'] = $this->document->getLinks();
        $this->data['description'] = $this->document->getDescription();
        $this->data['keywords'] = $this->document->getKeywords();
        $this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES);

        $this->data['name'] = $this->config->get('config_name');

        $this->data['home'] = $this->url->link('common/home');

        if ($this->request->server['HTTPS']) {
            $this->data['base'] = HTTPS_SERVER;
        } else {
            $this->data['base'] = HTTP_SERVER;
        }

        if ($this->config->get('config_logo')) {
            $this->data['logo'] = $this->config->get('config_logo');
        } else {
            $this->data['logo'] = '';
        }

        if ($this->config->get('config_icon')) {
            $this->data['icon'] = $this->config->get('config_icon');
        } else {
            $this->data['icon'] = '';
        }

        $this->load->model('content/article');

        $articles = $this->model_content_article->getArticles();

        $this->data['articles'] = array();

        foreach ($articles as $article) {
            if ($article['top']) {
                $children = $this->model_content_article->getArticles($article['article_id']);

                $children_data = array();

                foreach ($children as $child) {
                    $children_data[] = array(
                        'title' => $child['title'],
                        'href'  => $this->url->link('content/article', 'article_id=' . $child['article_id']),
                    );
                }

                $this->data['articles'][] = array(
                    'title'    => $article['title'],
                    'href'     => $this->url->link('content/article', 'article_id=' . $article['article_id']),
                    'children' => $children_data
                );
            }
        }

        if ($this->customer->isLogged()) {
            $this->data['logged'] = true;
            $this->data['account'] = $this->url->link('account/account', '', 'SSL');
            $this->data['invoice'] = $this->url->link('account/invoice', '', 'SSL');
            $this->data['logout'] = $this->url->link('account/logout', '', 'SSL');
        } else {
            $this->data['logged'] = false;
            $this->data['account'] = $this->url->link('account/account', '', 'SSL');
            $this->data['register'] = $this->config->get('config_registration') ? $this->url->link('account/register', '', 'SSL') : false;
            $this->data['login'] = $this->url->link('account/login', '', 'SSL');
        }

        return $this->render('common/header.tpl');
    }
}