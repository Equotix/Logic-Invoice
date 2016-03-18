<?php
abstract class Controller {
    protected $registry;
    protected $data;
	protected $config;

    public function __construct($registry) {
        $this->registry = $registry;
		$this->config = $registry->get('config');
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
                if (_FRONT) {
					$file_theme = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/view/theme/' . $this->config->get('config_theme') . '/template/';
					
                    $file_default = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/view/theme/default/template/';
                } else {
                    $file = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/view/template/';
                }

                array_shift($parts);

                array_shift($parts);

				if (_FRONT) {
					$file_theme .= implode('/', $parts) . '.tpl';
					
					$file_default .= implode('/', $parts) . '.tpl';
					
					if (file_exists($file_theme)) {
						$file = $file_theme;
					} else {
						$file = $file_default;
					}
				} else {
					$file .= implode('/', $parts) . '.tpl';
				}
            } else {
                trigger_error('Error: Could not load template ' . $file . '!');
                exit();
            }
        } else {
			if (_FRONT) {
				$file_theme = DIR_TEMPLATE . 'theme/' . $this->config->get('config_theme') . '/template/' . $template . '.tpl';
				
				$file_default = DIR_TEMPLATE . 'theme/default/template/' . $template . '.tpl';
				
				if (file_exists($file_theme)) {
					$file = $file_theme;
				} else {
					$file = $file_default;
				}
			} else {
				$file = DIR_TEMPLATE . 'template/' . $template . '.tpl';
			}
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