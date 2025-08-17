<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;

class ProfileController extends Db{
    
    /**
     * Afficher la page de profil
     */
    public function show() {
        
        // Récupérer les informations complètes de l'utilisateur depuis la base de données
        try {
            $this->getConnexion();
            $user = ORM::for_table('users')
                ->where('id', $_SESSION['user']['id'])
                ->find_one();
            
            if ($user) {
                // Mettre à jour les informations de session avec les données les plus récentes
                $_SESSION['user'] = [
                    'id' => $user->id,
                    'username' => $user->username,
                    'matricule' => $user->matricule,
                    'poste' => $user->poste,
                    'role' => $user->role,
                    'telephone' => $user->telephone,
                    'status' => $user->status,
                    'created_at' => $user->created_at
                ];
            }
        } catch (Exception $e) {
            // En cas d'erreur, continuer avec les données de session existantes
            error_log("Erreur lors de la récupération du profil: " . $e->getMessage());
        }
        
        // Inclure la vue du profil
        include 'views/profile.php';
    }
    
    /**
     * Mettre à jour le profil utilisateur
     */
    public function update() {
      
        
        try {
            $this->getConnexion();
            // Récupérer l'utilisateur depuis la base de données
            $user = ORM::for_table('users')
                ->where('id', $_SESSION['user']['id'])
                ->find_one();
            
            if (!$user) {
                $_SESSION['error'] = 'Utilisateur non trouvé.';
                header('Location: /profile');
                exit;
            }
            
            // Validation des données
            $errors = [];
            
            // Vérifier les champs obligatoires pour tous les utilisateurs
            if (empty(trim($_POST['username']))) {
                $errors[] = 'Le nom d\'utilisateur est obligatoire.';
            }
            
            // Validation spécifique pour les superadmins
            if ($_SESSION['user']['role'] == 'superadmin') {
                if (empty(trim($_POST['matricule']))) {
                    $errors[] = 'Le matricule est obligatoire.';
                }
                
                // Vérifier l'unicité du matricule (sauf pour l'utilisateur actuel)
                if (!empty(trim($_POST['matricule']))) {
                    $existingUser = ORM::for_table('users')
                        ->where('matricule', trim($_POST['matricule']))
                        ->where_not_equal('id', $_SESSION['user']['id'])
                        ->find_one();
                    
                    if ($existingUser) {
                        $errors[] = 'Ce matricule est déjà utilisé par un autre utilisateur.';
                    }
                }
                
                // Validation du rôle
                $allowedRoles = ['opj', 'admin', 'superadmin'];
                if (!in_array($_POST['role'], $allowedRoles)) {
                    $errors[] = 'Rôle non valide.';
                }
            }
            
            // Gestion du changement de mot de passe
            $updatePassword = false;
            if (!empty($_POST['new_password']) || !empty($_POST['confirm_password'])) {
                if (empty($_POST['current_password'])) {
                    $errors[] = 'Le mot de passe actuel est requis pour changer le mot de passe.';
                } else {
                    // Vérifier le mot de passe actuel
                    if (md5($_POST['current_password']) !== $user->password) {
                        $errors[] = 'Le mot de passe actuel est incorrect.';
                    } else {
                        if ($_POST['new_password'] !== $_POST['confirm_password']) {
                            $errors[] = 'Les nouveaux mots de passe ne correspondent pas.';
                        } elseif (strlen($_POST['new_password']) < 6) {
                            $errors[] = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
                        } else {
                            $updatePassword = true;
                        }
                    }
                }
            }
            
            // S'il y a des erreurs, les afficher
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: /profile');
                exit;
            }
            
            // Mettre à jour les informations de l'utilisateur
            $user->username = trim($_POST['username']);
            $user->telephone = !empty($_POST['telephone']) ? trim($_POST['telephone']) : null;
            
            // Les champs suivants ne sont modifiables que par les superadmins
            if ($_SESSION['user']['role'] == 'superadmin') {
                $user->matricule = trim($_POST['matricule']);
                $user->poste = trim($_POST['poste']) ?: null;
                $user->role = $_POST['role'];
            }
            
            // Mettre à jour le mot de passe si nécessaire
            if ($updatePassword) {
                $user->password = md5($_POST['new_password']);
            }

            // Planning de connexion (optionnel)
            if (isset($_POST['schedule']) && is_array($_POST['schedule'])) {
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
                    $enabled = isset($_POST['schedule'][$key]['enabled']) && (string)$_POST['schedule'][$key]['enabled'] === '1';
                    $start = $_POST['schedule'][$key]['start'] ?? '00:00';
                    $end   = $_POST['schedule'][$key]['end'] ?? '23:59';
                    if (!preg_match('/^\d{2}:\d{2}$/', (string)$start)) { $start = '00:00'; }
                    if (!preg_match('/^\d{2}:\d{2}$/', (string)$end))   { $end   = '23:59'; }
                    $normalized[$key] = [
                        'enabled' => $enabled,
                        'start' => $start,
                        'end' => $end,
                    ];
                }
                try {
                    $user->login_schedule = json_encode($normalized, JSON_UNESCAPED_UNICODE);
                } catch (Exception $e) {
                    // Si la colonne n'existe pas encore, ignorer silencieusement; la migration l'ajoutera
                    error_log('[ProfileController] login_schedule non enregistré: ' . $e->getMessage());
                }
            }

            $user->updated_at = date('Y-m-d H:i:s');
            
            // Sauvegarder les modifications
            if ($user->save()) {
                // Mettre à jour les informations de session
                $_SESSION['user']['username'] = $user->username;
                $_SESSION['user']['telephone'] = $user->telephone;
                
                // Mettre à jour les champs superadmin seulement si c'est un superadmin
                if ($_SESSION['user']['role'] == 'superadmin') {
                    $_SESSION['user']['matricule'] = $user->matricule;
                    $_SESSION['user']['poste'] = $user->poste;
                    $_SESSION['user']['role'] = $user->role;
                }
                
                $_SESSION['success'] = 'Profil mis à jour avec succès.';
                
                // Si le mot de passe a été changé, ajouter un message spécifique
                if ($updatePassword) {
                    $_SESSION['success'] .= ' Votre mot de passe a également été modifié.';
                }

                // Logger la mise à jour du profil
                try {
                    $logger = new ActivityLogger();
                    $logger->logUpdate(
                        $_SESSION['user']['username'] ?? null,
                        'users',
                        $_SESSION['user']['id'] ?? null,
                        null,
                        [
                            'username' => $user->username,
                            'telephone' => $user->telephone,
                            // Inclure les champs superadmin si applicables
                            'matricule' => $_SESSION['user']['role'] == 'superadmin' ? $user->matricule : null,
                            'poste' => $_SESSION['user']['role'] == 'superadmin' ? $user->poste : null,
                            'role' => $_SESSION['user']['role'] == 'superadmin' ? $user->role : null,
                            'password_changed' => $updatePassword,
                            'login_schedule_updated' => isset($_POST['schedule']) && is_array($_POST['schedule'])
                        ]
                    );
                } catch (Exception $e) {
                    error_log("[ProfileController] Erreur lors du logging d'update profil: " . $e->getMessage());
                }
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour du profil.';
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur lors de la mise à jour du profil: ' . $e->getMessage();
            error_log("Erreur ProfileController::update: " . $e->getMessage());
        }
        
        header('Location: /profile');
        exit;
    }
    
    /**
     * Récupérer les informations d'un utilisateur par ID
     */
    public function getUserById($id) {
        try {
            $user = ORM::for_table('users')
                ->where('id', $id)
                ->find_one();
            
            if ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'matricule' => $user->matricule,
                    'poste' => $user->poste,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Erreur ProfileController::getUserById: " . $e->getMessage());
            return null;
        }
    }
}

?>
