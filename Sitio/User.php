<?php
namespace Sitio;

use Sitio\Commands\Users;
use System\BaseController;
use UnexpectedValueException;

class User extends BaseController
{
    /**
     * @var Users
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->users = new Users();
    }

    /**
     * Muestra la Pagina
     */
    public function index()
    {
        $this->register();

        echo '<hr>';

        $users = $this->users->listAll();

        foreach ($users as $user) {
            echo '<p>' . $user->name . '</p>';
        }
        echo '<hr>';
    }

    /**
     * Crea un usuario
     */
    public function register()
    {
        if (!empty($_POST)) {

            try {
                $this->users->create($_POST);
            } catch (UnexpectedValueException $e) {
                throw $e;
            }
        }
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
    public function login()
    {
        if (!empty($_POST))
        {
            $user = $this->users->getByEmailAndPassword($_POST['email'], $_POST['password']);

            if (false !== $user) {
                session_start();
                $_SESSION['id']   = $user->id;
                $_SESSION['name'] = $user->name;
                $_SESSION['email'] = $user->email;

                $_SESSION['flashMessage'] = 'Conectado al sistema.';

                $destination = '/panel';
                if (!empty($_GET['destination'])) {
                    $destination = urldecode($_GET['destination']);
                }
                header('Location: ' . $destination);
                exit();
            } else {
                $_SESSION['flashMessage'] = 'No se puede iniciar su sesión.';
            }
        }
    }
}
