<?php

require_once 'vendor/autoload.php';

date_default_timezone_set('Africa/Lubumbashi');

//use Control\Control_user;
use Control\UsersController;
use Control\NotificationsController;
use Control\GeneralController;
use Control\EntrepriseController;
use Control\DossierController;
use Control\ParticulierController;
use Control\VehiculePlaqueController;
use Control\ContraventionsController;
use Control\ContraventionController;
use Control\ConducteurVehiculeController;
use Control\ParticulierVehiculeController;
use Control\AgentAccountController;
use Control\ProfileController;
use Control\AgentManagementController;
use Control\AccidentController;
use Control\AvisRechercheController;
use Control\LogoutController;
use Control\SearchController;
use Control\PermisTemporaireController;
use Control\ArrestationController;

//initialisation de la superglobal SESSION
if(!isset($_SESSION))
{
   session_start();
}

//var_dump($_SERVER['REQUEST_URI']);

$router = new AltoRouter();


$router->map('GET','/',function (){

    if(isset($_SESSION['user']) ){
        require_once 'views/home2.php';
    }else{
        require_once 'views/login.php';
    }
});

// API: Remettre un véhicule en circulation (met en_circulation=1)
$router->map('POST','/vehicule/[i:id]/remettre', function($id){
    // JSON clean response
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        // Initialiser la connexion DB pour configurer Idiorm correctement
        (new \Model\Db())->getConnexion();
        $vehIdPath = (int)$id;
        $vehIdPost = isset($_POST['vehicule_id']) ? (int)$_POST['vehicule_id'] : 0;
        $vehId = $vehIdPath > 0 ? $vehIdPath : $vehIdPost;
        if ($vehId <= 0) { ob_clean(); http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Paramètres manquants']); return; }
        // Charger le véhicule
        $row = \ORM::for_table('vehicule_plaque')->find_one($vehId);
        if (!$row) { ob_clean(); http_response_code(404); echo json_encode(['ok'=>false,'error'=>'Véhicule introuvable']); return; }
        // Mémoriser l'ancien état
        $old = [ 'en_circulation' => isset($row->en_circulation) ? (int)$row->en_circulation : null ];
        // Remettre en circulation
        $row->set('en_circulation', 1);
        $row->save();
        // Etat après
        $afterRow = \ORM::for_table('vehicule_plaque')->find_one($vehId);
        $after = $afterRow ? [ 'en_circulation' => isset($afterRow->en_circulation) ? (int)$afterRow->en_circulation : null ] : null;
        // Journaliser
        try {
            (new \Model\ActivityLogger())->logUpdate(
                $_SESSION['user']['username'] ?? null,
                'vehicule_plaque',
                (int)$row->id,
                $old,
                [ 'en_circulation' => 1 ]
            );
        } catch (\Throwable $e) { /* ignore logging errors */ }
        ob_clean(); echo json_encode(['ok'=>true, 'before'=>$old, 'after'=>$after]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
    }
});

// API: Retirer une plaque d'immatriculation d'un véhicule
$router->map('POST','/plaque/retirer', function(){
    // JSON clean response
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        // Initialiser la connexion DB pour configurer Idiorm correctement
        (new \Model\Db())->getConnexion();
        $vehId = isset($_POST['vehicule_id']) ? (int)$_POST['vehicule_id'] : 0;
        $raison = isset($_POST['raison']) ? trim((string)$_POST['raison']) : '';
        if ($vehId <= 0 || $raison === '') { ob_clean(); http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Paramètres manquants']); return; }
        // Charger le véhicule
        $row = \ORM::for_table('vehicule_plaque')->find_one($vehId);
        if (!$row) { ob_clean(); http_response_code(404); echo json_encode(['ok'=>false,'error'=>'Véhicule introuvable']); return; }
        // Mémoriser l'ancien état pour log éventuel
        $old = [
            'en_circulation' => isset($row->en_circulation) ? (int)$row->en_circulation : null,
            'plaque' => $row->plaque,
            'plaque_valide_le' => $row->plaque_valide_le,
            'plaque_expire_le' => $row->plaque_expire_le
        ];
        // Mettre le véhicule hors circulation sans toucher aux champs de plaque
        $row->set('en_circulation', 0);
        $row->save();
        // Relire l'état après sauvegarde
        $afterRow = \ORM::for_table('vehicule_plaque')->find_one($vehId);
        $after = $afterRow ? [
            'en_circulation' => isset($afterRow->en_circulation) ? (int)$afterRow->en_circulation : null,
            'plaque' => $afterRow->plaque,
            'plaque_valide_le' => $afterRow->plaque_valide_le,
            'plaque_expire_le' => $afterRow->plaque_expire_le
        ] : null;
        // Optionnel: logger
        try {
            $logger = new \Model\ActivityLogger();
            $logger->logUpdate(
                $_SESSION['user']['username'] ?? null,
                'vehicule_plaque',
                (int)$row->id,
                $old,
                [ 'en_circulation' => 0, 'raison' => $raison ]
            );
        } catch (\Throwable $e) { /* ignore logging errors */ }
        ob_clean(); echo json_encode(['ok'=>true, 'before'=>$old, 'after'=>$after]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
    }
});

// API: Retirer un véhicule de la circulation (met en_circulation=0)
$router->map('POST','/vehicule/[i:id]/retirer', function($id){
    // JSON clean response
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        // Initialiser la connexion DB pour configurer Idiorm correctement
        (new \Model\Db())->getConnexion();
        $vehIdPath = (int)$id;
        $vehIdPost = isset($_POST['vehicule_id']) ? (int)$_POST['vehicule_id'] : 0;
        $vehId = $vehIdPath > 0 ? $vehIdPath : $vehIdPost;
        $raison = isset($_POST['raison']) ? trim((string)$_POST['raison']) : '';
        // date_effet optionnelle (non utilisée pour l'instant)
        $dateEffet = isset($_POST['date_effet']) ? trim((string)$_POST['date_effet']) : null;
        if ($vehId <= 0) { ob_clean(); http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Paramètres manquants']); return; }
        // Charger le véhicule
        $row = \ORM::for_table('vehicule_plaque')->find_one($vehId);
        if (!$row) { ob_clean(); http_response_code(404); echo json_encode(['ok'=>false,'error'=>'Véhicule introuvable']); return; }
        // Mémoriser l'ancien état
        $old = [ 'en_circulation' => isset($row->en_circulation) ? (int)$row->en_circulation : null ];
        // Mettre hors circulation
        $row->set('en_circulation', 0);
        $row->save();
        // Etat après
        $afterRow = \ORM::for_table('vehicule_plaque')->find_one($vehId);
        $after = $afterRow ? [ 'en_circulation' => isset($afterRow->en_circulation) ? (int)$afterRow->en_circulation : null ] : null;
        // Journaliser
        try {
            (new \Model\ActivityLogger())->logUpdate(
                $_SESSION['user']['username'] ?? null,
                'vehicule_plaque',
                (int)$row->id,
                $old,
                [ 'en_circulation' => 0, 'raison' => $raison, 'date_effet' => $dateEffet ]
            );
        } catch (\Throwable $e) { /* ignore logging errors */ }
        ob_clean(); echo json_encode(['ok'=>true, 'before'=>$old, 'after'=>$after]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
    }
});

// API: Permis temporaire - lister par véhicule
$router->map('GET','/vehicule/[i:id]/permis-temporaire', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new PermisTemporaireController();
        $rows = $ctrl->listByVehicule((int)$id);
        ob_clean(); echo json_encode(['ok'=>true,'data'=>$rows]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
    }
});

// API: Plaque temporaire pour un véhicule
$router->map('POST','/plaque/temporaire', function(){
    // Réponse JSON propre
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $vehId = isset($_POST['vehicule_id']) ? (int)$_POST['vehicule_id'] : 0;
        $numero = isset($_POST['numero']) ? trim((string)$_POST['numero']) : '';
        $du = isset($_POST['du']) ? trim((string)$_POST['du']) : '';
        $au = isset($_POST['au']) ? trim((string)$_POST['au']) : '';
        if ($vehId <= 0 || $numero === '') {
            ob_clean(); http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Paramètres manquants']); return;
        }
        $payload = [
            'cible_type' => 'vehicule_plaque',
            'cible_id' => $vehId,
            'numero' => $numero,
            'motif' => 'plaque_temporaire'
        ];
        if ($du !== '') { $payload['date_debut'] = $du; }
        if ($au !== '') { $payload['date_fin'] = $au; }
        $ctrl = new PermisTemporaireController();
        $res = $ctrl->create($payload);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: transférer la propriété d'un véhicule à un particulier
$router->map('POST','/vehicule/[i:id]/transferer', function($id){
    // JSON propre sans pollution d'output
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $vehIdPath = (int)$id;
        $vehIdPost = isset($_POST['vehicule_id']) ? (int)$_POST['vehicule_id'] : 0;
        // Fiabiliser: si les deux existent et diffèrent, on prend celui de l'URL
        $vehId = $vehIdPath > 0 ? $vehIdPath : $vehIdPost;
        $newOwner = isset($_POST['nouveau_proprietaire']) ? (int)$_POST['nouveau_proprietaire'] : 0;
        $motif = isset($_POST['motif']) ? trim((string)$_POST['motif']) : null;
        if ($vehId <= 0 || $newOwner <= 0) { ob_clean(); http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Paramètres manquants']); return; }
        $ctrl = new ParticulierVehiculeController();
        $res = $ctrl->transferOwnership($vehId, $newOwner, $motif);
        // Journaliser l'opération
        try {
            (new \Model\ActivityLogger())->logUpdate(
                $_SESSION['user']['username'] ?? null,
                'vehicule_transfer',
                $vehId,
                null,
                ['nouveau_proprietaire'=>$newOwner, 'motif'=>$motif, 'result'=>$res['ok'] ?? null]
            );
        } catch (\Throwable $e) { /* ignore logging errors */ }
        ob_clean(); echo json_encode($res);
    } catch (Exception $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Particulier - lister les arrestations (JSON)
$router->map('GET','/particulier/[i:id]/arrestations', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new ArrestationController();
        $rows = $ctrl->listByParticulier((int)$id) ?? [];
        ob_clean(); echo json_encode(['ok'=>true,'items'=>$rows]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Arrestation - libérer par ID
$router->map('POST','/arrestation/[i:id]/release', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new ArrestationController();
        $res = $ctrl->releaseById((int)$id, $_POST['date_sortie_prison'] ?? null);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Particulier - libérer la dernière arrestation active
$router->map('POST','/particulier/[i:id]/liberer', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new ArrestationController();
        $res = $ctrl->releaseLatestActiveByParticulier((int)$id, $_POST['date_sortie_prison'] ?? null);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Arrestation d'un particulier - consigner
$router->map('POST','/arrestation', function(){
    // Réponse JSON propre
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new ArrestationController();
        $res = $ctrl->create($_POST);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Arrestations - lister par particulier
$router->map('GET','/particulier/[i:id]/arrestations', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new ArrestationController();
        $rows = $ctrl->listByParticulier((int)$id);
        ob_clean(); echo json_encode(['ok'=>true,'data'=>$rows]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Permis temporaire - créer
$router->map('POST','/permis-temporaire', function(){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new PermisTemporaireController();
        $res = $ctrl->create($_POST);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Avis de recherche - créer
$router->map('POST','/avis-recherche', function(){
    // JSON propre
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new AvisRechercheController();
        $res = $ctrl->create($_POST);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Avis de recherche - lister par particulier
$router->map('GET','/particulier/[i:id]/avis', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new AvisRechercheController();
        $rows = $ctrl->listByParticulier((int)$id);
        ob_clean(); echo json_encode(['ok'=>true,'data'=>$rows]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Avis de recherche - clore
$router->map('POST','/avis-recherche/[i:id]/close', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new AvisRechercheController();
        $res = $ctrl->close((int)$id);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Permis temporaire - lister par particulier
$router->map('GET','/particulier/[i:id]/permis-temporaire', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new PermisTemporaireController();
        $rows = $ctrl->listByParticulier((int)$id);
        ob_clean(); echo json_encode(['ok'=>true,'data'=>$rows]);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: Permis temporaire - clore
$router->map('POST','/permis-temporaire/[i:id]/close', function($id){
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $ctrl = new PermisTemporaireController();
        $res = $ctrl->close((int)$id);
        ob_clean(); echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: véhicules d'un particulier (JSON)
$router->map('GET','/particulier/[i:id]/vehicules', function($id){
    // Eviter toute pollution de sortie qui casse le JSON
    error_reporting(0);
    ini_set('display_errors', 0);
    ob_start();

    if(!isset($_SESSION['user'])){
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
        return;
    }
    try {
        $pid = (int)$id;
        $ctrl = new ParticulierVehiculeController();
        $rows = $ctrl->listByParticulier($pid);
        // Log consultation
        try {
            (new \Model\ActivityLogger())->logView($_SESSION['user']['username'] ?? null, 'particulier_vehicules', ['particulier_id'=>$pid, 'results'=>count($rows)]);
        } catch (\Throwable $e) {}
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok'=>true,'data'=>$rows]);
    } catch (\Throwable $e) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// Associer un véhicule à un particulier
$router->map('POST','/particulier/associer-vehicule', function(){
    // Eviter toute pollution de sortie qui casse le JSON
    error_reporting(0);
    ini_set('display_errors', 0);
    ob_start();

    if (!isset($_SESSION['user'])) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode(['ok'=>false, 'error'=>'Unauthorized']);
        return;
    }
    try {
        $pid = $_POST['particulier_id'] ?? null;
        $vid = $_POST['vehicule_plaque_id'] ?? null;
        if (!$pid || !$vid) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(['ok'=>false, 'error'=>'Paramètres manquants']);
            return;
        }
        $ctrl = new ParticulierVehiculeController();
        $res = $ctrl->createAssociation($_POST);
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res);
    } catch (\Throwable $e) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode(['ok'=>false, 'error'=>'Server error']);
    }
});

// API: recherche véhicule par plaque
$router->map('GET','/api/vehicules/search', function(){
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
        return;
    }
    try {
        $plate = isset($_GET['plate']) ? (string)$_GET['plate'] : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $ctrl = new VehiculePlaqueController();
        $rows = $ctrl->searchByPlate($plate, $limit);
        echo json_encode(['ok'=>true, 'data'=>$rows]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
    }
});

// API: rechercher des particuliers par nom (pour transfert véhicule)
$router->map('GET','/particuliers/search', function(){
    // Réponse JSON propre
    error_reporting(0); ini_set('display_errors', 0); ob_start();
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) { ob_clean(); http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Unauthorized']); return; }
    try {
        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $ctrl = new ParticulierController();
        $rows = $ctrl->searchByName($q, $limit);
        // Journaliser la recherche
        try {
            (new \Model\ActivityLogger())->logSearch($_SESSION['user']['username'] ?? null, 'particuliers_search', ['q'=>$q, 'limit'=>$limit], is_array($rows) ? count($rows) : null);
        } catch (\Throwable $e) { /* ignore logging errors */ }
        ob_clean(); echo json_encode(['ok'=>true,'items'=>$rows]);
    } catch (Exception $e) {
        ob_clean(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// Admin: migration des contraventions -> remapper dossier_id vers ID primaire
$router->map('GET','/admin/migrate-contraventions-dossier-id', function(){
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'superadmin') {
        http_response_code(403);
        echo json_encode(['ok'=>false, 'error'=>'Accès non autorisé']);
        return;
    }
    try {
        $ctrl = new ContraventionController();
        $res = $ctrl->migrateDossierIdToPrimary();
        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
    }
});

// Créer une contravention
$router->map('POST','/contravention/create', function(){
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['ok'=>false, 'error'=>'Unauthorized']);
        return;
    }
    try {
        $ctrl = new ContraventionsController();
        $res = $ctrl->create($_POST);
        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
    }
});

// Recherche globale
$router->map('GET','/search', function(){
    if(isset($_SESSION['user'])){
        $ctrl = new SearchController();
        $ctrl->search();
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour effectuer une recherche.';
        header('Location: /login');
        exit;
    }
});

$router->map('GET','/search/detail', function(){
    if(isset($_SESSION['user'])){
        $ctrl = new SearchController();
        $ctrl->detail();
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour effectuer une recherche.';
        header('Location: /login');
        exit;
    }
});

 

$router->map('GET','/login',function (){
    if(isset($_SESSION['user']) ){
        require_once 'views/home2.php';
    }else{
        require_once 'views/login.php';
    }
});

$router->map('GET','/logout',function (){
    (new LogoutController)->logout();
    require_once 'views/login.php';
});


$router->map('GET','/create-folder',function (){
    
    if(isset($_SESSION['user']) ){
        // Préparer la liste des véhicules/plaque et leurs contraventions
        // $dossierController = new DossierController();
        // $vehiculesData = $dossierController->getVehiculesWithContraventions();
        // $vehicules = $vehiculesData['vehicules'] ?? [];
        // $contraventionsByVehicule = $vehiculesData['contraventionsByVehicule'] ?? [];
        require_once 'views/create-folder2.php';
    }else{
        require_once 'views/login.php';
    }
});


// Route pour afficher le profil utilisateur
$router->map('GET','/profile',function (){
    if(isset($_SESSION['user'])){
       // require_once 'control/ProfileController.php';
        //$controller = new ProfileController();
        //$controller->show();
        require_once 'views/profile2.php';
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
        header('Location: /login');
        exit;
    }
});

$router->map('GET','/consulter-dossier',function (){
    if(isset($_SESSION['user'])){
        $dossierController = new DossierController();
        $data = $dossierController->getConducteursWithContraventions();
        
        //var_dump($data);
        // Passer les données à la vue
        $conducteurs = $data['conducteurs'];
        $contraventionsByDossier = $data['contraventionsByDossier'];
        // Préparer aussi les véhicules/plaque et leurs contraventions
        $vehData = $dossierController->getVehiculesWithContraventions();
        $vehicules = $vehData['vehicules'] ?? [];
        $contraventionsByVehicule = $vehData['contraventionsByVehicule'] ?? [];
        // Préparer la liste des particuliers
        $partCtrl = new ParticulierController();
        $particuliers = $partCtrl->listAll();
        // Préparer les entreprises et leurs contraventions
        $entData = $dossierController->getEntreprisesWithContraventions();
        $entreprises = $entData['entreprises'] ?? [];
        $contraventionsByEntreprise = $entData['contraventionsByEntreprise'] ?? [];
        
        require_once 'views/consulter-dossier2.php';
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
        header('Location: /login');
        exit;
    }
});

$router->map('GET','/rapport-accidents',function (){
    if(isset($_SESSION['user'])){
        // Récupérer les données des accidents via le contrôleur
        $accidentController = new AccidentController();
        $accidentsResult = $accidentController->getAll();
        
        if($accidentsResult['state']) {
            $accidents = $accidentsResult['data'];
        } else {
            $accidents = [];
            $_SESSION['error'] = 'Erreur lors du chargement des accidents: ' . $accidentsResult['message'];
        }
        
        require_once 'views/rapport-accidents.php';
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
        header('Location: /login');
        exit;
    }
});

$router->map('GET','/rapport-activites',function (){
    if(isset($_SESSION['user'])){
        require_once 'views/activities-log.php';
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
        header('Location: /login');
        exit;
    }
});

// API: contraventions d'un particulier (JSON)
$router->map('GET','/particulier/[i:id]/contraventions', function($id){
    header('Content-Type: application/json');
    if(!isset($_SESSION['user'])){
        http_response_code(401);
        echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
        return;
    }
    try {
        // Use the provided ID directly; mapping by numero_national is unnecessary here
        $dossierId = (string)$id;
        $ctrl = new \Control\ContraventionController();
        $rows = $ctrl->getByDossierIdAndType($dossierId, 'particuliers');
        echo json_encode(['ok'=>true,'data'=>$rows]);
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});

// API: contraventions d'un véhicule (JSON)
$router->map('GET','/vehicule/[i:id]/contraventions', function($id){
    header('Content-Type: application/json');
    if(!isset($_SESSION['user'])){
        http_response_code(401);
        echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
        return;
    }
    try {
        $dossierId = (string)$id;
        $ctrl = new \Control\ContraventionController();
        $rows = $ctrl->getByDossierIdAndType($dossierId, 'vehicule_plaque');
        echo json_encode(['ok'=>true,'data'=>$rows]);
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode(['ok'=>false,'error'=>'Server error']);
    }
});








//----------------- routes post
$router->map('POST','/contact',function (){
    require_once 'views/contact.php';
});

$router->map('POST','/contravention/update-payed', function(){
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['ok'=>false, 'error'=>'Unauthorized']);
        return;
    }
    try {
        $id = $_POST['id'] ?? null;
        $payed = $_POST['payed'] ?? null;
        if ($id === null) {
            http_response_code(400);
            echo json_encode(['ok'=>false, 'error'=>'Paramètre id manquant']);
            return;
        }
        $ctrl = new \Control\ContraventionController();
        $res = $ctrl->updatePayed($id, $payed);
        // Journalisation de la mise à jour du statut de paiement
        try {
            $status = null;
            if (is_array($res)) { $status = $res['ok'] ?? ($res['state'] ?? null); }
            (new \Model\ActivityLogger())->logUpdate(
                $_SESSION['user']['username'] ?? null,
                'contraventions',
                $id,
                null,
                [ 'action' => 'update_payed', 'payed' => $payed, 'result' => $status ]
            );
        } catch (\Throwable $e) { /* ignore logging errors */ }
        echo json_encode($res);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
    }
});

$router->map('POST','/login',function (){
    $result = (new UsersController)->login($_POST['username'], $_POST['password']);
    
    //var_dump($result['data'][0]['first_connexion']);

    if($result['state'] == true){

        $_SESSION['user'] = $result['data'][0];

        if($result['data'][0]['first_connexion'] == 'yes'){
           
            require_once 'views/first_connexion.php';
        }else{
            
            require_once 'views/home2.php';
        }
    }else{
        require_once 'views/login.php';
    } 
});

$router->map('POST','/confirm-password',function (){
    
    $result = (new UsersController)->changePassword($_SESSION['user']['id'], $_POST['password'], $_POST['password2']);
    
    
    if($result['state'] == true){
        $_SESSION['user'] = $result['data'];
        require_once 'views/home2.php';
    }else{
        require_once 'views/first_connexion.php';
    }
});

$router->map('POST','/create-entreprise',function (){
    
    if(isset($_SESSION['user']) ){
        
        try {
            // Create enterprise record first
            $entrepriseResult = (new EntrepriseController)->create($_POST);
            
            // Check if enterprise creation was successful
            if ($entrepriseResult['state'] === true) {
                $_SESSION['success'] = $entrepriseResult['message'] ?? "Entreprise enregistrée avec succès";
            } else {
                $_SESSION['error'] = $entrepriseResult['message'] ?? "Échec de l'enregistrement de l'entreprise";
            }
            
            require_once 'views/create-folder2.php';
            
        } catch (Exception $e) {
            // Handle errors
            $_SESSION['error'] = $e->getMessage();
            require_once 'views/create-folder2.php';
        }
        
    }else{
        require_once 'views/login.php';
    }
}); 

$router->map('POST','/create-particulier',function (){
    if(isset($_SESSION['user']) ){
        try {
            $particulierResult = (new ParticulierController)->create($_POST);
            if ($particulierResult['state'] === true) {
                $_SESSION['success'] = ($particulierResult['data']['nom'] ?? 'Particulier')." a été enregistré avec succès";
            } else {
                $_SESSION['error'] = $particulierResult['message'];
            }
            require_once 'views/create-folder2.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            require_once 'views/create-folder2.php';
        }
    } else {
        require_once 'views/login.php';
    }
});

$router->map('POST','/create-vehicule-plaque',function (){
    if (!isset($_SESSION['user'])) {
        require_once 'views/login.php';
        return;
    }

    try {
        // Création du véhicule
        (new VehiculePlaqueController)->create($_POST);
        $_SESSION['success'] = 'Véhicule enregistré avec succès';
        require_once 'views/create-folder2.php';
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        require_once 'views/create-folder2.php';
    }
}); 

$router->map('POST','/create-conducteur-vehicule',function (){
    
    if(isset($_SESSION['user']) ){
        
        try {
            // Appeler le contrôleur avec les données POST et les fichiers
            $resultCreate = (new ConducteurVehiculeController)->create($_POST, $_FILES);
            
            if($resultCreate['state'] == true){
                $_SESSION['success'] = $resultCreate['message'];
                require_once 'views/create-folder2.php';
            }else{
                $_SESSION['error'] = $resultCreate['message'];
                require_once 'views/create-folder2.php';
            }
        } catch (Exception $e) {
        // En cas d'erreur, renvoyer le message d'erreur
        
        $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
        require_once 'views/create-folder2.php';
    }
    }else{
        require_once 'views/login.php';
    }
});


$router->map('POST','/create-agent-account',function (){
    
    if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'superadmin'){
        
        try {
            // Appeler le contrôleur avec les données POST
            $result = (new AgentAccountController)->create($_POST);
            
            if($result['state'] == true){
                $_SESSION['success'] = $result['message'];
                require_once 'views/home2.php';
            }else{
                $_SESSION['error'] = $result['message'];
                require_once 'views/home2.php';
            }
        } catch (Exception $e) {
            // En cas d'erreur, renvoyer le message d'erreur
            $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
            require_once 'views/home2.php';
        }
        
    }else{
        require_once 'views/403.php';
    }
});



// Route pour mettre à jour le profil utilisateur
$router->map('POST','/update-profile',function (){
    if(isset($_SESSION['user'])){
        require_once 'control/ProfileController.php';
        $controller = new ProfileController();
        $controller->update();
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour effectuer cette action.';
        header('Location: /login');
        exit;
    }
});

// Routes pour la gestion des agents
$router->map('GET','/agents',function (){
    if(isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'superadmin')){
        $controller = new AgentManagementController();
        $controller->index();
    } else {
        require_once 'views/403.php';
    }
});

$router->map('GET','/get-agent/[i:id]',function ($id){
    if(isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'superadmin')){
        $controller = new AgentManagementController();
        $controller->getAgent($id);
    } else {
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    }
});

$router->map('POST','/update-agent',function (){
    if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'superadmin'){
        $controller = new AgentManagementController();
        $controller->updateAgent();
    } else {
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    }
});

$router->map('POST','/toggle-agent-status',function (){
    if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'superadmin'){
        $controller = new AgentManagementController();
        $controller->toggleAgentStatus();
    } else {
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    }
});

$router->map('POST','/create-accident',function (){
    // Supprimer l'affichage des erreurs PHP pour éviter la pollution HTML
    error_reporting(0);
    ini_set('display_errors', 0);
    
    // Démarrer la capture de sortie
    ob_start();
    
    if(!isset($_SESSION['user'])){
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode(['state' => false, 'message' => 'Vous devez être connecté pour enregistrer un accident']);
        return;
    }
    
    try {
        $controller = new AccidentController();
        $result = $controller->create($_POST, $_FILES);
        
        // Nettoyer tout output capturé (erreurs PHP)
        ob_clean();
        
        // S'assurer que le résultat est valide avant de l'envoyer
        if (!is_array($result)) {
            throw new Exception('Réponse invalide du contrôleur');
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        
    } catch (Exception $e) {
        // Nettoyer tout output capturé
        ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode([
            'state' => false, 
            'message' => 'Erreur serveur: ' . $e->getMessage()
        ]);
    }
});


//----------------- routes api




//------------------------------------------------- corespondance des routes

$match = $router->match();

if( is_array($match) && is_callable( $match['target'] ) ) {
    call_user_func_array( $match['target'], $match['params'] );
} else {
    // no route was matched
    header( $_SERVER["SERVER_PROTOCOL"] . ' Accueil ');
    require_once 'views/404.php';
}