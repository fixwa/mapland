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
}
