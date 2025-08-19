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
            <?php $canEdit = isset($_SESSION['user']); ?>
            <script>
                window.CAN_EDIT = <?php echo $canEdit ? 'true' : 'false'; ?>;
            </script>
            
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
<?php
    $__pexp = trim((string)($c->permis_expire_le ?? ''));
    $__badge = '';
    if ($__pexp !== '') {
        $ts = strtotime($__pexp);
        if ($ts !== false) {
            $__badge = ($ts < strtotime(date('Y-m-d'))) ? ' <span class="badge bg-danger ms-1">Permis expiré</span>' : '';
        }
    }
?>
                                                                        <td><span class="date-text"><?= htmlspecialchars($__pexp) ?></span><?= $__badge ?></td>
                                                                        <td class="text-center">
                                                                            <div class="btn-group btn-group-sm" role="group">
                                                                                <button type="button" class="btn btn-outline-primary btn-open-conducteur" data-bs-toggle="modal" data-bs-target="#modalConducteurDetails">Détails</button>
                                                                                <?php if ($canEdit): ?>
                                                                                <button type="button" class="btn btn-outline-success btn-assign-contrav" data-dossier-type="conducteur_vehicule" data-dossier-id="<?= htmlspecialchars($c->id ?? '', ENT_QUOTES) ?>" data-target-label="Conducteur: <?= htmlspecialchars($c->nom ?? '') ?>">Assigner</button>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
<?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

        <!-- Modal: Associer un véhicule -->
        <div class="modal fade" id="associerVehiculeModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Associer un véhicule au particulier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
              </div>
              <div class="modal-body">
                <form id="associerVehiculeForm">
                  <input type="hidden" name="particulier_id" id="av_particulier_id">
                  <div class="mb-3">
                    <label class="form-label">Plaque d'immatriculation</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="av_search_plate" placeholder="Ex: ABC1234" autocomplete="off">
                      <button class="btn btn-outline-secondary" type="button" id="av_btn_search"><i class="ri-search-line"></i></button>
                    </div>
                    <div class="form-text">Saisissez la plaque puis cliquez sur rechercher.</div>
                  </div>
                  <div id="av_vehicle_result" class="border rounded p-2 d-none">
                    <div class="small text-muted">Véhicule trouvé</div>
                    <div class="fw-semibold" id="av_vehicle_title"></div>
                    <div class="text-muted" id="av_vehicle_sub"></div>
                    <input type="hidden" name="vehicule_plaque_id" id="av_vehicule_plaque_id">
                  </div>
                  <div id="av_no_result" class="alert alert-warning d-none mt-2">Aucun véhicule trouvé pour cette plaque.</div>
                  <div class="mb-3 mt-3">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea name="notes" id="av_notes" class="form-control" rows="2" placeholder="Notes sur l'association"></textarea>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="av_btn_submit">Associer</button>
              </div>
            </div>
          </div>
        </div>

        <script>
        (function(){
          // Helper: show a quick success message (Bootstrap-like alert)
          window.showSuccess = function(msg){
            try {
              let c = document.getElementById('global-toast-container');
              if (!c) {
                c = document.createElement('div');
                c.id = 'global-toast-container';
                c.style.position = 'fixed';
                c.style.top = '1rem';
                c.style.right = '1rem';
                c.style.zIndex = '1080';
                c.style.pointerEvents = 'none';
                document.body.appendChild(c);
              }
              const el = document.createElement('div');
              el.className = 'alert alert-success shadow mb-2';
              el.textContent = msg || 'Succès';
              el.style.pointerEvents = 'auto';
              c.appendChild(el);
              setTimeout(()=>{
                try { el.remove(); } catch{}
                if (c && !c.childElementCount) { try { c.remove(); } catch{} }
              }, 2500);
            } catch {}
          }
          const actionsModal = document.getElementById('particulierActionsModal');
          const assocModal = document.getElementById('associerVehiculeModal');
          const inputPid = document.getElementById('av_particulier_id');
          const inputPlate = document.getElementById('av_search_plate');
          const btnSearch = document.getElementById('av_btn_search');
          const resBox = document.getElementById('av_vehicle_result');
          const noRes = document.getElementById('av_no_result');
          const resTitle = document.getElementById('av_vehicle_title');
          const resSub = document.getElementById('av_vehicle_sub');
          const hiddenVid = document.getElementById('av_vehicule_plaque_id');
          const btnSubmit = document.getElementById('av_btn_submit');

          function ensurePid(){
            const ctx = window.__lastParticulierCtx || (function(){
              try { return JSON.parse(localStorage.getItem('recent_particulier_ctx')||'null'); } catch { return null; }
            })();
            if (ctx && ctx.id) { inputPid.value = ctx.id; }
          }
          function resetResult(){
            hiddenVid.value = '';
            resTitle.textContent = '';
            resSub.textContent = '';
            resBox.classList.add('d-none');
            noRes.classList.add('d-none');
          }
          function openAssocModal(){
            ensurePid();
            resetResult();
            inputPlate.value = '';
            document.getElementById('av_notes').value = '';
            try {
              if (window.bootstrap && bootstrap.Modal) {
                if (actionsModal) {
                  try { bootstrap.Modal.getOrCreateInstance(actionsModal).hide(); } catch {}
                }
                // Ensure modal is a direct child of body to avoid clipping
                if (assocModal && assocModal.parentNode !== document.body) {
                  document.body.appendChild(assocModal);
                }
                bootstrap.Modal.getOrCreateInstance(assocModal).show();
              }
            } catch {}
            // Fallback if Bootstrap JS absent
            if (!window.bootstrap || !bootstrap.Modal) {
              // Hide actions modal (fallback)
              if (actionsModal) {
                actionsModal.classList.remove('show');
                actionsModal.style.display = 'none';
                actionsModal.setAttribute('aria-hidden','true');
              }
              // Remove any existing backdrops
              document.querySelectorAll('.modal-backdrop').forEach(el=> el.parentNode && el.parentNode.removeChild(el));
              // Create backdrop
              const bd = document.createElement('div');
              bd.className = 'modal-backdrop fade show';
              document.body.appendChild(bd);
              // Show assoc modal
              if (assocModal && assocModal.parentNode !== document.body) {
                document.body.appendChild(assocModal);
              }
              assocModal.classList.add('show');
              assocModal.style.display = 'block';
              assocModal.removeAttribute('aria-hidden');
              document.body.classList.add('modal-open');
            }
          }
          // Event delegation: handle clicks on the dynamic button
          document.addEventListener('click', function(e){
            const btn = e.target && (e.target.id === 'pa_action_associer_btn' ? e.target : e.target.closest && e.target.closest('#pa_action_associer_btn'));
            if (!btn) return;
            // Prevent default only if we will handle show ourselves
            if (!window.bootstrap || !bootstrap.Modal) e.preventDefault();
            openAssocModal();
          });
          function renderVehicle(v){
            const plaque = v.plaque || '';
            const title = plaque ? ('Plaque: ' + plaque) : 'Véhicule';
            const sub = [v.marque, v.modele, v.couleur, v.annee].filter(Boolean).join(' · ');
            resTitle.textContent = title;
            resSub.textContent = sub;
          }
          function doSearch(){
            resetResult();
            const q = (inputPlate.value || '').trim();
            if (!q) return;
            fetch(`/api/vehicules/search?plate=${encodeURIComponent(q)}`)
              .then(r=>r.json())
              .then(j=>{
                if (!j.ok){ noRes.classList.remove('d-none'); return; }
                const rows = j.data || [];
                if (!rows.length){ noRes.classList.remove('d-none'); return; }
                const v = rows[0];
                hiddenVid.value = String(v.id || '');
                renderVehicle(v);
                resBox.classList.remove('d-none');
              })
              .catch(()=>{ noRes.classList.remove('d-none'); });
          }
          if (btnSearch){ btnSearch.addEventListener('click', doSearch); }
          if (inputPlate){ inputPlate.addEventListener('keydown', function(e){ if (e.key==='Enter'){ e.preventDefault(); doSearch(); }}); }

          function submitAssoc(){
            ensurePid();
            const pid = inputPid.value.trim();
            const vid = hiddenVid.value.trim();
            if (!pid){ alert("Contexte Particulier manquant. Veuillez réouvrir depuis la liste."); return; }
            if (!vid){ alert("Veuillez rechercher et sélectionner un véhicule valide."); return; }
            const fd = new FormData();
            fd.append('particulier_id', pid);
            fd.append('vehicule_plaque_id', vid);
            const notes = document.getElementById('av_notes').value || '';
            if (notes) fd.append('notes', notes);
            btnSubmit.disabled = true;
            fetch('/particulier/associer-vehicule', { method:'POST', body: fd })
              .then(r=>r.json())
              .then(j=>{
                if (j && j.ok){
                  alert(j.dup ? 'Cette association existe déjà.' : 'Véhicule associé avec succès.');
                  try { if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(assocModal).hide(); } catch {}
                } else {
                  alert('Erreur: ' + (j && j.error ? j.error : 'Echec'));
                }
              })
              .catch(()=> alert('Erreur réseau'))
              .finally(()=>{ btnSubmit.disabled = false; });
          }
          if (btnSubmit){ btnSubmit.addEventListener('click', submitAssoc); }
        })();
        </script>
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
                                                              <div class="col-md-6"><strong>Permis expire le:</strong> <span id="dc_permis_expire_le"></span><span class="badge bg-danger ms-2 d-none" id="dc_permis_badge">Permis expiré</span></div>
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
                                                                    if (td) {
                                                                        const span = td.querySelector('.date-text');
                                                                        if (span) span.textContent = window.formatDMY(span.textContent);
                                                                        else td.textContent = window.formatDMY(td.textContent);
                                                                    }
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
                                                        // Helpers fallback si Bootstrap.Modal n'est pas dispo
                                                        function closeModalFallback(el){
                                                            if (!el) return;
                                                            el.classList.remove('show');
                                                            el.style.display = 'none';
                                                            // retirer backdrops existants
                                                            document.querySelectorAll('.modal-backdrop').forEach(b=>b.parentNode&&b.parentNode.removeChild(b));
                                                            document.body.classList.remove('modal-open');
                                                            document.body.style.removeProperty('padding-right');
                                                        }
                                                        function openModalFallback(el){
                                                            if (!el) return;
                                                            // créer un backdrop
                                                            const bd = document.createElement('div');
                                                            bd.className = 'modal-backdrop fade show';
                                                            document.body.appendChild(bd);
                                                            el.style.display = 'block';
                                                            el.classList.add('show');
                                                            document.body.classList.add('modal-open');
                                                        }
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
                                                            (function(){
                                                                const raw = get('permis_expire_le');
                                                                const badge = document.getElementById('dc_permis_badge');
                                                                if (!badge) return;
                                                                if (raw) {
                                                                    const d = new Date(raw);
                                                                    if (!isNaN(d.getTime())) {
                                                                        const today = new Date(); today.setHours(0,0,0,0);
                                                                        const d0 = new Date(d.getFullYear(), d.getMonth(), d.getDate());
                                                                        if (d0 < today) badge.classList.remove('d-none'); else badge.classList.add('d-none');
                                                                    } else { badge.classList.add('d-none'); }
                                                                } else { badge.classList.add('d-none'); }
                                                            })();
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
                                                                                <input class="form-check-input cv-payed-switch" type="checkbox" role="switch" data-cv-id="${cv.id}" ${checked} ${window.CAN_EDIT ? '' : 'disabled'}>
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
                                                            if (!window.CAN_EDIT) { input.checked = !input.checked; alert('Action réservée au superadmin'); return; }
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
                                                                        <th style="width:140px;">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $idxv=1; if(!empty($vehicules)) { foreach($vehicules as $v): ?>
                                                                        <?php
                                                                            // Déterminer expiration assurance côté serveur pour styliser la ligne
                                                                            $__expAssu = (string)($v['date_expire_assurance'] ?? '');
                                                                            $__isExpiredRow = false;
                                                                            if ($__expAssu !== '') {
                                                                                $ts = strtotime($__expAssu);
                                                                                if ($ts !== false) {
                                                                                    $__isExpiredRow = ($ts < strtotime(date('Y-m-d')));
                                                                                }
                                                                            }
                                                                        ?>
                                                                        <tr data-veh-id="<?php echo htmlspecialchars($v['id']); ?>" data-assu-exp="<?php echo htmlspecialchars($v['date_expire_assurance'] ?? ''); ?>" data-plaque-exp="<?php echo htmlspecialchars($v['plaque_expire_le'] ?? ''); ?>" data-veh-images="<?php echo htmlspecialchars($v['images'] ?? '[]', ENT_QUOTES, 'UTF-8'); ?>" data-frontiere-entree="<?php echo htmlspecialchars($v['frontiere_entree'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-date-importation="<?php echo htmlspecialchars($v['date_importation'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                                            <td><?php echo $idxv++; ?></td>
                                                                            <td class="veh2-marque"><?php echo htmlspecialchars($v['marque'] ?? ''); ?></td>
                                                                            <td class="veh2-annee"><?php echo htmlspecialchars($v['annee'] ?? ''); ?></td>
                                                                            <td class="veh2-couleur"><?php echo htmlspecialchars($v['couleur'] ?? ''); ?></td>
                                                                            <td class="veh2-plaque"><?php echo htmlspecialchars($v['plaque'] ?? ''); ?></td>
                                                                            <td class="veh2-valide"><?php
                                                                                $valPlaque = trim((string)($v['plaque_valide_le'] ?? ''));
                                                                                echo $valPlaque !== '' ? htmlspecialchars($valPlaque) : 'N/A';
                                                                            ?></td>
                                                                            <td class="veh2-expire">
                                                                                <?php
                                                                                    $expPlaque = trim((string)($v['plaque_expire_le'] ?? ''));
                                                                                    echo $expPlaque !== '' ? htmlspecialchars($expPlaque) : 'N/A';
                                                                                    $isExpiredPlaque = false;
                                                                                    if ($expPlaque !== '') {
                                                                                        $tsExpP = strtotime($expPlaque);
                                                                                        if ($tsExpP !== false) {
                                                                                            $today = strtotime(date('Y-m-d'));
                                                                                            $isExpiredPlaque = ($tsExpP < $today);
                                                                                        }
                                                                                    }
                                                                                    if ($isExpiredPlaque) {
                                                                                        echo ' <span class="badge bg-danger ms-2">Plaque expirée</span>';
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                            <td class="veh2-assu">
                                                                                <?php
                                                                                    $assuText = trim((string)($v['nume_assurance'] ?? '') . ' ' . (string)($v['societe_assurance'] ?? ''));
                                                                                    $expAssu = (string)($v['date_expire_assurance'] ?? '');
                                                                                    $isExpiredAssu = false;
                                                                                    if ($expAssu !== '') {
                                                                                        $tsExp = strtotime($expAssu);
                                                                                        if ($tsExp !== false) {
                                                                                            // Comparer à aujourd'hui en ignorant l'heure
                                                                                            $today = strtotime(date('Y-m-d'));
                                                                                            $isExpiredAssu = ($tsExp < $today);
                                                                                        }
                                                                                    }
                                                                                    echo $assuText !== '' ? htmlspecialchars($assuText) : 'N/A';
                                                                                    if ($isExpiredAssu) {
                                                                                        echo ' <span class="badge bg-danger ms-2">Assurance expirée</span>';
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <div class="btn-group btn-group-sm" role="group">
                                                                                    <button type="button" class="btn btn-outline-primary btn-veh2-details" data-bs-toggle="modal" data-bs-target="#vehiculeDetailsModal2">Détails</button>
                                                                                    <?php if ($canEdit): ?>
                                                                                    <button type="button" class="btn btn-outline-success btn-assign-contrav" data-dossier-type="vehicule_plaque" data-dossier-id="<?php echo htmlspecialchars($v['id']); ?>" data-target-label="Véhicule: <?php echo htmlspecialchars(($v['marque'] ?? '').' '.($v['plaque'] ?? '')); ?>">Assigner</button>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                            <div class="col-md-6"><strong>Frontière d'entrée:</strong> <span id="md2-frontiere"></span></div>
                                                                            <div class="col-md-6"><strong>Date d'importation:</strong> <span id="md2-date-import"></span></div>
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
                                                        // Champs importation
                                                        document.getElementById('md2-frontiere').textContent = tr.getAttribute('data-frontiere-entree') || '';
                                                        document.getElementById('md2-date-import').textContent = window.formatDMY(tr.getAttribute('data-date-importation') || '');
                                                        // Badge plaque expirée si date dépassée
                                                        (function(){
                                                            const target = document.getElementById('md2-expire');
                                                            const expPlaque = tr.getAttribute('data-plaque-exp') || '';
                                                            if (expPlaque && target) {
                                                                const d = new Date(expPlaque);
                                                                if (!isNaN(d.getTime())) {
                                                                    const today = new Date(); today.setHours(0,0,0,0);
                                                                    const d0 = new Date(d.getFullYear(), d.getMonth(), d.getDate());
                                                                    if (d0 < today) {
                                                                        const badge = document.createElement('span');
                                                                        badge.className = 'badge bg-danger ms-2';
                                                                        badge.textContent = 'Plaque expirée';
                                                                        target.appendChild(badge);
                                                                    }
                                                                }
                                                            }
                                                        })();
                                                        const assuEl = document.getElementById('md2-assu');
                                                        const assuText = tr.querySelector('.veh2-assu')?.textContent || '';
                                                        assuEl.textContent = assuText;
                                                        // Badge assurance expirée si date dépassée
                                                        const expAssu = tr.getAttribute('data-assu-exp') || '';
                                                        if (expAssu) {
                                                            const d = new Date(expAssu);
                                                            if (!isNaN(d.getTime())) {
                                                                const today = new Date(); today.setHours(0,0,0,0);
                                                                const d0 = new Date(d.getFullYear(), d.getMonth(), d.getDate());
                                                                if (d0 < today) {
                                                                    const badge = document.createElement('span');
                                                                    badge.className = 'badge bg-danger ms-2';
                                                                    badge.textContent = 'Assurance expirée';
                                                                    assuEl.appendChild(badge);
                                                                }
                                                            }
                                                        }

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
                                                                            <input class="form-check-input veh2-cv-payed" type="checkbox" data-cv-id="${cv.id}" ${isPaid?'checked':''} ${window.CAN_EDIT ? '' : 'disabled'}>
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
                                                        if (!window.CAN_EDIT) { input.checked = !input.checked; alert('Action réservée au superadmin'); return; }
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
                                                                                <div class="btn-group btn-group-sm" role="group">
                                                                                    <button type="button" class="btn btn-outline-primary btn-part-details" data-bs-toggle="modal" data-bs-target="#particulierDetailsModal">Détails</button>
                                                                                    <button type="button" class="btn btn-outline-secondary btn-part-actions" data-bs-toggle="modal" data-bs-target="#particulierActionsModal" title="Plus d'actions">Plus</button>
                                                                                </div>
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
                                                                            <div class="col-12">
                                                                                <div id="pt_avis_banner" class="alert alert-warning d-none d-flex align-items-center justify-content-between" role="alert">
                                                                                    <div>
                                                                                        <i class="ri-megaphone-line me-2"></i>
                                                                                        <strong>Avis de recherche actif</strong>
                                                                                        <span class="ms-2" id="pt_avis_text"></span>
                                                                                    </div>
                                                                                    <div>
                                                                                        <button type="button" class="btn btn-sm btn-outline-dark" id="pt_btn_close_avis" data-avis-id="">Clore l'avis</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 d-flex justify-content-end">
                                                                                <button type="button" class="btn btn-sm btn-outline-danger" id="pt_btn_launch_avis">
                                                                                    <i class="ri-megaphone-line me-1"></i> Lancer un avis de recherche
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-12"><hr class="my-3"></div>
                                                                            <div class="col-12">
                                                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                                                    <h6 class="mb-0"><i class="ri-car-line me-2 text-info"></i>Véhicules associés</h6>
                                                                                    <span class="badge bg-secondary" id="pt_veh_count">0</span>
                                                                                </div>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-sm table-hover mb-0">
                                                                                        <thead class="table-light">
                                                                                            <tr>
                                                                                                <th style="width:60px;">#</th>
                                                                                                <th>Plaque</th>
                                                                                                <th>Marque/Modèle</th>
                                                                                                <th>Couleur</th>
                                                                                                <th>Année</th>
                                                                                                <th>Depuis</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="pt_veh_tbody">
                                                                                            <tr><td colspan="6" class="text-center text-muted">Aucun véhicule</td></tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
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
                                                    function fillParticulierModalFromRow(tr){
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
                                                        const tbodyCv = document.getElementById('pt_cv_tbody');
                                                        if (tbodyCv) { tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Chargement...</td></tr>'; }
                                                        const vehTbody = document.getElementById('pt_veh_tbody');
                                                        const vehCount = document.getElementById('pt_veh_count');
                                                        if (vehTbody) { vehTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Chargement...</td></tr>'; }
                                                        if (vehCount) { vehCount.textContent = '0'; }
                                                        const avisBanner = document.getElementById('pt_avis_banner');
                                                        const avisText = document.getElementById('pt_avis_text');
                                                        const btnCloseAvis = document.getElementById('pt_btn_close_avis');
                                                        if (avisBanner) avisBanner.classList.add('d-none');
                                                        if (avisText) avisText.textContent = '';
                                                        if (btnCloseAvis) btnCloseAvis.setAttribute('data-avis-id','');
                                                        if (pid) {
                                                            const url = `/particulier/${pid}/contraventions` + (pnumero ? `?numero=${encodeURIComponent(pnumero)}` : '');
                                                            fetch(url).then(r=>r.json()).then(json=>{
                                                                if (!tbodyCv) return;
                                                                if (!json.ok) { tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur de chargement</td></tr>'; return; }
                                                                const rows = json.data || [];
                                                                if (rows.length === 0) { tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune contravention</td></tr>'; return; }
                                                                tbodyCv.innerHTML = '';
                                                                rows.forEach((cv, idx)=>{
                                                                    const trEl = document.createElement('tr');
                                                                    const payed = String((cv.payed ?? cv.paye ?? cv.paid ?? '0')) === '1';
                                                                    const ref = cv.reference ?? cv.reference_loi ?? cv.ref ?? '';
                                                                    const rawDate = cv.date ?? cv.date_infraction ?? cv.dateInfraction ?? cv.date_contravention ?? '';
                                                                    const dateDisp = window.formatDMY ? (window.formatDMY(rawDate) || '') : (rawDate || '');
                                                                    const desc = cv.description ?? cv.type_infraction ?? cv.typeInfraction ?? '';
                                                                    const montant = (cv.montant ?? cv.amende ?? cv.amount ?? 0);
                                                                    const cid = (cv.id ?? cv.contravention_id ?? cv.id_contravention ?? cv.idContravention ?? '');
                                                                    trEl.innerHTML = `
                                                                        <td>${idx+1}</td>
                                                                        <td>${ref}</td>
                                                                        <td>${dateDisp}</td>
                                                                        <td>${desc}</td>
                                                                        <td>${window.formatMoneyCDF ? (window.formatMoneyCDF(montant) || '') : montant}</td>
                                                                        <td>
                                                                          <div class="form-check form-switch m-0">
                                                                            <input class="form-check-input pt-cv-payed" type="checkbox" data-cv-id="${cid}" ${payed ? 'checked' : ''}>
                                                                            <span class="ms-2 pt-cv-payed-label">${payed ? 'Payé' : 'Non payé'}</span>
                                                                          </div>
                                                                        </td>
                                                                    `;
                                                                    tbodyCv.appendChild(trEl);
                                                                });
                                                            }).catch(()=>{
                                                                if (tbodyCv) tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur réseau</td></tr>';
                                                            });

                                                            // Charger véhicules associés
                                                            fetch(`/particulier/${pid}/vehicules`).then(r=>r.json()).then(json=>{
                                                                if (!vehTbody) return;
                                                                if (!json.ok) { vehTbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur de chargement</td></tr>'; return; }
                                                                const rows = json.data || [];
                                                                if (vehCount) vehCount.textContent = String(rows.length);
                                                                if (rows.length === 0) { vehTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun véhicule</td></tr>'; return; }
                                                                vehTbody.innerHTML = '';
                                                                rows.forEach((v, idx)=>{
                                                                    const trEl = document.createElement('tr');
                                                                    const plaque = v.plaque || '';
                                                                    const mm = [v.marque, v.modele].filter(Boolean).join(' ');
                                                                    const coul = v.couleur || '';
                                                                    const an = v.annee || '';
                                                                    const date = v.date_assoc || '';
                                                                    const dateDisp = window.formatDMY ? (window.formatDMY(date) || '') : (date || '');
                                                                    trEl.innerHTML = `
                                                                        <td>${idx+1}</td>
                                                                        <td>${plaque}</td>
                                                                        <td>${mm}</td>
                                                                        <td>${coul}</td>
                                                                        <td>${an}</td>
                                                                        <td>${dateDisp}</td>
                                                                    `;
                                                                    vehTbody.appendChild(trEl);
                                                                });
                                                            }).catch(()=>{
                                                                if (vehTbody) vehTbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Erreur réseau</td></tr>';
                                                            });

                                                            // Charger avis de recherche pour ce particulier
                                                            fetch(`/particulier/${pid}/avis`).then(r=>r.json()).then(json=>{
                                                                if (!json || !json.ok) return;
                                                                const list = Array.isArray(json.data) ? json.data : [];
                                                                const active = list.find(a=> (a.statut||'') === 'actif');
                                                                if (active && avisBanner) {
                                                                    const motif = active.motif || '';
                                                                    const niveau = active.niveau || '';
                                                                    if (avisText) avisText.textContent = `${motif} — niveau: ${niveau}`;
                                                                    if (btnCloseAvis) btnCloseAvis.setAttribute('data-avis-id', String(active.id||''));
                                                                    avisBanner.classList.remove('d-none');
                                                                }
                                                            }).catch(()=>{});
                                                        } else {
                                                            if (tbodyCv) tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune contravention</td></tr>';
                                                            if (vehTbody) vehTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun véhicule</td></tr>';
                                                        }
                                                    }
                                                    // Ouvrir le modal de création d'avis (Bootstrap 5 uniquement)
                                                    document.addEventListener('click', (e)=>{
                                                        const btn = e.target.closest('#pt_btn_launch_avis, .btn-launch-avis, [data-action="launch-avis"]'); if (!btn) return;
                                                        e.preventDefault(); e.stopPropagation();
                                                        // Retirer le focus pour éviter conflit aria-hidden
                                                        try { document.activeElement?.blur?.(); btn?.blur?.(); } catch(_){}
                                                        const launchEl = document.getElementById('launchAvisModal'); if (!launchEl) return;
                                                        // S'assurer que le modal est sous body
                                                        if (launchEl.parentElement && launchEl.parentElement !== document.body) { document.body.appendChild(launchEl); }
                                                        const pid = document.querySelector('#particulierDetailsModal [data-id]')?.getAttribute('data-id')
                                                            || document.querySelector('tr[data-id].selected')?.getAttribute('data-id')
                                                            || document.querySelector('tr[data-id]')?.getAttribute('data-id') || '';
                                                        launchEl.setAttribute('data-pid', pid);
                                                        const currentModalEl = btn.closest('.modal');
                                                        const showLaunch = ()=>{ requestAnimationFrame(()=>{ bootstrap.Modal.getOrCreateInstance(launchEl).show(); launchEl.focus?.({preventScroll:true}); }); };
                                                        if (currentModalEl && currentModalEl.classList.contains('show')) {
                                                            const onHidden = ()=>{ currentModalEl.removeEventListener('hidden.bs.modal', onHidden); showLaunch(); };
                                                            currentModalEl.addEventListener('hidden.bs.modal', onHidden);
                                                            bootstrap.Modal.getOrCreateInstance(currentModalEl).hide();
                                                            launchEl.addEventListener('hidden.bs.modal', function onLaunchHidden(){
                                                                launchEl.removeEventListener('hidden.bs.modal', onLaunchHidden);
                                                                bootstrap.Modal.getOrCreateInstance(currentModalEl).show();
                                                            });
                                                        } else {
                                                            showLaunch();
                                                        }
                                                    });
                                                    // Soumission création d'avis
                                                    document.addEventListener('submit', async (e)=>{
                                                        const form = e.target.closest('#launchAvisForm'); if (!form) return;
                                                        e.preventDefault();
                                                        const modalEl = document.getElementById('launchAvisModal');
                                                        const pid = modalEl?.getAttribute('data-pid') || '';
                                                        const fd = new FormData(form);
                                                        const motif = (fd.get('motif')||'').toString().trim();
                                                        const niveau = (fd.get('niveau')||'moyen').toString();
                                                        if (!pid || !motif) { alert('Veuillez renseigner le motif'); return; }
                                                        try {
                                                            const resp = await fetch('/avis-recherche', {
                                                                method: 'POST',
                                                                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                                                                body: new URLSearchParams({ cible_type: 'particulier', cible_id: pid, motif, niveau }).toString()
                                                            });
                                                            const data = await resp.json();
                                                            if (!resp.ok || !data.ok) throw new Error(data.error||'Erreur serveur');
                                                            // Succès: notifier puis réinitialiser le formulaire et fermer la modal
                                                            try { window.showSuccess && window.showSuccess('Avis de recherche créé avec succès'); } catch(_){ }
                                                            try {
                                                                const f = document.getElementById('launchAvisForm');
                                                                if (f) {
                                                                    f.reset();
                                                                    const sel = f.querySelector('select[name="niveau"]');
                                                                    if (sel) sel.value = 'moyen';
                                                                    const ta = f.querySelector('textarea[name="motif"]');
                                                                    if (ta) ta.value = '';
                                                                }
                                                            } catch(_){ }
                                                            try { bootstrap.Modal.getInstance(modalEl)?.hide(); } catch(_) { modalEl.classList.remove('show'); modalEl.style.display='none'; }
                                                            // Refresh banner
                                                            const tr = document.querySelector(`tr[data-id="${pid}"]`);
                                                            if (tr) fillParticulierModalFromRow(tr);
                                                        } catch(err){ alert(err.message||'Erreur réseau'); }
                                                    });
                                                    // Clore avis
                                                    document.addEventListener('click', async (e)=>{
                                                        const btn = e.target.closest('#pt_btn_close_avis'); if (!btn) return;
                                                        const id = btn.getAttribute('data-avis-id')||''; if (!id) return;
                                                        if (!confirm('Confirmer la clôture de cet avis ?')) return;
                                                        try {
                                                            const resp = await fetch(`/avis-recherche/${id}/close`, { method: 'POST' });
                                                            const data = await resp.json();
                                                            if (!resp.ok || !data.ok) throw new Error(data.error||'Erreur serveur');
                                                            // Masquer la bannière et recharger
                                                            const banner = document.getElementById('pt_avis_banner');
                                                            if (banner) banner.classList.add('d-none');
                                                            // Succès: notifier l'utilisateur
                                                            showSuccess('Avis de recherche clôturé avec succès');
                                                        } catch(err){ alert(err.message||'Erreur réseau'); }
                                                    });
                                                    // Click handler: Détails
                                                    document.addEventListener('click', (e)=>{
                                                        const btnDetails = e.target.closest('.btn-part-details'); if (!btnDetails) return;
                                                        const tr = btnDetails.closest('tr'); if (!tr) return;
                                                        fillParticulierModalFromRow(tr);
                                                        // Assurer l'onglet Infos actif
                                                        setTimeout(()=>{
                                                            const tabBtn = document.getElementById('pt-infos-tab');
                                                            if (tabBtn) {
                                                                try {
                                                                    if (window.bootstrap && bootstrap.Tab) {
                                                                        bootstrap.Tab.getOrCreateInstance(tabBtn).show();
                                                                    } else { tabBtn.click(); }
                                                                } catch { tabBtn.click(); }
                                                            }
                                                        }, 120);
                                                    });
                                                    // Click handler: Plus (ouvre modal indépendante)
                                                    function fillParticulierActionsModalFromRow(tr){
                                                        const get = (k)=> tr.getAttribute('data-'+k) || '';
                                                        const id = get('id');
                                                        const nom = get('nom');
                                                        const numero = get('numero_national');
                                                        const elNom = document.getElementById('pa_nom'); if (elNom) elNom.textContent = nom;
                                                        const elNum = document.getElementById('pa_numero'); if (elNum) elNum.textContent = numero ? `(N° ${numero})` : '';
                                                        // Persist context for later actions
                                                        window.__lastParticulierCtx = { id, nom, numero };
                                                        const modalEl = document.getElementById('particulierActionsModal');
                                                        if (modalEl) modalEl.setAttribute('data-dossier-id', id);
                                                        try { localStorage.setItem('recent_particulier_ctx', JSON.stringify({id, nom, numero})); } catch {}
                                                    }
                                                    document.addEventListener('click', (e)=>{
                                                        const btnActions = e.target.closest('.btn-part-actions'); if (!btnActions) return;
                                                        const tr = btnActions.closest('tr'); if (!tr) return;
                                                        fillParticulierActionsModalFromRow(tr);
                                                        // Open the modal programmatically to avoid issues with data attributes
                                                        const modalEl = document.getElementById('particulierActionsModal');
                                                        if (modalEl) {
                                                            try {
                                                                if (window.bootstrap && bootstrap.Modal) {
                                                                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                                                                } else {
                                                                    // Fallback: trigger click on a hidden opener if needed
                                                                    modalEl.classList.add('show');
                                                                    modalEl.style.display = 'block';
                                                                }
                                                            } catch {}
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
                                                                                <div class="btn-group btn-group-sm" role="group">
                                                                                    <button type="button" class="btn btn-outline-primary btn-ent-details" data-bs-toggle="modal" data-bs-target="#entrepriseDetailsModal">Détails</button>
                                                                                    <?php if ($canEdit): ?>
                                                                                    <button type="button" class="btn btn-outline-success btn-assign-contrav" data-dossier-type="entreprises" data-dossier-id="<?php echo htmlspecialchars($e['id'] ?? '', ENT_QUOTES); ?>" data-target-label="Entreprise: <?php echo htmlspecialchars($e['designation'] ?? ''); ?>">Assigner</button>
                                                                                    <?php endif; ?>
                                                                                </div>
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
                                                                                <input class="form-check-input ent-cv-payed" type="checkbox" role="switch" data-cv-id="${cv.id}" ${checked} ${window.CAN_EDIT ? '' : 'disabled'}>
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
                                                        if (!window.CAN_EDIT) { input.checked = !input.checked; alert('Action réservée au superadmin'); return; }
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
                
        <!-- Modal actions Particulier (indépendante) -->
        <div class="modal fade" id="particulierActionsModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                  <i class="ri-flashlight-line me-1"></i>
                  Actions pour: <span class="fw-semibold" id="pa_nom"></span>
                  <small class="text-muted ms-2" id="pa_numero"></small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
              </div>
              <div class="modal-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="card h-100 border">
                      <div class="card-body d-flex">
                        <div class="flex-shrink-0 me-3"><i class="ri-id-card-line fs-2 text-primary"></i></div>
                        <div class="flex-grow-1">
                          <h6 class="card-title mb-1">Émettre un permis de conduire temporaire</h6>
                          <p class="text-muted small mb-2">Créer un permis provisoire pour une durée limitée.</p>
                          <button class="btn btn-sm btn-primary" disabled>Prochainement</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card h-100 border">
                      <div class="card-body d-flex">
                        <div class="flex-shrink-0 me-3"><i class="ri-car-line fs-2 text-info"></i></div>
                        <div class="flex-grow-1">
                          <h6 class="card-title mb-1">Associer un véhicule</h6>
                          <p class="text-muted small mb-2">Lier un véhicule existant à cet individu.</p>
                          <button class="btn btn-sm btn-info text-white" id="pa_action_associer_btn" data-bs-toggle="modal" data-bs-target="#associerVehiculeModal">Associer un véhicule</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card h-100 border">
                      <div class="card-body d-flex">
                        <div class="flex-shrink-0 me-3"><i class="ri-alarm-warning-line fs-2 text-warning"></i></div>
                        <div class="flex-grow-1">
                          <h6 class="card-title mb-1">Sanctionner l'individu</h6>
                          <p class="text-muted small mb-2">Enregistrer une sanction administrative.</p>
                          <button class="btn btn-sm btn-warning" id="pa_action_sanction_btn">Créer une contravention</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card h-100 border">
                      <div class="card-body d-flex">
                        <div class="flex-shrink-0 me-3"><i class="ri-handcuffs-line fs-2 text-danger"></i></div>
                        <div class="flex-grow-1">
                          <h6 class="card-title mb-1">Arrestation de l'individu</h6>
                          <p class="text-muted small mb-2">Consigner une interpellation et motif.</p>
                          <button class="btn btn-sm btn-danger" disabled>Prochainement</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card h-100 border">
                      <div class="card-body d-flex">
                        <div class="flex-shrink-0 me-3"><i class="ri-notification-badge-line fs-2 text-secondary"></i></div>
                        <div class="flex-grow-1">
                          <h6 class="card-title mb-1">Lancer un avis de recherche</h6>
                          <p class="text-muted small mb-2">Déclencher un avis de recherche pour cet individu.</p>
                          <button type="button" class="btn btn-sm btn-danger btn-launch-avis" data-action="launch-avis">Émettre un avis de recherche</button>
                        </div>
                      </div>
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

        <!-- Vendor js -->

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
        
        <!-- Modal: Assigner une contravention -->
        <div class="modal fade" id="assignContravModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><i class="ri-ticket-2-line me-2"></i>Assigner une contravention</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="alert alert-info py-2 mb-3" id="ac_target_label" style="display:none"></div>
                <form id="assign-contrav-form">
                  <input type="hidden" name="dossier_id" id="ac_dossier_id">
                  <input type="hidden" name="type_dossier" id="ac_type_dossier">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Date de l'infraction</label>
                      <input type="date" class="form-control" name="date_infraction" required>
                    </div>
                    <div class="col-md-8">
                      <label class="form-label">Lieu</label>
                      <input type="text" class="form-control" name="lieu" placeholder="Lieu de l'infraction" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Type d'infraction</label>
                      <input type="text" class="form-control" name="type_infraction" placeholder="Ex: Excès de vitesse" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Référence de la loi</label>
                      <input type="text" class="form-control" name="reference_loi" placeholder="Ex: Art. 12 ...">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Montant de l'amende (CDF)</label>
                      <input type="number" min="0" step="1" class="form-control" name="amende" placeholder="Ex: 50000" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Payé ?</label>
                      <select class="form-select" name="payed">
                        <option value="0" selected>Non</option>
                        <option value="1">Oui</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <label class="form-label">Description</label>
                      <textarea class="form-control" name="description" rows="3" placeholder="Détails supplémentaires (optionnel)"></textarea>
                    </div>
                  </div>
                </form>
                <div class="alert mt-3 d-none" id="ac_feedback"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="ac_submit_btn">Enregistrer</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Flash helper (global, resilient) -->
        <script>
          (function(){
            if (!window.showSuccess) {
              window.showSuccess = function(msg){
                try {
                  var c = document.getElementById('global-toast-container');
                  if (!c) {
                    c = document.createElement('div');
                    c.id = 'global-toast-container';
                    c.style.position = 'fixed';
                    c.style.top = '1rem';
                    c.style.right = '1rem';
                    c.style.zIndex = '5000';
                    c.style.pointerEvents = 'none';
                    c.style.maxWidth = '90vw';
                    document.body.appendChild(c);
                  }
                  var el = document.createElement('div');
                  el.className = 'alert alert-success shadow mb-2';
                  el.textContent = msg || 'Succès';
                  el.style.pointerEvents = 'auto';
                  // Inline safety styles in case Bootstrap CSS is missing
                  el.style.backgroundColor = el.style.backgroundColor || '#198754';
                  el.style.color = el.style.color || '#fff';
                  el.style.border = el.style.border || '1px solid #146c43';
                  el.style.borderRadius = el.style.borderRadius || '0.25rem';
                  el.style.padding = el.style.padding || '0.5rem 0.75rem';
                  el.style.boxShadow = el.style.boxShadow || '0 .5rem 1rem rgba(0,0,0,.15)';
                  c.appendChild(el);
                  setTimeout(function(){ try{ el.remove(); }catch(e){} if (c && !c.childElementCount) { try{ c.remove(); }catch(e){} } }, 2500);
                } catch (e) { try { alert(msg || 'Succès'); } catch(_){} }
              }
            }
          })();
        </script>

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

        <script>
        (function(){
          // Handler .btn-assign-contrav (Bootstrap 5 uniquement)
          document.addEventListener('click', function(e){
            const btn = e.target.closest('.btn-assign-contrav');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            // Read context from button
            const typeDossier = btn.getAttribute('data-dossier-type') || '';
            const dossierId = btn.getAttribute('data-dossier-id') || '';
            const targetLabel = btn.getAttribute('data-target-label') || '';

            const modalAssign = document.getElementById('assignContravModal');
            if (!modalAssign) return;

            // Fill form hidden fields and info label
            const typeEl = document.getElementById('ac_type_dossier');
            const idEl = document.getElementById('ac_dossier_id');
            const infoEl = document.getElementById('ac_target_label');
            if (typeEl) typeEl.value = typeDossier;
            if (idEl) idEl.value = dossierId;
            if (infoEl) { infoEl.textContent = targetLabel; infoEl.style.display = targetLabel ? 'block' : 'none'; }

            // Ensure modal exists directly under body
            if (modalAssign.parentElement && modalAssign.parentElement !== document.body) document.body.appendChild(modalAssign);

            // If clicked inside another modal, close it first then open assign
            const currentModal = btn.closest('.modal');
            const openAssign = function(){ requestAnimationFrame(()=>{ bootstrap.Modal.getOrCreateInstance(modalAssign).show(); }); };
            if (currentModal && currentModal !== modalAssign && currentModal.classList.contains('show')) {
              const onHidden = function(){
                currentModal.removeEventListener('hidden.bs.modal', onHidden);
                requestAnimationFrame(openAssign);
              };
              currentModal.addEventListener('hidden.bs.modal', onHidden);
              bootstrap.Modal.getOrCreateInstance(currentModal).hide();
            } else {
              openAssign();
            }
          });

          // Prefill from localStorage if present
          function loadRecentContraventionPrefill() {
            try {
              const raw = localStorage.getItem('recent_contravention_prefill');
              if (!raw) return null;
              return JSON.parse(raw);
            } catch (e) { return null; }
          }
          function saveRecentContraventionPrefill(formEl) {
            try {
              const data = {
                date_infraction: formEl.querySelector('[name="date_infraction"]').value || '',
                lieu: formEl.querySelector('[name="lieu"]').value || '',
                type_infraction: formEl.querySelector('[name="type_infraction"]').value || '',
                reference_loi: formEl.querySelector('[name="reference_loi"]').value || '',
                amende: formEl.querySelector('[name="amende"]').value || '',
                payed: formEl.querySelector('[name="payed"]').value || '0'
              };
              localStorage.setItem('recent_contravention_prefill', JSON.stringify(data));
            } catch (e) { /* ignore */ }
          }

          // Handle click on "Sanctionner l'individu"
          document.addEventListener('click', function(e){
            const btn = e.target.closest('#pa_action_sanction_btn');
            if (!btn) return;
            // Find selected particulier context from the actions modal header
            const nom = document.getElementById('pa_nom')?.textContent?.trim() || '';
            const numero = document.getElementById('pa_numero')?.textContent?.trim() || '';
            const datasetEl = document.querySelector('#particulierActionsModal');
            // We expect we previously set hidden context when opening modal; fallback by finding the row marked as active, else use data cached globally if any
            // Prefer reading cached last selected row info if populated by elsewhere
            let lastCtx = window.__lastParticulierCtx || {};
            if (!lastCtx.id) {
              try { lastCtx = JSON.parse(localStorage.getItem('recent_particulier_ctx')||'{}') || {}; } catch {}
            }
            const dossierId = lastCtx.id || datasetEl?.getAttribute('data-dossier-id') || '';

            // Close actions modal then open contravention modal
            const actionsModalEl = document.getElementById('particulierActionsModal');
            bootstrap.Modal.getInstance(actionsModalEl)?.hide();

            // Persist last selected particulier context
            try {
              localStorage.setItem('recent_particulier_ctx', JSON.stringify({ id: dossierId }));
            } catch (e) { /* ignore */ }

            // Prefill assign contravention modal
            const mEl = document.getElementById('assignContravModal');
            const recent = loadRecentContraventionPrefill();
            document.getElementById('ac_type_dossier').value = 'particuliers';
            document.getElementById('ac_dossier_id').value = dossierId;
            const info = document.getElementById('ac_target_label');
            info.textContent = nom ? (nom + (numero ? ' ('+numero+')' : '')) : '';
            info.style.display = nom ? 'block' : 'none';
            if (recent) {
              const f = document.getElementById('assign-contravention-form') || document.getElementById('assign-contrav-form') || document.getElementById('assign-contrav-form');
            }
            // Fill known ids using standard form id
            const form = document.getElementById('assign-contrav-form');
            if (recent && form) {
              form.querySelector('[name="date_infraction"]').value = recent.date_infraction || '';
              form.querySelector('[name="lieu"]').value = recent.lieu || '';
              form.querySelector('[name="type_infraction"]').value = recent.type_infraction || '';
              form.querySelector('[name="reference_loi"]').value = recent.reference_loi || '';
              form.querySelector('[name="amende"]').value = recent.amende || '';
              form.querySelector('[name="payed"]').value = recent.payed || '0';
            }
            bootstrap.Modal.getOrCreateInstance(mEl).show();
          });

          // Submit form
          const submitBtn = document.getElementById('ac_submit_btn');
          const form = document.getElementById('assign-contrav-form');
          const feedback = document.getElementById('ac_feedback');
          if (submitBtn && !window.CAN_EDIT) submitBtn.disabled = true;
          submitBtn?.addEventListener('click', async function(){
            if (!window.CAN_EDIT) { alert('Action réservée au superadmin'); return; }
            // Require dossier_id
            const did = (document.getElementById('ac_dossier_id')?.value || '').trim();
            const t = (document.getElementById('ac_type_dossier')?.value || '').trim();
            if (!did || !t) { alert('Veuillez sélectionner un dossier valide avant de soumettre.'); return; }
            if (!form.checkValidity()) { form.reportValidity(); return; }
            submitBtn.disabled = true;
            feedback.classList.remove('d-none','alert-success','alert-danger');
            feedback.classList.add('alert','alert-info');
            feedback.textContent = 'Enregistrement en cours...';
            try {
              const formData = new FormData(form);
              // ensure payed field exists
              if (!formData.has('payed')) formData.set('payed','0');
              const resp = await fetch('/contravention/create', { method:'POST', body: formData });
              const data = await resp.json();
              if (!resp.ok || data.state === false || data.ok === false) {
                const msg = data.message || data.error || 'Erreur lors de l\'enregistrement';
                throw new Error(msg);
              }
              // Persist recent values for prefill next time
              saveRecentContraventionPrefill(form);
              // Immediately reset form fields so they don't remain filled
              try { form.reset(); } catch {}
              const hidDid = document.getElementById('ac_dossier_id'); if (hidDid) hidDid.value = '';
              const hidType = document.getElementById('ac_type_dossier'); if (hidType) hidType.value = '';
              const info = document.getElementById('ac_target_label'); if (info) { info.textContent = ''; info.style.display = 'none'; }
              feedback.classList.remove('alert-info');
              feedback.classList.add('alert-success');
              feedback.textContent = 'Contravention enregistrée avec succès';
              // Close after short delay and optionally refresh
              setTimeout(()=>{ 
                const modalEl = document.getElementById('assignContravModal');
                bootstrap.Modal.getInstance(modalEl)?.hide();
                // simple strategy: reload to update lists if necessary
                location.reload();
              }, 800);
            } catch(err){
              feedback.classList.remove('alert-info');
              feedback.classList.add('alert-danger');
              feedback.textContent = err.message || 'Erreur inconnue';
            } finally {
              submitBtn.disabled = false;
            }
          });
        })();
        </script>

        <?php require_once '_partials/_modal_avis_recherche.php'; ?>

         <!-- Include Modal creation de compte agent -->
         <?php require_once '_partials/_modal_agent_account.php'; ?>
        
    </body>

<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:58 GMT -->
</html>