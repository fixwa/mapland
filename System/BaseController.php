<?php
namespace System;

use Config\Database;
use Klein\Request;

class BaseController
{
    /**
     * @var bool
     */
    protected $renderCalled = false;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var View
     */
    public $view;

    public function __construct()
    {
        $db = new Database();

        $this->view = new View($this);

//        $this->render('Layout.phtml');
//        $this->renderCalled = false;
    }

    public function __destruct()
    {

        $this->view->render();

//        if (!$this->renderCalled) {
//
//            $params = $this->request()->paramsNamed();
//
//            if (isset($params->class)) {
//                $action = 'index';
//                $class = ucfirst($params->class);
//                if (isset($params->action)) {
//                    $action = strtolower($params->action);
//                }
//
//                $file = $class . '/' . $action . '.phtml';
//
//                if (file_exists(__DIR__ . '/../Views/' . $file)) {
//                    $this->render($file);
//                }
//            }
//
//        }
//
//        $this->render('footer.phtml');
    }

    protected function requireLogin()
    {
        $destination = urlencode($this->request->uri());

        //@session_start();
        if (empty($_SESSION['id'])) {
            header('Location: /user/login?destination=' . $destination);
            exit();
        }
    }

    /**
     * Get/Set the Request.
     *
     * @param Request $request
     * @return Request
     */
    public function request(Request $request = null)
    {
        if (!is_null($request)) {
            $this->request = $request;
        }
        return $this->request;
    }

    /**
     * Renders a PHTML template.
     *
     * @param $file
     */
    protected function render1($file)
    {
        $this->renderCalled = true;
        include __DIR__ . '/../Views/' . $file;
    }
}
