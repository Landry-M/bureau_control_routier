<?php


require_once __DIR__ . '/../model/ActivityLogger.php';
use Model\ActivityLogger;

$activityLogger = new ActivityLogger();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Filtres
$filters = [];
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $filters['user_id'] = $_GET['user_id'];
}
if (isset($_GET['action']) && !empty($_GET['action'])) {
    $filters['action'] = $_GET['action'];
}
if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
    $filters['date_from'] = $_GET['date_from'] . ' 00:00:00';
}
if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
    $filters['date_to'] = $_GET['date_to'] . ' 23:59:59';
}

$activities = $activityLogger->getAllActivities($limit, $offset, $filters);
?>

<!DOCTYPE html>
<html lang="en" data-layout="topnav" data-menu-color="brand">
<head>
    <meta charset="utf-8" />
    <title>Journal des Activités | Bureau de Contrôle Routier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Journal des activités utilisateurs" name="description" />
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
                            <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">     
                                <h4 class="page-title">Journal des Activités</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                       
                        <div class="col-xl-12 col-lg-12">

                            <div class="card">
                                <div class="card-body">
                                    <!-- Filtres -->
                                    <div class="card mt-1">
                                        <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <h5 class="card-title mb-0">
                                                <i class="ri-filter-line me-2"></i>Filtres
                                            </h5>
                                            <form method="GET" class="d-flex align-items-center gap-2">
                                                <input type="number" id="user_id" name="user_id" class="form-control form-control-sm" 
                                                       value="<?= htmlspecialchars($_GET['user_id'] ?? '') ?>" placeholder="ID utilisateur" style="min-width:120px;">
                                                <input type="text" id="action" name="action" class="form-control form-control-sm" 
                                                       value="<?= htmlspecialchars($_GET['action'] ?? '') ?>" placeholder="Type d'action" style="min-width:150px;">
                                                <input type="date" id="date_from" name="date_from" class="form-control form-control-sm" 
                                                       value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" placeholder="Date début">
                                                <input type="date" id="date_to" name="date_to" class="form-control form-control-sm" 
                                                       value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" placeholder="Date fin">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="ri-search-line me-1"></i>Filtrer
                                                </button>
                                                <a href="activities-log.php" class="btn btn-outline-secondary btn-sm">
                                                    <i class="ri-refresh-line"></i>
                                                </a>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Table des activités -->
                                    <div class="card mt-1">
                                        <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <h5 class="card-title mb-0">
                                                <i class="ri-file-list-3-line me-2"></i>Activités Récentes
                                            </h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Utilisateur</th>
                                                            <th>Action</th>
                                                            <th>Détails</th>
                                                            <th>IP</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($activities)): ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center py-4">
                                                                <i class="ri-inbox-line fs-1 text-muted"></i>
                                                                <p class="text-muted mt-2">Aucune activité trouvée</p>
                                                            </td>
                                                        </tr>
                                                        <?php else: ?>
                                                        <?php foreach ($activities as $activity): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($activity['id']) ?></td>
                                                            <td>
                                                                <?php if ($activity['username']): ?>
                                                                    <span class="badge bg-info"><?= htmlspecialchars($activity['username']) ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">Anonyme</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $actionClass = 'bg-secondary';
                                                                if (strpos($activity['action'], 'LOGIN') !== false) {
                                                                    $actionClass = strpos($activity['action'], 'FAILED') !== false ? 'bg-danger' : 'bg-success';
                                                                } elseif (strpos($activity['action'], 'CREATE') !== false) {
                                                                    $actionClass = 'bg-success';
                                                                } elseif (strpos($activity['action'], 'UPDATE') !== false) {
                                                                    $actionClass = 'bg-warning';
                                                                } elseif (strpos($activity['action'], 'DELETE') !== false) {
                                                                    $actionClass = 'bg-danger';
                                                                } elseif (strpos($activity['action'], 'VIEW') !== false) {
                                                                    $actionClass = 'bg-info';
                                                                } elseif (strpos($activity['action'], 'SEARCH') !== false) {
                                                                    $actionClass = 'bg-primary';
                                                                }
                                                                ?>
                                                                <span class="badge <?= $actionClass ?>"><?= htmlspecialchars($activity['action']) ?></span>
                                                            </td>
                                                            <td>
                                                                <div class="activity-details" style="max-width: 300px;">
                                                                    <?php 
                                                                    $details = htmlspecialchars($activity['details_operation'] ?? '');
                                                                    if (strlen($details) > 100) {
                                                                        echo '<span class="details-short">' . substr($details, 0, 100) . '...</span>';
                                                                        echo '<span class="details-full d-none">' . $details . '</span>';
                                                                        echo '<a href="#" class="text-primary ms-1 toggle-details">Voir plus</a>';
                                                                    } else {
                                                                        echo $details;
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <small class="text-muted"><?= htmlspecialchars($activity['ip_address'] ?? 'N/A') ?></small>
                                                            </td>
                                                            <td>
                                                                <small><?= date('d/m/Y H:i:s', strtotime($activity['date_creation'])) ?></small>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pagination -->
                                    <?php if (!empty($activities) && count($activities) == $limit): ?>
                                    <nav aria-label="Navigation des activités" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $page - 1 ?>&<?= http_build_query(array_filter($_GET, function($k) { return $k !== 'page'; }, ARRAY_FILTER_USE_KEY)) ?>">
                                                    <i class="ri-arrow-left-line"></i> Précédent
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            <li class="page-item active">
                                                <span class="page-link">Page <?= $page ?></span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $page + 1 ?>&<?= http_build_query(array_filter($_GET, function($k) { return $k !== 'page'; }, ARRAY_FILTER_USE_KEY)) ?>">
                                                    Suivant <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container -->
            </div> <!-- content -->
        </div> <!-- content-page -->

    </div> <!-- wrapper -->



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
    <script src="assets/js/pages/dashboard.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

       <!-- Include Modal creation de compte agent -->
       <?php require_once '_partials/_modal_agent_account.php'; ?>
        
       
    <script>
        // Toggle pour afficher/masquer les détails complets
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-details').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const container = this.closest('.activity-details');
                    const shortText = container.querySelector('.details-short');
                    const fullText = container.querySelector('.details-full');
                    
                    if (fullText.classList.contains('d-none')) {
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        this.textContent = 'Voir moins';
                    } else {
                        shortText.classList.remove('d-none');
                        fullText.classList.add('d-none');
                        this.textContent = 'Voir plus';
                    }
                });
            });
        });
    </script>
</body>
</html>
