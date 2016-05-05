<?php
defined('_PATH') or die('Restricted!');

class ControllerModuleContactFormContactForm extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('module/contact_form/contact_form');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/summernote/summernote.min.js');
        $this->document->addStyle('view/javascript/summernote/summernote.css');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_modules'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/contact_form/contact_form', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('contact_form', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
        }

        $this->data['action'] = $this->url->link('module/contact_form/contact_form', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_receiving_email'] = $this->build->data('receiving_email', $this->error);

        $setting = $this->model_system_setting->getSetting('contact_form');

        $this->data['contact_form_receiving_email'] = $this->build->data('contact_form_receiving_email', $this->request->post, $setting);
        $this->data['contact_form_description'] = $this->build->data('contact_form_description', $this->request->post, $setting, array());
        $this->data['contact_form_status'] = $this->build->data('contact_form_status', $this->request->post, $setting);

        $this->load->model('system/language');

        $this->data['languages'] = $this->model_system_language->getLanguages();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('module/contact_form/contact_form'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/contact_form')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['contact_form_receiving_email'])) {
            $this->error['receiving_email'] = $this->language->get('contact_form_receiving_email');
        }

        return !$this->error;
    }
}