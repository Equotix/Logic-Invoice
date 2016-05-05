<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonLogin extends Controller {
    private $error = array();

    public function index() {
        $this->data = $this->load->language('common/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('system/activity');

            $this->model_system_activity->addActivity(sprintf($this->language->get('text_login'), $this->user->getUsername()));

            $this->session->data['token'] = md5(mt_rand());

            if (!empty($this->request->post['redirect'])) {
                $this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
            } else {
                $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true));
            }
        }

        if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
            $this->error['warning'] = $this->language->get('error_token');
        }

        $this->data['action'] = $this->url->link('common/login', '', true);

        if ($this->config->get('config_forgotten_admin')) {
            $this->data['text_forgotten'] = sprintf($this->language->get('text_forgotten'), $this->url->link('common/forgotten', '', true));
        } else {
            $this->data['text_forgotten'] = false;
        }

        $this->data['error_warning'] = $this->build->data('warning', $this->error);

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        if (isset($this->request->get['load'])) {
            $load = $this->request->get['load'];

            unset($this->request->get['load']);

            if (isset($this->request->get['token'])) {
                unset($this->request->get['token']);
            }

            $url = '';

            if ($this->request->get) {
                $url .= http_build_query($this->request->get);
            }

            $this->data['redirect'] = $this->url->link($load, $url, true);
        } else {
            $this->data['redirect'] = '';
        }

        $this->data['username'] = $this->build->data('username', $this->request->post);
        $this->data['password'] = $this->build->data('password', $this->request->post);

        $this->data['header'] = $this->load->controller('common/header');
        $this->data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->render('common/login'));
    }

    public function check() {
        $load = '';

        if (isset($this->request->get['load'])) {
            $part = explode('/', $this->request->get['load']);

            if (isset($part[0])) {
                $load .= $part[0];
            }

            if (isset($part[1])) {
                $load .= '/' . $part[1];
            }
        }

        $ignore = array(
            'common/login',
            'common/forgotten',
            'common/reset'
        );

        if (!$this->user->isLogged() && !in_array($load, $ignore)) {
            return new Action('common/login');
        }

        if (isset($this->request->get['load'])) {
            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/permission',
                'common/reset',
                'error/not_found'
            );

            if (!in_array($load, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return new Action('common/login');
            }
        } else {
            if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return new Action('common/login');
            }
        }
    }

    protected function validate() {
        if (empty($this->request->post['username']) || empty($this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        if (!$this->error && !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        return !$this->error;
    }
}