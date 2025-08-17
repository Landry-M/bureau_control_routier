<!DOCTYPE html>
<html lang="en" data-layout="topnav" data-menu-color="brand">

    
<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:56 GMT -->
<head>
        <meta charset="utf-8" />
        <title>Consulter les dossiers | Bureau de Contrôle Routier</title>
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
                                <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">     
                                    <h4 class="page-title">Consultation des dossiers</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                           
                            <div class="col-xl-12 col-lg-12">

                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                            <li class="nav-item">
                                                <a href="#conducteurs" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-start rounded-0 active">
                                                    Conducteurs et véhicules
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#vehicules" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                                                    Véhicules et plaques d'immatriculations
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#particuliers" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-end rounded-0">
                                                    Particuliers
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#entreprises" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-end rounded-0">
                                                    Entreprises
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="conducteurs">
                                                <div class="card mt-1">
                                                    <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <h5 class="card-title mb-0">
                                                            <i class="ri-user-2-line me-2"></i>Conducteurs & Véhicules
                                                        </h5>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="text" id="filter-conducteurs" class="form-control form-control-sm" placeholder="Rechercher (nom, permis, date)..." style="min-width:240px;">
                                                            <select id="sort-conducteurs" class="form-select form-select-sm" style="min-width:220px;">
                                                                <option value="">Tri: récent d'abord (défaut)</option>
                                                                <option value="nom_asc">Nom A→Z</option>
                                                                <option value="nom_desc">Nom Z→A</option>
                                                                <option value="permis_asc">Permis A→Z</option>
                                                                <option value="permis_desc">Permis Z→A</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0" id="table-conducteurs">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Nom</th>
                                                                        <th>Numéro permis</th>
                                                                        <th>Date de naissance</th>
                                                                        <th>Expiration permis</th>
                                                                        <th class="text-center">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
<?php $i=1; foreach ($conducteurs as $c): 
    $c = (object)$c; // Convertir le tableau en objet pour la compatibilité
?>
                                                                    <tr 
                                                                        data-cid="<?= htmlspecialchars($c->id ?? '', ENT_QUOTES) ?>"
                                                                        data-nom="<?= htmlspecialchars($c->nom ?? '', ENT_QUOTES) ?>"
                                                                        data-numero_permis="<?= htmlspecialchars($c->numero_permis ?? '', ENT_QUOTES) ?>"
                                                                        data-date_naissance="<?= htmlspecialchars($c->date_naissance ?? '', ENT_QUOTES) ?>"
                                                                        data-adresse="<?= htmlspecialchars($c->adresse ?? '', ENT_QUOTES) ?>"
                                                                        data-permis_valide_le="<?= htmlspecialchars($c->permis_valide_le ?? '', ENT_QUOTES) ?>"
                                                                        data-permis_expire_le="<?= htmlspecialchars($c->permis_expire_le ?? '', ENT_QUOTES) ?>"
                                                                        data-photo="<?= htmlspecialchars($c->photo ?? '', ENT_QUOTES) ?>"
                                                                        data-permis_recto="<?= htmlspecialchars($c->permis_recto ?? '', ENT_QUOTES) ?>"
                                                                        data-permis_verso="<?= htmlspecialchars($c->permis_verso ?? '', ENT_QUOTES) ?>"
                                                                    >
                                                                        <td><?= $i++; ?></td>
                                                                        <td><?= htmlspecialchars($c->nom ?? '') ?></td>
                                                                        <td><?= htmlspecialchars($c->numero_permis ?? '') ?></td>
                                                                        <td><?= htmlspecialchars($c->date_naissance ?? '') ?></td>
                                                                        <td><?= htmlspecialchars($c->permis_expire_le ?? '') ?></td>
                                                                        <td class="text-center">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-open-conducteur" data-bs-toggle="modal" data-bs-target="#modalConducteurDetails">Détails</button>
                                                                        </td>
                                                                    </tr>
<?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Details Conducteur -->
                                                <div class="modal fade" id="modalConducteurDetails" tabindex="-1" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title">Détails du conducteur</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                          <li class="nav-item" role="presentation">
                                                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-cond-infos" role="tab">Informations</a>
                                                          </li>
                                                          <li class="nav-item" role="presentation">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-cond-contravs" role="tab">Contraventions</a>
                                                          </li>
                                                        </ul>
                                                        <div class="tab-content pt-3">
                                                          <div class="tab-pane fade show active" id="tab-cond-infos" role="tabpanel">
                                                            <div class="row g-2">
                                                              <div class="col-md-6"><strong>Nom:</strong> <span id="dc_nom"></span></div>
                                                              <div class="col-md-6"><strong>Numéro permis:</strong> <span id="dc_numero_permis"></span></div>
                                                              <div class="col-md-6"><strong>Date de naissance:</strong> <span id="dc_date_naissance"></span></div>
                                                              <div class="col-md-6"><strong>Adresse:</strong> <span id="dc_adresse"></span></div>
                                                              <div class="col-md-6"><strong>Permis valide le:</strong> <span id="dc_permis_valide_le"></span></div>
                                                              <div class="col-md-6"><strong>Permis expire le:</strong> <span id="dc_permis_expire_le"></span></div>
                                                              <div class="col-md-4"><strong>Photo:</strong><br><img id="dc_photo" src="" alt="Photo" class="img-thumbnail" style="max-height:120px"></div>
                                                              <div class="col-md-4"><strong>Permis recto:</strong><br><img id="dc_permis_recto" src="" alt="Recto" class="img-thumbnail" style="max-height:120px"></div>
                                                              <div class="col-md-4"><strong>Permis verso:</strong><br><img id="dc_permis_verso" src="" alt="Verso" class="img-thumbnail" style="max-height:120px"></div>
                                                            </div>
                                                          </div>
                                                          <div class="tab-pane fade" id="tab-cond-contravs" role="tabpanel">
                                                            <div class="table-responsive">
                                                              <style>
                                                                /* Switch payed en vert quand activé */
                                                                #table-contravs .form-check-input:checked {
                                                                  background-color: #0d6efd; /* bootstrap primary blue */
                                                                  border-color: #0d6efd;
                                                                }

                                                        // Utilitaire: formater des colonnes date dans un tableau
                                                        function formatTableDateColumns(tableSelector, colIndexes){
                                                            const table = document.querySelector(tableSelector);
                                                            if (!table || !Array.isArray(colIndexes) || !colIndexes.length) return;
                                                            const rows = table.querySelectorAll('tbody tr');
                                                            rows.forEach(tr=>{
                                                                colIndexes.forEach(idx=>{
                                                                    const td = tr.querySelector(`td:nth-child(${idx})`);
                                                                    if (td) td.textContent = window.formatDMY(td.textContent);
                                                                });
                                                            });
                                                        }

                                                        // Appliquer sur les tables visibles dans la page
                                                        // Conducteurs: 4 = Date de naissance, 5 = Expiration permis
                                                        formatTableDateColumns('#table-conducteurs', [4,5]);
                                                        // Véhicules: 6 = Valide le, 7 = Expire le
                                                        formatTableDateColumns('#vehicules-table2', [6,7]);
                                                        // Particuliers: 4 = Date naissance
                                                        formatTableDateColumns('#particuliers-table', [4]);
                                                              </style>
                                                              <table class="table table-sm table-striped align-middle" id="table-contravs">
                                                                <thead>
                                                                  <tr>
                                                                    <th>#</th>
                                                                    <th>Date</th>
                                                                    <th>Lieu</th>
                                                                    <th>Type</th>
                                                                    <th>Référence</th>
                                                                    <th>Amende</th>
                                                                    <th>Description</th>
                                                                    <th>Payé</th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                              </table>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                                <script>
                                                    (function(){
                                                        // Contraventions groupées par dossier (conducteur) depuis PHP
                                                        const CONTRAVS = <?php echo json_encode($contraventionsByDossier ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;
                                                        // Helper global: format date to DD-MM-YYYY (client-side only)
                                                        if (!window.formatDMY) {
                                                            window.formatDMY = function(input){
                                                                if (!input) return '';
                                                                const s = String(input).trim();
                                                                // Try YYYY-MM-DD inside any datetime-like string
                                                                const m = s.match(/(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})/);
                                                                if (m) {
                                                                    const y = m[1], mo = m[2].padStart(2,'0'), d = m[3].padStart(2,'0');
                                                                    return `${d}-${mo}-${y}`;
                                                                }
                                                                const dt = new Date(s);
                                                                if (!isNaN(dt.getTime())) {
                                                                    const d = String(dt.getDate()).padStart(2,'0');
                                                                    const mo = String(dt.getMonth()+1).padStart(2,'0');
                                                                    const y = dt.getFullYear();
                                                                    return `${d}-${mo}-${y}`;
                                                                }
                                                                return s;
                                                            }
                                                        }

                                                        // Helper global: format money with CDF prefix and thousands separators
                                                        if (!window.formatMoneyCDF) {
                                                            window.formatMoneyCDF = function(value){
                                                                if (value === null || value === undefined || value === '') return '';
                                                                const num = Number(String(value).replace(/[^0-9.-]/g, ''));
                                                                if (isNaN(num)) return String(value);
                                                                try {
                                                                    return 'CDF ' + new Intl.NumberFormat('fr-CD', { maximumFractionDigits: 0 }).format(num);
                                                                } catch (e) {
                                                                    return 'CDF ' + String(num);
                                                                }
                                                            }
                                                        }

                                                        function setImg(id, path){
                                                            const img = document.getElementById(id);
                                                            if (!img) return;
                                                            if (path) { img.src = path; img.style.display=''; }
                                                            else { img.removeAttribute('src'); img.style.display='none'; }
                                                        }

                                                        function fillDetails(row){
                                                            const get = (key)=> row.getAttribute('data-'+key) || '';
                                                            document.getElementById('dc_nom').textContent = get('nom');
                                                            document.getElementById('dc_numero_permis').textContent = get('numero_permis');
                                                            document.getElementById('dc_date_naissance').textContent = window.formatDMY(get('date_naissance'));
                                                            document.getElementById('dc_adresse').textContent = get('adresse');
                                                            document.getElementById('dc_permis_valide_le').textContent = window.formatDMY(get('permis_valide_le'));
                                                            document.getElementById('dc_permis_expire_le').textContent = window.formatDMY(get('permis_expire_le'));
                                                            setImg('dc_photo', get('photo'));
                                                            setImg('dc_permis_recto', get('permis_recto'));
                                                            setImg('dc_permis_verso', get('permis_verso'));

                                                            const cid = row.getAttribute('data-cid');
                                                            const tbody = document.querySelector('#table-contravs tbody');
                                                            tbody.innerHTML = '';
                                                            const list = CONTRAVS[cid] || [];
                                                            list.forEach((cv, idx)=>{
                                                                const tr = document.createElement('tr');
                                                                // Normaliser la valeur payed (MySQL peut renvoyer '0'/'1' en string)
                                                                const isPaid = (cv.payed === 1 || cv.payed === '1' || cv.payed === true);
                                                                const checked = isPaid ? 'checked' : '';
                                                                const label = isPaid ? 'Payé' : 'Non payé';
                                                                tr.innerHTML = `
                                                                    <td>${idx+1}</td>
                                                                    <td>${window.formatDMY(cv.date_infraction || '')}</td>
                                                                    <td>${cv.lieu || ''}</td>
                                                                    <td>${cv.type_infraction || ''}</td>
                                                                    <td>${cv.reference_loi || ''}</td>
                                                                    <td>${window.formatMoneyCDF(cv.amende)}</td>
                                                                    <td>${cv.description ? cv.description : ''}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <div class="form-check form-switch m-0">
                                                                                <input class="form-check-input cv-payed-switch" type="checkbox" role="switch" data-cv-id="${cv.id}" ${checked}>
                                                                            </div>
                                                                            <span class="badge bg-light text-dark cv-payed-label">${label}</span>
                                                                        </div>
                                                                    </td>
                                                                `;
                                                                tbody.appendChild(tr);
                                                            });
                                                        }

                                                        document.addEventListener('click', function(e){
                                                            const a = e.target.closest('.btn-open-conducteur');
                                                            if (!a) return;
                                                            const tr = a.closest('tr');
                                                            if (tr) fillDetails(tr);
                                                        });
                                                        
                                                        // Filtre & Tri
                                                        const filterInput = document.getElementById('filter-conducteurs');
                                                        const sortSelect = document.getElementById('sort-conducteurs');
                                                        const table = document.getElementById('table-conducteurs');
                                                        const tbody = table.querySelector('tbody');
                                                        
                                                        function norm(v){ return (v||'').toString().toLowerCase(); }
                                                        
                                                        function rowKey(tr, key){
                                                            switch(key){
                                                                case 'nom': return norm(tr.querySelector('td:nth-child(2)')?.textContent);
                                                                case 'permis': return norm(tr.querySelector('td:nth-child(3)')?.textContent);
                                                                default: return '';
                                                            }
                                                        }
                                                        
                                                        function applyFilter(){
                                                            const q = norm(filterInput.value);
                                                            const rows = Array.from(tbody.querySelectorAll('tr'));
                                                            rows.forEach(tr => {
                                                                const text = [
                                                                    tr.querySelector('td:nth-child(2)')?.textContent,
                                                                    tr.querySelector('td:nth-child(3)')?.textContent,
                                                                    tr.querySelector('td:nth-child(4)')?.textContent,
                                                                    tr.querySelector('td:nth-child(5)')?.textContent,
                                                                ].join(' ').toLowerCase();
                                                                tr.style.display = q && !text.includes(q) ? 'none' : '';
                                                            });
                                                        }
                                                        
                                                        function applySort(){
                                                            const mode = sortSelect.value;
                                                            if (!mode) { renumber(); return; }
                                                            const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.style.display !== 'none');
                                                            const [field, dir] = mode.split('_');
                                                            rows.sort((a,b)=>{
                                                                const va = rowKey(a, field);
                                                                const vb = rowKey(b, field);
                                                                if (va < vb) return dir==='asc' ? -1 : 1;
                                                                if (va > vb) return dir==='asc' ? 1 : -1;
                                                                return 0;
                                                            });
                                                            // Réinjection des lignes triées (on conserve les lignes cachées en place)
                                                            rows.forEach(r=> tbody.appendChild(r));
                                                            renumber();
                                                        }
                                                        
                                                        function renumber(){
                                                            let idx = 1;
                                                            Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                                if (tr.style.display === 'none') return;
                                                                const cell = tr.querySelector('td:first-child');
                                                                if (cell) cell.textContent = idx++;
                                                            });
                                                        }
                                                        
                                                        filterInput?.addEventListener('input', ()=>{ applyFilter(); applySort(); });
                                                        sortSelect?.addEventListener('change', ()=>{ applySort(); });
                                                        
                                                        // Gestion switch payé
                                                        document.addEventListener('change', async (e)=>{
                                                            const input = e.target.closest('.cv-payed-switch');
                                                            if (!input) return;
                                                            const id = input.getAttribute('data-cv-id');
                                                            const prevChecked = !input.checked; // état AVANT clic (change est déclenché après bascule)
                                                            const payed = input.checked ? '1' : '0';
                                                            const labelEl = input.closest('td')?.querySelector('.cv-payed-label');
                                                            input.disabled = true;
                                                            try {
                                                                const resp = await fetch('/contravention/update-payed', {
                                                                    method: 'POST',
                                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                                                                    body: new URLSearchParams({ id, payed }).toString()
                                                                });
                                                                const data = await resp.json();
                                                                if (!resp.ok || !data.ok) {
                                                                    throw new Error(data.error || 'Erreur lors de la mise à jour');
                                                                }
                                                                // succès: mettre à jour le libellé
                                                                if (labelEl) labelEl.textContent = input.checked ? 'Payé' : 'Non payé';
                                                            } catch (err) {
                                                                // revert UI
                                                                input.checked = prevChecked;
                                                                if (labelEl) labelEl.textContent = input.checked ? 'Payé' : 'Non payé';
                                                                alert(err.message || 'Erreur réseau');
                                                            } finally {
                                                                input.disabled = false;
                                                            }
                                                        });

                                                        // Initial
                                                        applyFilter();
                                                    })();
                                                </script>

                                            </div> <!-- end tab-pane -->
                                            <!-- end about me section content -->
    
                                            <div class="tab-pane" id="vehicules">
                                                <div class="card mt-2">
                                                    <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <h5 class="card-title mb-0"><i class="ri-car-line me-2"></i>Véhicules et plaques d'immatriculation</h5>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="text" id="veh-filter2" class="form-control form-control-sm" placeholder="Filtrer (marque, plaque, couleur...)" style="min-width:240px;">
                                                            <select id="veh-sort2" class="form-select form-select-sm" style="min-width:220px;">
                                                                <option value="">Trier...</option>
                                                                <option value="marque_asc">Marque A→Z</option>
                                                                <option value="marque_desc">Marque Z→A</option>
                                                                <option value="plaque_asc">Plaque A→Z</option>
                                                                <option value="plaque_desc">Plaque Z→A</option>
                                                                <option value="id_desc">Plus récent</option>
                                                                <option value="id_asc">Plus ancien</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0" id="vehicules-table2">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width:60px;">#</th>
                                                                        <th>Marque</th>
                                                                        <th>Année</th>
                                                                        <th>Couleur</th>
                                                                        <th>Plaque</th>
                                                                        <th>Valide le</th>
                                                                        <th>Expire le</th>
                                                                        <th>Assurance</th>
                                                                        <th style="width:90px;">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $idxv=1; if(!empty($vehicules)) { foreach($vehicules as $v): ?>
                                                                        <tr data-veh-id="<?php echo htmlspecialchars($v['id']); ?>" data-veh-images="<?php echo htmlspecialchars($v['images'] ?? '[]', ENT_QUOTES, 'UTF-8'); ?>">
                                                                            <td><?php echo $idxv++; ?></td>
                                                                            <td class="veh2-marque"><?php echo htmlspecialchars($v['marque'] ?? ''); ?></td>
                                                                            <td class="veh2-annee"><?php echo htmlspecialchars($v['annee'] ?? ''); ?></td>
                                                                            <td class="veh2-couleur"><?php echo htmlspecialchars($v['couleur'] ?? ''); ?></td>
                                                                            <td class="veh2-plaque"><?php echo htmlspecialchars($v['plaque'] ?? ''); ?></td>
                                                                            <td class="veh2-valide"><?php echo htmlspecialchars($v['plaque_valide_le'] ?? ''); ?></td>
                                                                            <td class="veh2-expire"><?php echo htmlspecialchars($v['plaque_expire_le'] ?? ''); ?></td>
                                                                            <td class="veh2-assu"><?php echo htmlspecialchars(($v['nume_assurance'] ?? '').' '.($v['societe_assurance'] ?? '')); ?></td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary btn-veh2-details" data-bs-toggle="modal" data-bs-target="#vehiculeDetailsModal2">Détails</button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal détails véhicule -->
                                                <div class="modal fade" id="vehiculeDetailsModal2" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><i class="ri-car-line me-2"></i>Détails du véhicule</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul class="nav nav-tabs" role="tablist">
                                                                    <li class="nav-item" role="presentation">
                                                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#veh2-info" type="button" role="tab">Informations</button>
                                                                    </li>
                                                                    <li class="nav-item" role="presentation">
                                                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#veh2-contravs" type="button" role="tab">Contraventions</button>
                                                                    </li>
                                                                </ul>
                                                                <div class="tab-content pt-3">
                                                                    <div class="tab-pane fade show active" id="veh2-info" role="tabpanel">
                                                                        <div class="row g-2">
                                                                            <div class="col-md-6"><strong>Marque:</strong> <span id="md2-marque"></span></div>
                                                                            <div class="col-md-3"><strong>Année:</strong> <span id="md2-annee"></span></div>
                                                                            <div class="col-md-3"><strong>Couleur:</strong> <span id="md2-couleur"></span></div>
                                                                            <div class="col-md-4"><strong>Plaque:</strong> <span id="md2-plaque"></span></div>
                                                                            <div class="col-md-4"><strong>Valide le:</strong> <span id="md2-valide"></span></div>
                                                                            <div class="col-md-4"><strong>Expire le:</strong> <span id="md2-expire"></span></div>
                                                                            <div class="col-12"><strong>Assurance:</strong> <span id="md2-assu"></span></div>
                                                                        </div>
                                                                        <hr/>
                                                                        <div>
                                                                            <strong>Images du véhicule:</strong>
                                                                            <div id="md2-images" class="d-flex flex-wrap gap-2 mt-2"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="veh2-contravs" role="tabpanel">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm" id="table-veh2-contravs">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Date</th>
                                                                                        <th>Lieu</th>
                                                                                        <th>Type</th>
                                                                                        <th>Référence</th>
                                                                                        <th>Amende</th>
                                                                                        <th>Description</th>
                                                                                        <th>Payé</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script>
                                                (function(){
                                                    const CONTRAVS_V2 = <?php echo json_encode($contraventionsByVehicule ?? []); ?>;
                                                    const tbl = document.getElementById('vehicules-table2');
                                                    const tbody = tbl ? tbl.querySelector('tbody') : null;
                                                    const filterInput = document.getElementById('veh-filter2');
                                                    const sortSelect = document.getElementById('veh-sort2');

                                                    function rowKey(tr, field){
                                                        const get = (sel)=> (tr.querySelector(sel)?.textContent || '').toLowerCase();
                                                        switch(field){
                                                            case 'marque': return get('.veh2-marque');
                                                            case 'plaque': return get('.veh2-plaque');
                                                            case 'id': return parseInt(tr.getAttribute('data-veh-id')||'0',10);
                                                            default: return get('.veh2-marque');
                                                        }
                                                    }
                                                    function applyFilter(){
                                                        if (!tbody) return;
                                                        const q = (filterInput?.value || '').toLowerCase().trim();
                                                        Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                            const text = [
                                                                tr.querySelector('.veh2-marque')?.textContent,
                                                                tr.querySelector('.veh2-plaque')?.textContent,
                                                                tr.querySelector('.veh2-couleur')?.textContent
                                                            ].join(' ').toLowerCase();
                                                            tr.style.display = q && !text.includes(q) ? 'none' : '';
                                                        });
                                                    }
                                                    function renumber(){
                                                        if (!tbody) return;
                                                        let i=1; Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                            if (tr.style.display==='none') return; tr.querySelector('td:first-child').textContent = i++;
                                                        });
                                                    }
                                                    function applySort(){
                                                        if (!tbody) return;
                                                        const mode = sortSelect?.value; if (!mode){ renumber(); return; }
                                                        const [field, dir] = mode.split('_');
                                                        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r=> r.style.display !== 'none');
                                                        rows.sort((a,b)=>{
                                                            const va = rowKey(a, field), vb = rowKey(b, field);
                                                            if (va < vb) return dir==='asc' ? -1 : 1;
                                                            if (va > vb) return dir==='asc' ? 1 : -1; return 0;
                                                        });
                                                        rows.forEach(r=> tbody.appendChild(r)); renumber();
                                                    }
                                                    // Switch "Payé" (Particuliers)
                                                    document.addEventListener('change', async (e)=>{
                                                        const input = e.target.closest('.pt-cv-payed'); if (!input) return;
                                                        const id = input.getAttribute('data-cv-id');
                                                        const prevChecked = !input.checked; // état avant clic
                                                        const payed = input.checked ? '1' : '0';
                                                        const labelEl = input.closest('td')?.querySelector('.pt-cv-payed-label');
                                                        input.disabled = true;
                                                        try {
                                                            const resp = await fetch('/contravention/update-payed', {
                                                                method: 'POST',
                                                                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                                                                body: new URLSearchParams({ id, payed }).toString()
                                                            });
                                                            const data = await resp.json();
                                                            if (!resp.ok || !data.ok) throw new Error(data.error || 'Erreur lors de la mise à jour');
                                                            if (labelEl) labelEl.textContent = input.checked ? 'Payé' : 'Non payé';
                                                        } catch (err) {
                                                            input.checked = prevChecked;
                                                            if (labelEl) labelEl.textContent = input.checked ? 'Payé' : 'Non payé';
                                                            alert(err.message || 'Erreur réseau');
                                                        } finally {
                                                            input.disabled = false;
                                                        }
                                                    });

                                                    filterInput?.addEventListener('input', ()=>{ applyFilter(); applySort(); });
                                                    sortSelect?.addEventListener('change', ()=> applySort());

                                                    // Détails modal
                                                    document.addEventListener('click', (e)=>{
                                                        const btn = e.target.closest('.btn-veh2-details'); if (!btn) return;
                                                        const tr = btn.closest('tr'); const id = tr?.getAttribute('data-veh-id'); if (!id) return;
                                                        // Infos
                                                        document.getElementById('md2-marque').textContent = tr.querySelector('.veh2-marque')?.textContent || '';
                                                        document.getElementById('md2-annee').textContent = tr.querySelector('.veh2-annee')?.textContent || '';
                                                        document.getElementById('md2-couleur').textContent = tr.querySelector('.veh2-couleur')?.textContent || '';
                                                        document.getElementById('md2-plaque').textContent = tr.querySelector('.veh2-plaque')?.textContent || '';
                                                        document.getElementById('md2-valide').textContent = window.formatDMY(tr.querySelector('.veh2-valide')?.textContent || '');
                                                        document.getElementById('md2-expire').textContent = window.formatDMY(tr.querySelector('.veh2-expire')?.textContent || '');
                                                        document.getElementById('md2-assu').textContent = tr.querySelector('.veh2-assu')?.textContent || '';

                                                        // Images
                                                        const imgsContainer = document.getElementById('md2-images');
                                                        if (imgsContainer) {
                                                            imgsContainer.innerHTML = '';
                                                            let imgs = [];
                                                            const raw = tr.getAttribute('data-veh-images') || '[]';
                                                            try { imgs = JSON.parse(raw); } catch(_) { imgs = []; }
                                                            if (Array.isArray(imgs) && imgs.length) {
                                                                imgs.forEach(src => {
                                                                    if (!src) return;
                                                                    const a = document.createElement('a');
                                                                    a.href = src; a.target = '_blank'; a.rel = 'noopener';
                                                                    a.innerHTML = `<img src="${src}" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">`;
                                                                    imgsContainer.appendChild(a);
                                                                });
                                                            } else {
                                                                imgsContainer.innerHTML = '<span class="text-muted">Aucune image disponible</span>';
                                                            }
                                                        }

                                                        // Contraventions
                                                        const list = CONTRAVS_V2[id] || [];
                                                        const tb = document.querySelector('#table-veh2-contravs tbody');
                                                        tb.innerHTML = '';
                                                        list.forEach((cv, i)=>{
                                                            const isPaid = (cv.payed === 1 || cv.payed === '1' || cv.payed === true);
                                                            const trCv = document.createElement('tr');
                                                            trCv.innerHTML = `
                                                                <td>${i+1}</td>
                                                                <td>${window.formatDMY(cv.date_infraction || '')}</td>
                                                                <td>${cv.lieu || ''}</td>
                                                                <td>${cv.type_infraction || ''}</td>
                                                                <td>${cv.reference_loi || ''}</td>
                                                                <td>${cv.amende || ''}</td>
                                                                <td>${cv.description || ''}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <div class="form-check form-switch m-0">
                                                                            <input class="form-check-input veh2-cv-payed" type="checkbox" data-cv-id="${cv.id}" ${isPaid?'checked':''}>
                                                                        </div>
                                                                        <span class="badge bg-light text-dark veh2-cv-label">${isPaid?'Payé':'Non payé'}</span>
                                                                    </div>
                                                                </td>`;
                                                            tb.appendChild(trCv);
                                                        });
                                                    });

                                                    // Update Payé via AJAX
                                                    document.addEventListener('change', async (e)=>{
                                                        const input = e.target.closest('.veh2-cv-payed'); if (!input) return;
                                                        const id = input.getAttribute('data-cv-id');
                                                        const prevChecked = !input.checked; // état avant clic
                                                        const payed = input.checked ? '1':'0';
                                                        const labelEl = input.closest('td')?.querySelector('.veh2-cv-label');
                                                        input.disabled = true;
                                                        try{
                                                            const resp = await fetch('/contravention/update-payed', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'}, body: new URLSearchParams({id, payed}).toString()});
                                                            const data = await resp.json();
                                                            if (!resp.ok || !data.ok) throw new Error(data.error || 'Erreur lors de la mise à jour');
                                                            if (labelEl) labelEl.textContent = input.checked ? 'Payé':'Non payé';
                                                        }catch(err){
                                                            input.checked = prevChecked;
                                                            if (labelEl) labelEl.textContent = input.checked ? 'Payé':'Non payé';
                                                            alert(err.message || 'Erreur réseau');
                                                        }finally{
                                                            input.disabled = false;
                                                        }
                                                    });

                                                    // Initial
                                                    applyFilter();
                                                })();
                                                </script>

                                            </div>
                                            <!-- end timeline content-->
    
                                            <div class="tab-pane" id="particuliers">
                                                <div class="card mt-2">
                                                    <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <h5 class="card-title mb-0"><i class="ri-contacts-book-2-line me-2"></i>Particuliers</h5>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="text" id="part-filter" class="form-control form-control-sm" placeholder="Filtrer (nom, n° national, email...)" style="min-width:240px;">
                                                            <select id="part-sort" class="form-select form-select-sm" style="min-width:220px;">
                                                                <option value="">Trier...</option>
                                                                <option value="nom_asc">Nom A→Z</option>
                                                                <option value="nom_desc">Nom Z→A</option>
                                                                <option value="num_asc">N° national A→Z</option>
                                                                <option value="num_desc">N° national Z→A</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0" id="particuliers-table">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width:60px;">#</th>
                                                                        <th>Nom</th>
                                                                        <th>N° National</th>
                                                                        <th>Date naissance</th>
                                                                        <th>Adresse</th>
                                                                        <th>Profession</th>
                                                                        <th>Téléphone</th>
                                                                        <th>Email</th>
                                                                        <th style="width:90px;">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $idxp=1; if(!empty($particuliers)) { foreach($particuliers as $p): ?>
                                                                        <tr 
                                                                            data-id="<?php echo htmlspecialchars($p['id'] ?? '', ENT_QUOTES); ?>"
                                                                            data-nom="<?php echo htmlspecialchars($p['nom'] ?? '', ENT_QUOTES); ?>"
                                                                            data-numero_national="<?php echo htmlspecialchars($p['numero_national'] ?? '', ENT_QUOTES); ?>"
                                                                            data-date_naissance="<?php echo htmlspecialchars($p['date_naissance'] ?? '', ENT_QUOTES); ?>"
                                                                            data-genre="<?php echo htmlspecialchars($p['genre'] ?? '', ENT_QUOTES); ?>"
                                                                            data-adresse="<?php echo htmlspecialchars($p['adresse'] ?? '', ENT_QUOTES); ?>"
                                                                            data-profession="<?php echo htmlspecialchars($p['profession'] ?? '', ENT_QUOTES); ?>"
                                                                            data-gsm="<?php echo htmlspecialchars($p['gsm'] ?? '', ENT_QUOTES); ?>"
                                                                            data-email="<?php echo htmlspecialchars($p['email'] ?? '', ENT_QUOTES); ?>"
                                                                            data-lieu_naissance="<?php echo htmlspecialchars($p['lieu_naissance'] ?? '', ENT_QUOTES); ?>"
                                                                            data-nationalite="<?php echo htmlspecialchars($p['nationalite'] ?? '', ENT_QUOTES); ?>"
                                                                            data-etat_civil="<?php echo htmlspecialchars($p['etat_civil'] ?? '', ENT_QUOTES); ?>"
                                                                            data-personne_contact="<?php echo htmlspecialchars($p['personne_contact'] ?? '', ENT_QUOTES); ?>"
                                                                            data-observations="<?php echo htmlspecialchars($p['observations'] ?? '', ENT_QUOTES); ?>"
                                                                        >
                                                                            <td><?php echo $idxp++; ?></td>
                                                                            <td class="p-nom"><?php echo htmlspecialchars($p['nom'] ?? ''); ?></td>
                                                                            <td class="p-num"><?php echo htmlspecialchars($p['numero_national'] ?? ''); ?></td>
                                                                            <td class="p-dn"><?php echo htmlspecialchars($p['date_naissance'] ?? ''); ?></td>
                                                                            <td class="p-adr"><?php echo htmlspecialchars($p['adresse'] ?? ''); ?></td>
                                                                            <td class="p-prof"><?php echo htmlspecialchars($p['profession'] ?? ''); ?></td>
                                                                            <td class="p-gsm"><?php echo htmlspecialchars($p['gsm'] ?? ''); ?></td>
                                                                            <td class="p-email"><?php echo htmlspecialchars($p['email'] ?? ''); ?></td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary btn-part-details" data-bs-toggle="modal" data-bs-target="#particulierDetailsModal">Détails</button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal détails Particulier -->
                                                <div class="modal fade" id="particulierDetailsModal" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><i class="ri-contacts-book-2-line me-2"></i>Détails du particulier</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul class="nav nav-tabs" id="ptTabs" role="tablist">
                                                                    <li class="nav-item" role="presentation">
                                                                        <button class="nav-link active" id="pt-infos-tab" data-bs-toggle="tab" data-bs-target="#pt-infos" type="button" role="tab">Informations</button>
                                                                    </li>
                                                                    <li class="nav-item" role="presentation">
                                                                        <button class="nav-link" id="pt-cv-tab" data-bs-toggle="tab" data-bs-target="#pt-cv" type="button" role="tab">Contraventions</button>
                                                                    </li>
                                                                </ul>
                                                                <div class="tab-content pt-3">
                                                                    <div class="tab-pane fade show active" id="pt-infos" role="tabpanel" aria-labelledby="pt-infos-tab">
                                                                        <div class="mb-3 p-3 rounded-2 border bg-light">
                                                                            <div class="row g-3 align-items-center">
                                                                                <div class="col-md-6">
                                                                                    <div class="text-muted small">Nom</div>
                                                                                    <div class="fw-semibold fs-5" id="pt_nom"></div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="text-muted small">N° National</div>
                                                                                    <div class="fw-semibold" id="pt_numero_national"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-4">
                                                                                <div class="text-muted small">Date de naissance</div>
                                                                                <div class="fw-medium" id="pt_date_naissance"></div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="text-muted small">Genre</div>
                                                                                <div class="fw-medium" id="pt_genre"></div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="text-muted small">État civil</div>
                                                                                <div class="fw-medium" id="pt_etat_civil"></div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <hr class="my-2">
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="text-muted small">Adresse</div>
                                                                                <div class="fw-medium" id="pt_adresse"></div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="text-muted small">Profession</div>
                                                                                <div class="fw-medium" id="pt_profession"></div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="text-muted small">Personne de contact</div>
                                                                                <div class="fw-medium" id="pt_personne_contact"></div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="text-muted small">Téléphone</div>
                                                                                <div class="fw-medium" id="pt_gsm"></div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="text-muted small">Email</div>
                                                                                <div class="fw-medium" id="pt_email"></div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="text-muted small">Nationalité</div>
                                                                                <div class="fw-medium" id="pt_nationalite"></div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="text-muted small">Lieu de naissance</div>
                                                                                <div class="fw-medium" id="pt_lieu_naissance"></div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="text-muted small">Observations</div>
                                                                                <div class="fw-medium" id="pt_observations"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="pt-cv" role="tabpanel" aria-labelledby="pt-cv-tab">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm table-hover mb-0">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Référence</th>
                                                                                        <th>Date</th>
                                                                                        <th>Description</th>
                                                                                        <th>Montant</th>
                                                                                        <th>Payé</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="pt_cv_tbody"></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                (function(){
                                                    const tbl = document.getElementById('particuliers-table');
                                                    const tbody = tbl ? tbl.querySelector('tbody') : null;
                                                    const filterInput = document.getElementById('part-filter');
                                                    const sortSelect = document.getElementById('part-sort');
                                                    function norm(v){ return (v||'').toString().toLowerCase(); }
                                                    function rowKey(tr, field){
                                                        const get = (sel)=> norm(tr.querySelector(sel)?.textContent);
                                                        switch(field){
                                                            case 'nom': return get('.p-nom');
                                                            case 'num': return get('.p-num');
                                                            default: return get('.p-nom');
                                                        }
                                                    }
                                                    function applyFilter(){
                                                        if (!tbody) return;
                                                        const q = norm(filterInput?.value || '');
                                                        Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                            const text = [
                                                                tr.querySelector('.p-nom')?.textContent,
                                                                tr.querySelector('.p-num')?.textContent,
                                                                tr.querySelector('.p-email')?.textContent,
                                                                tr.querySelector('.p-adr')?.textContent
                                                            ].join(' ').toLowerCase();
                                                            tr.style.display = q && !text.includes(q) ? 'none' : '';
                                                        });
                                                    }
                                                    function renumber(){
                                                        if (!tbody) return; let i=1;
                                                        Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                            if (tr.style.display==='none') return;
                                                            const cell = tr.querySelector('td:first-child'); if (cell) cell.textContent = i++;
                                                        });
                                                    }
                                                    function applySort(){
                                                        if (!tbody) return; const mode = sortSelect?.value; if (!mode){ renumber(); return; }
                                                        const [field, dir] = mode.split('_');
                                                        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r=> r.style.display !== 'none');
                                                        rows.sort((a,b)=>{
                                                            const va = rowKey(a, field), vb = rowKey(b, field);
                                                            if (va < vb) return dir==='asc' ? -1 : 1;
                                                            if (va > vb) return dir==='asc' ? 1 : -1; return 0;
                                                        });
                                                        rows.forEach(r=> tbody.appendChild(r)); renumber();
                                                    }
                                                    // Détails modal
                                                    document.addEventListener('click', (e)=>{
                                                        const btn = e.target.closest('.btn-part-details'); if (!btn) return;
                                                        const tr = btn.closest('tr'); if (!tr) return;
                                                        const get = (k)=> tr.getAttribute('data-'+k) || '';
                                                        const pid = tr.getAttribute('data-id') || '';
                                                        const pnumero = get('numero_national');
                                                        document.getElementById('pt_nom').textContent = get('nom');
                                                        document.getElementById('pt_numero_national').textContent = get('numero_national');
                                                        document.getElementById('pt_date_naissance').textContent = window.formatDMY(get('date_naissance'));
                                                        document.getElementById('pt_genre').textContent = get('genre');
                                                        document.getElementById('pt_etat_civil').textContent = get('etat_civil');
                                                        document.getElementById('pt_adresse').textContent = get('adresse');
                                                        document.getElementById('pt_profession').textContent = get('profession');
                                                        document.getElementById('pt_personne_contact').textContent = get('personne_contact');
                                                        document.getElementById('pt_gsm').textContent = get('gsm');
                                                        document.getElementById('pt_email').textContent = get('email');
                                                        document.getElementById('pt_nationalite').textContent = get('nationalite');
                                                        document.getElementById('pt_lieu_naissance').textContent = get('lieu_naissance');
                                                        document.getElementById('pt_observations').textContent = get('observations');
                                                        // Charger contraventions
                                                        const tbodyCv = document.getElementById('pt_cv_tbody');
                                                        if (tbodyCv) { tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Chargement...</td></tr>'; }
                                                        if (pid) {
                                                            const url = `/particulier/${pid}/contraventions` + (pnumero ? `?numero=${encodeURIComponent(pnumero)}` : '');
                                                            fetch(url).then(r=>r.json()).then(json=>{
                                                                if (!tbodyCv) return;
                                                                if (!json.ok) { tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur de chargement</td></tr>'; return; }
                                                                const rows = json.data || [];
                                                                if (rows.length === 0) { tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune contravention</td></tr>'; return; }
                                                                tbodyCv.innerHTML = '';
                                                                let i=1;
                                                                rows.forEach(cv=>{
                                                                    const trcv = document.createElement('tr');
            
                                                                    const isPaid = (cv.payed===1||cv.payed==='1'||cv.payed===true ||cv.payed==="Payé");
                                                                    const checked = isPaid ? 'checked' : '';
                                                                    const label = isPaid ? 'Payé' : 'Non payé';
                                                                    trcv.innerHTML = `
                                                                        <td>${i++}</td>
                                                                        <td>${cv.reference_loi ?? ''}</td>
                                                                        <td>${window.formatDMY(cv.date_infraction ?? cv.created_at ?? '')}</td>
                                                                        <td>${cv.description ?? ''}</td>
                                                                        <td>${window.formatMoneyCDF(cv.amende)}</td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <div class="form-check form-switch m-0">
                                                                                    <input class="form-check-input pt-cv-payed" type="checkbox" role="switch" data-cv-id="${cv.id}" ${checked}>
                                                                                </div>
                                                                                <span class="badge bg-light text-dark pt-cv-payed-label">${label}</span>
                                                                            </div>
                                                                        </td>
                                                                    `;
                                                                    tbodyCv.appendChild(trcv);
                                                                });
                                                            }).catch(()=>{
                                                                if (tbodyCv) tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur réseau</td></tr>';
                                                            });
                                                        } else {
                                                            if (tbodyCv) tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune contravention</td></tr>';
                                                        }
                                                    });
                                                    filterInput?.addEventListener('input', ()=>{ applyFilter(); applySort(); });
                                                    sortSelect?.addEventListener('change', ()=> applySort());
                                                    // Initial
                                                    applyFilter();
                                                })();
                                                </script>
                                            </div>
                                            <!-- end settings content-->
    

                                            <div class="tab-pane" id="entreprises">

                                                <div class="card mt-2">
                                                    <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <h5 class="card-title mb-0"><i class="ri-building-2-line me-2"></i>Entreprises</h5>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="text" id="ent-filter" class="form-control form-control-sm" placeholder="Filtrer (désignation, RCCM, email...)" style="min-width:240px;">
                                                            <select id="ent-sort" class="form-select form-select-sm" style="min-width:220px;">
                                                                <option value="">Trier...</option>
                                                                <option value="designation_asc">Désignation A→Z</option>
                                                                <option value="designation_desc">Désignation Z→A</option>
                                                                <option value="rccm_asc">RCCM A→Z</option>
                                                                <option value="rccm_desc">RCCM Z→A</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0" id="entreprises-table">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width:60px;">#</th>
                                                                        <th>Désignation</th>
                                                                        <th>RCCM</th>
                                                                        <th>Siège social</th>
                                                                        <th>Téléphone</th>
                                                                        <th>Email</th>
                                                                        <th style="width:90px;">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $idxe=1; if(!empty($entreprises)) { foreach($entreprises as $e): ?>
                                                                        <tr 
                                                                            data-id="<?php echo htmlspecialchars($e['id'] ?? '', ENT_QUOTES); ?>"
                                                                            data-designation="<?php echo htmlspecialchars($e['designation'] ?? '', ENT_QUOTES); ?>"
                                                                            data-rccm="<?php echo htmlspecialchars($e['rccm'] ?? '', ENT_QUOTES); ?>"
                                                                            data-siege_social="<?php echo htmlspecialchars($e['siege_social'] ?? '', ENT_QUOTES); ?>"
                                                                            data-gsm="<?php echo htmlspecialchars($e['gsm'] ?? '', ENT_QUOTES); ?>"
                                                                            data-email="<?php echo htmlspecialchars($e['email'] ?? '', ENT_QUOTES); ?>"
                                                                            data-personne_contact="<?php echo htmlspecialchars($e['personne_contact'] ?? '', ENT_QUOTES); ?>"
                                                                            data-plaque_vehicule="<?php echo htmlspecialchars($e['plaque_vehicule'] ?? '', ENT_QUOTES); ?>"
                                                                            data-marque_vehicule="<?php echo htmlspecialchars($e['marque_vehicule'] ?? '', ENT_QUOTES); ?>"
                                                                            data-secteur="<?php echo htmlspecialchars($e['secteur'] ?? '', ENT_QUOTES); ?>"
                                                                            data-observations="<?php echo htmlspecialchars($e['observations'] ?? '', ENT_QUOTES); ?>"
                                                                        >
                                                                            <td><?php echo $idxe++; ?></td>
                                                                            <td class="e-desig"><?php echo htmlspecialchars($e['designation'] ?? ''); ?></td>
                                                                            <td class="e-rccm"><?php echo htmlspecialchars($e['rccm'] ?? ''); ?></td>
                                                                            <td class="e-siege"><?php echo htmlspecialchars($e['siege_social'] ?? ''); ?></td>
                                                                            <td class="e-gsm"><?php echo htmlspecialchars($e['gsm'] ?? ''); ?></td>
                                                                            <td class="e-email"><?php echo htmlspecialchars($e['email'] ?? ''); ?></td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary btn-ent-details" data-bs-toggle="modal" data-bs-target="#entrepriseDetailsModal">Détails</button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal détails Entreprise -->
                                                <div class="modal fade" id="entrepriseDetailsModal" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><i class="ri-building-2-line me-2"></i>Détails de l'entreprise</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul class="nav nav-tabs" role="tablist">
                                                                    <li class="nav-item" role="presentation">
                                                                        <a class="nav-link active" data-bs-toggle="tab" href="#ent-infos" role="tab">Informations</a>
                                                                    </li>
                                                                    <li class="nav-item" role="presentation">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#ent-contravs" role="tab">Contraventions</a>
                                                                    </li>
                                                                </ul>
                                                                <div class="tab-content pt-3">
                                                                    <div class="tab-pane fade show active" id="ent-infos" role="tabpanel">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6"><div class="text-muted small">Désignation</div><div class="fw-semibold" id="ent_designation"></div></div>
                                                                            <div class="col-md-6"><div class="text-muted small">RCCM</div><div class="fw-semibold" id="ent_rccm"></div></div>
                                                                            <div class="col-12"><div class="text-muted small">Siège social</div><div class="fw-medium" id="ent_siege"></div></div>
                                                                            <div class="col-md-4"><div class="text-muted small">Téléphone</div><div class="fw-medium" id="ent_gsm"></div></div>
                                                                            <div class="col-md-4"><div class="text-muted small">Email</div><div class="fw-medium" id="ent_email"></div></div>
                                                                            <div class="col-md-4"><div class="text-muted small">Personne de contact</div><div class="fw-medium" id="ent_contact"></div></div>
                                                                            <div class="col-md-6"><div class="text-muted small">Véhicule</div><div class="fw-medium" id="ent_marque"></div></div>
                                                                            <div class="col-md-6"><div class="text-muted small">Plaque</div><div class="fw-medium" id="ent_plaque"></div></div>
                                                                            <div class="col-12"><div class="text-muted small">Secteur</div><div class="fw-medium" id="ent_secteur"></div></div>
                                                                            <div class="col-12"><div class="text-muted small">Observations</div><div class="fw-medium" id="ent_obs"></div></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="ent-contravs" role="tabpanel">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm table-striped align-middle" id="ent_table_contravs">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Date</th>
                                                                                        <th>Lieu</th>
                                                                                        <th>Type</th>
                                                                                        <th>Référence</th>
                                                                                        <th>Amende</th>
                                                                                        <th>Description</th>
                                                                                        <th>Payé</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script>
                                                (function(){
                                                    const CONTRAVS_ENT = <?php echo json_encode($contraventionsByEntreprise ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;
                                                    const tbl = document.getElementById('entreprises-table');
                                                    const tbody = tbl ? tbl.querySelector('tbody') : null;
                                                    const filterInput = document.getElementById('ent-filter');
                                                    const sortSelect = document.getElementById('ent-sort');
                                                    function norm(v){ return (v||'').toString().toLowerCase(); }
                                                    function rowKey(tr, field){
                                                        const get = (sel)=> norm(tr.querySelector(sel)?.textContent);
                                                        switch(field){
                                                            case 'designation': return get('.e-desig');
                                                            case 'rccm': return get('.e-rccm');
                                                            default: return get('.e-desig');
                                                        }
                                                    }
                                                    function applyFilter(){
                                                        if (!tbody) return; const q = norm(filterInput?.value || '');
                                                        Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                            const text = [
                                                                tr.querySelector('.e-desig')?.textContent,
                                                                tr.querySelector('.e-rccm')?.textContent,
                                                                tr.querySelector('.e-email')?.textContent,
                                                                tr.querySelector('.e-siege')?.textContent
                                                            ].join(' ').toLowerCase();
                                                            tr.style.display = q && !text.includes(q) ? 'none' : '';
                                                        });
                                                    }
                                                    function renumber(){ if (!tbody) return; let i=1; Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{ if (tr.style.display==='none') return; const c=tr.querySelector('td:first-child'); if(c) c.textContent=i++; }); }
                                                    function applySort(){
                                                        if (!tbody) return; const mode = sortSelect?.value; if (!mode){ renumber(); return; }
                                                        const [field, dir] = mode.split('_');
                                                        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r=> r.style.display !== 'none');
                                                        rows.sort((a,b)=>{ const va=rowKey(a,field), vb=rowKey(b,field); if(va<vb) return dir==='asc'?-1:1; if(va>vb) return dir==='asc'?1:-1; return 0; });
                                                        rows.forEach(r=> tbody.appendChild(r)); renumber();
                                                    }
                                                    // Détails modal + contraventions
                                                    document.addEventListener('click', (e)=>{
                                                        const btn = e.target.closest('.btn-ent-details'); if (!btn) return;
                                                        const tr = btn.closest('tr'); if (!tr) return;
                                                        const get = (k)=> tr.getAttribute('data-'+k) || '';
                                                        const eid = tr.getAttribute('data-id') || '';
                                                        document.getElementById('ent_designation').textContent = get('designation');
                                                        document.getElementById('ent_rccm').textContent = get('rccm');
                                                        document.getElementById('ent_siege').textContent = get('siege_social');
                                                        document.getElementById('ent_gsm').textContent = get('gsm');
                                                        document.getElementById('ent_email').textContent = get('email');
                                                        document.getElementById('ent_contact').textContent = get('personne_contact');
                                                        document.getElementById('ent_marque').textContent = get('marque_vehicule');
                                                        document.getElementById('ent_plaque').textContent = get('plaque_vehicule');
                                                        document.getElementById('ent_secteur').textContent = get('secteur');
                                                        document.getElementById('ent_obs').textContent = get('observations');
                                                        const tcv = document.querySelector('#ent_table_contravs tbody');
                                                        if (tcv){ tcv.innerHTML = ''; const list = CONTRAVS_ENT[eid] || []; if(list.length===0){ tcv.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Aucune contravention</td></tr>'; return; }
                                                            list.forEach((cv, idx)=>{
                                                                const isPaid = (cv.payed===1||cv.payed==='1'||cv.payed===true);
                                                                const checked = isPaid ? 'checked' : '';
                                                                const label = isPaid ? 'Payé' : 'Non payé';
                                                                const trcv = document.createElement('tr');
                                                                trcv.innerHTML = `
                                                                    <td>${idx+1}</td>
                                                                    <td>${window.formatDMY(cv.date_infraction || '')}</td>
                                                                    <td>${cv.lieu || ''}</td>
                                                                    <td>${cv.type_infraction || ''}</td>
                                                                    <td>${cv.reference_loi || ''}</td>
                                                                    <td>${cv.amende || ''}</td>
                                                                    <td>${cv.description || ''}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <div class="form-check form-switch m-0">
                                                                                <input class="form-check-input ent-cv-payed" type="checkbox" role="switch" data-cv-id="${cv.id}" ${checked}>
                                                                            </div>
                                                                            <span class="badge bg-light text-dark ent-cv-payed-label">${label}</span>
                                                                        </div>
                                                                    </td>`;
                                                                tcv.appendChild(trcv);
                                                            });
                                                        }
                                                    });
                                                    // Switch payé handler (réutilise l'API existante)
                                                    document.addEventListener('change', async (e)=>{
                                                        const input = e.target.closest('.ent-cv-payed'); if (!input) return;
                                                        const id = input.getAttribute('data-cv-id'); const prevChecked = !input.checked; const payed = input.checked ? '1' : '0';
                                                        const labelEl = input.closest('td')?.querySelector('.ent-cv-payed-label'); input.disabled = true;
                                                        try {
                                                            const resp = await fetch('/contravention/update-payed', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'}, body:new URLSearchParams({ id, payed }).toString() });
                                                            const data = await resp.json(); if (!resp.ok || !data.ok) throw new Error(data.error || 'Erreur lors de la mise à jour');
                                                            if (labelEl) labelEl.textContent = input.checked ? 'Payé' : 'Non payé';
                                                        } catch (err) {
                                                            input.checked = prevChecked; if (labelEl) labelEl.textContent = input.checked ? 'Payé' : 'Non payé'; alert(err.message || 'Erreur réseau');
                                                        } finally { input.disabled = false; }
                                                    });
                                                    filterInput?.addEventListener('input', ()=>{ applyFilter(); applySort(); });
                                                    sortSelect?.addEventListener('change', ()=> applySort());
                                                    // Initial
                                                    applyFilter();
                                                })();
                                                </script>

                                                <!-- comment box -->
                                                <div class="border rounded mt-2 mb-3">
                                                    <form action="#" class="comment-area-box">
                                                        <textarea rows="3" class="form-control border-0 resize-none" placeholder="Write something...."></textarea>
                                                        <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-contacts-book-2-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-map-pin-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-camera-3-line"></i></a>
                                                                <a href="#" class="btn btn-sm px-2 fs-16 btn-light"><i class="ri-emoji-sticker-line"></i></a>
                                                            </div>
                                                            <button type="submit" class="btn btn-sm btn-dark">Post</button>
                                                        </div>
                                                    </form>
                                                </div> <!-- end .border-->
                                                <!-- end comment box -->
    
                                                <!-- Story Box-->
                                                <div class="border border-light rounded p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-4.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Thelma Fridley</h5>
                                                            <p class="text-muted"><small>about 1 hour ago</small></p>
                                                        </div>
                                                    </div>
                                                    <div class="fs-16 text-center fst-italic text-dark">
                                                        <i class="ri-double-quotes-l fs-20"></i> Cras sit amet nibh libero, in
                                                        gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
                                                        purus odio, vestibulum in vulputate at, tempus viverra turpis. Duis
                                                        sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper
                                                        porta. Mauris massa.
                                                    </div>
    
                                                    <div class="mx-n2 p-2 mt-3 bg-light">
                                                        <div class="d-flex">
                                                            <img class="me-2 rounded-circle" src="assets/images/users/avatar-3.jpg"
                                                                alt="Generic placeholder image" height="32">
                                                            <div>
                                                                <h5 class="mt-0">Jeremy Tomlinson <small class="text-muted">about 2 minuts ago</small></h5>
                                                                Nice work, makes me think of The Money Pit.
    
                                                                <br/>
                                                                <a href="javascript: void(0);" class="text-muted fs-13 d-inline-block mt-2"><i
                                                                    class="ri-reply-line"></i> Reply</a>
    
                                                                <div class="d-flex mt-3">
                                                                    <a class="pe-2" href="#">
                                                                        <img src="assets/images/users/avatar-4.jpg" class="rounded-circle"
                                                                            alt="Generic placeholder image" height="32">
                                                                    </a>
                                                                    <div>
                                                                        <h5 class="mt-0">Thelma Fridley <small class="text-muted">5 hours ago</small></h5>
                                                                        i'm in the middle of a timelapse animation myself! (Very different though.) Awesome stuff.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="d-flex mt-2">
                                                            <a class="pe-2" href="#">
                                                                <img src="assets/images/users/avatar-1.jpg" class="rounded-circle"
                                                                    alt="Generic placeholder image" height="32">
                                                            </a>
                                                            <div class="w-100">
                                                                <input type="text" id="simpleinput" class="form-control border-0 form-control-sm" placeholder="Add comment">
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="mt-2">
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-danger"><i
                                                                class="ri-heart-line"></i> Like (28)</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-share-line"></i> Share</a>
                                                    </div>
                                                </div>
    
                                                <!-- Story Box-->
                                                <div class="border border-light rounded p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-3.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Jeremy Tomlinson</h5>
                                                            <p class="text-muted"><small>3 hours ago</small></p>
                                                        </div>
                                                    </div>
                                                    <p>Story based around the idea of time lapse, animation to post soon!</p>
    
                                                    <img src="assets/images/small/small-1.jpg" alt="post-img" class="rounded me-1"
                                                        height="60" />
                                                    <img src="assets/images/small/small-2.jpg" alt="post-img" class="rounded me-1"
                                                        height="60" />
                                                    <img src="assets/images/small/small-3.jpg" alt="post-img" class="rounded"
                                                        height="60" />
    
                                                    <div class="mt-2">
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-reply-line"></i> Reply</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-heart-line"></i> Like</a>
                                                        <a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
                                                                class="ri-share-line"></i> Share</a>
                                                    </div>
                                                </div>
                                                
                                                <!-- Story Box-->
                                                <div class="border border-light p-2 mb-3">
                                                    <div class="d-flex">
                                                        <img class="me-2 rounded-circle" src="assets/images/users/avatar-6.jpg"
                                                            alt="Generic placeholder image" height="32">
                                                        <div>
                                                            <h5 class="m-0">Martin Williamson</h5>
                                                            <p class="text-muted"><small>15 hours ago</small></p>
                                                        </div>
                                                    </div>
                                                    <p>The parallax is a little odd but O.o that house build is awesome!!</p>
    
                                                    <iframe src='https://player.vimeo.com/video/87993762' height='300' class="img-fluid border-0"></iframe>
                                                </div>
    
                                                <div class="text-center">
                                                    <a href="javascript:void(0);" class="text-danger"><i class="ri-loader-fill me-1"></i> Load more </a>
                                                </div>
    
                                            </div>
                                            <!-- end timeline content-->
    
                                        </div> <!-- end tab-content -->
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div>
                    <!-- container -->

                </div>
                <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> © Jidox - Coderthemes.com
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end footer-links d-none d-md-block">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
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
        
    </body>

<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:58 GMT -->
</html>