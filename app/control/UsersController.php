<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;

class UsersController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

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
                // Logger la tentative de connexion échouée (compte inactif)
                $this->activityLogger->logLoginFailed($username, 'Compte désactivé');
            } else {
                // Enforce optional login schedule if defined
                $denyBySchedule = false;
                $denyMessage = null;
                try {
                    $scheduleJson = $user[0]['login_schedule'] ?? null;
                    $schedule = $scheduleJson ? json_decode($scheduleJson, true) : null;
                    if (is_array($schedule)) {
                        // Check if any day is enabled; if none, no restriction
                        $anyEnabled = false;
                        foreach ($schedule as $d => $cfg) {
                            if (!empty($cfg['enabled'])) { $anyEnabled = true; break; }
                        }
                        if ($anyEnabled) {
                            $map = [
                                'Mon' => 'mon','Tue' => 'tue','Wed' => 'wed','Thu' => 'thu','Fri' => 'fri','Sat' => 'sat','Sun' => 'sun'
                            ];
                            $phpDay = date('D');
                            $dayKey = $map[$phpDay] ?? null;
                            $now = date('H:i');
                            $cfg = $dayKey && isset($schedule[$dayKey]) ? $schedule[$dayKey] : null;
                            if (!$cfg || empty($cfg['enabled'])) {
                                $denyBySchedule = true;
                                $denyMessage = "Connexion non autorisée aujourd'hui.";
                            } else {
                                $start = isset($cfg['start']) && preg_match('/^\d{2}:\d{2}$/', $cfg['start']) ? $cfg['start'] : '00:00';
                                $end   = isset($cfg['end']) && preg_match('/^\d{2}:\d{2}$/', $cfg['end']) ? $cfg['end'] : '23:59';
                                if (!($now >= $start && $now <= $end)) {
                                    $denyBySchedule = true;
                                    $denyMessage = 'Connexion en dehors des heures autorisées (' . $start . ' - ' . $end . ').';
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // En cas d'erreur de parsing, ne pas bloquer la connexion
                }

                if ($denyBySchedule) {
                    $result['state'] = false;
                    $result['message'] = $denyMessage ?? 'Connexion non autorisée par la politique d\'horaires.';
                    $this->activityLogger->logLoginFailed($username, 'Restriction horaires');
                } else {
                    $result['state'] = true;
                    $result['data'] = $user;
                    // Logger la connexion réussie
                    $this->activityLogger->logLogin($username);
                }
            }
        }else{
            $result['state'] = false;
            $result['message'] = 'Coordonnées de connexion incorrectes';
            // Logger la tentative de connexion échouée
            $this->activityLogger->logLoginFailed($username, 'Identifiants incorrects');
        }
        //return an array
        $result = (array)$result;
        return $result;
    } 
 
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
                // Logger le changement de mot de passe
                $this->activityLogger->logUpdate(
                    $_SESSION['username'] ?? null, 
                    'users', 
                    $userId, 
                    null, 
                    ['action' => 'password_change']
                );
                
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


}