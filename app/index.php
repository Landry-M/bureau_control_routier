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
use Control\ConducteurVehiculeController;
use Control\AgentAccountController;
use Control\ProfileController;
use Control\AgentManagementController;
use Control\AccidentController;
use Control\LogoutController;
use Control\SearchController;

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
        // Utiliser le numero_national si fourni en query pour éviter une dépendance directe à ORM ici
        $numero = isset($_GET['numero']) ? (string)$_GET['numero'] : '';
        if ($numero === '') {
            // Pas de numero fourni -> renvoyer liste vide (le front peut réessayer ou fournir le numero)
            echo json_encode(['ok'=>true,'data'=>[]]);
            return;
        }
        $ctrl = new \Control\ContraventionController();
        $rows = $ctrl->getByDossierIdAndType($numero, 'particuliers');
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