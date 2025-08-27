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
        $this->getConnexion();
        $contravention = ORM::for_table('contraventions')
            ->create();
        
        $contravention->dossier_id = $data['dossier_id'];
        $contravention->type_dossier = $data['type_dossier'];
        $contravention->date_infraction = $data['date_infraction'];
        $contravention->lieu = $data['lieu'];
        $contravention->type_infraction = $data['type_infraction'];
        $contravention->description = $data['description'];
        $contravention->reference_loi = $data['reference_loi'];
        $contravention->amende = $data['amende'];
        $contravention->payed = $data['payed'];
        
        if ($contravention->save()) {
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
}