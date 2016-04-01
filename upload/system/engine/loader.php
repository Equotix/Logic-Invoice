<?php
final class Loader {
    private $registry;
	private $config;

    public function __construct($registry) {
        $this->registry = $registry;
		
		$this->config = $registry->get('config');
    }

    public function controller($route, $args = array()) {
        $action = new Action($route, $args);

        return $action->execute($this->registry);
    }

    public function model($model) {
        $parts = explode('/', str_replace('../', '', (string)$model));

        if (isset($parts[0]) && ($parts[0] == 'module' || $parts[0] == 'payment' || $parts[0] == 'total')) {
            if (isset($parts[1])) {
                $file = DIR_EXTENSION . $parts[0] . '/' . $parts[1] . '/model/';

                array_shift($parts);

                array_shift($parts);

                $file .= implode('/', $parts) . '.php';
                $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
            } else {
                trigger_error('Error: Could not load model ' . $file . '!');
                exit();
            }
        } else {
            $file = DIR_APPLICATION . 'model/' . $model . '.php';
            $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
        }

        if (file_exists($file)) {
            include_once($file);

            $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
        } else {
            trigger_error('Error: Could not load model ' . $file . '!');
            exit();
        }
    }

    public function view($template, $data = array()) {
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
            extract($data);

            ob_start();

            require($file);

            $output = ob_get_clean();

            return $output;
        } else {
            trigger_error('Error: Could not load template ' . $file . '!');
            exit();
        }
    }

    public function library($library) {
        $file = DIR_SYSTEM . 'library/' . $library . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load library ' . $file . '!');
            exit();
        }
    }

    public function helper($helper) {
        $file = DIR_SYSTEM . 'helper/' . $helper . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load helper ' . $file . '!');
            exit();
        }
    }

    public function config($config) {
        $this->registry->get('config')->load($config);
    }

    public function language($language) {
        return $this->registry->get('language')->load($language);
    }
}