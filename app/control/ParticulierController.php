<?php

namespace Control;

use Exception;
use Model\Db;
use Model\ActivityLogger;
use ORM;

class ParticulierController extends Db{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function create($data){

        $this->getConnexion();

        $particulier = ORM::for_table('particuliers')
            ->create();
        $particulier->nom = $data['nom'];
        $particulier->adresse = $data['adresse'];
        $particulier->profession = $data['profession'];
        $particulier->date_naissance = $data['date_naissance'];
        $particulier->genre = $data['genre'];
        $particulier->numero_national = $data['numero_national'];
        $particulier->gsm = $data['telephone'];
        $particulier->email = $data['email'];
        $particulier->lieu_naissance = $data['lieu_naissance'];
        $particulier->nationalite = $data['nationalite'];
        $particulier->etat_civil = $data['etat_civil'];
        $particulier->personne_contact = $data['personne_contact'];
        $particulier->observations = $data['observations'];

        // Optional photo upload handling
        try {
            if (isset($_FILES) && isset($_FILES['photo']) && is_array($_FILES['photo']) && (int)($_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['photo'];
                $err = (int)($file['error'] ?? UPLOAD_ERR_OK);
                if ($err === UPLOAD_ERR_OK) {
                    $tmp = $file['tmp_name'] ?? null;
                    $size = (int)($file['size'] ?? 0);
                    $name = (string)($file['name'] ?? '');
                    // Basic validations
                    if ($tmp && is_uploaded_file($tmp) && $size > 0 && $size <= 5 * 1024 * 1024) {
                        // Validate mime/type
                        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
                        $mime = $finfo ? finfo_file($finfo, $tmp) : null;
                        if ($finfo) { finfo_close($finfo); }
                        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
                        $ext = null;
                        if ($mime && isset($allowed[$mime])) {
                            $ext = $allowed[$mime];
                        } else {
                            // Fallback by extension
                            $lower = strtolower($name);
                            foreach ($allowed as $m => $e) { if (str_ends_with($lower, '.'.$e)) { $ext = $e; break; } }
                        }
                        if ($ext) {
                            $uploadDir = __DIR__ . '/../uploads/particuliers/';
                            if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
                            $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($name, PATHINFO_FILENAME));
                            if ($safeBase === '' || $safeBase === null) { $safeBase = 'photo'; }
                            $filename = $safeBase . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                            $dest = $uploadDir . $filename;
                            if (@move_uploaded_file($tmp, $dest)) {
                                // Store relative path from app/ root
                                $relative = 'uploads/particuliers/' . $filename;
                                $particulier->photo = $relative;
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore photo errors to keep upload optional
        }
        
        if($particulier->save()){
            // Logger la création du particulier
            $this->activityLogger->logCreate(
                $_SESSION['username'] ?? null,
                'particuliers',
                $particulier->id(),
                ['nom' => $data['nom'], 'numero_national' => $data['numero_national'], 'photo' => $particulier->photo ?? null]
            );
            
            $result['state'] = true;
            $result['message'] = $data['nom'].' a été enregistré avec succès';
            $result['id'] = $particulier->id;
            $result['data'] = $particulier;
            return $result;
        }else{
            $result['state'] = false;
            $result['message'] = 'Erreur lors de l\'enregistrement du particulier';
            return $result;
        }


    }  

    /**
     * Récupérer la liste des particuliers (plus récents d'abord)
     * @return array
     */
    public function listAll(): array
    {
        try {
            $this->getConnexion();
            $rows = ORM::for_table('particuliers')
                ->order_by_desc('id')
                ->find_array();
            
            // Logger la consultation de la liste des particuliers
            $this->activityLogger->logView(
                $_SESSION['username'] ?? null,
                'particuliers_list',
                "Consultation de la liste des particuliers (" . count($rows) . " résultats)"
            );
            
            return is_array($rows) ? $rows : [];
        } catch (Exception $e) {
            error_log('ParticulierController::listAll error: '.$e->getMessage());
            return [];
        }
    }

    /**
     * Liste paginée des particuliers
     * @return array{particuliers: array, part_pagination: array}|array
     */
    public function listAllPaginated(): array
    {
        try {
            $this->getConnexion();
            $page = isset($_GET['part_page']) ? (int)$_GET['part_page'] : 1;
            if ($page < 1) $page = 1;
            $perPage = isset($_GET['part_per_page']) ? (int)$_GET['part_per_page'] : 20;
            if ($perPage < 5) $perPage = 20; if ($perPage > 100) $perPage = 100;

            $total = ORM::for_table('particuliers')->count();
            $totalPages = $perPage > 0 ? (int)ceil($total / $perPage) : 1;
            if ($totalPages < 1) $totalPages = 1;
            if ($page > $totalPages) $page = $totalPages;
            $offset = ($page - 1) * $perPage;

            $rows = ORM::for_table('particuliers')
                ->order_by_desc('id')
                ->limit($perPage)
                ->offset($offset)
                ->find_array();

            // Logger
            $this->activityLogger->logView(
                $_SESSION['username'] ?? null,
                'particuliers_list',
                "Consultation Particuliers (page $page/$totalPages, perPage $perPage, affichées " . count($rows) . "/$total)"
            );

            return [
                'particuliers' => is_array($rows) ? $rows : [],
                'part_pagination' => [
                    'total' => (int)$total,
                    'page' => (int)$page,
                    'per_page' => (int)$perPage,
                    'total_pages' => (int)$totalPages,
                ],
            ];
        } catch (Exception $e) {
            error_log('ParticulierController::listAllPaginated error: '.$e->getMessage());
            return [
                'particuliers' => [],
                'part_pagination' => [
                    'total' => 0,
                    'page' => 1,
                    'per_page' => 20,
                    'total_pages' => 1,
                ],
            ];
        }
    }

    /**
     * Rechercher des particuliers par nom (utilisé par le transfert de véhicule)
     * @param string $q
     * @param int $limit
     * @return array{id:int,nom:string,numero_national?:string,gsm?:string}[]
     */
    public function searchByName(string $q = '', int $limit = 20): array
    {
        try {
            $this->getConnexion();
            $q = trim($q);
            $orm = ORM::for_table('particuliers')
                ->select_many('id','nom','numero_national','gsm')
                ->order_by_asc('nom');
            if ($q !== '') {
                $orm = $orm->where_like('nom', '%'.$q.'%');
            }
            if ($limit > 0) { $orm = $orm->limit($limit); }
            $rows = $orm->find_array();
            return is_array($rows) ? $rows : [];
        } catch (Exception $e) {
            error_log('ParticulierController::searchByName error: '.$e->getMessage());
            return [];
        }
    }
}
