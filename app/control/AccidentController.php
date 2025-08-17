<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;

class AccidentController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        // Ne pas initialiser ActivityLogger dans le constructeur pour éviter les erreurs
        $this->activityLogger = null;
    }
    
    private function initActivityLogger()
    {
        if ($this->activityLogger === null) {
            try {
                require_once 'model/ActivityLogger.php';
                $this->activityLogger = new ActivityLogger();
            } catch (Exception $e) {
                error_log('AccidentController: Impossible d\'initialiser ActivityLogger - ' . $e->getMessage());
                return false;
            }
        }
        return true;
    }
    public function create($data, $files = [])
    {
        try {
            $this->getConnexion();
            
            // Commencer une transaction
            ORM::get_db()->beginTransaction();
            
            // Créer l'enregistrement de l'accident
            $accident = ORM::for_table('accidents')->create();
            
            $accident->date_accident = $data['date_accident'];
            $accident->lieu = $data['lieu'];
            $accident->gravite = $data['gravite'];
            $accident->description = $data['description'];
            $accident->created_at = date('Y-m-d H:i:s');
            
            if (!$accident->save()) {
                throw new Exception('Erreur lors de l\'enregistrement de l\'accident');
            }
            
            $accidentId = $accident->id();
            
            // Traitement des images
            $imagesPaths = [];
            if (!empty($files['images']['name'][0])) {
                $imagesPaths = $this->handleImageUploads($files['images'], $accidentId);
            }
            
            // Mettre à jour l'accident avec les chemins des images
            if (!empty($imagesPaths)) {
                $accident->images = json_encode($imagesPaths);
                $accident->save();
            }
            
            // Traitement des témoins
            if (!empty($data['temoins_data'])) {
                $temoinsData = json_decode($data['temoins_data'], true);
                if (is_array($temoinsData)) {
                    foreach ($temoinsData as $temoinData) {
                        $this->createTemoin($accidentId, $temoinData);
                    }
                }
            }
            
            // Valider la transaction
            ORM::get_db()->commit();
            
            // Logger la création de l'accident
            if ($this->initActivityLogger()) {
                $this->activityLogger->logCreate(
                    $_SESSION['user']['username'] ?? null,
                    'accidents',
                    $accident->id,
                    [
                        'lieu' => $data['lieu'],
                        'date_accident' => $data['date_accident'],
                        'heure_accident' => $data['heure_accident'],
                        'nombre_temoins' => isset($temoinsData) ? count($temoinsData) : 0
                    ]
                );
            }
            
            return [
                'state' => true,
                'message' => 'Accident enregistré avec succès',
                'data' => ['id' => $accidentId]
            ];
            
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            if (ORM::get_db()->inTransaction()) {
                ORM::get_db()->rollBack();
            }
            
            return [
                'state' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ];
        }
    }
    
    private function createTemoin($accidentId, $temoinData)
    {
        $temoin = ORM::for_table('temoins')->create();
        
        $temoin->id_accident = $accidentId;
        $temoin->nom = $temoinData['nom'];
        $temoin->telephone = $temoinData['telephone'];
        $temoin->age = $temoinData['age'];
        $temoin->lien_avec_accident = $temoinData['lien_avec_accident'];
        $temoin->temoignage = $temoinData['temoignage'] ?? '';
        $temoin->created_at = date('Y-m-d H:i:s');
        
        if (!$temoin->save()) {
            throw new Exception('Erreur lors de l\'enregistrement du témoin: ' . $temoinData['nom']);
        }
        
        return $temoin->id();
    }
    
    private function handleImageUploads($images, $accidentId)
    {
        $uploadDir = 'uploads/accidents/' . $accidentId . '/';
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('Impossible de créer le dossier de téléchargement');
            }
        }
        
        $imagesPaths = [];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        
        for ($i = 0; $i < count($images['name']); $i++) {
            if ($images['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = $images['name'][$i];
                $fileTmpName = $images['tmp_name'][$i];
                $fileSize = $images['size'][$i];
                $fileType = $images['type'][$i];
                
                // Vérifier le type de fichier
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Type de fichier non autorisé: $fileName");
                }
                
                // Vérifier la taille
                if ($fileSize > $maxFileSize) {
                    throw new Exception("Fichier trop volumineux: $fileName");
                }
                
                // Générer un nom unique
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueName = uniqid() . '_' . time() . '.' . $fileExtension;
                $filePath = $uploadDir . $uniqueName;
                
                // Déplacer le fichier
                if (move_uploaded_file($fileTmpName, $filePath)) {
                    $imagesPaths[] = $filePath;
                } else {
                    throw new Exception("Erreur lors du téléchargement: $fileName");
                }
            } elseif ($images['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                throw new Exception("Erreur de téléchargement pour le fichier: " . $images['name'][$i]);
            }
        }
        
        return $imagesPaths;
    }
    
    public function getAll()
    {
        try {
            $this->getConnexion();
            
            $accidents = ORM::for_table('accidents')
                ->order_by_desc('created_at')
                ->find_array();
            
            // Récupérer les témoins pour chaque accident
            foreach ($accidents as &$accident) {
                $temoins = ORM::for_table('temoins')
                    ->where('id_accident', $accident['id'])
                    ->find_array();
                $accident['temoins'] = $temoins;
                
                // Décoder les images
                if (!empty($accident['images'])) {
                    $accident['images'] = json_decode($accident['images'], true);
                }
            }
            
            // Logger la consultation de la liste des accidents
            if ($this->initActivityLogger()) {
                $this->activityLogger->logView(
                    $_SESSION['user']['username'] ?? null,
                    'accidents_list',
                    "Consultation de la liste des accidents (" . count($accidents) . " résultats)"
                );
            }
            
            return [
                'state' => true,
                'data' => $accidents
            ];
            
        } catch (Exception $e) {
            return [
                'state' => false,
                'message' => 'Erreur lors de la récupération des accidents: ' . $e->getMessage()
            ];
        }
    }
    
    public function getById($id)
    {
        try {
            $this->getConnexion();
            
            $accident = ORM::for_table('accidents')
                ->where('id', $id)
                ->find_one();
            
            if (!$accident) {
                return [
                    'state' => false,
                    'message' => 'Accident non trouvé'
                ];
            }
            
            $accidentData = $accident->as_array();
            
            // Récupérer les témoins
            $temoins = ORM::for_table('temoins')
                ->where('id_accident', $id)
                ->find_array();
            $accidentData['temoins'] = $temoins;
            
            // Décoder les images
            if (!empty($accidentData['images'])) {
                $accidentData['images'] = json_decode($accidentData['images'], true);
            }
            
            // Logger la consultation de l'accident
            if ($this->initActivityLogger()) {
                $this->activityLogger->logView(
                    $_SESSION['user']['username'] ?? null,
                    'accident_details',
                    "Consultation des détails de l'accident ID: {$id}"
                );
            }
            
            return [
                'state' => true,
                'data' => $accidentData
            ];
            
        } catch (Exception $e) {
            return [
                'state' => false,
                'message' => 'Erreur lors de la récupération de l\'accident: ' . $e->getMessage()
            ];
        }
    }
}
