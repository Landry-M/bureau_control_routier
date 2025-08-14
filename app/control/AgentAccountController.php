<?php

namespace Control;

use Model\Db;
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
            // Validation des données
            if (empty($data['nom']) || empty($data['matricule']) || empty($data['poste']) || 
                empty($data['role']) || empty($data['password'])) {
                $result['message'] = 'Tous les champs obligatoires doivent être remplis';
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
            
            if (!$agent->save()) {
                $result['message'] = "Erreur lors de l'enregistrement de l'agent: " . $agent->error;
                return $result;
            }
            
            // Récupérer l'ID de l'agent créé
            $agentId = $agent->id();
            
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
            
            return $agents ? $agents->as_array() : [];
            
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
