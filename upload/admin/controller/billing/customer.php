<?php
defined('_PATH') or die('Restricted!');

class ControllerBillingCustomer extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('billing/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/datetimepicker/moment.js');
        $this->document->addScript('view/javascript/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('view/javascript/datetimepicker/bootstrap-datetimepicker.min.css');

        $url = $this->build->url(array(
            'filter_name',
            'filter_email',
            'filter_status',
            'filter_date_added',
            'filter_date_modified',
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
            'href' => $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = '';
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = '';
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = '';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        $filter_data = array(
            'filter_name'          => $filter_name,
            'filter_email'         => $filter_email,
            'filter_status'        => $filter_status,
            'filter_date_added'    => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'start'                => $this->config->get('config_limit_admin') * ($page - 1),
            'limit'                => $this->config->get('config_limit_admin'),
            'sort'                 => $sort,
            'order'                => $order
        );

        $this->load->model('billing/customer');

        $this->data['customers'] = array();

        $customers = $this->model_billing_customer->getCustomers($filter_data);

        foreach ($customers as $customer) {
            $this->data['customers'][] = array(
                'customer_id'   => $customer['customer_id'],
                'name'          => $customer['name'],
                'email'         => $customer['email'],
                'credit'        => $this->currency->format($customer['credit'], $this->config->get('config_currency')),
                'invoice'       => $this->currency->format($customer['invoice'], $this->config->get('config_currency')),
                'status'        => $customer['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'date_added'    => date($this->language->get('datetime_format_short'), strtotime($customer['date_added'])),
                'date_modified' => date($this->language->get('datetime_format_short'), strtotime($customer['date_modified'])),
                'edit'          => $this->url->link('billing/customer/form', 'token=' . $this->session->data['token'] . $url . '&customer_id=' . $customer['customer_id'], true),
                'login'         => $this->url->link('billing/customer/login', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer['customer_id'], true)
            );
        }

        $url = $this->build->url(array(
            'filter_name',
            'filter_email',
            'filter_status',
            'filter_date_added',
            'filter_date_modified',
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_billing_customer->getTotalCustomers($filter_data);
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('billing/customer/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('billing/customer/form', 'token=' . $this->session->data['token'], true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

        $url = $this->build->url(array(
            'filter_name',
            'filter_email',
            'filter_status',
            'filter_date_added',
            'filter_date_modified'
        ));

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_name'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url . '&sort=name&order=' . $order, true);
        $this->data['sort_email'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url . '&sort=email&order=' . $order, true);
        $this->data['sort_credit'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url . '&sort=credit&order=' . $order, true);
        $this->data['sort_invoice'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url . '&sort=invoice&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url . '&sort=status&order=' . $order, true);
        $this->data['sort_date_added'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url . '&sort=date_added&order=' . $order, true);
        $this->data['sort_date_modified'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url . '&sort=date_modified&order=' . $order, true);

        $this->data['filter_name'] = $filter_name;
        $this->data['filter_email'] = $filter_email;
        $this->data['filter_status'] = $filter_status;
        $this->data['filter_date_added'] = $filter_date_added;
        $this->data['filter_date_modified'] = $filter_date_modified;

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('billing/customer_list'));
    }

    public function delete() {
        $this->load->language('billing/customer');

        $this->load->model('billing/customer');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $customer_id) {
                $this->model_billing_customer->deleteCustomer($customer_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('billing/customer', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('billing/customer');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
            'filter_name',
            'filter_email',
            'filter_status',
            'filter_date_added',
            'filter_date_modified',
            'sort',
            'order',
            'page',
            'customer_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('billing/customer', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('billing/customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['customer_id'])) {
                $this->model_billing_customer->editCustomer((int)$this->request->get['customer_id'], $this->request->post);
            } else {
                $this->model_billing_customer->addCustomer($this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['customer_id'])) {
            $customer_info = $this->model_billing_customer->getCustomer((int)$this->request->get['customer_id']);
        } else {
            $customer_info = array();
        }

        $this->data['action'] = $this->url->link('billing/customer/form', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['cancel'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_firstname'] = $this->build->data('firstname', $this->error);
        $this->data['error_lastname'] = $this->build->data('lastname', $this->error);
        $this->data['error_email'] = $this->build->data('email', $this->error);
        $this->data['error_password'] = $this->build->data('password', $this->error);
        $this->data['error_confirm'] = $this->build->data('confirm', $this->error);

        if (isset($this->request->get['customer_id'])) {
            $this->data['customer_id'] = (int)$this->request->get['customer_id'];;
        } else {
            $this->data['customer_id'] = false;
        }

        $this->data['firstname'] = $this->build->data('firstname', $this->request->post, $customer_info);
        $this->data['lastname'] = $this->build->data('lastname', $this->request->post, $customer_info);
        $this->data['company'] = $this->build->data('company', $this->request->post, $customer_info);
        $this->data['website'] = $this->build->data('website', $this->request->post, $customer_info);
        $this->data['email'] = $this->build->data('email', $this->request->post, $customer_info);
        $this->data['password'] = $this->build->data('password', $this->request->post);
        $this->data['confirm'] = $this->build->data('confirm', $this->request->post);
        $this->data['status'] = $this->build->data('status', $this->request->post, $customer_info, '1');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('billing/customer_form'));
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            $filter_data = array(
                'filter_name'  => $filter_name,
                'filter_email' => $filter_email,
                'start'        => 0,
                'limit'        => $this->config->get('config_limit_admin')
            );

            $this->load->model('billing/customer');

            $customers = $this->model_billing_customer->getCustomers($filter_data);

            foreach ($customers as $customer) {
                $json[] = $customer;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function credit() {
        $json = $this->load->language('billing/customer');;

        $this->load->model('billing/customer');

        if (isset($this->request->get['customer_id'])) {
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCredit()) {
                $this->model_billing_customer->addCredit((int)$this->request->get['customer_id'], $this->request->post);

                $json['success'] = $this->language->get('text_success');
            }

            if (!isset($this->request->get['page'])) {
                $page = 1;
            } else {
                $page = (int)$this->request->get['page'];
            }

            $json['credit'] = sprintf($this->language->get('text_credit'), $this->currency->format($this->model_billing_customer->getCustomerTotalCredits((int)$this->request->get['customer_id']), $this->config->get('config_currency')));

            $credits = $this->model_billing_customer->getCreditsByCustomer((int)$this->request->get['customer_id'], ($page - 1) * $this->config->get('config_limit_admin'), $this->config->get('config_limit_admin'));

            $json['credits'] = array();

            foreach ($credits as $credit) {
                $json['credits'][] = array(
                    'amount'      => $this->currency->format($credit['amount'], $this->config->get('config_currency')),
                    'description' => $credit['description'],
                    'date_added'  => date($this->language->get('datetime_format_short'), strtotime($credit['date_added']))
                );
            }

            $pagination = new Pagination();
            $pagination->total = $this->model_billing_customer->getTotalCreditsByCustomer((int)$this->request->get['customer_id']);
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_limit_admin');
            $pagination->url = '{page}';

            $json['pagination'] = $pagination->render();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function ip() {
        $json = $this->load->language('billing/customer');

        $this->load->model('billing/customer');

        if (isset($this->request->get['customer_id'])) {
            if (!isset($this->request->get['page'])) {
                $page = 1;
            } else {
                $page = (int)$this->request->get['page'];
            }

            $ips = $this->model_billing_customer->getIPsByCustomer((int)$this->request->get['customer_id'], ($page - 1) * $this->config->get('config_limit_admin'), $this->config->get('config_limit_admin'));

            $json['ips'] = array();

            foreach ($ips as $ip) {
                $json['ips'][] = array(
                    'ip'         => $ip['ip'],
                    'date_added' => date($this->language->get('datetime_format_short'), strtotime($ip['date_added']))
                );
            }

            $pagination = new Pagination();
            $pagination->total = $this->model_billing_customer->getTotalIPsByCustomer((int)$this->request->get['customer_id']);
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_limit_admin');
            $pagination->url = $this->url->link('billing/customer/ip', 'token=' . $this->session->data['token'] . '&customer_id=' . (int)$this->request->get['customer_id'] . '&page={page}', true);

            $json['pagination'] = $pagination->render();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'billing/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function login() {
        $json = array();

        if (isset($this->request->get['customer_id'])) {
            $customer_id = $this->request->get['customer_id'];
        } else {
            $customer_id = 0;
        }

        $this->load->model('billing/customer');

        $customer_info = $this->model_billing_customer->getCustomer($customer_id);

        if ($customer_info) {
            $token = sha1(uniqid(mt_rand(), true));

            $this->model_billing_customer->editToken($customer_id, $token);

            $this->response->redirect(HTTP_APPLICATION . 'index.php?load=account/login&token=' . $token);
        } else {
            return new Action('error/not_found');
        }
    }

    protected function validateCredit() {
        if (!$this->user->hasPermission('modify', 'billing/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'billing/customer')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $customer_info = $this->model_billing_customer->getCustomerByEmail($this->request->post['email']);

        if (isset($this->request->get['customer_id'])) {
            if ($customer_info && ($this->request->get['customer_id'] != $customer_info['customer_id'])) {
                $this->error['email'] = $this->language->get('error_exists');
            }
        } else {
            if ($customer_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ($this->request->post['password'] || !isset($this->request->get['customer_id'])) {
            if ((utf8_strlen($this->request->post['password']) < 6) || (utf8_strlen($this->request->post['password']) > 25)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}