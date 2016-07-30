<?php
defined('_PATH') or die('Restricted!');

class ControllerSystemSetting extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('system/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/summernote/summernote.min.js');
        $this->document->addStyle('view/javascript/summernote/summernote.css');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('system/setting', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('system/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_system_setting->editSetting('config', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->data['action'] = $this->url->link('system/setting', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('system/setting', 'token=' . $this->session->data['token'], true);

        $this->data['success'] = $this->build->data('success', $this->session->data);

        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['error_name'] = $this->build->data('name', $this->error);
        $this->data['error_registered_name'] = $this->build->data('registered_name', $this->error);
        $this->data['error_email'] = $this->build->data('email', $this->error);
        $this->data['error_meta_title'] = $this->build->data('meta_title', $this->error);
        $this->data['error_limit_admin'] = $this->build->data('limit_admin', $this->error);
        $this->data['error_limit_application'] = $this->build->data('limit_application', $this->error);
        $this->data['error_error_log_filename'] = $this->build->data('error_log_filename', $this->error);

        $setting = $this->model_system_setting->getSetting('config');

        $this->data['config_name'] = $this->build->data('config_name', $this->request->post, $setting);
        $this->data['config_registered_name'] = $this->build->data('config_registered_name', $this->request->post, $setting);
        $this->data['config_address'] = $this->build->data('config_address', $this->request->post, $setting);
        $this->data['config_email'] = $this->build->data('config_email', $this->request->post, $setting);
        $this->data['config_telephone'] = $this->build->data('config_telephone', $this->request->post, $setting);
        $this->data['config_fax'] = $this->build->data('config_fax', $this->request->post, $setting);
        $this->data['config_theme'] = $this->build->data('config_theme', $this->request->post, $setting);

        $this->load->model('tool/image');

        $this->data['config_logo'] = $this->build->data('config_logo', $this->request->post, $setting);

        $this->data['config_logo_thumb'] = $this->model_tool_image->resize($this->data['config_logo'], 100, 100);

        $this->data['config_icon'] = $this->build->data('config_icon', $this->request->post, $setting);

        $this->data['config_icon_thumb'] = $this->model_tool_image->resize($this->data['config_icon'], 100, 100);

        $this->data['config_limit_admin'] = $this->build->data('config_limit_admin', $this->request->post, $setting);
        $this->data['config_limit_application'] = $this->build->data('config_limit_application', $this->request->post, $setting);
        $this->data['config_admin_language'] = $this->build->data('config_admin_language', $this->request->post, $setting);
        $this->data['config_language'] = $this->build->data('config_language', $this->request->post, $setting);
        $this->data['config_forgotten_admin'] = $this->build->data('config_forgotten_admin', $this->request->post, $setting);
        $this->data['config_forgotten_application'] = $this->build->data('config_forgotten_application', $this->request->post, $setting);
        $this->data['config_registration'] = $this->build->data('config_registration', $this->request->post, $setting);
        $this->data['config_meta_title'] = $this->build->data('config_meta_title', $this->request->post, $setting, array());
        $this->data['config_meta_description'] = $this->build->data('config_meta_description', $this->request->post, $setting, array());
        $this->data['config_home'] = $this->build->data('config_home', $this->request->post, $setting);
        $this->data['config_currency'] = $this->build->data('config_currency', $this->request->post, $setting);
        $this->data['config_financial_year'] = $this->build->data('config_financial_year', $this->request->post, $setting);
        $this->data['config_auto_update_currency'] = $this->build->data('config_auto_update_currency', $this->request->post, $setting);
        $this->data['config_invoice_prefix'] = $this->build->data('config_invoice_prefix', $this->request->post, $setting);
        $this->data['config_invoice_void_days'] = $this->build->data('config_invoice_void_days', $this->request->post, $setting);
        $this->data['config_draft_status'] = $this->build->data('config_draft_status', $this->request->post, $setting, array());
        $this->data['config_overdue_status'] = $this->build->data('config_overdue_status', $this->request->post, $setting, array());
        $this->data['config_paid_status'] = $this->build->data('config_paid_status', $this->request->post, $setting, array());
        $this->data['config_pending_status'] = $this->build->data('config_pending_status', $this->request->post, $setting, array());
        $this->data['config_void_status'] = $this->build->data('config_void_status', $this->request->post, $setting, array());
        $this->data['config_default_overdue_status'] = $this->build->data('config_default_overdue_status', $this->request->post, $setting, array());
        $this->data['config_default_void_status'] = $this->build->data('config_default_void_status', $this->request->post, $setting, array());
        $this->data['config_recurring_invoice_days'] = $this->build->data('config_recurring_invoice_days', $this->request->post, $setting);
        $this->data['config_recurring_disable_days'] = $this->build->data('config_recurring_disable_days', $this->request->post, $setting);
        $this->data['config_recurring_default_status'] = $this->build->data('config_recurring_default_status', $this->request->post, $setting);
        $this->data['config_mail'] = $this->build->data('config_mail', $this->request->post, $setting);
        $this->data['config_mail_alert'] = $this->build->data('config_mail_alert', $this->request->post, $setting);
        $this->data['config_secure'] = $this->build->data('config_secure', $this->request->post, $setting);
        $this->data['config_seo_url'] = $this->build->data('config_seo_url', $this->request->post, $setting);
        $this->data['config_maintenance'] = $this->build->data('config_maintenance', $this->request->post, $setting);
        $this->data['config_compression'] = $this->build->data('config_compression', $this->request->post, $setting);
        $this->data['config_cache'] = $this->build->data('config_cache', $this->request->post, $setting);
        $this->data['config_error_display'] = $this->build->data('config_error_display', $this->request->post, $setting);
        $this->data['config_error_log'] = $this->build->data('config_error_log', $this->request->post, $setting);
        $this->data['config_error_filename'] = $this->build->data('config_error_filename', $this->request->post, $setting);
        $this->data['config_cron_user_id'] = $this->build->data('config_cron_user_id', $this->request->post, $setting);
        $this->data['config_google_analytics'] = $this->build->data('config_google_analytics', $this->request->post, $setting);

        $this->data['placeholder'] = $this->model_tool_image->resize('placeholder.png', 100, 100);

        $this->load->model('system/language');

        $this->data['languages'] = $this->model_system_language->getLanguages();

        $this->data['themes'] = array();

        $files = glob(DIR_APPLICATION . '../application/view/theme/*', GLOB_ONLYDIR);

        if ($files) {
            foreach ($files as $file) {
                $this->data['themes'][] = basename($file);
            }
        }

        $this->load->model('accounting/currency');

        $this->data['currencies'] = $this->model_accounting_currency->getCurrencies();

        $this->load->model('accounting/account');

        $this->data['accounts'] = $this->model_accounting_account->getAccounts();

        $this->load->model('system/status');

        $this->data['statuses'] = $this->model_system_status->getStatuses();

        $this->load->model('system/user');

        $this->data['users'] = $this->model_system_user->getUsers();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('system/setting'));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'system/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['config_name']) < 3) || (utf8_strlen($this->request->post['config_name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['config_registered_name']) < 3) || (utf8_strlen($this->request->post['config_registered_name']) > 64)) {
            $this->error['registered_name'] = $this->language->get('error_registered_name');
        }

        foreach ($this->request->post['config_meta_title'] as $language_id => $meta_title) {
            if ((utf8_strlen($meta_title) < 3) || (utf8_strlen($meta_title) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if ((utf8_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (!$this->request->post['config_error_filename']) {
            $this->error['error_filename'] = $this->language->get('error_error_log_filename');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}