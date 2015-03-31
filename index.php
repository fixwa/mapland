<?php
require 'vendor/autoload.php';

/* @see https://github.com/chriso/klein.php */
$klein = new \Klein\Klein();


$klein->respond('/[:class]', function (\Klein\Request $request) {

    $className = "Sitio\\". ucfirst($request->class);

    if (class_exists($className)) {
        /* @var \System\BaseController $class */
        $class = new $className();
        $class->request($request);
        return $class->index();
    }

    return;
});

$klein->respond(['POST', 'GET'], '/[:class]/[:action]/[:param]', function (\Klein\Request $request) {

    $className = "Sitio\\". ucfirst($request->class);

    if (class_exists($className)) {
        /* @var \System\BaseController $class */
        $class = new $className();
        $class->request($request);

        if (method_exists($class, $request->action)) {
            return $class->{$request->action}($request->param);
        }
    }

    return;
});

$klein->respond(['POST', 'GET'], '/[:class]/[:action]', function (\Klein\Request $request) {

    $className = "Sitio\\". ucfirst($request->class);

    if (class_exists($className)) {
        /* @var \System\BaseController $class */
        $class = new $className();
        $class->request($request);

        if (method_exists($class, $request->action)) {
            return $class->{$request->action}();
        }
    }

    return;
});

$klein->respond('GET', '/', function (\Klein\Request $request) {
    $request->class = 'Home';
    $request->action = 'index';

    $class = new Sitio\Home();
    $class->request($request);
    return $class->index();
});

$klein->dispatch();
