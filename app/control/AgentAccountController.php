<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;

class AgentAccountController extends Db {
    
    public function create($data) {
        $result = [
            'state' => false,
            'message' => 'Une erreur est survenue lors de la création du compte',
            'data' => null
        ];
        
        $this->getConnexion();

        try {
            // Debug: Vérifier la structure de la table users
            $tableInfo = ORM::get_db()->query("DESCRIBE users")->fetchAll();
            error_log('[AgentAccountController] Structure table users: ' . json_encode($tableInfo));
            
            // Validation des données
            if (empty($data['nom']) || empty($data['matricule']) || empty($data['poste']) || 
                empty($data['role']) || empty($data['password'])) {
                $result['message'] = 'Tous les champs obligatoires doivent être remplis';
                return $result;
            }
            
            // Vérifier que les mots de passe correspondent
            if (isset($data['password_confirm']) && $data['password'] !== $data['password_confirm']) {
                $result['message'] = 'Les mots de passe ne correspondent pas';
                return $result;
            }
            
            // Vérifier si le matricule existe déjà
            $existingAgent = ORM::for_table('users')
                ->where('matricule', $data['matricule'])
                ->find_one();
                
            if ($existingAgent) {
                $result['message'] = 'Un agent avec ce matricule existe déjà';
                return $result;
            }
            
            // Validation du rôle
            $allowedRoles = ['opj', 'admin', 'superadmin'];
            if (!in_array($data['role'], $allowedRoles)) {
                $result['message'] = 'Rôle non valide';
                return $result;
            }
            
            // Validation du mot de passe
            if (strlen($data['password']) < 6) {
                $result['message'] = 'Le mot de passe doit contenir au moins 6 caractères';
                return $result;
            }
            
            // Démarrer une transaction
            $this->getConnexion();
            
            // Debug: Log des données reçues
            error_log('[AgentAccountController] Données reçues: ' . json_encode($data));
            
            // Création de l'enregistrement agent
            $agent = ORM::for_table('users')->create();
            
            // Données de l'agent
            $agent->username = trim($data['nom']);
            $agent->matricule = trim($data['matricule']);
            $agent->poste = trim($data['poste']);
            $agent->telephone = !empty($data['telephone']) ? trim($data['telephone']) : null;
            $agent->role = $data['role'];
            $agent->password = md5($data['password']);
            $agent->created_at = date('Y-m-d H:i:s');
            $agent->status = 'active';
            $agent->first_connexion = 'yes'; // Ajouter ce champ manquant
            
            // Optionnel: planning de connexion (login_schedule)
            if (isset($data['schedule']) && is_array($data['schedule'])) {
                $daysMap = [
                    'mon' => 'Lundi',
                    'tue' => 'Mardi',
                    'wed' => 'Mercredi',
                    'thu' => 'Jeudi',
                    'fri' => 'Vendredi',
                    'sat' => 'Samedi',
                    'sun' => 'Dimanche',
                ];
                $normalized = [];
                foreach ($daysMap as $key => $label) {
                    $enabled = isset($data['schedule'][$key]['enabled']) && (string)$data['schedule'][$key]['enabled'] === '1';
                    $start = $data['schedule'][$key]['start'] ?? '00:00';
                    $end   = $data['schedule'][$key]['end'] ?? '23:59';
                    // Validation très simple du format HH:MM
                    if (!preg_match('/^\d{2}:\d{2}$/', $start)) { $start = '00:00'; }
                    if (!preg_match('/^\d{2}:\d{2}$/', $end))   { $end   = '23:59'; }
                    $normalized[$key] = [
                        'enabled' => $enabled,
                        'start' => $start,
                        'end' => $end,
                    ];
                }
                // Stocker en JSON si la colonne existe
                try {
                    $agent->login_schedule = json_encode($normalized, JSON_UNESCAPED_UNICODE);
                } catch (Exception $e) {
                    // si la colonne n'existe pas, ignorer silencieusement
                    error_log('[AgentAccountController] login_schedule non enregistré: ' . $e->getMessage());
                }
            }
            
            // Debug: Log avant sauvegarde
            error_log('[AgentAccountController] Tentative de sauvegarde agent: ' . $agent->username);
            
            // Vérifier si la table users existe
            try {
                $tableExists = ORM::get_db()->query("SHOW TABLES LIKE 'users'")->rowCount();
                error_log('[AgentAccountController] Table users existe: ' . ($tableExists ? 'OUI' : 'NON'));
                
                if (!$tableExists) {
                    $result['message'] = "La table 'users' n'existe pas dans la base de données.";
                    return $result;
                }
            } catch (Exception $e) {
                error_log('[AgentAccountController] Erreur vérification table: ' . $e->getMessage());
            }
            
            $saveResult = $agent->save();
            error_log('[AgentAccountController] Résultat save(): ' . ($saveResult ? 'SUCCESS' : 'FAILED'));
            
            if (!$saveResult) {
                $lastQuery = ORM::get_last_query();
                $dbError = ORM::get_db()->errorInfo();
                error_log('[AgentAccountController] Dernière requête: ' . $lastQuery);
                error_log('[AgentAccountController] Erreur DB: ' . json_encode($dbError));
                $result['message'] = "Erreur lors de l'enregistrement de l'agent. Vérifiez que la table 'users' existe et a les bonnes colonnes.";
                return $result;
            }
            
            error_log('[AgentAccountController] Agent sauvegardé avec succès, ID: ' . $agent->id());
            
            // Récupérer l'ID de l'agent créé
            $agentId = $agent->id();
            
            // Logger la création de l'agent
            try {
                $activityLogger = new ActivityLogger();
                $activityLogger->logCreate(null, 'users', $agentId, [
                    'nom' => $data['nom'],
                    'matricule' => $data['matricule'],
                    'role' => $data['role'],
                    'poste' => $data['poste']
                ]);
            } catch (Exception $e) {
                error_log('Erreur lors du logging de création d\'agent: ' . $e->getMessage());
            }
            
            // Enregistrement réussi
            $result['state'] = true;
            $result['data'] = [
                'id' => $agentId,
                'nom' => $data['nom'],
                'matricule' => $data['matricule'],
                'role' => $data['role']
            ];
            $result['message'] = 'Compte agent créé avec succès pour ' . $data['nom'];
            
            return $result;
            
        } catch (Exception $e) {
            $result['state'] = false;
            $result['message'] = 'Une erreur est survenue lors de la création du compte. Veuillez réessayer. ' . $e->getMessage();
            
            return $result;
        }
    }
    
    /**
     * Récupérer tous les agents
     */
    public function getAllAgents() {
        try {
            $this->getConnexion();
            
            $agents = ORM::for_table('users')
                ->select('id')
                ->select('nom')
                ->select('matricule')
                ->select('poste')
                ->select('role')
                ->select('status')
                ->select('created_at')
                ->order_by_desc('created_at')
                ->find_many();
            
            $agentsArray = $agents ? $agents->as_array() : [];
            
            // Logger la consultation de la liste des agents
            try {
                $activityLogger = new ActivityLogger();
                $activityLogger->logView(null, 'agents_list', 'Consultation de la liste des agents (' . count($agentsArray) . ' résultats)');
            } catch (Exception $e) {
                error_log('Erreur lors du logging de consultation des agents: ' . $e->getMessage());
            }
            
            return $agentsArray;
            
        } catch (Exception $e) {
            error_log('Erreur dans AgentAccountController::getAllAgents: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer un agent par son ID
     */
    public function getAgentById($id) {
        try {
            $this->getConnexion();
            
            $agent = ORM::for_table('users')
                ->select('id')
                ->select('nom')
                ->select('matricule')
                ->select('poste')
                ->select('role')
                ->select('status')
                ->select('created_at')
                ->where('id', $id)
                ->find_one();
            
            return $agent ? $agent->as_array() : null;
            
        } catch (Exception $e) {
            error_log('Erreur dans AgentAccountController::getAgentById: ' . $e->getMessage());
            return null;
        }
    }
}
