<?php
abstract class Controller {
    protected $registry;
    protected $data;

    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }

    public function render($template) {
        $file = DIR_TEMPLATE . $template;

        if (file_exists($file)) {
            extract($this->data);

            ob_start();

            require($file);

            $output = ob_get_clean();

            return $output;
        } else {
            trigger_error('Error: Could not load template ' . $file . '!');
            exit();
        }
    }
}