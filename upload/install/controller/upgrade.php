<?php
class ControllerUpgrade extends Controller {
    public function index() {
        $this->data = $this->load->language('default');
    }
}