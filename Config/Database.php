<?php
namespace Config;

use ORM;

class Database
{
    public function __construct()
    {
        //ORM::configure('sqlite:./example.db');
        ORM::configure('mysql:host=localhost;dbname=mapa1');
        ORM::configure('username', 'root');
        ORM::configure('password', 'root');
        ORM::configure('driver_options', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
}

