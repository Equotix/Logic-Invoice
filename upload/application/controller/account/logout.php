<?php
class ControllerAccountLogout extends Controller {
    public function index() {
        $this->customer->logout();

        $this->session->destroy();

        $this->response->redirect($this->url->link('common/home'));
    }
}