<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;  

class VehiculePlaqueController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }
    public function create($data)
    {
        $this->getConnexion();
        
        // Handle multiple image uploads
        $imagePaths = $this->handleImageUploads($_FILES['images']);
        
        $vehicule_plaque = ORM::for_table('vehicule_plaque')
            ->create();
            
        // Vehicle information
        $vehicule_plaque->images = json_encode($imagePaths); // Store image paths as JSON
        $vehicule_plaque->marque = $data['marque'];
        $vehicule_plaque->modele = $data['modele'] ?? null;
        $vehicule_plaque->annee = $data['annee'];
        $vehicule_plaque->couleur = $data['couleur'];
        $vehicule_plaque->numero_chassis = $data['numero_chassis'] ?? null;
        
        // License plate information
        $vehicule_plaque->plaque = $data['plaque'];
        $vehicule_plaque->plaque_valide_le = $data['plaque_valide_le'];
        $vehicule_plaque->plaque_expire_le = $data['plaque_expire_le'];
        
        // Insurance information
        $vehicule_plaque->nume_assurance = $data['nume_assurance'];
        $vehicule_plaque->date_expire_assurance = $data['date_expire_assurance'];
        $vehicule_plaque->date_valide_assurance = $data['date_valide_assurance'];
        $vehicule_plaque->societe_assurance = $data['societe_assurance'];
        
        $vehicule_plaque->save();
        
        // Logger la création du véhicule
        $this->activityLogger->logCreate(
            $_SESSION['username'] ?? null,
            'vehicule_plaque',
            $vehicule_plaque->id(),
            ['marque' => $data['marque'], 'plaque' => $data['plaque']]
        );
        
        return $vehicule_plaque->id();
    }
    
    /**
     * Handle multiple image uploads
     * @param array $files - $_FILES['images'] array
     * @return array - Array of saved image paths
     */
    private function handleImageUploads($files)
    {
        $uploadDir = 'uploads/vehicules/';
        $imagePaths = [];
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Check if files were uploaded
        if (!isset($files['name']) || !is_array($files['name'])) {
            throw new Exception('Aucune image n\'a été uploadée');
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxFileSize = 8 * 1024 * 1024; // 8MB max per file (aligné avec le frontend)
        
        // Process each uploaded file
        for ($i = 0; $i < count($files['name']); $i++) {
            // Skip if no file was uploaded for this slot
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            
            // Check for upload errors
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                throw new Exception('Erreur lors de l\'upload de l\'image: ' . $files['name'][$i]);
            }
            
            // Validate file type
            $fileType = $files['type'][$i];
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception('Type de fichier non autorisé: ' . $files['name'][$i] . '. Types autorisés: JPG, PNG, GIF');
            }
            
            // Validate file size
            if ($files['size'][$i] > $maxFileSize) {
                throw new Exception('Fichier trop volumineux: ' . $files['name'][$i] . '. Taille maximum: 8MB');
            }
            
            // Generate unique filename
            $originalName = $files['name'][$i];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $uniqueName = 'vehicule_' . uniqid() . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $uniqueName;
            
            // Move uploaded file
            if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                $imagePaths[] = $targetPath;
            } else {
                throw new Exception('Erreur lors de la sauvegarde de l\'image: ' . $originalName);
            }
        }
        
        // Ensure at least one image was uploaded
        if (empty($imagePaths)) {
            throw new Exception('Au moins une image est requise');
        }
        
        return $imagePaths;
    }
}
