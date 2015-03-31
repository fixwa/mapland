<?php
namespace Sitio\Commands;

use UnexpectedValueException;

class Users
{
    /**
     * Devuelve un array con todos los usuarios en la tabla.
     *
     * @return mixed
     */
    public function listAll()
    {
        $users = \ORM::forTable('users')->orderByDesc('name')->findMany();

        return $users;
    }

    /**
     * Crea un nuevo usuario.
     *
     * @param $data
     * @throws UnexpectedValueException
     */
    public function create($data)
    {
        $user = \ORM::forTable('users')->create();

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (strlen($name) < 5) {
            throw new UnexpectedValueException('Invalid Name.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UnexpectedValueException('Invalid E-Mail.');
        }

        if (strlen($password) < 5) {
            throw new UnexpectedValueException('Invalid Password.');
        }

        $user->name     = $name;
        $user->email    = $email;
        $user->password = $password;

        $user->save();
    }


    public function getByEmailAndPassword($email, $password)
    {
        return \ORM::forTable('users')
            ->where_raw('(`email` = ? AND `password` = ?)', array($_POST['email'], $_POST['password']))
            ->findOne();
    }
}
