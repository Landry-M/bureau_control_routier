<?php

namespace Control;

use ORM;
use Model\Db;
use Model\ActivityLogger;

class DossierController extends Db {
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }
    
    public function getConducteursWithContraventions() {
        try {
            $this->getConnexion();
            
            
            // Diagnostic: vérifier qu'il existe des conducteurs dans la base
            $totalConducteurs = ORM::for_table('conducteur_vehicule')->count();

            // Étape 1: récupérer tous les conducteurs (tri récent d'abord)
            $conducteurs = ORM::for_table('conducteur_vehicule')
                ->order_by_desc('id')
                ->find_array();
                
            // Étape 2: récupérer toutes les contraventions liées aux conducteurs par id (clé primaire)
            $contraventionsByDossier = [];
            if (!empty($conducteurs)) {
                $ids = array_filter(array_column($conducteurs, 'id'), function($val){
                    return $val !== null && $val !== '';
                });
                if (!empty($ids)) {
                    $rowsCv = ORM::for_table('contraventions')
                        ->where('type_dossier', 'conducteur_vehicule')
                        ->where_in('dossier_id', $ids)
                        ->order_by_desc('date_infraction')
                        ->find_array();
                    foreach ($rowsCv as $cv) {
                        $cid = $cv['dossier_id'] ?? null;
                        if ($cid !== null) {
                            if (!isset($contraventionsByDossier[$cid])) $contraventionsByDossier[$cid] = [];
                            $contraventionsByDossier[$cid][] = [
                                'id' => $cv['id'] ?? null,
                                'date_infraction' => $cv['date_infraction'] ?? null,
                                'lieu' => $cv['lieu'] ?? null,
                                'type_infraction' => $cv['type_infraction'] ?? null,
                                'description' => $cv['description'] ?? null,
                                'reference_loi' => $cv['reference_loi'] ?? null,
                                'amende' => $cv['amende'] ?? null,
                                'payed' => $cv['payed'] ?? null,
                            ];
                        }
                    }
                }
            }

            // Les conducteurs sont déjà la liste unique souhaitée
            $uniqueConducteurs = $conducteurs;
            
            // Convertir le tableau associatif en tableau indexé pour les conducteurs
            $conducteurs = array_values($uniqueConducteurs);
            
            $result = [
                'conducteurs' => $conducteurs,
                'contraventionsByDossier' => $contraventionsByDossier
            ];
            
            // Logger la consultation des dossiers conducteurs
            $this->activityLogger->logView(
                $_SESSION['username'] ?? null,
                'dossiers_conducteurs',
                "Consultation des dossiers conducteurs (" . count($conducteurs) . " conducteurs)"
            );
            
            return $result;
            
        } catch (\Exception $e) {
            return [
                'conducteurs' => [],
                'contraventionsByDossier' => []
            ];
        }
    }

    public function getVehiculesWithContraventions() {
        try {
            $this->getConnexion();

            // Étape 1: récupérer tous les véhicules (tri récent d'abord)
            $vehicules = ORM::for_table('vehicule_plaque')
                ->order_by_desc('id')
                ->find_array();

            // Étape 2: récupérer toutes les contraventions liées aux véhicules par dossier_id = vehicule_id
            $contraventionsByVehicule = [];
            if (!empty($vehicules)) {
                $ids = array_filter(array_column($vehicules, 'id'), function($val) {
                    return $val !== null && $val !== '';
                });
                if (!empty($ids)) {
                    $rowsCv = ORM::for_table('contraventions')
                        ->where('type_dossier', 'vehicule_plaque')
                        ->where_in('dossier_id', $ids)
                        ->order_by_desc('date_infraction')
                        ->find_array();
                    foreach ($rowsCv as $cv) {
                        $vid = $cv['dossier_id'] ?? null;
                        if ($vid !== null) {
                            if (!isset($contraventionsByVehicule[$vid])) $contraventionsByVehicule[$vid] = [];
                            $contraventionsByVehicule[$vid][] = [
                                'id' => $cv['id'] ?? null,
                                'date_infraction' => $cv['date_infraction'] ?? null,
                                'lieu' => $cv['lieu'] ?? null,
                                'type_infraction' => $cv['type_infraction'] ?? null,
                                'description' => $cv['description'] ?? null,
                                'reference_loi' => $cv['reference_loi'] ?? null,
                                'amende' => $cv['amende'] ?? null,
                                'payed' => $cv['payed'] ?? null,
                            ];
                        }
                    }
                }
            }

            // Logger la consultation des dossiers véhicules
            $this->activityLogger->logView(
                $_SESSION['username'] ?? null,
                'dossiers_vehicules',
                "Consultation des dossiers véhicules (" . count($vehicules) . " véhicules)"
            );
            
            return [
                'vehicules' => array_values($vehicules),
                'contraventionsByVehicule' => $contraventionsByVehicule,
            ];
        } catch (\Exception $e) {
            return [
                'vehicules' => [],
                'contraventionsByVehicule' => [],
            ];
        }
    }

    public function getEntreprisesWithContraventions() {
        try {
            $this->getConnexion();

            // Récupérer toutes les entreprises (récents d'abord)
            $entreprises = ORM::for_table('entreprises')
                ->order_by_desc('id')
                ->find_array();

            // Contraventions liées aux entreprises par id (clé primaire)
            $contraventionsByEntreprise = [];
            if (!empty($entreprises)) {
                $eids = array_filter(array_column($entreprises, 'id'), function($val){
                    return $val !== null && $val !== '';
                });
                if (!empty($eids)) {
                    $rowsCv = ORM::for_table('contraventions')
                        ->where('type_dossier', 'entreprises')
                        ->where_in('dossier_id', $eids)
                        ->order_by_desc('date_infraction')
                        ->find_array();
                    foreach ($rowsCv as $cv) {
                        $eid = $cv['dossier_id'] ?? null;
                        if ($eid !== null) {
                            if (!isset($contraventionsByEntreprise[$eid])) $contraventionsByEntreprise[$eid] = [];
                            $contraventionsByEntreprise[$eid][] = [
                                'id' => $cv['id'] ?? null,
                                'date_infraction' => $cv['date_infraction'] ?? null,
                                'lieu' => $cv['lieu'] ?? null,
                                'type_infraction' => $cv['type_infraction'] ?? null,
                                'description' => $cv['description'] ?? null,
                                'reference_loi' => $cv['reference_loi'] ?? null,
                                'amende' => $cv['amende'] ?? null,
                                'payed' => $cv['payed'] ?? null,
                            ];
                        }
                    }
                }
            }

            // Logger la consultation des dossiers entreprises
            $this->activityLogger->logView(
                $_SESSION['username'] ?? null,
                'dossiers_entreprises',
                "Consultation des dossiers entreprises (" . count($entreprises) . " entreprises)"
            );
            
            return [
                'entreprises' => array_values($entreprises),
                'contraventionsByEntreprise' => $contraventionsByEntreprise,
            ];
        } catch (\Exception $e) {
            return [
                'entreprises' => [],
                'contraventionsByEntreprise' => [],
            ];
        }
    }
}
