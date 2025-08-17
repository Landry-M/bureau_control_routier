<?php
// Précharger le planning de connexion de l'utilisateur connecté
// pour préremplir l'UI du profil
use ORM;
use Exception;
$currentSchedule = [];
try {
    if (isset($_SESSION['user']['id'])) {
        $userRow = ORM::for_table('users')
            ->select('login_schedule')
            ->where('id', $_SESSION['user']['id'])
            ->find_one();
        if ($userRow) {
            $json = $userRow->login_schedule ?? null;
            if (is_string($json) && $json !== '') {
                $decoded = json_decode($json, true);
                if (is_array($decoded)) { $currentSchedule = $decoded; }
            } elseif (is_array($json)) {
                // Au cas où l'ORM retournerait déjà un tableau
                $currentSchedule = $json;
            }
        }
    }
} catch (Exception $e) {
    // En cas d'erreur, laisser $currentSchedule vide
}
// Jours de la semaine (clés courtes)
$scheduleDays = [
    'mon' => 'Lundi',
    'tue' => 'Mardi',
    'wed' => 'Mercredi',
    'thu' => 'Jeudi',
    'fri' => 'Vendredi',
    'sat' => 'Samedi',
    'sun' => 'Dimanche',
];
?>
<!DOCTYPE html>
<html lang="en" data-layout="topnav" data-menu-color="brand">

    
<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:56 GMT -->
<head>
        <meta charset="utf-8" />
        <title>Horizontal Layout | Jidox - Material Design Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/logo.jpg">

        <!-- Plugin css -->
        <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">
        <link href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

        <!-- Theme Config Js -->
        <script src="assets/js/config.js"></script>

        <!-- App css -->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Topbar Start ========== -->
            <?php require_once '_partials/_topmenu.php'; ?>
            <!-- ========== Topbar End ========== -->

            <!-- ========== Horizontal Menu Start ========== -->
           <?php require_once '_partials/_navbar.php'; ?>
            <!-- ========== Horizontal Menu End ========== -->
            
            <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                    
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            
                            <!-- Messages d'alerte -->
                            <?php if(isset($_SESSION['error'])) { ?>
                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <?php echo $_SESSION['error']; ?>
                                </div>
                            <?php } ?>
                            <?php if(isset($_SESSION['error'])) unset($_SESSION['error']); ?>

                            <?php if(isset($_SESSION['success'])) { ?>
                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <?php echo $_SESSION['success']; ?>
                                </div>
                            <?php } ?>
                            <?php if(isset($_SESSION['success'])) unset($_SESSION['success']); ?>
                            
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                                        <li class="breadcrumb-item active">Mon Profil</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Mon Profil</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-xl-4 col-lg-5">
                            <div class="card text-center">
                                <div class="card-body">
                                    <img src="assets/images/users/avatar-1.jpg" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                                    <h4 class="mb-0 mt-2"><?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'Utilisateur'); ?></h4>
                                    <p class="text-muted fs-14"><?php echo ucfirst($_SESSION['user']['role'] ?? 'Agent'); ?></p>

                                    <div class="text-start mt-3">
                                        <h4 class="fs-13 text-uppercase">À propos :</h4>
                                        <p class="text-muted fs-13 mb-3">
                                            Agent du Bureau de Contrôle Routier avec les privilèges de <?php echo ucfirst($_SESSION['user']['role'] ?? 'agent'); ?>.
                                        </p>
                                        <p class="text-muted mb-2 fs-13"><strong>Matricule :</strong> <span class="ms-2"><?php echo htmlspecialchars($_SESSION['user']['matricule'] ?? 'N/A'); ?></span></p>
                                        <p class="text-muted mb-2 fs-13"><strong>Poste :</strong> <span class="ms-2"><?php echo htmlspecialchars($_SESSION['user']['poste'] ?? 'N/A'); ?></span></p>
                                        <p class="text-muted mb-1 fs-13"><strong>Statut :</strong> <span class="ms-2 badge bg-success"><?php echo ucfirst($_SESSION['user']['status'] ?? 'Actif'); ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8 col-lg-7">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="header-title">Informations Personnelles</h4>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="editModeSwitch">
                                            <label class="form-check-label" for="editModeSwitch">
                                                Mode édition
                                            </label>
                                        </div>
                                    </div>

                                    <form id="profileForm" method="POST" action="/update-profile">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Nom d'utilisateur</label>
                                                    <input type="text" class="form-control" id="username" name="username" 
                                                           value="<?php echo htmlspecialchars($_SESSION['user']['username'] ?? ''); ?>" 
                                                           disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="telephone" class="form-label">Numéro de téléphone</label>
                                                    <input type="tel" class="form-control" id="telephone" name="telephone" 
                                                           value="<?php echo htmlspecialchars($_SESSION['user']['telephone'] ?? ''); ?>" 
                                                           placeholder="Ex: +243 123 456 789" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'superadmin') { ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="matricule" class="form-label">Matricule</label>
                                                    <input type="text" class="form-control" id="matricule" name="matricule" 
                                                           value="<?php echo htmlspecialchars($_SESSION['user']['matricule'] ?? ''); ?>" 
                                                           disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="poste" class="form-label">Poste</label>
                                                    <input type="text" class="form-control" id="poste" name="poste" 
                                                           value="<?php echo htmlspecialchars($_SESSION['user']['poste'] ?? ''); ?>" 
                                                           disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Rôle</label>
                                                    <select class="form-select" id="role" name="role" disabled>
                                                        <option value="opj" <?php echo ($_SESSION['user']['role'] ?? '') === 'opj' ? 'selected' : ''; ?>>OPJ</option>
                                                        <option value="admin" <?php echo ($_SESSION['user']['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                        <option value="superadmin" <?php echo ($_SESSION['user']['role'] ?? '') === 'superadmin' ? 'selected' : ''; ?>>Super Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <h5 class="mb-3 text-uppercase bg-light p-2"><i class="ri-lock-line me-1"></i> Sécurité</h5>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                                    <input type="password" class="form-control" id="current_password" name="current_password" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                                    <input type="password" class="form-control" id="new_password" name="new_password" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mb-3 text-uppercase bg-light p-2"><i class="ri-time-line me-1"></i> Planning de connexion (optionnel)</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold">Jours et heures autorisés pour la connexion</label>
                                                    <small class="text-muted d-block">Laissez tout décoché pour ne pas restreindre les horaires de connexion.</small>
                                                </div>
                                                <div class="table-responsive border rounded p-2">
                                                    <table class="table table-sm align-middle mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 140px;">Jour</th>
                                                                <th style="width: 110px;">Autoriser</th>
                                                                <th style="width: 180px;">Heure début</th>
                                                                <th style="width: 180px;">Heure fin</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($scheduleDays as $dkey => $dlabel):
                                                                $cfg = $currentSchedule[$dkey] ?? [];
                                                                $enabled = !empty($cfg['enabled']);
                                                                $start = isset($cfg['start']) && preg_match('/^\d{2}:\d{2}$/', $cfg['start']) ? $cfg['start'] : '08:00';
                                                                $end   = isset($cfg['end'])   && preg_match('/^\d{2}:\d{2}$/', $cfg['end'])   ? $cfg['end']   : '17:00';
                                                            ?>
                                                            <tr>
                                                                <td class="fw-medium"><?php echo $dlabel; ?></td>
                                                                <td>
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input schedule-field" type="checkbox" id="sched_<?php echo $dkey; ?>" name="schedule[<?php echo $dkey; ?>][enabled]" value="1" <?php echo $enabled ? 'checked' : ''; ?> disabled>
                                                                        <label class="form-check-label" for="sched_<?php echo $dkey; ?>">Autoriser</label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="time" class="form-control form-control-sm schedule-field" name="schedule[<?php echo $dkey; ?>][start]" value="<?php echo htmlspecialchars($start); ?>" disabled>
                                                                </td>
                                                                <td>
                                                                    <input type="time" class="form-control form-control-sm schedule-field" name="schedule[<?php echo $dkey; ?>][end]" value="<?php echo htmlspecialchars($end); ?>" disabled>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end" id="saveButtonContainer" style="display: none;">
                                            <button type="button" class="btn btn-secondary me-2" id="cancelBtn">Annuler</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-save-line me-1"></i> Sauvegarder les modifications
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> © Bureau de Contrôle Routier
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="javascript: void(0);">À propos</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Theme Settings -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
            <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                <h5 class="text-white m-0">Theme Settings</h5>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div data-simplebar class="h-100">
                    <div class="card mb-0 p-3">
                        <div class="alert alert-warning" role="alert">
                            <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                        </div>

                        <h5 class="mt-0 fs-16 fw-bold mb-3">Choose Layout</h5>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input id="customizer-layout01" name="data-layout" type="checkbox" value="vertical" class="form-check-input">
                                <label class="form-check-label" for="customizer-layout01">Vertical</label>
                            </div>
                            <div class="form-check form-switch">
                                <input id="customizer-layout02" name="data-layout" type="checkbox" value="horizontal" class="form-check-input">
                                <label class="form-check-label" for="customizer-layout02">Accueil</label>
                            </div>
                        </div>

                        <h5 class="my-3 fs-16 fw-bold">Color Scheme</h5>

                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-light" value="light">
                                <label class="form-check-label" for="layout-color-light">Light</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-dark" value="dark">
                                <label class="form-check-label" for="layout-color-dark">Dark</label>
                            </div>
                        </div>

                        <div id="layout-width">
                            <h5 class="my-3 fs-16 fw-bold">Layout Mode</h5>

                            <div class="d-flex flex-column gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-fluid" value="fluid">
                                    <label class="form-check-label" for="layout-mode-fluid">Fluid</label>
                                </div>

                                <div id="layout-boxed">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-boxed" value="boxed">
                                        <label class="form-check-label" for="layout-mode-boxed">Boxed</label>
                                    </div>
                                </div>

                                <div id="layout-detached">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="data-layout-mode" id="data-layout-detached" value="detached">
                                        <label class="form-check-label" for="data-layout-detached">Detached</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="my-3 fs-16 fw-bold">Topbar Color</h5>

                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-light" value="light">
                                <label class="form-check-label" for="topbar-color-light">Light</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-dark" value="dark">
                                <label class="form-check-label" for="topbar-color-dark">Dark</label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-brand" value="brand">
                                <label class="form-check-label" for="topbar-color-brand">Brand</label>
                            </div>
                        </div>

                        <div>
                            <h5 class="my-3 fs-16 fw-bold">Menu Color</h5>

                            <div class="d-flex flex-column gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-light" value="light">
                                    <label class="form-check-label" for="leftbar-color-light">Light</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-dark" value="dark">
                                    <label class="form-check-label" for="leftbar-color-dark">Dark</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-brand" value="brand">
                                    <label class="form-check-label" for="leftbar-color-brand">Brand</label>
                                </div>
                            </div>
                        </div>

                        <div id="sidebar-size">
                            <h5 class="my-3 fs-16 fw-bold">Sidebar Size</h5>

                            <div class="d-flex flex-column gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-default" value="default">
                                    <label class="form-check-label" for="leftbar-size-default">Default</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-compact" value="compact">
                                    <label class="form-check-label" for="leftbar-size-compact">Compact</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-small" value="condensed">
                                    <label class="form-check-label" for="leftbar-size-small">Condensed</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-small-hover" value="sm-hover">
                                    <label class="form-check-label" for="leftbar-size-small-hover">Hover View</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-full" value="full">
                                    <label class="form-check-label" for="leftbar-size-full">Full Layout</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-fullscreen" value="fullscreen">
                                    <label class="form-check-label" for="leftbar-size-fullscreen">Fullscreen Layout</label>
                                </div>
                            </div>
                        </div>

                        <div id="layout-position">
                            <h5 class="my-3 fs-16 fw-bold">Layout Position</h5>

                            <div class="btn-group checkbox" role="group">
                                <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                                <label class="btn btn-soft-primary w-sm" for="layout-position-fixed">Fixed</label>

                                <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                                <label class="btn btn-soft-primary w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                            </div>
                        </div>

                        <div id="sidebar-user">
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <label class="fs-16 fw-bold m-0" for="sidebaruser-check">Sidebar User Info</label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="sidebar-user" id="sidebaruser-check">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="offcanvas-footer border-top p-3 text-center">
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-light w-100" id="reset-layout">Reset</button>
                    </div>
                    <div class="col-6">
                        <a href="#" role="button" class="btn btn-primary w-100">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>          
        
        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Daterangepicker js -->
        <script src="assets/vendor/daterangepicker/moment.min.js"></script>
        <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>
        
        <!-- Apex Charts js -->
        <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

        <!-- Vector Map js -->
        <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

        <!-- Dashboard App js -->
        <script src="assets/js/pages/demo.dashboard.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

         <!-- Include Modal creation de compte agent -->
         <?php require_once '_partials/_modal_agent_account.php'; ?>
        

           <!-- Profile Edit Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModeSwitch = document.getElementById('editModeSwitch');
            const profileForm = document.getElementById('profileForm');
            const saveButtonContainer = document.getElementById('saveButtonContainer');
            const cancelBtn = document.getElementById('cancelBtn');
            
            // Éléments de formulaire à activer/désactiver
            const formElements = [
                'username', 'telephone', 'current_password', 'new_password', 'confirm_password'
            ];
            
            // Ajouter les champs superadmin s'ils existent
            const superadminFields = ['matricule', 'poste', 'role'];
            superadminFields.forEach(fieldId => {
                if (document.getElementById(fieldId)) {
                    formElements.push(fieldId);
                }
            });
            
            // Stocker les valeurs originales
            let originalValues = {};
            
            // Fonction pour sauvegarder les valeurs originales
            function saveOriginalValues() {
                formElements.forEach(elementId => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        originalValues[elementId] = element.value;
                    }
                });
            }
            
            // Fonction pour restaurer les valeurs originales
            function restoreOriginalValues() {
                formElements.forEach(elementId => {
                    const element = document.getElementById(elementId);
                    if (element && originalValues[elementId] !== undefined) {
                        element.value = originalValues[elementId];
                    }
                });
                // Vider les champs de mot de passe
                document.getElementById('current_password').value = '';
                document.getElementById('new_password').value = '';
                document.getElementById('confirm_password').value = '';
            }
            
            // Initialiser les valeurs originales
            saveOriginalValues();
            
            // Gestionnaire du switch
            editModeSwitch.addEventListener('change', function() {
                const isEditMode = this.checked;
                
                // Activer/désactiver les champs
                formElements.forEach(elementId => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.disabled = !isEditMode;
                        
                        // Ajouter/retirer la classe pour le style visuel
                        if (isEditMode) {
                            element.classList.add('border-primary');
                        } else {
                            element.classList.remove('border-primary');
                        }
                    }
                });
                // Activer/désactiver les champs du planning
                document.querySelectorAll('.schedule-field').forEach(el => {
                    if (el.type === 'checkbox') {
                        el.disabled = !isEditMode;
                    } else {
                        el.disabled = !isEditMode;
                    }
                    if (isEditMode) {
                        el.classList.add('border-primary');
                    } else {
                        el.classList.remove('border-primary');
                    }
                });
                
                // Afficher/masquer les boutons de sauvegarde
                if (isEditMode) {
                    saveButtonContainer.style.display = 'block';
                } else {
                    saveButtonContainer.style.display = 'none';
                    restoreOriginalValues();
                }
            });
            
            // Gestionnaire du bouton Annuler
            cancelBtn.addEventListener('click', function() {
                editModeSwitch.checked = false;
                editModeSwitch.dispatchEvent(new Event('change'));
            });
            
            // Validation du formulaire
            profileForm.addEventListener('submit', function(e) {
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                const currentPassword = document.getElementById('current_password').value;
                
                // Si un nouveau mot de passe est saisi
                if (newPassword || confirmPassword) {
                    if (!currentPassword) {
                        e.preventDefault();
                        alert('Veuillez saisir votre mot de passe actuel pour modifier votre mot de passe.');
                        return;
                    }
                    
                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        alert('Les mots de passe ne correspondent pas.');
                        return;
                    }
                    
                    if (newPassword.length < 6) {
                        e.preventDefault();
                        alert('Le nouveau mot de passe doit contenir au moins 6 caractères.');
                        return;
                    }
                }
            });
        });
    </script>


    </body>

<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:58 GMT -->
</html>