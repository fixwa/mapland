<?php
namespace System;

class View
{
    public $vars = [];

    protected $useView = true;

    /**
     * @var BaseController
     */
    protected $controller;

    public function __construct(BaseController $controller)
    {
        $this->controller = $controller;
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }

    public function disableView()
    {
        $this->useView = false;
    }


    public function render()
    {
        if ($this->useView === false) { return; }

        $params = $this->controller->request()->paramsNamed();

        if (isset($params->class)) {
            $action = 'index';
            $class = ucfirst($params->class);
            if (isset($params->action)) {
                $action = strtolower($params->action);
            }

            $file = __DIR__ . '/../Views/' .$class . '/' . $action . '.phtml';

            if (file_exists($file)) {
                extract($this->vars);
                ob_start();
                include $file;
                $content = ob_get_clean();
            } else {
                die('View file not found: ' . $file);
            }


            if (!empty($content)) {
                header('Content-Type: text/html; charset=utf-8');

                include __DIR__ . '/../Views/Layout.phtml';
//                if (!empty($this->layout)) {
//                    include $this->layout;
//                } else {
//                    include $content;
//                }

            }
        }

    }
}
