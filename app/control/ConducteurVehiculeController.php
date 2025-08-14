<?php

namespace Control;

use Model\Db;
use ORM;
use Exception;

class ConducteurVehiculeController extends Db {
    
    protected function handleFileUpload($file, $targetDir = 'uploads/conducteurs/') {
        // Vérifier si le fichier est vide
        if (empty($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        
        // Vérifier les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du téléchargement du fichier: " . $this->getUploadError($file['error']));
        }
        
        // File size validation has been removed to allow any file size
        
        // Vérifier le type MIME du fichier (images uniquement)
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new Exception("Type de fichier non autorisé. Seuls les fichiers JPEG, PNG et GIF sont acceptés.");
        }
        
        // Créer le répertoire s'il n'existe pas
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Générer un nom de fichier unique
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . md5(basename($file['name'])) . '.' . $fileExtension;
        $targetPath = $targetDir . $fileName;
        
        // Déplacer le fichier téléchargé
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Erreur lors de l'enregistrement du fichier");
        }
        
        return $targetPath;
    }
    
    protected function handleMultipleFileUploads($files, $targetDir = 'uploads/contraventions/') {
        if (!is_array($files) || !isset($files['name']) || !is_array($files['name'])) {
            return [];
        }
        
        // Créer le répertoire s'il n'existe pas
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $uploadedFiles = [];
        $fileCount = count($files['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            // Vérifier si le fichier actuel est vide
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            
            // Vérifier les erreurs d'upload
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                throw new Exception("Erreur lors du téléchargement d'un fichier: " . $this->getUploadError($files['error'][$i]));
            }
            
            // Vérifier la taille du fichier (8 Mo max)
            $maxFileSize = 8 * 1024 * 1024; // 8 Mo en octets
            if ($files['size'][$i] > $maxFileSize) {
                throw new Exception("Le fichier \"{$files['name'][$i]}\" dépasse la taille maximale autorisée de 8 Mo");
            }
            
            // Vérifier le type MIME du fichier (images uniquement)
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $files['tmp_name'][$i]);
            finfo_close($fileInfo);
            
            if (!in_array($mimeType, $allowedMimeTypes)) {
                throw new Exception("Type de fichier non autorisé pour \"{$files['name'][$i]}\". Seuls les fichiers JPEG, PNG et GIF sont acceptés.");
            }
            
            // Générer un nom de fichier unique
            $fileExtension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . md5($files['name'][$i]) . '.' . $fileExtension;
            $targetPath = $targetDir . $fileName;
            
            // Déplacer le fichier téléchargé
            if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                $uploadedFiles[] = $targetPath;
            } else {
                throw new Exception("Erreur lors de l'enregistrement du fichier \"{$files['name'][$i]}\"");
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Traduit les codes d'erreur d'upload en messages lisibles
     */
    protected function getUploadError($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "La taille du fichier dépasse la limite autorisée par le serveur";
            case UPLOAD_ERR_FORM_SIZE:
                return "La taille du fichier dépasse la limite spécifiée dans le formulaire";
            case UPLOAD_ERR_PARTIAL:
                return "Le fichier n'a été que partiellement téléchargé";
            case UPLOAD_ERR_NO_FILE:
                return "Aucun fichier n'a été téléchargé";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Dossier temporaire manquant";
            case UPLOAD_ERR_CANT_WRITE:
                return "Échec de l'écriture du fichier sur le disque";
            case UPLOAD_ERR_EXTENSION:
                return "Une extension PHP a arrêté le téléchargement du fichier";
            default:
                return "Erreur inconnue lors du téléchargement du fichier";
        }
    }
    
    /**
     * Gère l'enregistrement des données de contravention séparément
     */
    protected function handleContraventionData($data, $conducteurId) {
        $result = [
            'state' => true,
            'message' => '',
            'hasContravention' => false
        ];
        
        // Vérifier si des données de contravention sont présentes
        // On considère qu'il y a des données de contravention si au moins lieu OU type_infraction est rempli
        $hasContraventionData = (!empty($data['lieu']) && trim($data['lieu']) !== '') || 
                              (!empty($data['type_infraction']) && trim($data['type_infraction']) !== '');
        
        if (!$hasContraventionData) {
            return $result; // Pas de données de contravention
        }
        
        $result['hasContravention'] = true;
        
        try {
            // Création de l'enregistrement de contravention
            $contravention = ORM::for_table('contraventions')->create();
            
            // Données de la contravention
            $contravention->dossier_id = $conducteurId;
            $contravention->type_dossier = 'conducteur_vehicule';
            $contravention->date_infraction = $data['date_infraction'] ?? null;
            $contravention->lieu = $data['lieu'] ?? null;
            $contravention->type_infraction = $data['type_infraction'] ?? null;
            $contravention->reference_loi = $data['reference_loi'] ?? null;
            $contravention->amende = !empty($data['amende']) ? (float)$data['amende'] : 0;
            $contravention->payed = $data['payed'] ?? null;
            $contravention->description = $data['description'] ?? null;
            
            if (!$contravention->save()) {
                $result['state'] = false;
                $result['message'] = "Erreur lors de l'enregistrement de la contravention: " . $contravention->error;
                return $result;
            }
            
            $result['message'] = 'Contravention enregistrée avec succès';
            
        } catch (Exception $e) {
            $result['state'] = false;
            $result['message'] = "Erreur lors de l'enregistrement de la contravention: " . $e->getMessage();
        }
        
        return $result;
    }
    
    public function create($data, $files = []) {
        $result = [
            'state' => false,
            'message' => '',
            'data' => null
        ];
        
        try {
            // Démarrer une transaction
            $this->getConnexion();
            
            // Gestion des uploads de fichiers du conducteur
            $photoPath = !empty($files['photo']['name']) ? $this->handleFileUpload($files['photo']) : null;
            $permisRectoPath = !empty($files['permis_recto']['name']) ? $this->handleFileUpload($files['permis_recto']) : null;
            $permisVersoPath = !empty($files['permis_verso']['name']) ? $this->handleFileUpload($files['permis_verso']) : null;
            
            // Création de l'enregistrement conducteur
            $conducteur_vehicule = ORM::for_table('conducteur_vehicule')->create();
            
            // Données du conducteur
            $conducteur_vehicule->nom = $data['nom'];
            $conducteur_vehicule->date_naissance = $data['date_naissance'];
            $conducteur_vehicule->adresse = $data['adresse'];
            // Nouveau champ: numéro du permis
            $conducteur_vehicule->numero_permis = $data['numero_permis'] ?? null;
            $conducteur_vehicule->photo = $photoPath;
            $conducteur_vehicule->permis_recto = $permisRectoPath;
            $conducteur_vehicule->permis_verso = $permisVersoPath;
            $conducteur_vehicule->permis_valide_le = $data['permis_valide_le'];
            $conducteur_vehicule->permis_expire_le = $data['permis_expire_le'];
            
            if (!$conducteur_vehicule->save()) {
                $result['state'] = false;
                $result['message'] = "Erreur lors de l'enregistrement du conducteur: " . $conducteur_vehicule->error;
                return $result;
            }
            
            // Récupérer l'ID du conducteur créé
            $conducteurId = $conducteur_vehicule->id();
            
            // Enregistrement réussi du conducteur
            $result['state'] = true;
            $result['data'] = [
                'id' => $conducteurId,
                'nom' => $data['nom']
            ];
            $result['message'] = 'Conducteur et véhicule enregistrés avec succès';
            
            // Vérifier et traiter les données de contravention séparément
            $contraventionResult = $this->handleContraventionData($data, $conducteurId);
            
            // Si il y a eu une contravention enregistrée, mettre à jour le message
            if ($contraventionResult['hasContravention']) {
                if ($contraventionResult['state']) {
                    $result['message'] = 'Conducteur, véhicule et contravention enregistrés avec succès';
                } else {
                    // Si la contravention a échoué, on retourne l'erreur mais le conducteur est déjà sauvé
                    $result['state'] = false;
                    $result['message'] = $contraventionResult['message'];
                    return $result;
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
           
            $result['state'] = false;
            $result['message'] = 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer. '. $e->getMessage();
            
            return $result;
        }
    }

}