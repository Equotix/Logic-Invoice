<?php
defined('_PATH') or die('Restricted!');

class ControllerModuleContactFormContactForm extends Controller {
    private $error = array();

    public function index() {
        if (!$this->config->get('contact_form_status')) {
            return new Action('error/not_found');
        }

        $this->data = $this->load->language('module/contact_form/contact_form');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/contact_form/contact_form')
        );

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $mail = new Mail($this->config->get('config_mail'));
            $mail->setFrom($this->request->post['email']);
            $mail->setSender($this->request->post['name']);
            $mail->setSubject(html_entity_decode(sprintf($this->language->get('text_enquiry'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8'));

            $emails = explode(',', $this->config->get('contact_form_receiving_email'));

            foreach ($emails as $email) {
                if (trim($email)) {
                    $mail->setTo(trim($email));
                    $mail->send();
                }
            }

            $this->response->redirect($this->url->link('module/contact_form/contact_form/success'));
        }

        $this->data['action'] = $this->url->link('module/contact_form/contact_form');

        $description = $this->config->get('contact_form_description');

        $this->data['description'] = html_entity_decode($description[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_email'] = $this->build->data('email', $this->error);
        $this->data['error_message'] = $this->build->data('message', $this->error);
        $this->data['error_captcha'] = $this->build->data('captcha', $this->error);

        $this->data['name'] = $this->build->data('name', $this->request->post);
        $this->data['email'] = $this->build->data('email', $this->request->post);
        $this->data['message'] = $this->build->data('message', $this->request->post);
        $this->data['captcha'] = $this->build->data('captcha', $this->request->post);

        $this->data['captcha_image'] = $this->url->link('tool/captcha');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('module/contact_form/contact_form'));
    }

    public function success() {
        if (!$this->config->get('contact_form_status')) {
            return new Action('error/not_found');
        }

        $this->data = $this->load->language('module/contact_form/contact_form');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/contact_form/contact_form')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/contact_form/contact_form/success')
        );

        $this->data['home'] = $this->url->link('common/home');

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('module/contact_form/contact_form_success'));
    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (!isset($this->request->post['captcha']) || !isset($this->session->data['captcha']) || $this->request->post['captcha'] != $this->session->data['captcha']) {
            $this->error['captcha'] = $this->language->get('error_captcha');
        }

        if ((utf8_strlen($this->request->post['message']) < 20) || (utf8_strlen($this->request->post['message']) > 2500)) {
            $this->error['message'] = $this->language->get('error_message');
        }

        return !$this->error;
    }
}