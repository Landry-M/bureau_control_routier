<!DOCTYPE html>
<html lang="en" data-layout="topnav" data-menu-color="brand">

<head>
    <meta charset="utf-8" />
    <title>Rapport d'Accidents | Bureau de Contrôle Routier</title>
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
    
    <style>
        .accident-table img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        .gravite-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .gravite-leger { background-color: #d1ecf1; color: #0c5460; }
        .gravite-grave { background-color: #f8d7da; color: #721c24; }
        .gravite-mortel { background-color: #343a40; color: #ffffff; }
        .gravite-materiel { background-color: #fff3cd; color: #856404; }
    </style>
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
                                <h4 class="page-title">Rapport d'Accidents</h4>
                                <div class="btn-toolbar mb-2 mb-md-0">
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                            <i class="ri-printer-line me-1"></i>Imprimer
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportToCSV()">
                                            <i class="ri-file-excel-line me-1"></i>Exporter CSV
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Statistiques rapides -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary" id="total-accidents">0</h5>
                                                    <p class="card-text">Total accidents</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-danger" id="accidents-graves">0</h5>
                                                    <p class="card-text">Accidents graves/mortels</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-warning" id="accidents-mois">0</h5>
                                                    <p class="card-text">Ce mois-ci</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title text-info" id="total-temoins">0</h5>
                                                    <p class="card-text">Total témoins</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table des accidents -->
                                    <div class="card mt-1">
                                        <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <h5 class="card-title mb-0">
                                                <i class="ri-file-list-3-line me-2"></i>Liste des accidents
                                            </h5>
                                            <div class="d-flex align-items-center gap-2">
                                                <select id="filter-gravite" class="form-select form-select-sm" style="min-width:180px;">
                                                    <option value="">Toutes les gravités</option>
                                                    <option value="leger">Léger</option>
                                                    <option value="grave">Grave</option>
                                                    <option value="mortel">Mortel</option>
                                                    <option value="materiel">Matériel uniquement</option>
                                                </select>
                                                <input type="date" id="filter-date-debut" class="form-control form-control-sm" placeholder="Date début">
                                                <input type="date" id="filter-date-fin" class="form-control form-control-sm" placeholder="Date fin">
                                                <input type="text" id="filter-lieu" class="form-control form-control-sm" placeholder="Rechercher par lieu..." style="min-width:200px;">
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0 accident-table" id="accidents-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Date</th>
                                                            <th>Lieu</th>
                                                            <th>Gravité</th>
                                                            <th>Description</th>
                                                            <th>Images</th>
                                                            <th>Témoins</th>
                                                            <th class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="accidents-tbody">
                                                        <!-- Les données seront chargées ici -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container -->
            </div> <!-- content -->
        </div> <!-- content-page -->

    <!-- Modal détails accident -->
    <div class="modal fade" id="accident-details-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-file-text-line me-2"></i>Détails de l'accident
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="accident-details-content">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="printAccidentDetails()">
                        <i class="ri-printer-line me-1"></i>Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal galerie d'images -->
    <div class="modal fade" id="images-gallery-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Galerie d'images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="images-gallery-content">
                    <!-- Les images seront chargées ici -->
                </div>
            </div>
        </div>
    </div>

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

    <!-- Include Modal creation de compte agent -->
    <?php require_once '_partials/_modal_agent_account.php'; ?>
        
    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script>
        // Données des accidents chargées côté serveur
        let accidentsData = <?= json_encode($accidents ?? []) ?>;
        let filteredData = [...accidentsData];

        // Charger les données au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            displayAccidents();
            updateStatistics();
            setupFilters();
        });

        // Afficher les accidents dans le tableau
        function displayAccidents() {
            const tbody = document.getElementById('accidents-tbody');
            tbody.innerHTML = '';

            filteredData.forEach(accident => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>#${accident.id}</td>
                    <td>${formatDate(accident.date_accident)}</td>
                    <td>${truncateText(accident.lieu, 30)}</td>
                    <td><span class="badge gravite-${accident.gravite} gravite-badge">${getGraviteLabel(accident.gravite)}</span></td>
                    <td>${truncateText(accident.description, 50)}</td>
                    <td>
                        ${accident.images && accident.images.length > 0 ? 
                            `<img src="${accident.images[0]}" alt="Image" onclick="showImagesGallery(${accident.id})"> 
                             ${accident.images.length > 1 ? `<small>(+${accident.images.length - 1})</small>` : ''}` 
                            : '<span class="text-muted">Aucune</span>'}
                    </td>
                    <td>
                        <span class="badge bg-info">${accident.temoins ? accident.temoins.length : 0}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="showAccidentDetails(${accident.id})">
                            <i class="ri-eye-line me-1"></i>Détails
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Afficher les détails d'un accident
        function showAccidentDetails(accidentId) {
            const accident = accidentsData.find(acc => acc.id == accidentId);
            if (accident) {
                displayAccidentDetails(accident);
                const modal = new bootstrap.Modal(document.getElementById('accident-details-modal'));
                modal.show();
            } else {
                showAlert('danger', 'Accident non trouvé');
            }
        }

        // Afficher le contenu des détails
        function displayAccidentDetails(accident) {
            const content = document.getElementById('accident-details-content');
            
            const imagesHtml = accident.images && accident.images.length > 0 ? 
                accident.images.map(img => `<img src="${img}" class="img-thumbnail me-2 mb-2" style="width: 150px; height: 150px; object-fit: cover;">`).join('') :
                '<p class="text-muted">Aucune image disponible</p>';

            const temoinsHtml = accident.temoins && accident.temoins.length > 0 ?
                accident.temoins.map(temoin => `
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="card-title">${temoin.nom}</h6>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="ri-phone-line me-1"></i>${temoin.telephone} | 
                                    <i class="ri-user-line me-1"></i>${temoin.age} ans | 
                                    <i class="ri-link me-1"></i>${temoin.lien_avec_accident}
                                </small>
                            </p>
                            ${temoin.temoignage ? `<p><strong>Témoignage:</strong> ${temoin.temoignage}</p>` : ''}
                        </div>
                    </div>
                `).join('') :
                '<p class="text-muted">Aucun témoin enregistré</p>';

            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations générales</h6>
                        <table class="table table-borderless">
                            <tr><th>ID:</th><td>#${accident.id}</td></tr>
                            <tr><th>Date:</th><td>${formatDate(accident.date_accident)}</td></tr>
                            <tr><th>Lieu:</th><td>${accident.lieu}</td></tr>
                            <tr><th>Gravité:</th><td><span class="badge gravite-${accident.gravite} gravite-badge">${getGraviteLabel(accident.gravite)}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Description</h6>
                        <p>${accident.description}</p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Images (${accident.images ? accident.images.length : 0})</h6>
                        <div class="images-container">
                            ${imagesHtml}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Témoins (${accident.temoins ? accident.temoins.length : 0})</h6>
                        <div class="temoins-container">
                            ${temoinsHtml}
                        </div>
                    </div>
                </div>
            `;
        }

        // Fonctions utilitaires
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});
        }

        function truncateText(text, maxLength) {
            return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
        }

        function getGraviteLabel(gravite) {
            const labels = {
                'leger': 'Léger',
                'grave': 'Grave',
                'mortel': 'Mortel',
                'materiel': 'Matériel'
            };
            return labels[gravite] || gravite;
        }

        function showAlert(type, message) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }

        // Fonction pour afficher la galerie d'images
        function showImagesGallery(accidentId) {
            const accident = accidentsData.find(acc => acc.id == accidentId);
            if (accident && accident.images && accident.images.length > 0) {
                const content = document.getElementById('images-gallery-content');
                const imagesHtml = accident.images.map(img => 
                    `<img src="${img}" class="img-fluid mb-2" style="max-width: 100%; height: auto;">`
                ).join('');
                content.innerHTML = imagesHtml;
                
                const modal = new bootstrap.Modal(document.getElementById('images-gallery-modal'));
                modal.show();
            }
        }

        // Mise à jour des statistiques
        function updateStatistics() {
            document.getElementById('total-accidents').textContent = accidentsData.length;
            
            const gravesEtMortels = accidentsData.filter(a => a.gravite === 'grave' || a.gravite === 'mortel').length;
            document.getElementById('accidents-graves').textContent = gravesEtMortels;
            
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            const accidentsMois = accidentsData.filter(a => {
                const accidentDate = new Date(a.date_accident);
                return accidentDate.getMonth() === currentMonth && accidentDate.getFullYear() === currentYear;
            }).length;
            document.getElementById('accidents-mois').textContent = accidentsMois;
            
            const totalTemoins = accidentsData.reduce((total, accident) => total + (accident.temoins ? accident.temoins.length : 0), 0);
            document.getElementById('total-temoins').textContent = totalTemoins;
        }

        // Configuration des filtres
        function setupFilters() {
            const filterGravite = document.getElementById('filter-gravite');
            const filterDateDebut = document.getElementById('filter-date-debut');
            const filterDateFin = document.getElementById('filter-date-fin');
            const filterLieu = document.getElementById('filter-lieu');

            [filterGravite, filterDateDebut, filterDateFin, filterLieu].forEach(filter => {
                filter.addEventListener('change', applyFilters);
                filter.addEventListener('input', applyFilters);
            });
        }

        // Appliquer les filtres
        function applyFilters() {
            const gravite = document.getElementById('filter-gravite').value;
            const dateDebut = document.getElementById('filter-date-debut').value;
            const dateFin = document.getElementById('filter-date-fin').value;
            const lieu = document.getElementById('filter-lieu').value.toLowerCase();

            filteredData = accidentsData.filter(accident => {
                let match = true;

                if (gravite && accident.gravite !== gravite) match = false;
                if (dateDebut && new Date(accident.date_accident) < new Date(dateDebut)) match = false;
                if (dateFin && new Date(accident.date_accident) > new Date(dateFin + ' 23:59:59')) match = false;
                if (lieu && !accident.lieu.toLowerCase().includes(lieu)) match = false;

                return match;
            });

            displayAccidents();
        }

        // Fonctions d'export et d'impression
        function exportToCSV() {
            const headers = ['ID', 'Date', 'Lieu', 'Gravité', 'Description', 'Nombre de témoins'];
            const csvContent = [
                headers.join(','),
                ...filteredData.map(accident => [
                    accident.id,
                    accident.date_accident,
                    `"${accident.lieu}"`,
                    accident.gravite,
                    `"${accident.description.replace(/"/g, '""')}"`,
                    accident.temoins ? accident.temoins.length : 0
                ].join(','))
            ].join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `rapport_accidents_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        }

        function printAccidentDetails() {
            window.print();
        }
    </script>
</body>
</html>
