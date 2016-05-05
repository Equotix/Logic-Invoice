<?php
defined('_PATH') or die('Restricted!');

class ControllerReportInvoice extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('report/invoice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $url = $this->build->url(array(
            'filter_status_id',
            'filter_date_issued_start',
            'filter_date_issued_end',
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
            'href' => $this->url->link('report/invoice', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['filter_status_id'])) {
            $filter_status_id = $this->request->get['filter_status_id'];
        } else {
            $filter_status_id = '';
        }

        if (isset($this->request->get['filter_date_issued_start'])) {
            $filter_date_issued_start = $this->request->get['filter_date_issued_start'];
        } else {
            $filter_date_issued_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
        }

        if (isset($this->request->get['filter_date_issued_end'])) {
            $filter_date_issued_end = $this->request->get['filter_date_issued_end'];
        } else {
            $filter_date_issued_end = date('Y-m-d');
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
            'filter_status_id',
            'filter_date_issued_start',
            'filter_date_issued_end',
            'filter_group',
            'page'
        ));

        $filter_data = array(
            'filter_status_id'         => $filter_status_id,
            'filter_date_issued_start' => $filter_date_issued_start,
            'filter_date_issued_end'   => $filter_date_issued_end,
            'filter_group'             => $filter_group,
            'start'                    => $this->config->get('config_limit_admin') * ($page - 1),
            'limit'                    => $this->config->get('config_limit_admin'),
        );

        $this->load->model('report/invoice');

        $this->data['invoices'] = array();

        $invoices = $this->model_report_invoice->getInvoicesByGroup($filter_data);

        foreach ($invoices as $invoice) {
            $this->data['invoices'][] = array(
                'date_start' => date($this->language->get('date_format_short'), strtotime($invoice['date_start'])),
                'date_end'   => date($this->language->get('date_format_short'), strtotime($invoice['date_end'])),
                'invoices'   => $invoice['invoices'],
                'items'      => $invoice['items'],
                'tax'        => $this->currency->format($invoice['tax'], $this->config->get('config_currency')),
                'total'      => $this->currency->format($invoice['total'], $this->config->get('config_currency'))
            );
        }

        $this->data['token'] = $this->session->data['token'];

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

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
        $pagination->total = $this->model_report_invoice->getTotalInvoicesByGroup($filter_data);
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->data['filter_status_id'] = $filter_status_id;
        $this->data['filter_date_issued_start'] = $filter_date_issued_start;
        $this->data['filter_date_issued_end'] = $filter_date_issued_end;
        $this->data['filter_group'] = $filter_group;

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('report/invoice'));
    }
}