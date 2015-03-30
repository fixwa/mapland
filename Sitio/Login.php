<?php
namespace Sitio;

use System\BaseController;

class Login extends BaseController
{
    /**
     * Muestra la Pagina
     */
    public function index()
    {
        $this->checkLogin();
    }

    public function logout()
    {
        @session_start();
        session_unset();
        session_destroy();
        header('Location: /');
        exit();
    }

    /**
     *
     */
    private function checkLogin()
    {
        if (!empty($_POST))
        {
            $user = \ORM::forTable('users')
                ->where_raw('(`email` = ? AND `password` = ?)', array($_POST['email'], $_POST['password']))
                ->findOne();

            if (false !== $user) {
                session_start();
                $_SESSION['id']   = $user->id;
                $_SESSION['name'] = $user->name;
                $_SESSION['email'] = $user->email;

                $_SESSION['flashMessage'] = 'Conectado al sistema.';

                header('Location: /panel');
                exit();
            } else {
                $_SESSION['flashMessage'] = 'No se puede iniciar su sesión.';
            }
        }

        $this->render('Login/form.phtml');
    }
}
