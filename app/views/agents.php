<!DOCTYPE html>
<html lang="fr" data-layout="topnav" data-menu-color="brand">

<head>
    <meta charset="utf-8" />
    <title>Gestion des Agents - Bureau de Contrôle Routier</title>
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
                                        <li class="breadcrumb-item active">Gestion des Agents</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Gestion des Agents</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-5">
                                            <h4 class="header-title">Liste des Agents</h4>
                                            <p class="text-muted fs-13 mb-4">
                                                Gérez les comptes des agents dans le système
                                            </p>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="text-sm-end">
                                                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'superadmin') { ?>
                                                <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#agent-account-modal">
                                                    <i class="ri-add-circle-line"></i> Nouvel Agent
                                                </button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Champ de recherche -->
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="search-box">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" id="agentSearch" placeholder="Rechercher un agent (nom, matricule, poste, téléphone...)">
                                                    <!-- <i class="ri-search-line search-icon"></i> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-sm-end">
                                                <small class="text-muted" id="searchResults">Affichage de tous les agents</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="agents-datatable">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20px;">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                                            <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                                        </div>
                                                    </th>
                                                    <th>Agent</th>
                                                    <th>Matricule</th>
                                                    <th>Poste</th>
                                                    <th>Téléphone</th>
                                                    <th>Rôle</th>
                                                    <th>Statut</th>
                                                    <th>Date de création</th>
                                                    <th style="width: 125px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($agents) && !empty($agents)) { ?>
                                                    <?php foreach($agents as $agent) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck<?php echo $agent['id']; ?>">
                                                                <label class="form-check-label" for="customCheck<?php echo $agent['id']; ?>">&nbsp;</label>
                                                            </div>
                                                        </td>
                                                        <td class="table-user">
                                                            <img src="assets/images/users/avatar-1.jpg" alt="table-user" class="me-2 rounded-circle">
                                                            <a href="javascript:void(0);" class="text-body fw-semibold"><?php echo htmlspecialchars($agent['username']); ?></a>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($agent['matricule']); ?></td>
                                                        <td><?php echo htmlspecialchars($agent['poste'] ?? 'N/A'); ?></td>
                                                        <td><?php echo htmlspecialchars($agent['telephone'] ?? 'N/A'); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $agent['role'] == 'superadmin' ? 'danger' : ($agent['role'] == 'admin' ? 'warning' : 'info'); ?>">
                                                                <?php echo ucfirst($agent['role']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $agent['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                                <?php echo $agent['status'] == 'active' ? 'Actif' : 'Inactif'; ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('d/m/Y', strtotime($agent['created_at'])); ?></td>
                                                        <td>
                                                            <a href="javascript:void(0);" class="action-icon me-2" onclick="viewAgent(<?php echo $agent['id']; ?>)" title="Voir détails" style="font-size: 18px;">
                                                                <i class="ri-eye-line text-primary"></i>
                                                            </a>
                                                            <?php if($_SESSION['user']['role'] == 'superadmin' && $agent['id'] != $_SESSION['user']['id']) { ?>
                                                            <a href="javascript:void(0);" class="action-icon" onclick="toggleAgentStatus(<?php echo $agent['id']; ?>, '<?php echo $agent['status']; ?>')" 
                                                               title="<?php echo $agent['status'] == 'active' ? 'Désactiver' : 'Activer'; ?>" style="font-size: 18px;">
                                                                <i class="ri-<?php echo $agent['status'] == 'active' ? 'close' : 'check'; ?>-circle-line text-<?php echo $agent['status'] == 'active' ? 'danger' : 'success'; ?>"></i>
                                                            </a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">Aucun agent trouvé</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- end row-->

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

    </div>
    <!-- END wrapper -->

    <!-- Modal pour voir/éditer les détails d'un agent -->
    <div class="modal fade" id="agent-details-modal" tabindex="-1" aria-labelledby="agent-details-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="agent-details-modal-label">Détails de l'Agent</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="agent-details-form" method="POST" action="/update-agent">
                        <input type="hidden" id="agent-id" name="agent_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent-username" class="form-label">Nom d'utilisateur</label>
                                    <input type="text" class="form-control" id="agent-username" name="username" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent-matricule" class="form-label">Matricule</label>
                                    <input type="text" class="form-control" id="agent-matricule" name="matricule" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent-poste" class="form-label">Poste</label>
                                    <input type="text" class="form-control" id="agent-poste" name="poste" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent-role" class="form-label">Rôle</label>
                                    <select class="form-select" id="agent-role" name="role" disabled>
                                        <option value="opj">OPJ</option>
                                        <option value="admin">Admin</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent-status" class="form-label">Statut</label>
                                    <select class="form-select" id="agent-status" name="status" disabled>
                                        <option value="active">Actif</option>
                                        <option value="inactive">Inactif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent-created" class="form-label">Date de création</label>
                                    <input type="text" class="form-control" id="agent-created" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-mode-switch">
                                <label class="form-check-label" for="edit-mode-switch">
                                    Mode édition
                                </label>
                            </div>
                        </div>

                        <div class="text-end" id="modal-save-buttons" style="display: none;">
                            <button type="button" class="btn btn-secondary me-2" onclick="cancelEdit()">Annuler</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Sauvegarder
                            </button>
                        </div>
                    </form>
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

    <!-- Scripts pour la gestion des agents -->
    <script>
        // Variables globales
        let originalAgentData = {};

        // Fonction pour voir les détails d'un agent
        function viewAgent(agentId) {
            fetch(`/get-agent/${agentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const agent = data.agent;
                        
                        // Remplir le modal avec les données de l'agent
                        document.getElementById('agent-id').value = agent.id;
                        document.getElementById('agent-username').value = agent.username;
                        document.getElementById('agent-matricule').value = agent.matricule;
                        document.getElementById('agent-poste').value = agent.poste || '';
                        document.getElementById('agent-role').value = agent.role;
                        document.getElementById('agent-status').value = agent.status;
                        document.getElementById('agent-created').value = new Date(agent.created_at).toLocaleDateString('fr-FR');

                        // Sauvegarder les données originales
                        originalAgentData = {
                            username: agent.username,
                            matricule: agent.matricule,
                            poste: agent.poste || '',
                            role: agent.role,
                            status: agent.status
                        };

                        // Réinitialiser le mode édition
                        document.getElementById('edit-mode-switch').checked = false;
                        toggleEditMode(false);

                        // Afficher le modal
                        new bootstrap.Modal(document.getElementById('agent-details-modal')).show();
                    } else {
                        alert('Erreur lors du chargement des données de l\'agent');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement des données de l\'agent');
                });
        }

        // Fonction pour activer/désactiver un agent
        function toggleAgentStatus(agentId, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'activer' : 'désactiver';
            
            if (confirm(`Êtes-vous sûr de vouloir ${action} cet agent ?`)) {
                fetch('/toggle-agent-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        agent_id: agentId,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Recharger la page pour voir les changements
                    } else {
                        alert(data.message || 'Erreur lors de la modification du statut');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la modification du statut');
                });
            }
        }

        // Fonction pour basculer le mode édition
        function toggleEditMode(isEditMode) {
            const fields = ['agent-username', 'agent-matricule', 'agent-poste', 'agent-role', 'agent-status'];
            const saveButtons = document.getElementById('modal-save-buttons');

            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field.tagName === 'SELECT') {
                    field.disabled = !isEditMode;
                } else {
                    field.readOnly = !isEditMode;
                }
                
                if (isEditMode) {
                    field.classList.add('border-primary');
                } else {
                    field.classList.remove('border-primary');
                }
            });

            saveButtons.style.display = isEditMode ? 'block' : 'none';
        }

        // Fonction pour annuler l'édition
        function cancelEdit() {
            // Restaurer les données originales
            document.getElementById('agent-username').value = originalAgentData.username;
            document.getElementById('agent-matricule').value = originalAgentData.matricule;
            document.getElementById('agent-poste').value = originalAgentData.poste;
            document.getElementById('agent-role').value = originalAgentData.role;
            document.getElementById('agent-status').value = originalAgentData.status;

            // Désactiver le mode édition
            document.getElementById('edit-mode-switch').checked = false;
            toggleEditMode(false);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Gestionnaire du switch de mode édition
            document.getElementById('edit-mode-switch').addEventListener('change', function() {
                toggleEditMode(this.checked);
            });

            // Gestionnaire de soumission du formulaire
            document.getElementById('agent-details-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('/update-agent', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Agent mis à jour avec succès');
                        location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de la mise à jour');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la mise à jour');
                });
            });

            // Fonctionnalité de recherche d'agents
            const searchInput = document.getElementById('agentSearch');
            const agentsTable = document.getElementById('agents-datatable');
            const searchResults = document.getElementById('searchResults');
            const tableBody = agentsTable.querySelector('tbody');
            const allRows = Array.from(tableBody.querySelectorAll('tr'));
            const totalAgents = allRows.length;

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;

                allRows.forEach(row => {
                    // Ignorer la ligne "Aucun agent trouvé" si elle existe
                    if (row.querySelector('td[colspan]')) {
                        return;
                    }

                    const cells = row.querySelectorAll('td');
                    const username = cells[1] ? cells[1].textContent.toLowerCase() : '';
                    const matricule = cells[2] ? cells[2].textContent.toLowerCase() : '';
                    const poste = cells[3] ? cells[3].textContent.toLowerCase() : '';
                    const telephone = cells[4] ? cells[4].textContent.toLowerCase() : '';
                    const role = cells[5] ? cells[5].textContent.toLowerCase() : '';
                    const status = cells[6] ? cells[6].textContent.toLowerCase() : '';

                    const isVisible = username.includes(searchTerm) || 
                                    matricule.includes(searchTerm) || 
                                    poste.includes(searchTerm) || 
                                    telephone.includes(searchTerm) || 
                                    role.includes(searchTerm) || 
                                    status.includes(searchTerm);

                    if (isVisible) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Mettre à jour le compteur de résultats
                if (searchTerm === '') {
                    searchResults.textContent = `Affichage de tous les agents (${totalAgents})`;
                } else {
                    searchResults.textContent = `${visibleCount} agent(s) trouvé(s) sur ${totalAgents}`;
                }

                // Afficher un message si aucun résultat
                const noResultsRow = tableBody.querySelector('.no-results-row');
                if (visibleCount === 0 && searchTerm !== '') {
                    if (!noResultsRow) {
                        const newRow = document.createElement('tr');
                        newRow.className = 'no-results-row';
                        newRow.innerHTML = '<td colspan="9" class="text-center text-muted py-4"><i class="ri-search-line me-2"></i>Aucun agent ne correspond à votre recherche</td>';
                        tableBody.appendChild(newRow);
                    }
                } else if (noResultsRow) {
                    noResultsRow.remove();
                }
            });

            // Effacer la recherche avec Escape
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.dispatchEvent(new Event('input'));
                    this.blur();
                }
            });
        });
    </script>

</body>
</html>
