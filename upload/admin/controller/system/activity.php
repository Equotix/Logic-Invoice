<?php
defined('_PATH') or die('Restricted!');

class ControllerSystemActivity extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('system/activity');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'page'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/activity', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $filter_data = array(
            'start' => $this->config->get('config_limit_admin') * ($page - 1),
            'limit' => $this->config->get('config_limit_admin')
        );

        $this->load->model('system/activity');

        $this->data['activities'] = array();

        $activities = $this->model_system_activity->getActivities($filter_data);

        foreach ($activities as $activity) {
            $this->data['activities'][] = array(
                'date_added' => date($this->language->get('datetime_format_short'), strtotime($activity['date_added'])),
                'message'    => $activity['message']
            );
        }

        $pagination = new Pagination();
        $pagination->total = $this->model_system_activity->getTotalActivities();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('system/activity', 'token=' . $this->session->data['token'] . '&page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->data['clear'] = $this->url->link('system/activity/clear', 'token=' . $this->session->data['token'], true);

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/activity'));
    }

    public function clear() {
        $this->load->language('system/activity');

        if (!$this->user->hasPermission('modify', 'system/activity')) {
            $this->session->data['warning'] = $this->language->get('error_permission');
        } else {
            $this->load->model('system/activity');

            $this->model_system_activity->deleteActivities();

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_clear'), $this->user->getUsername()));

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->redirect($this->url->link('system/activity', 'token=' . $this->session->data['token'], true));
    }
}