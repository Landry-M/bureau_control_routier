<?php


namespace Model;


use ORM;
use PDO;

class Db
{

    protected function getConnexion()
    {
//        $db = $this->setConnexion();
//        return $db;

        ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        ORM::configure('mysql:host=mysql;dbname=control_routier');
        ORM::configure('username', 'root');
        ORM::configure('password', 'secret');
    }
}