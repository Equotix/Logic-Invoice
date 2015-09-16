<?php
class Session {
    public $data = array();

    public function __construct() {
        $this->data =& $_SESSION;
    }

    public function getId() {
        return session_id();
    }

    public function destroy() {
        return session_destroy();
    }
}