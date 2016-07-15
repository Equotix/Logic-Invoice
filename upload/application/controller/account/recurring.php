<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountRecurring extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/recurring', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/recurring');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/recurring', '', true)
        );

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->load->model('billing/recurring');

        $filter_data = array(
            'start' => $this->config->get('config_limit_application') * ($page - 1),
            'limit' => $this->config->get('config_limit_application')
        );

        $recurrings = $this->model_billing_recurring->getRecurrings($filter_data);

        $this->data['recurrings'] = array();

        foreach ($recurrings as $recurring) {
            $this->data['recurrings'][] = array(
                'recurring_id' => $recurring['recurring_id'],
                'total'        => $this->currency->format($recurring['total'], $recurring['currency_code'], $recurring['currency_value']),
                'status'       => $recurring['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'cycle'        => $this->language->get('text_' . $recurring['cycle']),
                'date_due'     => $recurring['status'] ? date($this->language->get('date_format_short'), strtotime($recurring['date_due'])) : $this->language->get('text_disabled'),
                'date_added'   => date($this->language->get('date_format_short'), strtotime($recurring['date_added'])),
                'view'         => $this->url->link('account/recurring/view', 'recurring_id=' . $recurring['recurring_id'], true)
            );
        }

        $pagination = new Pagination();
        $pagination->total = $this->model_billing_recurring->getTotalRecurrings();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_application');
        $pagination->url = $this->url->link('account/recurring', 'page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/recurring_list'));
    }

    public function view() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/recurring', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/recurring');

        $this->load->model('billing/recurring');

        $recurring_info = $this->model_billing_recurring->getRecurring((int)$this->request->get['recurring_id'], $this->customer->getId());

        if ($recurring_info) {
            $this->document->setTitle($this->language->get('text_view_recurring'));

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/recurring', '', true)
            );

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_view_recurring'),
                'href' => $this->url->link('account/recurring/view', 'recurring_id=' . $recurring_info['recurring_id'], true)
            );

            $this->data['recurring_id'] = $recurring_info['recurring_id'];
            $this->data['payment_name'] = $recurring_info['payment_name'];
            $this->data['payment_description'] = $recurring_info['payment_description'];
            $this->data['recurring_status'] = $recurring_info['status'];
            $this->data['status'] = $recurring_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled');
            $this->data['cycle'] = $this->language->get('text_' . $recurring_info['cycle']);
            $this->data['date_due'] = $recurring_info['status'] ? date($this->language->get('date_format_short'), strtotime($recurring_info['date_due'])) : $this->language->get('text_disabled');
            $this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($recurring_info['date_added']));

            $this->data['cancel'] = $this->url->link('account/recurring/cancel', 'recurring_id=' . $recurring_info['recurring_id'], true);

            $items = $recurring_info['items'];

            $this->data['items'] = array();

            $number = 1;

            foreach ($items as $item) {
                $this->data['items'][] = array(
                    'number'      => $number,
                    'title'       => html_entity_decode($item['title'], ENT_QUOTES),
                    'description' => html_entity_decode(nl2br($item['description']), ENT_QUOTES),
                    'quantity'    => $item['quantity'],
                    'price'       => $this->currency->format($item['price'], $recurring_info['currency_code'], $recurring_info['currency_value']),
                    'discount'    => (float)$item['discount'] ? $this->currency->format($item['discount'], $recurring_info['currency_code'], $recurring_info['currency_value']) : '-',
                    'total'       => $this->currency->format(($item['price'] - $item['discount']) * $item['quantity'], $recurring_info['currency_code'], $recurring_info['currency_value'])
                );

                $number++;
            }

            $totals = $recurring_info['totals'];

            $this->data['totals'] = array();

            foreach ($totals as $total) {
                $this->data['totals'][] = array(
                    'title' => html_entity_decode($total['title'], ENT_QUOTES),
                    'value' => $this->currency->format($total['value'], $recurring_info['currency_code'], $recurring_info['currency_value'])
                );
            }

            $this->data['header'] = $this->load->controller('common/header');
            $this->data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->render('account/recurring_view'));
        } else {
            return new Action('error/not_found');
        }
    }

    public function cancel() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/recurring', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        if (isset($this->request->get['recurring_id'])) {
            $this->load->model('billing/recurring');

            $recurring_info = $this->model_billing_recurring->getRecurring((int)$this->request->get['recurring_id'], $this->customer->getId());

            if ($recurring_info) {
                $this->model_billing_recurring->cancelRecurring($this->request->get['recurring_id']);
            } else {
                return new Action('error/not_found');
            }
        }

        $this->response->redirect($this->url->link('account/recurring'));
    }
}