<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;

class ConducteurVehiculeController extends Db {
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }
    
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
    
    // Contravention handling removed per product requirement: no contravention is created here.
    
    public function create($data, $files = []) {
        $result = [
            'state' => false,
            'message' => '',
            'data' => null
        ];
        
        try {
            // Démarrer une transaction
            $this->getConnexion();
            
            // Si un conducteur existant est sélectionné, on l'utilise directement
            if (!empty($data['existing_conducteur_id'])) {
                $existingId = (int)$data['existing_conducteur_id'];
                $existing = ORM::for_table('conducteur_vehicule')->find_one($existingId);
                if (!$existing) {
                    $result['state'] = false;
                    $result['message'] = "Le conducteur sélectionné n'existe pas";
                    return $result;
                }
                $conducteurId = $existingId;
                $result['state'] = true;
                $result['data'] = [ 'id' => $conducteurId, 'nom' => $existing->nom ];
                $result['message'] = 'Conducteur existant sélectionné';
                
                // Logger la consultation du conducteur existant
                $this->activityLogger->logView(
                    $_SESSION['username'] ?? null,
                    'conducteur_vehicule',
                    "Sélection du conducteur existant ID: {$conducteurId}"
                );

                // Plus de traitement de contravention ici

                return $result;
            }

            // Sinon, création/mise à jour (upsert) d'un nouveau conducteur
            // Gestion des uploads de fichiers du conducteur
            $photoPath = !empty($files['photo']['name']) ? $this->handleFileUpload($files['photo']) : null;
            $permisRectoPath = !empty($files['permis_recto']['name']) ? $this->handleFileUpload($files['permis_recto']) : null;
            $permisVersoPath = !empty($files['permis_verso']['name']) ? $this->handleFileUpload($files['permis_verso']) : null;

            // Upsert conducteur_vehicule par numero_permis s'il est fourni
            $conducteur_vehicule = null;
            if (!empty($data['numero_permis'])) {
                $conducteur_vehicule = ORM::for_table('conducteur_vehicule')
                    ->where('numero_permis', $data['numero_permis'])
                    ->find_one();
            }
            if (!$conducteur_vehicule) {
                $conducteur_vehicule = ORM::for_table('conducteur_vehicule')->create();
            }

            // Données du conducteur (mise à jour si existant, sinon création)
            $conducteur_vehicule->nom = $data['nom'];
            $conducteur_vehicule->date_naissance = $data['date_naissance'];
            $conducteur_vehicule->adresse = $data['adresse'];
            // Nouveau champ: numéro du permis
            $conducteur_vehicule->numero_permis = $data['numero_permis'] ?? ($conducteur_vehicule->numero_permis ?? null);
            if ($photoPath !== null) { $conducteur_vehicule->photo = $photoPath; }
            if ($permisRectoPath !== null) { $conducteur_vehicule->permis_recto = $permisRectoPath; }
            if ($permisVersoPath !== null) { $conducteur_vehicule->permis_verso = $permisVersoPath; }
            $conducteur_vehicule->permis_valide_le = $data['permis_valide_le'];
            $conducteur_vehicule->permis_expire_le = $data['permis_expire_le'];
            
            if (!$conducteur_vehicule->save()) {
                $result['state'] = false;
                $result['message'] = "Erreur lors de l'enregistrement du conducteur: " . $conducteur_vehicule->error;
                return $result;
            }
            
            // Récupérer l'ID du conducteur créé ou existant
            $conducteurId = $conducteur_vehicule->id();
            
            // Logger la création/mise à jour du conducteur véhicule
            $this->activityLogger->logCreate(
                $_SESSION['username'] ?? null,
                'conducteur_vehicule',
                $conducteurId,
                ['nom' => $data['nom'], 'numero_permis' => $data['numero_permis']]
            );
            
            // Enregistrement réussi du conducteur
            $result['state'] = true;
            $result['data'] = [
                'id' => $conducteurId,
                'nom' => $data['nom']
            ];
            $result['message'] = 'Conducteur et véhicule enregistrés avec succès';
            
            // Aucun enregistrement de contravention automatique
            
            return $result;
            
        } catch (Exception $e) {
           
            $result['state'] = false;
            $result['message'] = 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer. '. $e->getMessage();
            
            return $result;
        }
    }

}