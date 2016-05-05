<?php
defined('_PATH') or die('Restricted!');

class ControllerContentEmailTemplate extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('content/email_template');

        $this->document->setTitle($this->language->get('heading_title'));

        $url = $this->build->url(array(
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
            'href' => $this->url->link('content/email_template', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'type';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        $filter_data = array(
            'start' => $this->config->get('config_limit_admin') * ($page - 1),
            'limit' => $this->config->get('config_limit_admin'),
            'sort'  => $sort,
            'order' => $order
        );

        $this->load->model('system/status');

        $statuses = $this->model_system_status->getStatuses();

        $this->load->model('content/email_template');

        $this->data['email_templates'] = array();

        $email_templates = $this->model_content_email_template->getEmailTemplates($filter_data);

        foreach ($email_templates as $email_template) {
            if (strpos($email_template['type'], 'status_') !== false) {
                $type = '';

                foreach ($statuses as $status) {
                    if ($email_template['type'] == 'status_' . $status['status_id']) {
                        $type = $status['name'];

                        break;
                    }
                }
            } else {
                $type = $this->language->get('type_' . $email_template['type']);
            }

            $this->data['email_templates'][] = array(
                'email_template_id' => $email_template['email_template_id'],
                'type'              => $type,
                'subject'           => $email_template['subject'],
                'priority'          => $email_template['priority'],
                'status'            => $email_template['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit'              => $this->url->link('content/email_template/form', 'token=' . $this->session->data['token'] . $url . '&email_template_id=' . $email_template['email_template_id'], true)
            );
        }

        $url = $this->build->url(array(
            'sort',
            'order'
        ));

        $pagination = new Pagination();
        $pagination->total = $this->model_content_email_template->getTotalEmailTemplates();
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('content/email_template', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

        $this->data['pagination'] = $pagination->render();

        $this->data['delete'] = $this->url->link('content/email_template/delete', 'token=' . $this->session->data['token'], true);
        $this->data['insert'] = $this->url->link('content/email_template/form', 'token=' . $this->session->data['token'], true);

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);
        $this->data['selected'] = $this->build->data('selected', $this->request->post, array(), array());

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        if ($order == 'ASC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->data['sort_type'] = $this->url->link('content/email_template', 'token=' . $this->session->data['token'] . '&sort=type&order=' . $order, true);
        $this->data['sort_subject'] = $this->url->link('content/email_template', 'token=' . $this->session->data['token'] . '&sort=subject&order=' . $order, true);
        $this->data['sort_priority'] = $this->url->link('content/email_template', 'token=' . $this->session->data['token'] . '&sort=priority&order=' . $order, true);
        $this->data['sort_status'] = $this->url->link('content/email_template', 'token=' . $this->session->data['token'] . '&sort=status&order=' . $order, true);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/email_template_list'));
    }

    public function delete() {
        $this->load->language('content/email_template');

        $this->load->model('content/email_template');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {

            foreach ($this->request->post['selected'] as $email_template_id) {
                $this->model_content_email_template->deleteEmailTemplate($email_template_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('content/email_template', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function form() {
        $this->data = $this->load->language('content/email_template');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('view/javascript/summernote/summernote.min.js');
        $this->document->addStyle('view/javascript/summernote/summernote.css');

        $url = $this->build->url(array(
            'sort',
            'order',
            'page',
            'email_template_id'
        ));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/email_template', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('content/email_template/form', 'token=' . $this->session->data['token'] . $url, true)
        );

        $this->load->model('content/email_template');
        $this->load->model('system/url_alias');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (isset($this->request->get['email_template_id'])) {
                $this->model_content_email_template->editEmailTemplate((int)$this->request->get['email_template_id'], $this->request->post);
            } else {
                $this->model_content_email_template->addEmailTemplate($this->request->post);
            }

            $url = $this->build->url(array(
                'sort',
                'order',
                'page'
            ));

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('content/email_template', 'token=' . $this->session->data['token'] . $url, true));
        }

        if (isset($this->request->get['email_template_id'])) {
            $email_template_info = $this->model_content_email_template->getEmailTemplate((int)$this->request->get['email_template_id']);
        } else {
            $email_template_info = array();
        }

        $this->data['action'] = $this->url->link('content/email_template/form', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['token'] = $this->session->data['token'];

        $url = $this->build->url(array(
            'sort',
            'order',
            'page'
        ));

        $this->data['cancel'] = $this->url->link('content/email_template', 'token=' . $this->session->data['token'] . $url, true);

        $this->data['error_warning'] = $this->build->data('warning', $this->session->data);
        $this->data['error_subject'] = $this->build->data('subject', $this->error);
        $this->data['error_html'] = $this->build->data('html', $this->error);
        $this->data['error_text'] = $this->build->data('text', $this->error);

        $this->data['type'] = $this->build->data('type', $this->request->post, $email_template_info);
        $this->data['description'] = $this->build->data('description', $this->request->post, $email_template_info, array());
        $this->data['priority'] = $this->build->data('priority', $this->request->post, $email_template_info);
        $this->data['status'] = $this->build->data('status', $this->request->post, $email_template_info, '1');
        $this->data['email'] = $this->build->data('email', $this->request->post, $email_template_info);

        $email_template_types = $this->model_content_email_template->getEmailTemplateTypes();

        $this->data['email_template_types'] = array();

        foreach ($email_template_types as $email_template_type) {
            if ($email_template_type['type'] == 'status') {
                $this->load->model('system/status');

                $statuses = $this->model_system_status->getStatuses();

                foreach ($statuses as $status) {
                    $this->data['email_template_types'][] = array(
                        'type'      => 'status_' . $status['status_id'],
                        'name'      => $status['name'],
                        'variables' => '{website_name}, {website_url}, {customer_id}, {firstname}, {lastname}, {company}, {website}, {email}, {invoice_id}, {comment}, {history_comment}, {total}, {status}, {payment_name}, {date_issued}, {date_due}, {date_modified}'
                    );
                }
            } else {
                $this->data['email_template_types'][] = array(
                    'type'      => $email_template_type['type'],
                    'name'      => $this->language->get('type_' . $email_template_type['type']),
                    'variables' => $email_template_type['variables'],
                );
            }
        }

        $this->load->model('system/language');

        $this->data['languages'] = $this->model_system_language->getLanguages();

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('content/email_template_form'));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'content/email_template')) {
            $this->session->data['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'content/email_template')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['description'] as $language_id => $value) {
            if ((utf8_strlen($value['subject']) < 3) || (utf8_strlen($value['subject']) > 255)) {
                $this->error['subject'][$language_id] = $this->language->get('error_subject');
            }

            if (utf8_strlen($value['html']) < 3) {
                $this->error['html'][$language_id] = $this->language->get('error_html');
            }

            if (utf8_strlen($value['text']) < 3) {
                $this->error['text'][$language_id] = $this->language->get('error_text');
            }
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}