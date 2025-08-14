<?php


namespace Control;


use Model\Db;
use ORM;

class GeneralController extends Db
{

    public function test_db_connextion()
    {
        $this->getConnexion();
        $user = ORM::for_table('users')->find_array();

        $result['state'] = true;
        $result['message'] = 'Connexion à la base de données réussi';

        return json_encode($result);

    }

}