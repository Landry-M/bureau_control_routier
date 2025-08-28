<?php


namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;  
// PDF: use Dompdf if installed via Composer
use Dompdf\Dompdf;

class ContraventionsController extends Db{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function create($data)
    {
        // Initialize result array
        $result = ['state' => false, 'message' => ''];
        
        try {
            $this->getConnexion();
            $contravention = ORM::for_table('contraventions')
                ->create();
            
            // Validate required fields
            if (empty($data['dossier_id']) || empty($data['type_dossier'])) {
                $result['message'] = 'Dossier ID et type de dossier sont requis';
                return $result;
            }
            
            $contravention->dossier_id = $data['dossier_id'];
            $contravention->type_dossier = $data['type_dossier'];
            $contravention->date_infraction = $data['date_infraction'] ?? '';
            $contravention->lieu = $data['lieu'] ?? '';
            $contravention->type_infraction = $data['type_infraction'] ?? '';
            $contravention->description = $data['description'] ?? '';
            $contravention->reference_loi = $data['reference_loi'] ?? '';
            $contravention->amende = $data['amende'] ?? 0;
            $contravention->payed = $data['payed'] ?? 0;
        
            if ($contravention->save()) {
            // Handle photo uploads
            $this->handlePhotoUploads($contravention->id());
            
            // Log creation
            try {
                $this->activityLogger->logCreate(
                    $_SESSION['username'] ?? null,
                    'contraventions',
                    $contravention->id(),
                    [
                        'type_dossier' => $data['type_dossier'] ?? null,
                        'dossier_id' => $data['dossier_id'] ?? null,
                        'type_infraction' => $data['type_infraction'] ?? null,
                        'amende' => $data['amende'] ?? null
                    ]
                );
            } catch (\Throwable $e) {
                // non bloquant
            }
            $result['state'] = true;
            $result['message'] = 'Contravention enregistrée avec succès';
            $result['data'] = $contravention->id;

            // Tenter de générer un PDF si Dompdf est disponible
            try {
                if (class_exists('Dompdf\\Dompdf')) {
                    // Get photos for PDF
                    $photos = $this->getPhotos($contravention->id());
                    
                    $pdfInfo = $this->generateContraventionPdf([
                        'id' => $contravention->id,
                        'dossier_id' => $contravention->dossier_id,
                        'type_dossier' => $contravention->type_dossier,
                        // Forward the friendly display name coming from the UI alert label
                        'nom' => $data['nom'] ?? null,
                        'date_infraction' => $contravention->date_infraction,
                        'lieu' => $contravention->lieu,
                        'type_infraction' => $contravention->type_infraction,
                        'description' => $contravention->description,
                        'reference_loi' => $contravention->reference_loi,
                        'amende' => $contravention->amende,
                        'payed' => $contravention->payed,
                        'photos' => $photos,
                    ]);
                    if ($pdfInfo && isset($pdfInfo['path'])) {
                        $result['pdf'] = $pdfInfo['public_url'] ?? $pdfInfo['path'];
                    }
                }
            } catch (\Throwable $e) {
                // Ignorer les erreurs PDF pour ne pas bloquer la création
            }

            return $result;
            }else{
                $result['state'] = false;
                $result['message'] = 'Erreur lors de l\'enregistrement';
                return $result;
            }
        } catch (\Throwable $e) {
            $result['state'] = false;
            $result['message'] = 'Erreur: ' . $e->getMessage();
            return $result;
        }
    }

    // Génération du PDF de contravention
    private function generateContraventionPdf(array $cv)
    {
        // Préparer le HTML via une vue dédiée
        $logoPath = __DIR__ . '/../assets/images/logo.png';
        $logoUrl = file_exists($logoPath)
            ? (isset($_SERVER['HTTP_HOST']) ? (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/assets/images/logo.png' : 'assets/images/logo.png')
            : null;

        ob_start();
        $cvData = $cv; // alias pour la vue
        $logo = $logoUrl; // URL ou null
        include __DIR__ . '/../views/pdf/contravention_receipt.php';
        $html = ob_get_clean();

        // Générer PDF
        $dompdf = new Dompdf(['isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Sauvegarder dans uploads/contraventions/
        $dir = __DIR__ . '/../uploads/contraventions';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'contravention_' . $cv['id'] . '.pdf';
        $filePath = $dir . '/' . $filename;
        file_put_contents($filePath, $dompdf->output());

        // Construire une URL publique approximative si possible
        $publicUrl = null;
        if (isset($_SERVER['HTTP_HOST'])) {
            $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
            $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
            $publicUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $base . '/uploads/contraventions/' . $filename;
        }

        return ['path' => $filePath, 'public_url' => $publicUrl, 'filename' => $filename];
    }

    /**
     * Handle photo uploads for a contravention
     */
    private function handlePhotoUploads($contraventionId)
    {
        if (!isset($_FILES['photos']) || !is_array($_FILES['photos']['tmp_name'])) {
            return [];
        }

        $uploadDir = __DIR__ . '/../assets/images/contraventions/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        $photoPaths = [];

        foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
            if (empty($tmpName) || $_FILES['photos']['error'][$index] !== UPLOAD_ERR_OK) {
                continue;
            }

            $originalName = $_FILES['photos']['name'][$index] ?? '';
            $fileSize = $_FILES['photos']['size'][$index] ?? 0;
            $mimeType = $_FILES['photos']['type'][$index] ?? '';

            // Validate file type
            if (!in_array($mimeType, $allowedTypes)) {
                continue;
            }

            // Validate file size
            if ($fileSize > $maxFileSize) {
                continue;
            }

            // Generate unique filename with timestamp and random component
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $timestamp = date('YmdHis');
            $randomString = bin2hex(random_bytes(8)); // 16 character random string
            $filename = 'contravention_' . $contraventionId . '_' . $timestamp . '_' . $randomString . '.' . $extension;
            $filePath = $uploadDir . $filename;
            
            // Ensure filename is unique (extra safety check)
            $counter = 1;
            while (file_exists($filePath)) {
                $filename = 'contravention_' . $contraventionId . '_' . $timestamp . '_' . $randomString . '_' . $counter . '.' . $extension;
                $filePath = $uploadDir . $filename;
                $counter++;
            }

            // Move uploaded file
            if (move_uploaded_file($tmpName, $filePath)) {
                $photoPaths[] = 'assets/images/contraventions/' . $filename;
            }
        }

        // Update contravention record with photo paths
        if (!empty($photoPaths)) {
            try {
                $this->getConnexion();
                $contravention = ORM::for_table('contraventions')->find_one($contraventionId);
                if ($contravention) {
                    $contravention->photos = implode(',', $photoPaths);
                    $contravention->save();
                }
            } catch (\Throwable $e) {
                // If database update fails, remove uploaded files
                foreach ($photoPaths as $path) {
                    $fullPath = __DIR__ . '/../' . $path;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
        }

        return $photoPaths;
    }

    /**
     * Get photos for a contravention
     */
    public function getPhotos($contraventionId)
    {
        $this->getConnexion();
        $contravention = ORM::for_table('contraventions')->find_one($contraventionId);
        
        if (!$contravention || empty($contravention->photos)) {
            return [];
        }
        
        $photoPaths = explode(',', (string)$contravention->photos);
        $photos = [];
        
        foreach ($photoPaths as $path) {
            $path = trim($path);
            if (!empty($path)) {
                $fullPath = __DIR__ . '/../' . $path;
                if (file_exists($fullPath)) {
                    $photos[] = [
                        'file_path' => $path,
                        'original_name' => basename($path),
                        'file_size' => filesize($fullPath),
                        'mime_type' => mime_content_type($fullPath)
                    ];
                }
            }
        }
        
        return $photos;
    }
}