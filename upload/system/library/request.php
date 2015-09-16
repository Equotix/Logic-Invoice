<?php
class Request {
	public $cookie = array();
	public $files = array();
    public $get = array();
    public $post = array();
    public $request = array();
    public $server = array();
	public $session = array();

    public function __construct() {
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
        $this->get = $this->clean($_GET);
        $this->post = $this->clean($_POST);
        $this->request = $this->clean($_REQUEST);
        $this->server = $this->clean($_SERVER);
		$this->session = $this->clean($_SESSION);
    }

    private function clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars(trim($data), ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }
	
	public function cookie($key = false) {
		if ($key) {
			return isset($this->cookie[$key]) ? $this->cookie[$key] : false;
		} else {
			return $this->cookie;
		}
	}
	
	public function files($key = false) {
		if ($key) {
			return isset($this->files[$key]) ? $this->files[$key] : false;
		} else {
			return $this->files;
		}
	}
	
	public function get($key = false) {
		if ($key) {
			return isset($this->get[$key]) ? $this->get[$key] : false;
		} else {
			return $this->request->get;
		}
	}
	
	public function post($key = false) {
		if ($key) {
			return isset($this->post[$key]) ? $this->post[$key] : false;
		} else {
			return $this->request->post;
		}
	}
	
	public function request($key = false) {
		if ($key) {
		return isset($this->request[$key]) ? $this->request[$key] : false;
		} else {
			return $this->request;
		}
	}
	
	public function server($key = false) {
		if ($key) {
			return isset($this->server[$key]) ? $this->server[$key] : false;
		} else {
			return $this->server;
		}
	}
	
	public function session($key = false) {
		if ($key) {
			return isset($this->session[$key]) ? $this->session[$key] : false;
		} else {
			return $this->session;
		}
	}
}