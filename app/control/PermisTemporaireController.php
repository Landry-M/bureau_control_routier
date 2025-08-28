<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
// PDF
use Dompdf\Dompdf;

class PermisTemporaireController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    private function generateNumero(): string
    {
        $date = date('Ymd');
        $rand = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return "PT-{$date}-{$rand}";
    }

    public function create(array $data)
    {
        $this->getConnexion();
        $cibleType = trim((string)($data['cible_type'] ?? 'particulier'));
        $cibleId = (int)($data['cible_id'] ?? 0);
        $motif = trim((string)($data['motif'] ?? ''));
        $dateDebut = trim((string)($data['date_debut'] ?? date('Y-m-d')));
        $dateFin = trim((string)($data['date_fin'] ?? ''));
        // Si date_fin manquante, la définir par défaut à +7 jours après date_debut
        if ($dateFin === '') {
            $base = $dateDebut !== '' ? $dateDebut : date('Y-m-d');
            $dateFin = date('Y-m-d', strtotime($base . ' +7 days'));
        }

        if ($cibleType === '' || $cibleId <= 0 || $dateDebut === '' || $dateFin === '') {
            return ['ok' => false, 'error' => 'Paramètres invalides'];
        }

        $now = date('Y-m-d H:i:s');
        // Permettre un numéro fourni (ex: plaque temporaire saisie par l'agent), sinon auto-générer
        $numero = trim((string)($data['numero'] ?? ''));
        if ($numero === '') {
            $numero = $this->generateNumero();
        }

        $row = ORM::for_table('permis_temporaire')->create();
        $row->cible_type = $cibleType;
        $row->cible_id = $cibleId;
        $row->numero = $numero;
        $row->motif = $motif;
        $row->date_debut = $dateDebut;
        $row->date_fin = $dateFin;
        $row->statut = 'actif';
        $row->created_by = $_SESSION['user']['username'] ?? null;
        $row->created_at = $now;
        $row->updated_at = $now;
        $row->save();

        $id = (int)$row->id;
        $this->activityLogger->logCreate(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire',
            $id,
            [
                'cible_type' => $cibleType,
                'cible_id' => $cibleId,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin
            ]
        );

        // Génération PDF (paysage) avec le numéro
        $pdfInfo = null; $pdfError = null;
        try {
            // Générer le PDF selon le type de cible
            if ($cibleType === 'vehicule_plaque' && class_exists('Dompdf\\Dompdf')) {
                $pdfInfo = $this->generateTempPlatePdf([
                    'id' => $id,
                    'numero' => $numero,
                    'cible_type' => $cibleType,
                    'cible_id' => $cibleId,
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                ]);
                if (!$pdfInfo || empty($pdfInfo['path']) || !is_file($pdfInfo['path'])) {
                    $pdfError = 'PDF non écrit sur le disque';
                }
            } elseif ($cibleType === 'particulier' && class_exists('Dompdf\\Dompdf')) {
                $pdfInfo = $this->generatePermisParticulierPdfFromTemplate([
                    'id' => $id,
                    'numero' => $numero,
                    'cible_type' => $cibleType,
                    'cible_id' => $cibleId,
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                ]);
                if (!$pdfInfo || empty($pdfInfo['path']) || !is_file($pdfInfo['path'])) {
                    $pdfError = 'PDF non écrit sur le disque';
                }
            } else {
                if (!class_exists('Dompdf\\Dompdf')) {
                    $pdfError = 'Dompdf non disponible';
                }
            }
        } catch (\Throwable $e) {
            $pdfError = 'Erreur PDF: ' . $e->getMessage();
        }

        $res = ['ok' => true, 'id' => $id, 'numero' => $numero];
        if ($pdfInfo && isset($pdfInfo['path'])) {
            // Prefer absolute URL, then relative URL, then filesystem path
            $res['pdf'] = $pdfInfo['public_url'] ?? ($pdfInfo['relative_url'] ?? $pdfInfo['path']);
            if (isset($pdfInfo['filename'])) { $res['filename'] = $pdfInfo['filename']; }
        }
        if ($pdfError) { $res['pdf_error'] = $pdfError; }
        return $res;
    }

    public function listByParticulier(int $particulierId)
    {
        $this->getConnexion();
        $pid = (int)$particulierId;
        if ($pid <= 0) return [];
        $rows = ORM::for_table('permis_temporaire')
            ->where('cible_type', 'particulier')
            ->where('cible_id', $pid)
            ->order_by_desc('id')
            ->find_array();
        $this->activityLogger->logView(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire_particulier',
            ['particulier_id' => $pid, 'results' => count($rows)]
        );
        return $rows ?: [];
    }

    public function listByVehicule(int $vehiculeId)
    {
        $this->getConnexion();
        $vid = (int)$vehiculeId;
        if ($vid <= 0) return [];
        $rows = ORM::for_table('permis_temporaire')
            ->where('cible_type', 'vehicule_plaque')
            ->where('cible_id', $vid)
            ->order_by_desc('id')
            ->find_array();
        $this->activityLogger->logView(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire_vehicule',
            ['vehicule_id' => $vid, 'results' => is_array($rows) ? count($rows) : 0]
        );
        return $rows ?: [];
    }

    public function close(int $id)
    {
        $this->getConnexion();
        $rid = (int)$id;
        if ($rid <= 0) return ['ok' => false, 'error' => 'ID invalide'];
        $row = ORM::for_table('permis_temporaire')->find_one($rid);
        if (!$row) return ['ok' => false, 'error' => 'Permis introuvable'];
        $old = ['statut' => $row->statut];
        $row->statut = 'clos';
        $row->updated_at = date('Y-m-d H:i:s');
        $row->save();
        $this->activityLogger->logUpdate(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire',
            $rid,
            $old,
            ['statut' => 'clos']
        );
        return ['ok' => true, 'id' => $rid];
    }

    // Génère un PDF paysage avec le numéro de plaque/permis temporaire
    private function generateTempPlatePdf(array $data)
    {
        // Préparer le HTML via la vue dédiée
        ob_start();
        $numero = $data['numero'] ?? '';
        $date_debut = $data['date_debut'] ?? null;
        $date_fin = $data['date_fin'] ?? null;
        include __DIR__ . '/../views/pdf/plaque_temporaire.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf(['isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Sauvegarder dans uploads/permis_temporaire
        $dir = __DIR__ . '/../uploads/permis_temporaire';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        if (!is_dir($dir)) {
            // Echec de création
            throw new \RuntimeException('Impossible de créer le répertoire: ' . $dir);
        }
        if (!is_writable($dir)) {
            @chmod($dir, 0775);
        }
        if (!is_writable($dir)) {
            throw new \RuntimeException('Répertoire non inscriptible: ' . $dir);
        }
        $filename = 'permis_temporaire_' . ($data['id'] ?? 'N') . '.pdf';
        $filePath = $dir . '/' . $filename;
        $bytes = @file_put_contents($filePath, $dompdf->output());
        if ($bytes === false || !is_file($filePath) || filesize($filePath) <= 0) {
            throw new \RuntimeException('Echec écriture PDF dans ' . $filePath);
        }

        // URL publique et relative (pour le Front)
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        $relativeUrl = $basePath . '/uploads/permis_temporaire/' . $filename;
        $publicUrl = null;
        if (isset($_SERVER['HTTP_HOST'])) {
            $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
            $publicUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $relativeUrl;
        }

        return ['path' => $filePath, 'public_url' => $publicUrl, 'relative_url' => $relativeUrl, 'filename' => $filename];
    }

    // Génère un PDF permis temporaire pour un particulier à partir de la vue HTML dédiée
    private function generatePermisParticulierPdf(array $data)
    {
        // Récupérer les infos du particulier
        $this->getConnexion();
        $pid = (int)($data['cible_id'] ?? 0);
        $part = null;
        if ($pid > 0) {
            $row = ORM::for_table('particuliers')->find_one($pid);
            if ($row) { $part = $row->as_array(); }
        }
        if (!$part) {
            throw new \RuntimeException('Particulier introuvable pour le permis temporaire');
        }

        // Préparer le HTML via la vue dédiée
        ob_start();
        $numero = $data['numero'] ?? '';
        $date_debut = $data['date_debut'] ?? null;
        $date_fin = $data['date_fin'] ?? null;
        include __DIR__ . '/../views/pdf/permis_temporaire_particulier.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf(['isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Sauvegarder dans uploads/permis_temporaire
        $dir = __DIR__ . '/../uploads/permis_temporaire';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        if (!is_dir($dir)) { throw new \RuntimeException('Impossible de créer le répertoire: ' . $dir); }
        if (!is_writable($dir)) { @chmod($dir, 0775); }
        if (!is_writable($dir)) { throw new \RuntimeException('Répertoire non inscriptible: ' . $dir); }
        $filename = 'permis_temporaire_particulier_' . ($data['id'] ?? 'N') . '.pdf';
        $filePath = $dir . '/' . $filename;
        $bytes = @file_put_contents($filePath, $dompdf->output());
        if ($bytes === false || !is_file($filePath) || filesize($filePath) <= 0) {
            throw new \RuntimeException('Echec écriture PDF dans ' . $filePath);
        }

        // URL publique et relative
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        $relativeUrl = $basePath . '/uploads/permis_temporaire/' . $filename;
        $publicUrl = null;
        if (isset($_SERVER['HTTP_HOST'])) {
            $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
            $publicUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $relativeUrl;
        }

        return ['path' => $filePath, 'public_url' => $publicUrl, 'relative_url' => $relativeUrl, 'filename' => $filename];
    }

    // Génère un PDF permis temporaire pour un particulier à partir du template permis_tmp.php
    private function generatePermisParticulierPdfFromTemplate(array $data)
    {
        // Récupérer les infos du particulier
        $this->getConnexion();
        $pid = (int)($data['cible_id'] ?? 0);
        $part = null;
        if ($pid > 0) {
            $row = ORM::for_table('particuliers')->find_one($pid);
            if ($row) { $part = $row->as_array(); }
        }
        if (!$part) {
            throw new \RuntimeException('Particulier introuvable pour le permis temporaire');
        }

        // Préparer les données pour le template
        $numero = $data['numero'] ?? '';
        $date_debut = $data['date_debut'] ?? null;
        $date_fin = $data['date_fin'] ?? null;
        
        // Formater les données du particulier
        $nom = htmlspecialchars((string)($part['nom'] ?? ''), ENT_QUOTES);
        $prenom = '';
        // Séparer nom et prénom si stockés ensemble
        if ($nom && strpos($nom, ' ') !== false) {
            $pieces = preg_split('/\s+/', $nom, 2);
            if (is_array($pieces)) { 
                $nom = htmlspecialchars($pieces[0] ?? '', ENT_QUOTES); 
                $prenom = htmlspecialchars($pieces[1] ?? '', ENT_QUOTES); 
            }
        }
        
        $numero_national = htmlspecialchars((string)($part['numero_national'] ?? ''), ENT_QUOTES);
        $adresse = htmlspecialchars((string)($part['adresse'] ?? ''), ENT_QUOTES);
        $nationalite = htmlspecialchars((string)($part['nationalite'] ?? 'Congolaise'), ENT_QUOTES);
        $date_naissance = htmlspecialchars((string)($part['date_naissance'] ?? ''), ENT_QUOTES);
        $lieu_naissance = htmlspecialchars((string)($part['lieu_naissance'] ?? ''), ENT_QUOTES);
        
        // Gérer la photo
        $photoSrc = '';
        $photoRel = (string)($part['photo'] ?? '');
        if ($photoRel !== '') {
            $fsPath = __DIR__ . '/../' . ltrim($photoRel, '/');
            if (is_file($fsPath)) {
                $mime = 'image/jpeg';
                $ext = strtolower(pathinfo($fsPath, PATHINFO_EXTENSION));
                if ($ext === 'png') $mime = 'image/png'; 
                elseif ($ext === 'gif') $mime = 'image/gif';
                $photoData = @file_get_contents($fsPath);
                if ($photoData !== false) { 
                    $photoSrc = 'data:' . $mime . ';base64,' . base64_encode($photoData); 
                }
            }
        }

        // Préparer le HTML via le template permis_tmp.php modifié
        ob_start();
        include __DIR__ . '/../views/permis_tmp_filled.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf(['isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 500, 315], 'landscape'); // Format carte
        $dompdf->render();

        // Sauvegarder dans uploads/permis_temporaire
        $dir = __DIR__ . '/../uploads/permis_temporaire';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        if (!is_dir($dir)) { throw new \RuntimeException('Impossible de créer le répertoire: ' . $dir); }
        if (!is_writable($dir)) { @chmod($dir, 0775); }
        if (!is_writable($dir)) { throw new \RuntimeException('Répertoire non inscriptible: ' . $dir); }
        $filename = 'permis_temporaire_' . ($data['id'] ?? 'N') . '.pdf';
        $filePath = $dir . '/' . $filename;
        $bytes = @file_put_contents($filePath, $dompdf->output());
        if ($bytes === false || !is_file($filePath) || filesize($filePath) <= 0) {
            throw new \RuntimeException('Echec écriture PDF dans ' . $filePath);
        }

        // URL publique et relative
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        $relativeUrl = $basePath . '/uploads/permis_temporaire/' . $filename;
        $publicUrl = null;
        if (isset($_SERVER['HTTP_HOST'])) {
            $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
            $publicUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $relativeUrl;
        }

        return ['path' => $filePath, 'public_url' => $publicUrl, 'relative_url' => $relativeUrl, 'filename' => $filename];
    }

    /**
     * Sauvegarder le PDF du permis temporaire sur le serveur (depuis upload client)
     */
    public function savePdfToServer($id)
    {
        $this->getConnexion();
        
        // Récupérer les données du permis temporaire via l'ORM
        $permisRow = ORM::for_table('permis_temporaire')->find_one((int)$id);
        if (!$permisRow) {
            return ['ok' => false, 'error' => 'Permis temporaire introuvable'];
        }

        // Vérifier si le PDF existe déjà
        $existingPath = trim((string)($permisRow->pdf_path ?? ''));
        if ($existingPath !== '') {
            $existingPdfPath = __DIR__ . '/../' . ltrim($existingPath, '/');
            if (file_exists($existingPdfPath)) {
                return [
                    'ok' => true,
                    'message' => 'PDF déjà existant',
                    'pdf_path' => $existingPath,
                    'download_url' => '/' . ltrim($existingPath, '/')
                ];
            }
        }

        // Vérifier si un fichier PDF a été uploadé
        if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Aucun fichier PDF reçu ou erreur d\'upload'];
        }

        $uploadedFile = $_FILES['pdf'];
        
        // Vérifier le type MIME
        if ($uploadedFile['type'] !== 'application/pdf') {
            return ['ok' => false, 'error' => 'Le fichier doit être un PDF'];
        }

        // Créer le dossier de destination s'il n'existe pas
        $uploadsDir = __DIR__ . '/../uploads';
        $permisDir = $uploadsDir . '/permis_temporaire';
        if (!is_dir($permisDir)) {
            if (!mkdir($permisDir, 0755, true)) {
                return ['ok' => false, 'error' => 'Impossible de créer le dossier permis_temporaire'];
            }
        }

        // Nom du fichier de destination
        $numero = (string)($permisRow->numero ?? '');
        $cibleType = (string)($permisRow->cible_type ?? '');
        
        // Différencier le nom selon le type (permis ou plaque)
        if ($cibleType === 'vehicule_plaque') {
            $filename = 'plaque_temporaire_' . ($numero ?: $id) . '.pdf';
        } else {
            $filename = 'permis_temporaire_' . ($numero ?: $id) . '.pdf';
        }
        $destinationPath = $permisDir . '/' . $filename;

        // Déplacer le fichier uploadé
        if (!move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
            return ['ok' => false, 'error' => 'Impossible de sauvegarder le fichier PDF'];
        }

        // Mettre à jour le chemin dans la base de données via l'ORM
        $relativePath = 'uploads/permis_temporaire/' . $filename;
        $permisRow->pdf_path = $relativePath;
        $permisRow->updated_at = date('Y-m-d H:i:s');
        $permisRow->save();

        return [
            'ok' => true,
            'message' => 'PDF sauvegardé avec succès',
            'pdf_path' => $relativePath,
            'download_url' => '/' . $relativePath,
        ];
    }

    /**
     * Sauvegarder le PDF de la plaque temporaire sur le serveur (depuis upload client)
     */
    public function savePlaquePdfToServer($id)
    {
        return $this->savePdfToServer($id); // Utilise la même logique mais avec différenciation du nom de fichier
    }
}
