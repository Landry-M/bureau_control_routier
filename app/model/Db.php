<?php


namespace Model;


use ORM;
use PDO;

class Db
{

    protected function getConnexion()
    {
        try {
            ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            ORM::configure('mysql:host=mysql;dbname=control_routier');
            ORM::configure('username', 'root');
            ORM::configure('password', 'secret');
            // Rendre les erreurs explicites et activer le logging Idiorm
            ORM::configure('error_mode', PDO::ERRMODE_EXCEPTION);
            ORM::configure('logging', true);

            // Établir et tester la connexion
            $db = ORM::get_db();
            $dbName = $db->query('SELECT DATABASE()')->fetchColumn();
            error_log('[DB] Base courante: ' . $dbName);
            
            // Diagnostics de schéma
            $hasConducteur = $db->query("SHOW TABLES LIKE 'conducteur_vehicule'")->rowCount();
            $hasContrav = $db->query("SHOW TABLES LIKE 'contraventions'")->rowCount();
            $hasAccidents = $db->query("SHOW TABLES LIKE 'accidents'")->rowCount();
            error_log('[DB] Table conducteur_vehicule: ' . ($hasConducteur ? 'OK' : 'ABSENTE'));
            error_log('[DB] Table contraventions: ' . ($hasContrav ? 'OK' : 'ABSENTE'));
            error_log('[DB] Table accidents: ' . ($hasAccidents ? 'OK' : 'ABSENTE'));
            
            return $db;
        } catch (\Exception $e) {
            error_log('[DB] Erreur connexion/diagnostic: ' . $e->getMessage());
            throw new \Exception('Erreur de connexion à la base de données: ' . $e->getMessage());
        }
    }
}