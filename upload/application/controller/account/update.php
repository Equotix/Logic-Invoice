<?php
defined('_PATH') or die('Restricted!');

class ControllerAccountUpdate extends Controller {
    private $error = array();

    public function index() {
        $this->load->model('billing/customer');

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/update', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->data = $this->load->language('account/update');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_billing_customer->editCustomer($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->load->model('system/activity');

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_update'), $this->request->post['firstname'] . ' ' . $this->request->post['lastname']));

            $this->response->redirect($this->url->link('account/account', '', true));
        }

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
            'href' => $this->url->link('account/update', '', true)
        );

        $customer_info = $this->model_billing_customer->getCustomer($this->customer->getId());

        $this->data['action'] = $this->url->link('account/update', '', true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_firstname'] = $this->build->data('firstname', $this->error);
        $this->data['error_lastname'] = $this->build->data('lastname', $this->error);
        $this->data['error_email'] = $this->build->data('email', $this->error);

        $this->data['firstname'] = $this->build->data('firstname', $this->request->post, $customer_info);
        $this->data['lastname'] = $this->build->data('lastname', $this->request->post, $customer_info);
        $this->data['company'] = $this->build->data('company', $this->request->post, $customer_info);
        $this->data['website'] = $this->build->data('website', $this->request->post, $customer_info);
        $this->data['email'] = $this->build->data('email', $this->request->post, $customer_info);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('account/update'));
    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->request->post['email'] != $this->customer->getEmail()) {
            if ($this->model_billing_customer->getTotalCustomersByEmail($this->request->post['email'])) {
                $this->error['warning'] = $this->language->get('error_warning');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}