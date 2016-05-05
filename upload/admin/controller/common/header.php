<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonHeader extends Controller {
    public function index() {
        $this->data = $this->load->language('common/header');

        $this->data['title'] = $this->document->getTitle();
        $this->data['styles'] = $this->document->getStyles();
        $this->data['scripts'] = $this->document->getScripts();

        $this->data['direction'] = $this->language->get('direction');
        $this->data['language_code'] = $this->language->get('language_code');

        if ($this->request->server['HTTPS']) {
            $this->data['base'] = HTTPS_SERVER;
            $this->data['application'] = HTTPS_APPLICATION;
        } else {
            $this->data['base'] = HTTP_SERVER;
            $this->data['application'] = HTTP_APPLICATION;
        }

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->data['logged'] = true;

            $this->data['dashboard'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token']);
            $this->data['account'] = $this->url->link('accounting/account', 'token=' . $this->session->data['token'], true);
            $this->data['currency'] = $this->url->link('accounting/currency', 'token=' . $this->session->data['token'], true);
            $this->data['inventory'] = $this->url->link('accounting/inventory', 'token=' . $this->session->data['token'], true);
            $this->data['journal'] = $this->url->link('accounting/journal', 'token=' . $this->session->data['token'], true);
            $this->data['tax_class'] = $this->url->link('accounting/tax_class', 'token=' . $this->session->data['token'], true);
            $this->data['tax_rate'] = $this->url->link('accounting/tax_rate', 'token=' . $this->session->data['token'], true);
            $this->data['customer'] = $this->url->link('billing/customer', 'token=' . $this->session->data['token'], true);
            $this->data['invoice'] = $this->url->link('billing/invoice', 'token=' . $this->session->data['token'], true);
            $this->data['recurring'] = $this->url->link('billing/recurring', 'token=' . $this->session->data['token'], true);
            $this->data['article'] = $this->url->link('content/article', 'token=' . $this->session->data['token'], true);
            $this->data['blog_category'] = $this->url->link('content/blog_category', 'token=' . $this->session->data['token'], true);
            $this->data['blog_post'] = $this->url->link('content/blog_post', 'token=' . $this->session->data['token'], true);
            $this->data['email_template'] = $this->url->link('content/email_template', 'token=' . $this->session->data['token'], true);
            $this->data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);
            $this->data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);
            $this->data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], true);
            $this->data['report_recurring'] = $this->url->link('report/recurring', 'token=' . $this->session->data['token'], true);
            $this->data['report_invoice'] = $this->url->link('report/invoice', 'token=' . $this->session->data['token'], true);
            $this->data['chart_of_accounts'] = $this->url->link('report/chart_of_accounts', 'token=' . $this->session->data['token'], true);
            $this->data['sci'] = $this->url->link('report/sci', 'token=' . $this->session->data['token'], true);
            $this->data['sfp'] = $this->url->link('report/sfp', 'token=' . $this->session->data['token'], true);
            $this->data['language'] = $this->url->link('system/language', 'token=' . $this->session->data['token'], true);
            $this->data['activity'] = $this->url->link('system/activity', 'token=' . $this->session->data['token'], true);
            $this->data['error'] = $this->url->link('system/error', 'token=' . $this->session->data['token'], true);
            $this->data['setting'] = $this->url->link('system/setting', 'token=' . $this->session->data['token'], true);
            $this->data['status'] = $this->url->link('system/status', 'token=' . $this->session->data['token'], true);
            $this->data['user'] = $this->url->link('system/user', 'token=' . $this->session->data['token'], true);
            $this->data['user_group'] = $this->url->link('system/user_group', 'token=' . $this->session->data['token'], true);

            $this->data['username'] = $this->user->getUsername();
            $this->data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], true);
            $this->data['website'] = HTTP_APPLICATION;
        } else {
            $this->data['logged'] = false;
        }

        return $this->render('common/header');
    }
}