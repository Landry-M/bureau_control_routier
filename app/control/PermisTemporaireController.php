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
                $pdfInfo = $this->generatePermisParticulierPdf([
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
}
