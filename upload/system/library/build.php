<?php
class Build {
    public function __construct($registry) {
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');
    }

    public function data($key, $primary = array(), $secondary = array(), $default = '') {
        if (isset($primary[$key])) {
            $data = $primary[$key];

            if ($primary === $this->session->data) {
                unset($this->session->data[$key]);
            }
        } elseif (isset($secondary[$key])) {
            $data = $secondary[$key];

            if ($secondary === $this->session->data) {
                unset($this->session->data[$key]);
            }
        } else {
            $data = $default;
        }

        return $data;
    }

    public function url($keys) {
        $data = $this->request->get;

        foreach ($data as $key => $value) {
            if (!in_array($key, $keys)) {
                unset($data[$key]);
            }
        }

        $url = http_build_query($data);

        if ($url) {
            $url = '&' . $url;
        }

        return $url;
    }
}