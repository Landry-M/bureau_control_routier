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
    
    /**
     * Récupère les informations d'un véhicule depuis le site externe DGI
     * et renvoie un tableau clé/valeurs prêt à être utilisé côté frontend.
     * @param string $plate
     * @return array { ok: bool, data?: array, error?: string, raw?: string }
     */
    public function fetchFromExternal(string $plate): array
    {
        $this->getConnexion(); // s'assurer que l'appli est correctement initialisée
        $p = trim($plate);
        if ($p === '') {
            return ['ok' => false, 'error' => 'Plaque vide'];
        }

        // Construire l'URL en remplaçant le token par la plaque fournie
        // Exemple fourni: https://dgi-carterose.cd/register/7862ab14#enregistrement
        $url = 'https://dgi-carterose.cd/register/' . rawurlencode($p) . '#enregistrement';

        try {
            $html = $this->httpGet($url);
            if ($html === null) {
                return ['ok' => false, 'error' => 'Aucune réponse du site externe'];
            }
            $mapped = $this->parseExternalHtml($html);
            // Injecter la plaque si non trouvée par le parseur
            if (!isset($mapped['num_plaque']) || !$mapped['num_plaque']) {
                $mapped['num_plaque'] = $p;
            }
            return ['ok' => true, 'data' => $mapped];
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Erreur externe: ' . $e->getMessage()];
        }
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
        // Import information
        $vehicule_plaque->frontiere_entree = isset($data['frontiere_entree']) && $data['frontiere_entree'] !== '' ? $data['frontiere_entree'] : null;
        $vehicule_plaque->date_importation = isset($data['date_importation']) && $data['date_importation'] !== '' ? $data['date_importation'] : null;
        
        // License plate information
        $vehicule_plaque->plaque = $data['plaque'];
        $vehicule_plaque->plaque_valide_le = isset($data['plaque_valide_le']) && $data['plaque_valide_le'] !== '' ? $data['plaque_valide_le'] : null;
        $vehicule_plaque->plaque_expire_le = isset($data['plaque_expire_le']) && $data['plaque_expire_le'] !== '' ? $data['plaque_expire_le'] : null;
        
        // Insurance information
        $vehicule_plaque->nume_assurance = isset($data['nume_assurance']) && $data['nume_assurance'] !== '' ? $data['nume_assurance'] : null;
        $vehicule_plaque->date_expire_assurance = isset($data['date_expire_assurance']) && $data['date_expire_assurance'] !== '' ? $data['date_expire_assurance'] : null;
        $vehicule_plaque->date_valide_assurance = isset($data['date_valide_assurance']) && $data['date_valide_assurance'] !== '' ? $data['date_valide_assurance'] : null;
        $vehicule_plaque->societe_assurance = isset($data['societe_assurance']) && $data['societe_assurance'] !== '' ? $data['societe_assurance'] : null;
        
        // Technical details (optional, from DGI)
        if (isset($data['genre'])) $vehicule_plaque->genre = $data['genre'] !== '' ? $data['genre'] : null;
        if (isset($data['usage'])) $vehicule_plaque->usage = $data['usage'] !== '' ? $data['usage'] : null;
        if (isset($data['numero_declaration'])) $vehicule_plaque->numero_declaration = $data['numero_declaration'] !== '' ? $data['numero_declaration'] : null;
        if (isset($data['num_moteur'])) $vehicule_plaque->num_moteur = $data['num_moteur'] !== '' ? $data['num_moteur'] : null;
        if (isset($data['origine'])) $vehicule_plaque->origine = $data['origine'] !== '' ? $data['origine'] : null;
        if (isset($data['source'])) $vehicule_plaque->source = $data['source'] !== '' ? $data['source'] : null;
        if (isset($data['annee_fab'])) $vehicule_plaque->annee_fab = $data['annee_fab'] !== '' ? $data['annee_fab'] : null;
        if (isset($data['annee_circ'])) $vehicule_plaque->annee_circ = $data['annee_circ'] !== '' ? $data['annee_circ'] : null;
        if (isset($data['type_em'])) $vehicule_plaque->type_em = $data['type_em'] !== '' ? $data['type_em'] : null;
        
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
     * Effectue un GET HTTP simple avec cURL et renvoie le corps HTML.
     * @param string $url
     * @return string|null
     */
    private function httpGet(string $url): ?string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);
        $resp = curl_exec($ch);
        if ($resp === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('Echec de requête: ' . $err);
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code < 200 || $code >= 300) {
            throw new Exception('HTTP ' . $code);
        }
        return $resp;
    }

    /**
     * Parse l'HTML externe et essaie d'extraire les champs attendus.
     * Champs attendus: num_chassis, num_plaque, genre, usage, marque, modele,
     * couleur, numero_declaration, num_moteur, origine, source, annee_fab,
     * annee_circ, type_em
     * @param string $html
     * @return array
     */
    private function parseExternalHtml(string $html): array
    {
        $result = [
            'num_chassis' => null,
            'num_plaque' => null,
            'genre' => null,
            'usage' => null,
            'marque' => null,
            'modele' => null,
            'couleur' => null,
            'numero_declaration' => null,
            'num_moteur' => null,
            'origine' => null,
            'source' => null,
            'annee_fab' => null,
            'annee_circ' => null,
            'type_em' => null,
        ];

        // Charger l'HTML dans DOMDocument
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $loaded = $dom->loadHTML($html);
        libxml_clear_errors();
        if (!$loaded) {
            return $result;
        }
        $xpath = new \DOMXPath($dom);

        // Stratégie 1: rechercher des input/select/textarea par attribut name ou id
        $keys = array_keys($result);
        foreach ($keys as $key) {
            // by name
            $nodes = $xpath->query(sprintf('//*[@name="%s"]', $key));
            if ($nodes && $nodes->length > 0) {
                $val = $this->nodeValue($nodes->item(0));
                if ($val !== null && $val !== '') { $result[$key] = $val; continue; }
            }
            // by id
            $nodes = $xpath->query(sprintf('//*[@id="%s"]', $key));
            if ($nodes && $nodes->length > 0) {
                $val = $this->nodeValue($nodes->item(0));
                if ($val !== null && $val !== '') { $result[$key] = $val; continue; }
            }
        }

        // Stratégie 2: Tables libellées Label: Valeur (rechercher les libellés)
        // On tente de repérer des paires label/valeur fréquentes
        $labelMap = [
            'num_chassis' => ['chassis', 'numéro de châssis', 'vin'],
            'num_plaque' => ['plaque', 'immatriculation'],
            'genre' => ['genre'],
            'usage' => ['usage'],
            'marque' => ['marque'],
            'modele' => ['modèle', 'modele'],
            'couleur' => ['couleur'],
            'numero_declaration' => ['numero declaration', 'n° déclaration', 'declaration'],
            'num_moteur' => ['num moteur', 'moteur'],
            'origine' => ['origine'],
            'source' => ['source'],
            'annee_fab' => ['année fab', 'annee fab', 'année fabrication', 'fabrication'],
            'annee_circ' => ['année circ', 'annee circ', 'année mise en circulation', 'mise en circulation'],
            'type_em' => ['type em', 'type emission', 'type'],
        ];

        // Rechercher dans des cellules de tableau (th/td) ou dt/dd
        foreach ($labelMap as $field => $candidates) {
            if ($result[$field]) continue;
            foreach ($candidates as $cand) {
                $candLc = mb_strtolower($cand, 'UTF-8');
                // Chercher th ou td contenant le label
                $nodes = $xpath->query(sprintf('//*[self::th or self::td or self::label or self::dt][contains(translate(normalize-space(.), "ABCDEFGHIJKLMNOPQRSTUVWXYZÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸ", "abcdefghijklmnopqrstuvwxyzàâäçéèêëîïôöùûüÿ"), "%s")]//following::*[self::td or self::dd][1]', $candLc));
                if ($nodes && $nodes->length > 0) {
                    $val = trim($nodes->item(0)->textContent ?? '');
                    if ($val !== '') { $result[$field] = $val; break; }
                }
            }
        }

        return $result;
    }

    /**
     * Extrait la valeur d'un input/select/textarea ou le texte d'un noeud générique.
     * @param \DOMNode $node
     * @return string|null
     */
    private function nodeValue(\DOMNode $node): ?string
    {
        if ($node instanceof \DOMElement) {
            $tag = strtolower($node->tagName);
            if ($tag === 'input' || $tag === 'select' || $tag === 'textarea') {
                $val = $node->getAttribute('value');
                if ($tag === 'select') {
                    $options = $node->getElementsByTagName('option');
                    $selected = [];
                    foreach ($options as $o) {
                        if ($o instanceof \DOMElement && $o->hasAttribute('selected')) { $selected[] = $o; }
                    }
                    if (count($selected) >= 2) {
                        // Prendre la 2e option marquée selected
                        $opt = $selected[1];
                        $val = $opt->getAttribute('value');
                        if ($val === '') { $val = $opt->textContent; }
                    } elseif (count($selected) === 1) {
                        // Fallback: 1ère selected
                        $opt = $selected[0];
                        $val = $opt->getAttribute('value');
                        if ($val === '') { $val = $opt->textContent; }
                    } elseif ($options->length >= 2) {
                        // Fallback: 2e option de la liste
                        $opt = $options->item(1);
                        if ($opt instanceof \DOMElement) {
                            $val = $opt->getAttribute('value');
                            if ($val === '') { $val = $opt->textContent; }
                        }
                    }
                }
                return $val !== '' ? trim($val) : null;
            }
        }
        $txt = trim($node->textContent ?? '');
        return $txt !== '' ? $txt : null;
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

    /**
     * Recherche de véhicule par plaque.
     * Si une correspondance exacte (insensible à la casse) existe, la renvoyer en priorité.
     * Sinon, renvoie une liste limitée par LIKE.
     * @param string $plate
     * @param int $limit
     * @return array
     */
    public function searchByPlate(string $plate, int $limit = 10): array
    {
        $this->getConnexion();
        $p = trim($plate);
        if ($p === '') return [];
        try {
            // Exact match (case-insensitive)
            $stmt = ORM::get_db()->prepare("SELECT * FROM `vehicule_plaque` WHERE UPPER(`plaque`) = :pl ORDER BY id ASC");
            $stmt->execute([':pl' => mb_strtoupper($p, 'UTF-8')]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
            if ($rows) return $rows;
            // Fallback LIKE
            $stmt2 = ORM::get_db()->prepare("SELECT * FROM `vehicule_plaque` WHERE `plaque` LIKE :pl ORDER BY id DESC LIMIT :lim");
            $like = '%' . $p . '%';
            $stmt2->bindValue(':pl', $like, \PDO::PARAM_STR);
            $stmt2->bindValue(':lim', max(1, min(100, $limit)), \PDO::PARAM_INT);
            $stmt2->execute();
            return $stmt2->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            error_log('[VehiculePlaqueController] searchByPlate error: ' . $e->getMessage());
            return [];
        }
    }
}
