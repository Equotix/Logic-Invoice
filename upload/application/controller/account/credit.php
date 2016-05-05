<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountCredit extends Controller {
    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/credit', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/credit');

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
            'href' => $this->url->link('account/credit', '', true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->load->model('billing/customer');

        $this->data['credit'] = sprintf($this->language->get('text_credit'), $this->currency->format($this->model_billing_customer->getCustomerTotalCredits($this->customer->getId())));

        $credits = $this->model_billing_customer->getCreditsByCustomer($this->customer->getId(), $this->config->get('config_limit_application') * ($page - 1), $this->config->get('config_limit_application'));

        $this->data['credits'] = array();

        foreach ($credits as $credit) {
            $this->data['credits'][] = array(
                'amount'      => $this->currency->format($credit['amount']),
                'description' => $credit['description'],
                'date_added'  => date($this->language->get('date_format_short'), strtotime($credit['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $this->model_billing_customer->getTotalCreditsByCustomer($this->customer->getId());
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_application');
        $pagination->url = $this->url->link('account/credit', 'page={page}', true);

        $this->data['pagination'] = $pagination->render();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/credit'));
    }
}