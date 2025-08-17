<?php

namespace Control;

use Exception;
use Model\Db;
use Model\ActivityLogger;
use ORM;

class ConducteurController extends Db {
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function create($data, $files) {
        $this->getConnexion();
        
        try {
            // Vérifier si le conducteur existe déjà
            $existing = ORM::for_table('conducteurs')
                ->where('nom', $data['nom'])
                ->where('date_naissance', $data['date_naissance'])
                ->find_one();
                
            if ($existing) {
                return [
                    'state' => false,
                    'message' => 'Un conducteur avec ce nom et cette date de naissance existe déjà.'
                ];
            }
            
            // Gestion de l'upload des fichiers
            $uploadDir = __DIR__ . '/../uploads/conducteurs/';
            
            // Créer le répertoire s'il n'existe pas
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Fonction pour gérer l'upload d'un fichier
            $uploadFile = function($file, $prefix = '') use ($uploadDir) {
                if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                    return null;
                }
                
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = $prefix . uniqid() . '.' . $extension;
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    return 'uploads/conducteurs/' . $filename;
                }
                
                return null;
            };
            
            // Upload des fichiers
            $photoPath = $uploadFile($files['photo'] ?? null, 'photo_');
            $permisRectoPath = $uploadFile($files['permis_recto'] ?? null, 'permis_recto_');
            $permisVersoPath = $uploadFile($files['permis_verso'] ?? null, 'permis_verso_');
            
            // Vérification des champs obligatoires
            if (!$permisRectoPath || !$permisVersoPath) {
                return [
                    'state' => false,
                    'message' => 'Les fichiers du permis de conduire (recto et verso) sont obligatoires.'
                ];
            }
            
            // Création du conducteur
            $conducteur = ORM::for_table('conducteurs')->create();
            $conducteur->nom = $data['nom'];
            $conducteur->date_naissance = $data['date_naissance'];
            $conducteur->adresse = $data['adresse'];
            $conducteur->photo = $photoPath;
            $conducteur->permis_recto = $permisRectoPath;
            $conducteur->permis_verso = $permisVersoPath;
            $conducteur->permis_valide_le = $data['permis_valide_le'];
            $conducteur->permis_expire_le = $data['permis_expire_le'];
            $conducteur->created_at = date('Y-m-d H:i:s');
            
            if ($conducteur->save()) {
                // Logger la création du conducteur
                $this->activityLogger->logCreate(
                    $_SESSION['username'] ?? null,
                    'conducteurs',
                    $conducteur->id(),
                    ['nom' => $data['nom'], 'date_naissance' => $data['date_naissance']]
                );
                
                return [
                    'state' => true,
                    'message' => 'Le conducteur ' . $data['nom'] . ' a été enregistré avec succès.',
                    'data' => $conducteur->as_array()
                ];
            } else {
                return [
                    'state' => false,
                    'message' => 'Une erreur est survenue lors de l\'enregistrement du conducteur.'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'state' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }
    
    // Autres méthodes pour la gestion des conducteurs (lire, mettre à jour, supprimer, etc.)
    // ...
}
