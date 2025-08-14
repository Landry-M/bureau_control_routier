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

$router->map('GET','/login',function (){
    if(isset($_SESSION['user']) ){
        require_once 'views/home2.php';
    }else{
        require_once 'views/login.php';
    }
});

$router->map('GET','/logout',function (){
    session_destroy();
    require_once 'views/login.php';
});


$router->map('GET','/create-folder',function (){
   
    if(isset($_SESSION['user']) ){
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
       
        require_once 'views/consulter-dossier2.php';
    } else {
        $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
        header('Location: /login');
        exit;
    }
});








//----------------- routes post
$router->map('POST','/contact',function (){
    require_once 'views/contact.php';
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
                $entrepriseId = $entrepriseResult['data']->id();
                
                // Check if contravention data is provided and create contravention record
                if (!empty($_POST['lieu']) || !empty($_POST['type_infraction']) || !empty($_POST['description']) || !empty($_POST['reference_loi']) || !empty($_POST['amende'])) {
                    
                    // Prepare contravention data with enterprise ID as dossier_id
                    $contraventionData = $_POST;
                    $contraventionData['dossier_id'] = $entrepriseId;
                    $contraventionData['type_dossier'] = 'entreprises'; // Table name source
                    
                    // Create contravention record
                    $result = (new ContraventionsController)->create($contraventionData);
                }else{
                    $result['state'] = true;
                    $result['message'] = $entrepriseResult['message'];
                }
            } else {
                $result['state'] = false;
                $result['message'] = $entrepriseResult['message'];
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
            // Create particulier record first
            $particulierResult = (new ParticulierController)->create($_POST);
            
            // Check if particulier creation was successful
            if ($particulierResult['state'] === true) {
                $particulierId = $particulierResult['id'];
                
                // Check if contravention data is provided and create contravention record
                if ( !empty($_POST['lieu']) || !empty($_POST['type_infraction']) || !empty($_POST['description']) || !empty($_POST['reference_loi']) || !empty($_POST['amende'])) {
                    
                    // Prepare contravention data with particulier ID as dossier_id
                    $contraventionData = $_POST;
                    $contraventionData['dossier_id'] = $particulierId;
                    $contraventionData['type_dossier'] = 'particuliers'; // Table name source
                    
                    // Create contravention record
                    $result = (new ContraventionsController)->create($contraventionData);
                }else{
                    $result['state'] = true;
                    $result['message'] = $particulierResult['data']['nom']." a été enregistrer avec succes ";
                }

            } else {
                $result['state'] = false;
                $result['message'] = $particulierResult['message'];
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

$router->map('POST','/create-vehicule-plaque',function (){
    
    if(isset($_SESSION['user']) ){
        
        try {
            // Create vehicle record first
            $vehiculeId = (new VehiculePlaqueController)->create($_POST);
            
            // Check if contravention data is provided and create contravention record
            if (!empty($_POST['lieu']) || !empty($_POST['type_infraction']) || 
                !empty($_POST['description']) || !empty($_POST['reference_loi']) || !empty($_POST['amende'])) {
                
                // Prepare contravention data with vehicle ID as dossier_id
                $contraventionData = $_POST;
                $contraventionData['dossier_id'] = $vehiculeId;
                $contraventionData['type_dossier'] = 'vehicule_plaque'; // Table name source
                
                // Create contravention record
                $result = (new ContraventionsController)->create($contraventionData);
            } else {
            $_SESSION['error'] = "Vehicule enregistrer avec succes ";
            require_once 'views/create-folder2.php';
            }
        } catch (Exception $e) {
            // Handle errors
            $_SESSION['error'] = $e->getMessage();
            require_once 'views/create-folder2.php';
        }
        
    }else{
        require_once 'views/login.php';
    }
}); 

$router->map('POST','/create-conducteur-vehicule',function (){
    
    if(isset($_SESSION['user']) ){
        
        try {
            // Appeler le contrôleur avec les données POST et les fichiers
            $result = (new ConducteurVehiculeController)->create($_POST, $_FILES);
            
            if($result['state'] == true){
                $_SESSION['success'] = $result['message'];
                require_once 'views/create-folder2.php';
            }else{
                $_SESSION['error'] = $result['message'];
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