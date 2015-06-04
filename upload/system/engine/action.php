<?php
final class Action {
    private $file;
    private $class;
    private $method;
    private $args = array();

    public function __construct($route, $args = array()) {
        $path = '';

        $parts = explode('/', str_replace('../', '', (string)$route));

        if (isset($parts[0]) && ($parts[0] == 'module' || $parts[0] == 'payment' || $parts[0] == 'total')) {
            if (isset($parts[1])) {
                $path = $parts[0] . '/' . $parts[1] . '/';

                $file = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/controller/';

                array_shift($parts);

                array_shift($parts);

                foreach ($parts as $part) {
                    $path .= $part;

                    if (is_dir($file . $part)) {
                        $file .= $part . '/';
                        $path .= '/';

                        array_shift($parts);

                        continue;
                    }

                    $file = str_replace(array(
                            '../',
                            '..\\',
                            '..'
                        ), '', $file . $part) . '.php';

                    if (is_file($file)) {
                        $this->file = $file;

                        $this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $path);

                        array_shift($parts);

                        break;
                    }
                }
            }
        } else {
            foreach ($parts as $part) {
                $path .= $part;

                if (is_dir(DIR_APPLICATION . 'controller/' . $path)) {
                    $path .= '/';

                    array_shift($parts);

                    continue;
                }

                $file = DIR_APPLICATION . 'controller/' . str_replace(array(
                        '../',
                        '..\\',
                        '..'
                    ), '', $path) . '.php';

                if (is_file($file)) {
                    $this->file = $file;

                    $this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $path);

                    array_shift($parts);

                    break;
                }
            }
        }

        if ($args) {
            $this->args = $args;
        }

        $method = array_shift($parts);

        if ($method) {
            $this->method = $method;
        } else {
            $this->method = 'index';
        }
    }

    public function execute($registry) {
        if (substr($this->method, 0, 2) == '__') {
            return false;
        }

        if (is_file($this->file)) {
            include_once($this->file);

            $class = $this->class;

            $controller = new $class($registry);

            if (is_callable(array(
                $controller,
                $this->method
            ))) {
                return call_user_func(array(
                    $controller,
                    $this->method
                ), $this->args);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}