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
		$parts = explode('/', str_replace('../', '', (string)$template));
		
		if (isset($parts[0]) && ($parts[0] == 'module' || $parts[0] == 'payment' || $parts[0] == 'total')) {
			if (isset($parts[1])) {
				$file = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/view/template/';
				
				array_shift($parts);
				
				array_shift($parts);
				
				$file .= implode('/', $parts);
			} else {
				trigger_error('Error: Could not load template ' . $file . '!');
				exit();
			}
		} else {
			$file = DIR_TEMPLATE . $template;
		}

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