<?php

namespace Control;

use Model\Db;
use ORM;
use Exception;

class AgentManagementController extends Db {
    
    /**
     * Afficher la page de gestion des agents
     */
    public function index() {
        try {
            $this->getConnexion();
            
            // Récupérer tous les agents
            $agentsData = ORM::for_table('users')
                ->order_by_desc('created_at')
                ->find_array();
            
            $agents = [];
            foreach ($agentsData as $agent) {
                $agents[] = [
                    'id' => $agent['id'],
                    'username' => $agent['username'],
                    'matricule' => $agent['matricule'],
                    'poste' => $agent['poste'],
                    'telephone' => $agent['telephone'],
                    'role' => $agent['role'],
                    'status' => $agent['status'],
                    'created_at' => $agent['created_at']
                ];
            }
            
            // Inclure la vue avec les données
            include 'views/agents.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur lors du chargement des agents: ' . $e->getMessage();
            error_log("Erreur AgentManagementController::index: " . $e->getMessage());
            header('Location: /');
            exit;
        }
    }
    
    /**
     * Récupérer les détails d'un agent par ID (API JSON)
     */
    public function getAgent($agentId) {
        header('Content-Type: application/json');
        
        try {
            $this->getConnexion();
            
            $agent = ORM::for_table('users')
                ->where('id', $agentId)
                ->find_one();
            
            if (!$agent) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Agent non trouvé'
                ]);
                return;
            }
            
            echo json_encode([
                'success' => true,
                'agent' => [
                    'id' => $agent->id,
                    'username' => $agent->username,
                    'matricule' => $agent->matricule,
                    'poste' => $agent->poste,
                    'telephone' => $agent->telephone,
                    'role' => $agent->role,
                    'status' => $agent->status,
                    'created_at' => $agent->created_at
                ]
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement de l\'agent: ' . $e->getMessage()
            ]);
            error_log("Erreur AgentManagementController::getAgent: " . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour un agent
     */
    public function updateAgent() {
        header('Content-Type: application/json');
        
        try {
            $this->getConnexion();
            
            // Vérifier les permissions
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Permissions insuffisantes'
                ]);
                return;
            }
            
            $agentId = $_POST['agent_id'] ?? null;
            
            if (!$agentId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de l\'agent manquant'
                ]);
                return;
            }
            
            $agent = ORM::for_table('users')
                ->where('id', $agentId)
                ->find_one();
            
            if (!$agent) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Agent non trouvé'
                ]);
                return;
            }
            
            // Validation des données
            $errors = [];
            
            if (empty(trim($_POST['username']))) {
                $errors[] = 'Le nom d\'utilisateur est obligatoire';
            }
            
            if (empty(trim($_POST['matricule']))) {
                $errors[] = 'Le matricule est obligatoire';
            }
            
            // Vérifier l'unicité du matricule
            $existingAgent = ORM::for_table('users')
                ->where('matricule', trim($_POST['matricule']))
                ->where_not_equal('id', $agentId)
                ->find_one();
            
            if ($existingAgent) {
                $errors[] = 'Ce matricule est déjà utilisé par un autre agent';
            }
            
            // Validation du rôle
            $allowedRoles = ['opj', 'admin', 'superadmin'];
            if (!in_array($_POST['role'], $allowedRoles)) {
                $errors[] = 'Rôle non valide';
            }
            
            // Validation du statut
            $allowedStatuses = ['active', 'inactive'];
            if (!in_array($_POST['status'], $allowedStatuses)) {
                $errors[] = 'Statut non valide';
            }
            
            if (!empty($errors)) {
                echo json_encode([
                    'success' => false,
                    'message' => implode(', ', $errors)
                ]);
                return;
            }
            
            // Mettre à jour l'agent
            $agent->username = trim($_POST['username']);
            $agent->matricule = trim($_POST['matricule']);
            $agent->poste = trim($_POST['poste']) ?: null;
            $agent->role = $_POST['role'];
            $agent->status = $_POST['status'];
            $agent->updated_at = date('Y-m-d H:i:s');
            
            if ($agent->save()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Agent mis à jour avec succès'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de l\'agent'
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ]);
            error_log("Erreur AgentManagementController::updateAgent: " . $e->getMessage());
        }
    }
    
    /**
     * Activer/Désactiver un agent
     */
    public function toggleAgentStatus() {
        header('Content-Type: application/json');
        
        try {
            $this->getConnexion();
            
            // Vérifier les permissions
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Permissions insuffisantes'
                ]);
                return;
            }
            
            // Lire les données JSON
            $input = json_decode(file_get_contents('php://input'), true);
            $agentId = $input['agent_id'] ?? null;
            $newStatus = $input['status'] ?? null;
            
            if (!$agentId || !$newStatus) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Données manquantes'
                ]);
                return;
            }
            
            // Empêcher de se désactiver soi-même
            if ($agentId == $_SESSION['user']['id']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas modifier votre propre statut'
                ]);
                return;
            }
            
            $agent = ORM::for_table('users')
                ->where('id', $agentId)
                ->find_one();
            
            if (!$agent) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Agent non trouvé'
                ]);
                return;
            }
            
            // Validation du statut
            if (!in_array($newStatus, ['active', 'inactive'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Statut non valide'
                ]);
                return;
            }
            
            $agent->status = $newStatus;
            $agent->updated_at = date('Y-m-d H:i:s');
            
            if ($agent->save()) {
                $action = $newStatus === 'active' ? 'activé' : 'désactivé';
                echo json_encode([
                    'success' => true,
                    'message' => "Agent {$action} avec succès"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du statut'
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut: ' . $e->getMessage()
            ]);
            error_log("Erreur AgentManagementController::toggleAgentStatus: " . $e->getMessage());
        }
    }
    
    /**
     * Supprimer un agent (soft delete)
     */
    public function deleteAgent($agentId) {
        header('Content-Type: application/json');
        
        try {
            $this->getConnexion();
            
            // Vérifier les permissions
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Permissions insuffisantes'
                ]);
                return;
            }
            
            // Empêcher de se supprimer soi-même
            if ($agentId == $_SESSION['user']['id']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas supprimer votre propre compte'
                ]);
                return;
            }
            
            $agent = ORM::for_table('users')
                ->where('id', $agentId)
                ->find_one();
            
            if (!$agent) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Agent non trouvé'
                ]);
                return;
            }
            
            // Soft delete - marquer comme supprimé
            $agent->status = 'deleted';
            $agent->updated_at = date('Y-m-d H:i:s');
            
            if ($agent->save()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Agent supprimé avec succès'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'agent'
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
            error_log("Erreur AgentManagementController::deleteAgent: " . $e->getMessage());
        }
    }
}

?>
