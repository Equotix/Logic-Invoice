<?php
class Language {
    private $default = 'en-gb';
    private $directory;
    private $data = array();

    public function __construct($directory = '') {
        $this->directory = $directory;
    }

    public function get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : $key);
    }

    public function load($language) {
        $_ = array();

        $parts = explode('/', str_replace('../', '', (string)$language));

        if (isset($parts[0]) && ($parts[0] == 'module' || $parts[0] == 'payment' || $parts[0] == 'total')) {
            if (isset($parts[1])) {
                $default = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/language/' . $this->default . '/';
                $file = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/language/' . $this->directory . '/';

                array_shift($parts);

                array_shift($parts);

                $default .= implode('/', $parts) . '.php';
                $file .= implode('/', $parts) . '.php';
            }
        } else {
            $default = DIR_LANGUAGE . $this->default . '/' . $language . '.php';
            $file = DIR_LANGUAGE . $this->directory . '/' . $language . '.php';
        }

        if (file_exists($default)) {
            require($default);
        }

        if (file_exists($file)) {
            require($file);
        }

        $this->data = array_merge($this->data, $_);

        return $this->data;
    }
}
