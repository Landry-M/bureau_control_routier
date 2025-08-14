<?php

namespace Control;

use Model\Db;
use ORM;
use Exception;

class UsersController extends Db
{
    /**
     * Connexion utilisateur
     */
    public function login($username, $pass)
    {
        $this->getConnexion();
        $user = ORM::for_table('users')
            ->where(array(
                'username' => $username,
                'password' => md5($pass)
            ))
            ->find_array();

        if($user){
            // Vérifier le statut du compte
            if($user[0]['status'] === 'inactive') {
                $result['state'] = false;
                $result['message'] = 'Votre compte est désactivé. Veuillez contacter l\'administrateur pour réactiver votre compte.';
            } else {
                $result['state'] = true;
                $result['data'] = $user;
            }
        }else{
            $result['state'] = false;
            $result['message'] = 'Coordonnées de connexion incorrectes';
        }
        //return an array
        $result = (array)$result;
        return $result;
    }

    /**
     * Créer un nouveau profil utilisateur
     */
    public function createProfile($data)
    {
        try {
            $this->getConnexion();
            
            // Vérifier si l'email existe déjà
            $existingUser = ORM::for_table('users')
                ->where('email', $data['email'])
                ->find_one();
                
            if ($existingUser) {
                return json_encode([
                    'state' => false,
                    'message' => 'Un utilisateur avec cet email existe déjà'
                ]);
            }
            
            // Créer le nouvel utilisateur
            $user = ORM::for_table('users')->create();
            $user->nom = $data['nom'] ?? '';
            $user->prenom = $data['prenom'] ?? '';
            $user->email = $data['email'];
            $user->password = md5($data['password']);
            $user->telephone = $data['telephone'] ?? '';
            $user->adresse = $data['adresse'] ?? '';
            $user->date_creation = date('Y-m-d H:i:s');
            $user->statut = $data['statut'] ?? 'actif';
            
            if ($user->save()) {
                return json_encode([
                    'state' => true,
                    'message' => 'Profil utilisateur créé avec succès',
                    'data' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'nom' => $user->nom,
                        'prenom' => $user->prenom
                    ]
                ]);
            } else {
                return json_encode([
                    'state' => false,
                    'message' => 'Erreur lors de la création du profil'
                ]);
            }
            
        } catch (Exception $e) {
            return json_encode([
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupérer le profil d'un utilisateur
     */
    public function getProfile($userId)
    {
        try {
            $this->getConnexion();
            
            $user = ORM::for_table('users')
                ->where('id', $userId)
                ->find_one();
                
            if ($user) {
                // Ne pas retourner le mot de passe
                $userData = [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'adresse' => $user->adresse,
                    'date_creation' => $user->date_creation,
                    'statut' => $user->statut
                ];
                
                return json_encode([
                    'state' => true,
                    'data' => $userData
                ]);
            } else {
                return json_encode([
                    'state' => false,
                    'message' => 'Utilisateur non trouvé'
                ]);
            }
            
        } catch (Exception $e) {
            return json_encode([
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile($userId, $data)
    {
        try {
            $this->getConnexion();
            
            $user = ORM::for_table('users')
                ->where('id', $userId)
                ->find_one();
                
            if (!$user) {
                return json_encode([
                    'state' => false,
                    'message' => 'Utilisateur non trouvé'
                ]);
            }
            
            // Vérifier si l'email est déjà utilisé par un autre utilisateur
            if (isset($data['email']) && $data['email'] !== $user->email) {
                $existingUser = ORM::for_table('users')
                    ->where('email', $data['email'])
                    ->where_not_equal('id', $userId)
                    ->find_one();
                    
                if ($existingUser) {
                    return json_encode([
                        'state' => false,
                        'message' => 'Cet email est déjà utilisé par un autre utilisateur'
                    ]);
                }
            }
            
            // Mettre à jour les champs
            if (isset($data['nom'])) $user->nom = $data['nom'];
            if (isset($data['prenom'])) $user->prenom = $data['prenom'];
            if (isset($data['email'])) $user->email = $data['email'];
            if (isset($data['telephone'])) $user->telephone = $data['telephone'];
            if (isset($data['adresse'])) $user->adresse = $data['adresse'];
            if (isset($data['statut'])) $user->statut = $data['statut'];
            
            // Mettre à jour le mot de passe si fourni
            if (isset($data['password']) && !empty($data['password'])) {
                $user->password = md5($data['password']);
            }
            
            $user->date_modification = date('Y-m-d H:i:s');
            
            if ($user->save()) {
                return json_encode([
                    'state' => true,
                    'message' => 'Profil mis à jour avec succès',
                    'data' => [
                        'id' => $user->id,
                        'nom' => $user->nom,
                        'prenom' => $user->prenom,
                        'email' => $user->email,
                        'telephone' => $user->telephone,
                        'adresse' => $user->adresse
                    ]
                ]);
            } else {
                return json_encode([
                    'state' => false,
                    'message' => 'Erreur lors de la mise à jour du profil'
                ]);
            }
            
        } catch (Exception $e) {
            return json_encode([
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Supprimer le profil utilisateur
     */
    public function deleteProfile($userId)
    {
        try {
            $this->getConnexion();
            
            $user = ORM::for_table('users')
                ->where('id', $userId)
                ->find_one();
                
            if (!$user) {
                return json_encode([
                    'state' => false,
                    'message' => 'Utilisateur non trouvé'
                ]);
            }
            
            if ($user->delete()) {
                return json_encode([
                    'state' => true,
                    'message' => 'Profil supprimé avec succès'
                ]);
            } else {
                return json_encode([
                    'state' => false,
                    'message' => 'Erreur lors de la suppression du profil'
                ]);
            }
            
        } catch (Exception $e) {
            return json_encode([
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Lister tous les utilisateurs (avec pagination optionnelle)
     */
    public function getAllProfiles($limit = null, $offset = 0)
    {
        try {
            $this->getConnexion();
            
            $query = ORM::for_table('users')
                ->select_many('id', 'nom', 'prenom', 'email', 'telephone', 'date_creation', 'statut');
            
            if ($limit) {
                $query->limit($limit)->offset($offset);
            }
            
            $users = $query->find_array();
            
            return json_encode([
                'state' => true,
                'data' => $users,
                'count' => count($users)
            ]);
            
        } catch (Exception $e) {
            return json_encode([
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rechercher des utilisateurs par nom, prénom ou email
     */
    public function searchProfiles($searchTerm)
    {
        try {
            $this->getConnexion();
            
            $users = ORM::for_table('users')
                ->select_many('id', 'nom', 'prenom', 'email', 'telephone', 'date_creation', 'statut')
                ->where_like('nom', "%{$searchTerm}%")
                ->where_like('prenom', "%{$searchTerm}%")
                ->where_like('email', "%{$searchTerm}%")
                ->find_array();
            
            return json_encode([
                'state' => true,
                'data' => $users,
                'count' => count($users)
            ]);
            
        } catch (Exception $e) {
            return json_encode([
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Changer le mot de passe d'un utilisateur
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        try {
            $this->getConnexion();
            
            $user = ORM::for_table('users')
                ->where('id', $userId)
                ->find_one();
                
            if (!$user) {
                $result['state'] = false;
                $result['message'] = 'Utilisateur non trouvé';
                return $result;
            }
            
            // Vérifier l'ancien mot de passe
            if ($user->password !== md5($oldPassword)) {
               
                $result['state'] = false;
                $result['message'] = "Erreur lors du changement de mot de passe, L'ancien mot de passe est incorrecte.";
                return $result;
            }
            
            // Mettre à jour avec le nouveau mot de passe
            $user->password = md5($newPassword);
            $user->first_connexion = 'no';
            $user->updated_at = date('Y-m-d H:i:s');
            
            if ($user->save()) {
                $result['state'] = true;
                $result['message'] = 'Mot de passe changé avec succès';
                $result['data'] = $user;
                return $result;

            } else {
                $result['state'] = false;
                $result['message'] = "Une Erreur interne est survenue";
                return $result;
            }
            
        } catch (Exception $e) {
            $result['state'] = false;
            $result['message'] = 'Erreur: ' . $e->getMessage();
            return $result;
        }
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleUserStatus($userId, $status)
    {
        try {
            $this->getConnexion();
            
            $user = ORM::for_table('users')
                ->where('id', $userId)
                ->find_one();
                
            if (!$user) {
                return json_encode([
                    'state' => false,
                    'message' => 'Utilisateur non trouvé'
                ]);
            }
            
            $user->statut = $status;
            $user->date_modification = date('Y-m-d H:i:s');
            
            if ($user->save()) {
                return json_encode([
                    'state' => true,
                    'message' => "Statut utilisateur mis à jour: {$status}",
                    'data' => ['statut' => $status]
                ]);
            } else {
                return json_encode([
                    'state' => false,
                    'message' => 'Erreur lors de la mise à jour du statut'
                ]);
            }
            
        } catch (Exception $e) {
            return json_encode([
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }
}