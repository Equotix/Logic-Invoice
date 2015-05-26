<?php
defined('_PATH') or die('Restricted!');

class ControllerContentArticle extends Controller {
    public function index() {
        $this->load->model('content/article');

        $article_info = $this->model_content_article->getArticle((int)$this->request->get['article_id']);

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

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/content/article.tpl')) {
			$this->response->setOutput($this->render($this->config->get('config_theme') . '/template/content/article.tpl'));
		} else {
			$this->response->setOutput($this->render('default/template/content/article.tpl'));
		}
    }
}