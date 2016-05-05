<?php
defined('_PATH') or die('Restricted!');

class ControllerExtensionModule extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('extension/module');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
        );

        $this->data['success'] = $this->build->data('success', $this->session->data);
        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        $this->load->model('extension/extension');

        $extensions = $this->model_extension_extension->getInstalled('module');

        foreach ($extensions as $key => $value) {
            if (!file_exists(DIR_EXTENSION . 'module/' . $value . '/controller/' . $value . '.php')) {
                $this->model_extension_extension->uninstall('module', $value);

                unset($extensions[$key]);
            }
        }

        $this->data['extensions'] = array();

        $files = glob(DIR_EXTENSION . 'module/*', GLOB_ONLYDIR);

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file);

                $this->load->language('module/' . $extension . '/' . $extension);

                $xml = simplexml_load_file(DIR_EXTENSION . 'module/' . $extension . '/details.xml');

                $this->data['extensions'][] = array(
                    'name'      => $this->language->get('heading_title'),
                    'author'    => $xml->author,
                    'url'       => $xml->url,
                    'version'   => $xml->version,
                    'email'     => $xml->email,
                    'edit'      => $this->url->link('module/' . $extension . '/' . $extension, 'token=' . $this->session->data['token'], true),
                    'install'   => $this->url->link('extension/module/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'uninstall' => $this->url->link('extension/module/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, true),
                    'installed' => in_array($extension, $extensions)
                );
            }
        }

        $this->data['token'] = $this->session->data['token'];

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('extension/module'));
    }

    public function install() {
        $this->load->language('extension/module');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->install('module', $this->request->get['extension']);

            $this->load->model('system/user_group');

            $this->model_system_user_group->addPermission($this->user->getId(), 'access', 'module/' . $this->request->get['extension']);
            $this->model_system_user_group->addPermission($this->user->getId(), 'modify', 'module/' . $this->request->get['extension']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
        }

        $this->index();
    }

    public function uninstall() {
        $this->load->language('extension/module');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->uninstall('module', $this->request->get['extension']);

            $this->load->model('system/setting');

            $this->model_system_setting->deleteSetting($this->request->get['extension']);

            $this->load->model('system/user_group');

            $this->model_system_user_group->removePermission($this->user->getId(), 'access', 'module/' . $this->request->get['extension']);
            $this->model_system_user_group->removePermission($this->user->getId(), 'modify', 'module/' . $this->request->get['extension']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
        }

        $this->getList();
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}