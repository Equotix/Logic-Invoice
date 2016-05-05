<?php
defined('_PATH') or die('Restricted!');

class ControllerCommonLogout extends Controller {
    public function index() {
        $this->user->logout();

        unset($this->session->data['token']);

        $this->session->destroy();

        $this->response->redirect($this->url->link('common/login', '', true));
    }
}