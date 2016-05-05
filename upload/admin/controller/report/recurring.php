<?php
defined('_PATH') or die('Restricted!');

class ControllerReportRecurring extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('report/recurring');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $url = $this->build->url(array(
            'filter_status',
            'filter_date_added_start',
            'filter_date_added_end',
            'filter_group',
            'page'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/recurring', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_date_added_start'])) {
            $filter_date_added_start = $this->request->get['filter_date_added_start'];
        } else {
            $filter_date_added_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_group'])) {
            $filter_group = $this->request->get['filter_group'];
        } else {
            $filter_group = 'year';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = $this->build->url(array(
            'filter_status',
            'filter_date_added_start',
            'filter_date_added_end',
            'filter_group',
            'page'
        ));

        $filter_data = array(
            'filter_status'           => $filter_status,
            'filter_date_added_start' => $filter_date_added_start,
            'filter_date_added_end'   => $filter_date_added_end,
            'filter_group'            => $filter_group,
            'start'                   => $this->config->get('config_limit_admin') * ($page - 1),
            'limit'                   => $this->config->get('config_limit_admin'),
        );

        $this->load->model('report/recurring');

        $this->data['recurrings'] = array();

        $recurrings = $this->model_report_recurring->getRecurringsByGroup($filter_data);

        foreach ($recurrings as $recurring) {
            $this->data['recurrings'][] = array(
                'date_start' => date($this->language->get('date_format_short'), strtotime($recurring['date_start'])),
                'date_end'   => date($this->language->get('date_format_short'), strtotime($recurring['date_end'])),
                'cycle'      => $this->language->get('text_' . $recurring['cycle']),
                'recurrings' => $recurring['recurrings'],
                'items'      => $recurring['items'],
                'tax'        => $this->currency->format($recurring['tax'], $this->config->get('config_currency')),
                'total'      => $this->currency->format($recurring['total'], $this->config->get('config_currency'))
            );
        }

        $this->data['token'] = $this->session->data['token'];

        $this->data['groups'] = array();

        $this->data['groups'][] = array(
            'text'  => $this->language->get('text_year'),
            'value' => 'year',
        );

        $this->data['groups'][] = array(
            'text'  => $this->language->get('text_month'),
            'value' => 'month',
        );

        $this->data['groups'][] = array(
            'text'  => $this->language->get('text_week'),
            'value' => 'week',
        );

        $this->data['groups'][] = array(
            'text'  => $this->language->get('text_day'),
            'value' => 'day',
        );

        $data['groups'][] = array(
            'text'  => $this->language->get('text_day'),
            'value' => 'day',
        );

        $pagination = new Pagination();
        $pagination->total = $this->model_report_recurring->getTotalRecurringsByGroup($filter_data);
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/recurring', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->data['filter_status'] = $filter_status;
        $this->data['filter_date_added_start'] = $filter_date_added_start;
        $this->data['filter_date_added_end'] = $filter_date_added_end;
        $this->data['filter_group'] = $filter_group;

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('report/recurring'));
    }
}