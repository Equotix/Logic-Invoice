<?php
defined('_PATH') or die('Restricted!');

class ControllerContentArticle extends Controller {
    public function index() {
        $this->load->model('content/article');

        if (isset($this->request->get['article_id'])) {
            $article_info = $this->model_content_article->getArticle((int)$this->request->get['article_id']);
        } else {
            $article_info = false;
        }

        if ($article_info) {
            $this->document->setTitle($article_info['title']);

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $article_info['title'],
                'href' => $this->url->link('content/article', 'article_id=' . $this->request->get['article_id'])
            );

            $this->data['heading_title'] = $article_info['title'];
            $this->data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES);
        } else {
            return new Action('error/not_found');
        }

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/article'));
    }
}