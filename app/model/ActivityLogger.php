<?php

namespace Model;

use ORM;

class ActivityLogger extends Db
{
    public function __construct()
    {
        $this->getConnexion();
    }

    /**
     * Enregistrer une activité utilisateur
     */
    public function logActivity($action, $username = null, $details = null, $ipAddress = null, $userAgent = null)
    {
        try {
            // Obtenir le username depuis la session si non fourni
            if ($username === null) {
                $username = $_SESSION['user']['username'] ?? 'Anonyme';
            }

            // Obtenir l'adresse IP si non fournie
            if ($ipAddress === null) {
                $ipAddress = $this->getClientIP();
            }

            // Obtenir le user agent si non fourni
            if ($userAgent === null) {
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            }

            $activity = ORM::for_table('activites')->create();
            $activity->username = $username ?? 'Anonyme'; // Utiliser la colonne username
            $activity->action = $action ?? '';
            $activity->details_operation = $details ?? '';
            $activity->ip_address = $ipAddress ?? 'Anonyme';
            $activity->user_agent = $userAgent ?? '';
            $activity->date_creation = date('Y-m-d H:i:s');
            
            return $activity->save();
        } catch (\Exception $e) {
            error_log('[ActivityLogger] Erreur lors de l\'enregistrement: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Logger une connexion réussie
     */
    public function logLogin($username = null)
    {
        $details = $username ? "Connexion réussie pour l'utilisateur: {$username}" : "Connexion réussie";
        return $this->logActivity('CONNEXION_REUSSIE', $username, $details);
    }

    /**
     * Logger une tentative de connexion échouée
     */
    public function logLoginFailed($username, $reason = 'Identifiants incorrects')
    {
        $details = "Tentative de connexion échouée pour: {$username}. Raison: {$reason}";
        // Utiliser le username pour les connexions échouées
        return $this->logActivity('CONNEXION_ECHOUEE', $username, $details);
    }

    /**
     * Logger une déconnexion
     */
    public function logLogout($username = null)
    {
        $details = $username ? "Déconnexion de l'utilisateur: {$username}" : "Déconnexion";
        return $this->logActivity('DECONNEXION', $username, $details);
    }

    /**
     * Logger une création d'enregistrement
     */
    public function logCreate($username = null, $table, $recordId, $data = null)
    {
        $details = "Création d'un nouvel enregistrement dans la table '{$table}' (ID: {$recordId})";
        if ($data) {
            $details .= ". Données: " . json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return $this->logActivity('CREATION_' . strtoupper($table), $username, $details);
    }

    /**
     * Logger une modification d'enregistrement
     */
    public function logUpdate($username = null, $table, $recordId, $oldData = null, $newData = null)
    {
        $details = "Modification d'un enregistrement dans la table '{$table}' (ID: {$recordId})";
        if ($oldData && $newData) {
            $details .= ". Anciennes données: " . json_encode($oldData, JSON_UNESCAPED_UNICODE);
            $details .= ". Nouvelles données: " . json_encode($newData, JSON_UNESCAPED_UNICODE);
        }
        return $this->logActivity('MODIFICATION_' . strtoupper($table), $username, $details);
    }

    /**
     * Logger une suppression d'enregistrement
     */
    public function logDelete($username = null, $table, $recordId, $data = null)
    {
        $details = "Suppression d'un enregistrement dans la table '{$table}' (ID: {$recordId})";
        if ($data) {
            $details .= ". Données supprimées: " . json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return $this->logActivity('SUPPRESSION_' . strtoupper($table), $username, $details);
    }

    /**
     * Logger une recherche
     */
    public function logSearch($username = null, $searchType, $criteria, $resultsCount = null)
    {
        $details = "Recherche effectuée - Type: {$searchType}, Critères: " . json_encode($criteria, JSON_UNESCAPED_UNICODE);
        if ($resultsCount !== null) {
            $details .= ", Résultats trouvés: {$resultsCount}";
        }
        return $this->logActivity('RECHERCHE_' . strtoupper($searchType), $username, $details);
    }

    /**
     * Logger un listing/consultation
     */
    public function logView($username = null, $viewType, $details = null)
    {
        $detailsStr = "Consultation - Type: {$viewType}";
        if ($details) {
            $detailsStr .= ". Détails: " . (is_array($details) ? json_encode($details, JSON_UNESCAPED_UNICODE) : $details);
        }
        return $this->logActivity('CONSULTATION_' . strtoupper($viewType), $username, $detailsStr);
    }

    /**
     * Logger une exportation
     */
    public function logExport($username = null, $exportType, $format, $recordsCount = null)
    {
        $details = "Exportation - Type: {$exportType}, Format: {$format}";
        if ($recordsCount !== null) {
            $details .= ", Nombre d'enregistrements: {$recordsCount}";
        }
        return $this->logActivity('EXPORTATION_' . strtoupper($exportType), $username, $details);
    }

    /**
     * Logger une importation
     */
    public function logImport($username = null, $importType, $fileName, $recordsCount = null)
    {
        $details = "Importation - Type: {$importType}, Fichier: {$fileName}";
        if ($recordsCount !== null) {
            $details .= ", Nombre d'enregistrements: {$recordsCount}";
        }
        return $this->logActivity('IMPORTATION_' . strtoupper($importType), $username, $details);
    }

    /**
     * Obtenir l'adresse IP du client
     */
    private function getClientIP()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Anonyme';
    }

    /**
     * Obtenir les activités d'un utilisateur
     */
    public function getUserActivities($username, $limit = 50, $offset = 0)
    {
        try {
            return ORM::for_table('activites')
                ->where('username', $username)
                ->order_by_desc('date_creation')
                ->limit($limit)
                ->offset($offset)
                ->find_array();
        } catch (\Exception $e) {
            error_log('[ActivityLogger] Erreur lors de la récupération des activités: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtenir toutes les activités avec pagination
     */
    public function getAllActivities($limit = 50, $offset = 0, $filters = [])
    {
        try {
            $query = ORM::for_table('activites');
            
            if (isset($filters['username'])) {
                $query->where('username', $filters['username']);
            }
            
            if (isset($filters['action'])) {
                $query->where_like('action', '%' . $filters['action'] . '%');
            }
            
            if (isset($filters['date_from'])) {
                $query->where_gte('date_creation', $filters['date_from']);
            }
            
            if (isset($filters['date_to'])) {
                $query->where_lte('date_creation', $filters['date_to']);
            }
            
            return $query->order_by_desc('date_creation')
                ->limit($limit)
                ->offset($offset)
                ->find_array();
        } catch (\Exception $e) {
            error_log('[ActivityLogger] Erreur lors de la récupération des activités: ' . $e->getMessage());
            return [];
        }
    }
}
