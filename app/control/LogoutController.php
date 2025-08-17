<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use Exception;

class LogoutController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function logout()
    {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Logger la déconnexion avant de détruire la session (laisser ActivityLogger résoudre le username)
        try {
            $this->getConnexion(); // Établir la connexion à la base de données
            // Ne pas dépendre de $_SESSION['username']; ActivityLogger utilise $_SESSION['user']['username'] si dispo
            $this->activityLogger->logLogout();
        } catch (Exception $e) {
            error_log('Erreur lors du logging de déconnexion: ' . $e->getMessage());
        }

        // Détruire la session
        session_unset();
        session_destroy();

        return [
            'state' => true,
            'message' => 'Déconnexion réussie'
        ];
    }
}
