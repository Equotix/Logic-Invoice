<?php
defined('_PATH') or die('Restricted!');

class ControllerExtensionTotal extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('extension/total');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], true)
        );

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        $this->load->model('extension/extension');

        $extensions = $this->model_extension_extension->getInstalled('total');

        foreach ($extensions as $key => $value) {
            if (!file_exists(DIR_EXTENSION . 'total/' . $value . '/controller/' . $value . '.php')) {
                $this->model_extension_extension->uninstall('total', $value);

                unset($extensions[$key]);
            }
        }

        $this->data['extensions'] = array();

        $files = glob(DIR_EXTENSION . 'total/*', GLOB_ONLYDIR);

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                $this->load->language('total/' . $extension . '/' . $extension);

                $xml = simplexml_load_file(DIR_EXTENSION . 'total/' . $extension . '/details.xml');

                $this->data['extensions'][] = array(
                    'name'       => $this->language->get('heading_title'),
                    'author'     => $xml->author,
                    'url'        => $xml->url,
                    'version'    => $xml->version,
                    'email'      => $xml->email,
                    'code'       => $extension,
                    'status'     => $this->config->get($extension . '_status'),
                    'sort_order' => $this->config->get($extension . '_sort_order'),
                    'edit'       => $this->url->link('total/' . $extension . '/' . $extension, 'token=' . $this->session->data['token'], true),
                    'install'    => $this->url->link('extension/total/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'uninstall'  => $this->url->link('extension/total/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'installed'  => in_array($extension, $extensions)
                );
            }
        }

        $this->data['token'] = $this->session->data['token'];

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('extension/total'));
    }

    public function install() {
        $this->load->language('extension/total');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->install('total', $this->request->get['extension']);

            $this->load->model('system/user_group');

            $this->model_system_user_group->addPermission($this->user->getId(), 'access', 'total/' . $this->request->get['extension']);
            $this->model_system_user_group->addPermission($this->user->getId(), 'modify', 'total/' . $this->request->get['extension']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function uninstall() {
        $this->load->language('extension/total');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->uninstall('total', $this->request->get['extension']);

            $this->load->model('system/setting');

            $this->model_system_setting->deleteSetting($this->request->get['extension']);

            $this->load->model('system/user_group');

            $this->model_system_user_group->removePermission($this->user->getId(), 'access', 'total/' . $this->request->get['extension']);
            $this->model_system_user_group->removePermission($this->user->getId(), 'modify', 'total/' . $this->request->get['extension']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], true));
        }

        $this->getList();
    }

    public function update() {
        $json = array();

        $extension = $this->request->post['extension'];
        $key = $this->request->post['key'];
        $value = $this->request->post['value'];

        $this->load->language('total/' . $extension);

        if (!$this->user->hasPermission('modify', 'total/' . $extension)) {
            $json['warning'] = $this->language->get('error_permission');
        } else {
            $this->load->model('system/setting');

            if ($this->model_system_setting->getSettingValue($extension, $key)) {
                $this->model_system_setting->editSettingValue($extension, $key, (int)$value);
            } else {
                $this->model_system_setting->addSettingValue($extension, $key, (int)$value);
            }

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/total')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}