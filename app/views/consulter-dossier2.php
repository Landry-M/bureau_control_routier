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
                                                    <script>
                                                    (function(){
                                                        const sel = document.getElementById('part-per-page');
                                                        if (sel) {
                                                            sel.addEventListener('change', function(){
                                                                try {
                                                                    const params = new URLSearchParams(window.location.search);
                                                                    params.set('part_per_page', String(this.value));
                                                                    params.set('part_page', '1');
                                                                    const base = window.location.pathname;
                                                                    window.location.href = base + '?' + params.toString() + '#particuliers';
                                                                } catch (e) {
                                                                    window.location.hash = '#particuliers';
                                                                }
                                                            });
                                                        }
                                                    })();
                                                    </script>
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
                                                        <?php 
                                                        // Pagination controls
                                                        $pg = isset($ent_pagination) && is_array($ent_pagination) ? $ent_pagination : null;
                                                        if ($pg) {
                                                            $p = (int)($pg['page'] ?? 1);
                                                            $pp = (int)($pg['per_page'] ?? 20);
                                                            $tp = (int)($pg['total_pages'] ?? 1);
                                                            $tot = (int)($pg['total'] ?? 0);
                                                            $prev = max(1, $p - 1);
                                                            $next = min($tp, $p + 1);
                                                            // Build URL helper preserving existing query but adjusting ent_page/per_page
                                                            $baseUrl = strtok($_SERVER['REQUEST_URI'], '#');
                                                            $qs = $_GET;
                                                            $qs['ent_per_page'] = $pp;
                                                            $qsPrev = $qs; $qsPrev['ent_page'] = $prev;
                                                            $qsNext = $qs; $qsNext['ent_page'] = $next;
                                                            $qsFirst = $qs; $qsFirst['ent_page'] = 1;
                                                            $qsLast = $qs; $qsLast['ent_page'] = $tp;
                                                            $mk = function($arr){ return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($arr) . '#entreprises'); };
                                                        ?>
                                                        <div class="d-flex align-items-center justify-content-between p-2 border-top flex-wrap gap-2">
                                                            <div class="small text-muted">
                                                                Page <strong><?php echo $p; ?></strong> / <?php echo $tp; ?> ·
                                                                Affichées <strong><?php echo count($entreprises); ?></strong> sur <strong><?php echo $tot; ?></strong>
                                                            </div>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a class="btn btn-light <?php echo $p<=1?'disabled':''; ?>" href="<?php echo $mk($qsFirst); ?>">« Première</a>
                                                                <a class="btn btn-light <?php echo $p<=1?'disabled':''; ?>" href="<?php echo $mk($qsPrev); ?>">‹ Précédent</a>
                                                                <?php 
                                                                // Numérotation des pages (fenêtre autour de la page courante)
                                                                $winE = 2; 
                                                                $startE = max(1, $p - $winE);
                                                                $endE = min($tp, $p + $winE);
                                                                if ($endE - $startE < $winE*2) {
                                                                    $missingE = $winE*2 - ($endE - $startE);
                                                                    $startE = max(1, $startE - $missingE);
                                                                    $endE = min($tp, $endE + ($winE*2 - ($endE - $startE)));
                                                                }
                                                                for ($ei=$startE; $ei<=$endE; $ei++) {
                                                                    $qse = $qs; $qse['ent_page'] = $ei;
                                                                    $activeE = $ei === $p ? 'active' : '';
                                                                    echo '<a class="btn btn-light '.$activeE.'" href="'.$mk($qse).'">'.$ei.'</a>';
                                                                }
                                                                ?>
                                                                <a class="btn btn-light <?php echo $p>=$tp?'disabled':''; ?>" href="<?php echo $mk($qsNext); ?>">Suivant ›</a>
                                                                <a class="btn btn-light <?php echo $p>=$tp?'disabled':''; ?>" href="<?php echo $mk($qsLast); ?>">Dernière »</a>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    <script>
                                                    (function(){
                                                        const sel = document.getElementById('ent-per-page');
                                                        if (sel) {
                                                            sel.addEventListener('change', function(){
                                                                try {
                                                                    const params = new URLSearchParams(window.location.search);
                                                                    params.set('ent_per_page', String(this.value));
                                                                    params.set('ent_page', '1');
                                                                    const base = window.location.pathname;
                                                                    window.location.href = base + '?' + params.toString() + '#entreprises';
                                                                } catch (e) {
                                                                    // Fallback minimal
                                                                    window.location.hash = '#entreprises';
                                                                }
                                                            });
                                                        }
                                                    })();
                                                    </script>
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

          // Libérer le particulier (dernière arrestation active)
          document.addEventListener('click', async function(e){
            const btn = e.target.closest && e.target.closest('#pa_action_release_btn');
            if (!btn) return;
            e.preventDefault();
            const modalActions = document.getElementById('particulierActionsModal');
            const pid = modalActions ? modalActions.getAttribute('data-dossier-id') : (window.__lastParticulierCtx && window.__lastParticulierCtx.id);
            if (!pid) { alert('Veuillez sélectionner un particulier.'); return; }
            // Demander une date de libération (par défaut maintenant)
            const now = new Date();
            const pad = (n)=> String(n).padStart(2,'0');
            const defaultVal = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:00`;
            let input = window.prompt('Date de libération (YYYY-MM-DD HH:MM:SS) - laisser vide pour maintenant:', defaultVal);
            if (input === null) return; // annulé
            input = String(input).trim();
            const fd = new FormData();
            if (input !== '') fd.append('date_sortie_prison', input);
            try {
              const resp = await fetch(`/particulier/${encodeURIComponent(pid)}/liberer`, { method:'POST', body: fd });
              const j = await resp.json().catch(()=>({ok:false,error:'Invalid response'}));
              if (j && j.ok) {
                if (window.showSuccess) window.showSuccess('Particulier libéré.'); else alert('Particulier libéré.');
              } else {
                alert('Échec de la libération: ' + (j && (j.error||j.message) || 'Erreur'));
              }
            } catch(err){
              alert('Erreur réseau lors de la libération');
            }
          });
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
                                                                    <th>Document</th>
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
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.open('/uploads/contraventions/contravention_${cv.id}.pdf', '_blank')" title="Voir le PDF">
                                                                            <i class="ri-eye-line"></i>
                                                                        </button>
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
                                                                        <th>Année fabrication</th>
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
                                                                        <tr data-veh-id="<?php echo htmlspecialchars($v['id']); ?>" data-assu-exp="<?php echo htmlspecialchars($v['date_expire_assurance'] ?? ''); ?>" data-plaque-exp="<?php echo htmlspecialchars($v['plaque_expire_le'] ?? ''); ?>" data-veh-images="<?php echo htmlspecialchars($v['images'] ?? '[]', ENT_QUOTES, 'UTF-8'); ?>" data-frontiere-entree="<?php echo htmlspecialchars($v['frontiere_entree'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-date-importation="<?php echo htmlspecialchars($v['date_importation'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-en-circulation="<?php echo htmlspecialchars(isset($v['en_circulation']) ? (string)$v['en_circulation'] : '1'); ?>" data-genre="<?php echo htmlspecialchars($v['genre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-usage="<?php echo htmlspecialchars($v['usage'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-numero-declaration="<?php echo htmlspecialchars($v['numero_declaration'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-num-moteur="<?php echo htmlspecialchars($v['num_moteur'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-origine="<?php echo htmlspecialchars($v['origine'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-source="<?php echo htmlspecialchars($v['source'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-annee-fab="<?php echo htmlspecialchars($v['annee_fab'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-annee-circ="<?php echo htmlspecialchars($v['annee_circ'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-type-em="<?php echo htmlspecialchars($v['type_em'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-plaque-temp="<?php echo htmlspecialchars(isset($v['plaque_temporaire']) ? (string)$v['plaque_temporaire'] : '0', ENT_QUOTES, 'UTF-8'); ?>" data-plaque-temp-num="<?php echo htmlspecialchars($v['numero_plaque_temp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-plaque-temp-du="<?php echo htmlspecialchars($v['plaque_temp_valide_du'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-plaque-temp-au="<?php echo htmlspecialchars($v['plaque_temp_valide_au'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                                            <td><?php echo $idxv++; ?></td>
                                                                            <td class="veh2-marque"><?php echo htmlspecialchars($v['marque'] ?? ''); ?></td>
                                                                            <td class="veh2-annee-fab"><?php echo htmlspecialchars($v['annee_fab'] ?? ''); ?></td>
                                                                            <td class="veh2-couleur"><?php echo htmlspecialchars($v['couleur'] ?? ''); ?></td>
                                                                            <td class="veh2-plaque"><?php echo htmlspecialchars($v['plaque'] ?? ''); ?>
                                                                                <?php $__hasTemp = !empty($v['plaque_temporaire']); ?>
                                                                                <span class="badge bg-warning text-dark ms-2 veh2-temp-badge" style="display: <?php echo ($__hasTemp ? '' : 'none'); ?>;" title="Plaque temporaire" data-bs-toggle="tooltip">Plaque temporaire</span>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary ms-1 veh2-temp-view" style="display: <?php echo ($__hasTemp ? '' : 'none'); ?>;" title="Voir la plaque temporaire" data-bs-toggle="tooltip"><i class="ri-eye-line"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger ms-1 veh2-temp-close" style="display: <?php echo ($__hasTemp ? '' : 'none'); ?>;" title="Clôturer la plaque temporaire" data-bs-toggle="tooltip">Fermer</button>
                                                                            </td>
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
                                                                                        <button type="button" class="btn btn-outline-secondary btn-veh2-plus" data-veh-id="<?php echo htmlspecialchars($v['id']); ?>" data-bs-toggle="modal" data-bs-target="#vehActionsModal">Plus</button>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <?php 
                                                        // Pagination Particuliers
                                                        $pgp = isset($part_pagination) && is_array($part_pagination) ? $part_pagination : null;
                                                        if ($pgp) {
                                                            $ppage = (int)($pgp['page'] ?? 1);
                                                            $pper = (int)($pgp['per_page'] ?? 20);
                                                            $ptp = (int)($pgp['total_pages'] ?? 1);
                                                            $ptot = (int)($pgp['total'] ?? 0);
                                                            $pprev = max(1, $ppage - 1);
                                                            $pnext = min($ptp, $ppage + 1);
                                                            $qs2 = $_GET;
                                                            $qs2['part_per_page'] = $pper;
                                                            $qs2First = $qs2; $qs2First['part_page'] = 1;
                                                            $qs2Prev  = $qs2; $qs2Prev['part_page']  = $pprev;
                                                            $qs2Next  = $qs2; $qs2Next['part_page']  = $pnext;
                                                            $qs2Last  = $qs2; $qs2Last['part_page']  = $ptp;
                                                            $mkp = function($arr){ return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($arr) . '#particuliers'); };
                                                        ?>
                                                        <div class="d-flex align-items-center justify-content-between p-2 border-top flex-wrap gap-2">
                                                            <div class="small text-muted">
                                                                Page <strong><?php echo $ppage; ?></strong> / <?php echo $ptp; ?> ·
                                                                Affichées <strong><?php echo count($particuliers); ?></strong> sur <strong><?php echo $ptot; ?></strong>
                                                            </div>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a class="btn btn-light <?php echo $ppage<=1?'disabled':''; ?>" href="<?php echo $mkp($qs2First); ?>">« Première</a>
                                                                <a class="btn btn-light <?php echo $ppage<=1?'disabled':''; ?>" href="<?php echo $mkp($qs2Prev); ?>">‹ Précédent</a>
                                                                <?php 
                                                                // Numérotation des pages (fenêtre autour de la page courante)
                                                                $win = 2; // 2 de chaque côté => fenêtre de 5
                                                                $start = max(1, $ppage - $win);
                                                                $end = min($ptp, $ppage + $win);
                                                                // Ajuster pour toujours afficher 5 si possible
                                                                if ($end - $start < $win*2) {
                                                                    $missing = $win*2 - ($end - $start);
                                                                    $start = max(1, $start - $missing);
                                                                    $end = min($ptp, $end + ($win*2 - ($end - $start)));
                                                                }
                                                                for ($i=$start; $i<=$end; $i++) {
                                                                    $qsi = $qs2; $qsi['part_page'] = $i;
                                                                    $active = $i === $ppage ? 'active' : '';
                                                                    echo '<a class="btn btn-light '.$active.'" href="'.$mkp($qsi).'">'.$i.'</a>';
                                                                }
                                                                ?>
                                                                <a class="btn btn-light <?php echo $ppage>=$ptp?'disabled':''; ?>" href="<?php echo $mkp($qs2Next); ?>">Suivant ›</a>
                                                                <a class="btn btn-light <?php echo $ppage>=$ptp?'disabled':''; ?>" href="<?php echo $mkp($qs2Last); ?>">Dernière »</a>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
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
                                                                            <div class="col-md-3 d-none"><strong>Année:</strong> <span id="md2-annee"></span></div>
                                                                            <div class="col-md-3"><strong>Couleur:</strong> <span id="md2-couleur"></span></div>
                                                                            <div class="col-md-4"><strong>Plaque:</strong> <span id="md2-plaque"></span></div>
                                                                            <div class="col-md-12"><strong>Plaque temporaire:</strong> <span id="md2-temp-num"></span></div>
                                                                            <div class="col-md-4"><strong>Valide du:</strong> <span id="md2-valide"></span></div>
                                                                            <div class="col-md-4"><strong>Au:</strong> <span id="md2-expire"></span></div>
                                                                            <div class="col-md-6"><strong>Statut de circulation:</strong> <span id="md2-circulation" class="badge"></span></div>
                                                                            <div class="col-md-6"><strong>Frontière d'entrée:</strong> <span id="md2-frontiere"></span></div>
                                                                            <div class="col-md-6"><strong>Date d'importation:</strong> <span id="md2-date-import"></span></div>
                                                                            <div class="col-12"><strong>Assurance:</strong> <span id="md2-assu"></span></div>
                                                                            <div class="col-12"><hr class="my-2"/></div>
                                                                            <div class="col-12"><strong>Détails techniques</strong></div>
                                                                            <div class="col-md-4"><strong>Genre:</strong> <span id="md2-genre"></span></div>
                                                                            <div class="col-md-4"><strong>Usage:</strong> <span id="md2-usage"></span></div>
                                                                            <div class="col-md-4"><strong>N° déclaration:</strong> <span id="md2-numero-declaration"></span></div>
                                                                            <div class="col-md-4"><strong>N° moteur:</strong> <span id="md2-num-moteur"></span></div>
                                                                            <div class="col-md-4"><strong>Origine:</strong> <span id="md2-origine"></span></div>
                                                                            <div class="col-md-4"><strong>Source:</strong> <span id="md2-source"></span></div>
                                                                            <div class="col-md-4"><strong>Année fabrication:</strong> <span id="md2-annee-fab"></span></div>
                                                                            <div class="col-md-4"><strong>Année 1ère circ.:</strong> <span id="md2-annee-circ"></span></div>
                                                                            <div class="col-md-4"><strong>Type émission:</strong> <span id="md2-type-em"></span></div>
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
                                                                                        <th>Document</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                  (function(){
                                                    // Minimal toast helper (Bootstrap 5 if available, otherwise simple fade div)
                                                    function showToast(message, type){
                                                      try {
                                                        let container = document.getElementById('toasts-container');
                                                        if (!container){
                                                          container = document.createElement('div');
                                                          container.id = 'toasts-container';
                                                          container.style.position = 'fixed';
                                                          container.style.top = '1rem';
                                                          container.style.right = '1rem';
                                                          container.style.zIndex = '1080';
                                                          document.body.appendChild(container);
                                                        }
                                                        const wrap = document.createElement('div');
                                                        const color = type==='success' ? 'bg-success text-white' : (type==='danger'?'bg-danger text-white':'bg-dark text-white');
                                                        wrap.className = 'toast align-items-center show ' + color;
                                                        wrap.setAttribute('role','alert');
                                                        wrap.style.minWidth = '260px';
                                                        wrap.style.marginBottom = '0.5rem';
                                                        wrap.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
                                                        container.appendChild(wrap);
                                                        // If Bootstrap Toast exists, use it for auto-hide
                                                        try {
                                                          if (window.bootstrap && bootstrap.Toast){
                                                            const t = new bootstrap.Toast(wrap, { delay: 2500 });
                                                            t.show();
                                                            wrap.addEventListener('hidden.bs.toast', ()=> wrap.remove());
                                                          } else {
                                                            setTimeout(()=>{ wrap.classList.remove('show'); wrap.remove(); }, 2500);
                                                          }
                                                        } catch { setTimeout(()=>{ wrap.remove(); }, 2500); }
                                                      } catch {}
                                                    }
                                                    function setText(id, value){ const el = document.getElementById(id); if (el) el.textContent = value || ''; }
                                                    function clearVehDetails(){
                                                      setText('md2-marque',''); setText('md2-couleur',''); setText('md2-plaque','');
                                                      setText('md2-valide',''); setText('md2-expire',''); setText('md2-frontiere',''); setText('md2-date-import',''); setText('md2-assu','');
                                                      setText('md2-circulation','');
                                                      // Technical details
                                                      setText('md2-genre',''); setText('md2-usage',''); setText('md2-numero-declaration',''); setText('md2-num-moteur','');
                                                      setText('md2-origine',''); setText('md2-source',''); setText('md2-annee-fab',''); setText('md2-annee-circ',''); setText('md2-type-em','');
                                                      // Temp plate fields
                                                      setText('md2-temp-num','');
                                                      try { const b = document.getElementById('md2-circulation'); if (b) b.className = 'badge'; } catch {}
                                                      const imgs = document.getElementById('md2-images'); if (imgs) imgs.innerHTML = '';
                                                      const tbody = document.querySelector('#table-veh2-contravs tbody'); if (tbody) tbody.innerHTML = '';
                                                    }
                                                    function renderVehContravs(items){
                                                      const tbody = document.querySelector('#table-veh2-contravs tbody');
                                                      if (!tbody) return;
                                                      const arr = Array.isArray(items) ? items : [];
                                                      if (arr.length === 0){ tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Aucune contravention</td></tr>'; return; }
                                                      const fmtDate = (d)=>{
                                                        if (!d) return '';
                                                        try { const ts = Date.parse(d); if (!isNaN(ts)){ const dt = new Date(ts); const dd = String(dt.getDate()).padStart(2,'0'); const mm = String(dt.getMonth()+1).padStart(2,'0'); const yy = dt.getFullYear(); return `${dd}-${mm}-${yy}`; } } catch {}
                                                        return d;
                                                      };
                                                      let html = '';
                                                      arr.forEach((cv, i)=>{
                                                        const id = cv.id ?? '';
                                                        const date = fmtDate(cv.date_infraction ?? '');
                                                        const lieu = cv.lieu ?? '';
                                                        const type = cv.type_infraction ?? '';
                                                        const ref = cv.reference_loi ?? '';
                                                        const amende = (cv.amende ?? '') !== '' ? cv.amende : '';
                                                        const desc = cv.description ?? '';
                                                        const isPayed = (cv.payed==1 || cv.payed==='1' || cv.payed===true);
                                                        html += `<tr>
                                                          <td>${String(id)}</td>
                                                          <td>${date}</td>
                                                          <td>${escapeHtml(lieu)}</td>
                                                          <td>${escapeHtml(type)}</td>
                                                          <td>${escapeHtml(ref)}</td>
                                                          <td>${escapeHtml(String(amende))}</td>
                                                          <td>${escapeHtml(desc)}</td>
                                                          <td>
                                                            <div class="form-check form-switch m-0">
                                                              <input class="form-check-input contrav-pay-toggle" type="checkbox" role="switch" data-contrav-id="${String(id)}" ${isPayed ? 'checked' : ''}>
                                                              <label class="form-check-label small">${isPayed ? 'Payé' : 'Non payé'}</label>
                                                            </div>
                                                          </td>
                                                          <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary view-contrav-pdf" data-contrav-id="${String(id)}" title="Voir le PDF">
                                                              <i class="ri-eye-line"></i>
                                                            </button>
                                                          </td>
                                                        </tr>`;
                                                      });
                                                      tbody.innerHTML = html;
                                                    }
                                                    function escapeHtml(s){
                                                      return String(s).replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[m]); });
                                                    }
                                                    async function loadVehContravs(vehId){
                                                      const tbody = document.querySelector('#table-veh2-contravs tbody'); if (tbody) tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Chargement...</td></tr>';
                                                      if (!vehId){ renderVehContravs([]); return; }
                                                      try {
                                                        const r = await fetch(`/vehicule/${encodeURIComponent(vehId)}/contraventions`);
                                                        const j = await r.json().catch(()=>({ok:false,data:[]}));
                                                        if (j && j.ok){ renderVehContravs(j.data || []); }
                                                        else { renderVehContravs([]); }
                                                      } catch { renderVehContravs([]); }
                                                    }
                                                    function populateFromRow(tr){
                                                      if (!tr) return;
                                                      // Basic fields from cells
                                                      const marque = tr.querySelector('.veh2-marque')?.textContent?.trim() || '';
                                                      const couleur = tr.querySelector('.veh2-couleur')?.textContent?.trim() || '';
                                                      let plaque = '';
                                                      try {
                                                        const plaqueEl = tr.querySelector('.veh2-plaque');
                                                        if (plaqueEl){
                                                          // Prefer the first text node (before badges/buttons)
                                                          const first = Array.from(plaqueEl.childNodes).find(n=> n.nodeType === Node.TEXT_NODE);
                                                          plaque = (first ? first.textContent : plaqueEl.textContent || '').trim();
                                                        }
                                                      } catch { plaque = tr.querySelector('.veh2-plaque')?.textContent?.trim() || ''; }
                                                      const valide = tr.querySelector('.veh2-valide')?.textContent?.trim() || '';
                                                      const expire = tr.querySelector('.veh2-expire')?.textContent?.trim() || '';
                                                      const assu = tr.querySelector('.veh2-assu')?.textContent?.trim() || '';
                                                      // Extra fields from data-*
                                                      const frontiere = tr.getAttribute('data-frontiere-entree') || '';
                                                      const dateImp = tr.getAttribute('data-date-importation') || '';
                                                      // Technical fields from data-*
                                                      const genre = tr.getAttribute('data-genre') || '';
                                                      const usage = tr.getAttribute('data-usage') || '';
                                                      const numDecl = tr.getAttribute('data-numero-declaration') || '';
                                                      const numMot = tr.getAttribute('data-num-moteur') || '';
                                                      const origine = tr.getAttribute('data-origine') || '';
                                                      const source = tr.getAttribute('data-source') || '';
                                                      const anFab = tr.getAttribute('data-annee-fab') || '';
                                                      const anCirc = tr.getAttribute('data-annee-circ') || '';
                                                      const typeEm = tr.getAttribute('data-type-em') || '';
                                                      setText('md2-marque', marque);
                                                      setText('md2-couleur', couleur);
                                                      setText('md2-plaque', plaque);
                                                      // Do not set md2-valide/md2-expire from non-temp fields
                                                      setText('md2-frontiere', frontiere);
                                                      setText('md2-date-import', (window.formatDMY? window.formatDMY(dateImp): dateImp));
                                                      setText('md2-assu', assu);
                                                      // Temp plate
                                                      const tempNum = tr.getAttribute('data-plaque-temp-num') || '';
                                                      const hasTemp = ((tr.getAttribute('data-plaque-temp') || '') === '1') || (tempNum !== '');
                                                      const tempDu = tr.getAttribute('data-plaque-temp-du') || '';
                                                      const tempAu = tr.getAttribute('data-plaque-temp-au') || '';
                                                      setText('md2-temp-num', hasTemp ? tempNum : 'N/A');
                                                      setText('md2-valide', hasTemp ? ((window.formatDMY? window.formatDMY(tempDu): tempDu) || 'N/A') : 'N/A');
                                                      setText('md2-expire', hasTemp ? ((window.formatDMY? window.formatDMY(tempAu): tempAu) || 'N/A') : 'N/A');
                                                      // Set technical details
                                                      setText('md2-genre', genre);
                                                      setText('md2-usage', usage);
                                                      setText('md2-numero-declaration', numDecl);
                                                      setText('md2-num-moteur', numMot);
                                                      setText('md2-origine', origine);
                                                      setText('md2-source', source);
                                                      setText('md2-annee-fab', anFab);
                                                      setText('md2-annee-circ', anCirc);
                                                      setText('md2-type-em', typeEm);
                                                      // Circulation status
                                                      const enCirc = (tr.getAttribute('data-en-circulation') || '').trim();
                                                      const isOut = (enCirc === '0' || enCirc.toLowerCase() === 'false');
                                                      const status = isOut ? 'Retiré de la circulation' : 'En circulation';
                                                      const badge = document.getElementById('md2-circulation');
                                                      if (badge){
                                                        badge.textContent = status;
                                                        badge.className = 'badge ' + (isOut ? 'bg-danger' : 'bg-success');
                                                      }
                                                      // Images
                                                      try{
                                                        const imgs = document.getElementById('md2-images');
                                                        if (imgs){
                                                          imgs.innerHTML = '';
                                                          const raw = tr.getAttribute('data-veh-images') || '[]';
                                                          let arr = [];
                                                          try { arr = JSON.parse(raw); } catch { arr = []; }
                                                          (arr||[]).forEach(url=>{
                                                            if (!url) return;
                                                            const img = document.createElement('img');
                                                            img.src = url;
                                                            img.alt = 'Photo véhicule';
                                                            img.className = 'img-thumbnail';
                                                            img.style.maxHeight = '120px';
                                                            imgs.appendChild(img);
                                                          });
                                                        }
                                                      } catch{}
                                                    }
                                                    // Populate on click of details button
                                                    document.addEventListener('click', function(e){
                                                      const btn = e.target.closest && e.target.closest('.btn-veh2-details');
                                                      if (!btn) return;
                                                      const tr = btn.closest('tr');
                                                      clearVehDetails();
                                                      populateFromRow(tr);
                                                      const vehId = tr?.getAttribute('data-veh-id') || '';
                                                      loadVehContravs(vehId);
                                                    });
                                                    // Also populate when modal is shown via Bootstrap (ensures relatedTarget is used)
                                                    try{
                                                      const modal = document.getElementById('vehiculeDetailsModal2');
                                                      modal.addEventListener('show.bs.modal', function(ev){
                                                        const btn = ev.relatedTarget;
                                                        const tr = btn ? btn.closest('tr') : null;
                                                        clearVehDetails();
                                                        populateFromRow(tr);
                                                        const vehId = tr?.getAttribute('data-veh-id') || '';
                                                        loadVehContravs(vehId);
                                                      });
                                                    } catch{}

                                                    // Toggle handler for payed switch (delegated)
                                                    document.addEventListener('change', async function(e){
                                                      const input = e.target && e.target.classList && e.target.classList.contains('contrav-pay-toggle') ? e.target : null;
                                                      if (!input) return;
                                                      const tr = input.closest('tr');
                                                      const label = tr ? tr.querySelector('.form-check-label') : null;
                                                      const id = input.getAttribute('data-contrav-id');
                                                      if (!id) return;
                                                      const newVal = input.checked ? '1' : '0';
                                                      const prevChecked = !input.checked; // to revert if error
                                                      input.disabled = true; if (label) label.textContent = input.checked ? 'Payé' : 'Non payé';
                                                      try {
                                                        const fd = new FormData(); fd.append('id', id); fd.append('payed', newVal);
                                                        const r = await fetch('/contravention/update-payed', { method: 'POST', body: fd });
                                                        const j = await r.json().catch(()=>({ok:false,state:false}));
                                                        const ok = (j && (j.ok === true || j.state === true));
                                                        if (!ok) { throw new Error((j && (j.error||j.message)) || 'Erreur'); }
                                                        // success toast
                                                        showToast('Statut de paiement mis à jour.', 'success');
                                                      } catch (err){
                                                        // revert
                                                        input.checked = prevChecked;
                                                        if (label) label.textContent = input.checked ? 'Payé' : 'Non payé';
                                                        alert('Échec de la mise à jour du statut: ' + (err?.message || 'Erreur réseau'));
                                                      } finally {
                                                        input.disabled = false;
                                                      }
                                                    });

                                                    // PDF viewing handler for contraventions (delegated)
                                                    document.addEventListener('click', function(e){
                                                      const btn = e.target.closest && e.target.closest('.view-contrav-pdf');
                                                      if (!btn) return;
                                                      const contraventionId = btn.getAttribute('data-contrav-id');
                                                      if (!contraventionId) {
                                                        alert('ID de contravention manquant');
                                                        return;
                                                      }
                                                      // Open PDF in new window/tab
                                                      const pdfUrl = `/uploads/contraventions/contravention_${contraventionId}.pdf`;
                                                      window.open(pdfUrl, '_blank');
                                                    });

                                                    // Filtre et tri pour la table Véhicules
                                                    (function(){
                                                      const filterInput = document.getElementById('veh-filter2');
                                                      const sortSelect = document.getElementById('veh-sort2');
                                                      const table = document.getElementById('vehicules-table2');
                                                      const tbody = table ? table.querySelector('tbody') : null;
                                                      if (!table || !tbody) return;
                                                      function norm(v){ return (v||'').toString().toLowerCase(); }
                                                      function rowKey(tr, field){
                                                        switch(field){
                                                          case 'marque': return norm(tr.querySelector('.veh2-marque')?.textContent);
                                                          case 'plaque': return norm(tr.querySelector('.veh2-plaque')?.textContent);
                                                          case 'id': return parseInt(tr.getAttribute('data-veh-id') || '0', 10) || 0;
                                                          default: return norm(tr.textContent);
                                                        }
                                                      }
                                                      function renumber(){
                                                        let i=1; Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                          if (tr.style.display === 'none') return;
                                                          const td = tr.querySelector('td:first-child'); if (td) td.textContent = String(i++);
                                                        });
                                                      }
                                                      function applyFilter(){
                                                        const q = norm(filterInput?.value || '');
                                                        Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
                                                          const hay = [
                                                            tr.querySelector('.veh2-marque')?.textContent,
                                                            tr.querySelector('.veh2-annee')?.textContent,
                                                            tr.querySelector('.veh2-couleur')?.textContent,
                                                            tr.querySelector('.veh2-plaque')?.textContent,
                                                            tr.querySelector('.veh2-valide')?.textContent,
                                                            tr.querySelector('.veh2-expire')?.textContent,
                                                            tr.querySelector('.veh2-assu')?.textContent
                                                          ].map(norm).join(' ');
                                                          tr.style.display = q && !hay.includes(q) ? 'none' : '';
                                                        });
                                                      }
                                                      function applySort(){
                                                        const mode = sortSelect?.value || '';
                                                        if (!mode){ renumber(); return; }
                                                        const [field, dir] = mode.split('_');
                                                        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r=> r.style.display !== 'none');
                                                        rows.sort((a,b)=>{
                                                          const va = rowKey(a, field), vb = rowKey(b, field);
                                                          if (typeof va === 'number' && typeof vb === 'number') return dir==='asc' ? (va-vb) : (vb-va);
                                                          if (va < vb) return dir==='asc' ? -1 : 1;
                                                          if (va > vb) return dir==='asc' ? 1 : -1;
                                                          return 0;
                                                        });
                                                        rows.forEach(r=> tbody.appendChild(r));
                                                        renumber();
                                                      }
                                                      filterInput?.addEventListener('input', ()=>{ applyFilter(); applySort(); });
                                                      sortSelect?.addEventListener('change', ()=>{ applySort(); });
                                                    })();

                                                  })();
                                                </script>
                                            </div> <!-- end tab-pane: vehicules -->
                                            <!-- end timeline content-->

                                            <!-- Modal: Choix d'actions véhicule/plaque -->
                                            <div class="modal fade" id="vehActionsModal" tabindex="-1" aria-hidden="true">
                                              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Actions véhicule / plaque</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div class="row g-3" id="vehActionsList">
                                                      <div class="col-md-6">
                                                        <div class="card h-100 border">
                                                          <div class="card-body d-flex">
                                                            <div class="flex-shrink-0 me-3"><i class="ri-alert-line fs-2 text-danger"></i></div>
                                                            <div class="flex-grow-1">
                                                              <h6 class="card-title mb-1">Sanctionner le véhicule</h6>
                                                              <p class="text-muted small mb-2">Ouvrir le formulaire de contravention pour ce véhicule.</p>
                                                              <button type="button" class="btn btn-sm btn-danger" id="vehAct_sanctionner">Sanctionner</button>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="card h-100 border">
                                                          <div class="card-body d-flex">
                                                            <div class="flex-shrink-0 me-3"><i class="ri-exchange-line fs-2 text-primary"></i></div>
                                                            <div class="flex-grow-1">
                                                              <h6 class="card-title mb-1">Transférer le véhicule</h6>
                                                              <p class="text-muted small mb-2">Changer le propriétaire du véhicule.</p>
                                                              <button type="button" class="btn btn-sm btn-primary" id="vehAct_transferer">Transférer</button>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="card h-100 border">
                                                          <div class="card-body d-flex">
                                                            <div class="flex-shrink-0 me-3"><i class="ri-hand-coin-line fs-2 text-warning"></i></div>
                                                            <div class="flex-grow-1">
                                                              <h6 class="card-title mb-1">Retirer le véhicule</h6>
                                                              <p class="text-muted small mb-2">Retirer le véhicule de la circulation.</p>
                                                              <button type="button" class="btn btn-sm btn-warning" id="vehAct_retirer_vehicule">Retirer véhicule</button>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="card h-100 border">
                                                          <div class="card-body d-flex">
                                                            <div class="flex-shrink-0 me-3"><i class="ri-stop-circle-line fs-2 text-secondary"></i></div>
                                                            <div class="flex-grow-1">
                                                              <h6 class="card-title mb-1">Retirer la plaque</h6>
                                                              <p class="text-muted small mb-2">Retirer la plaque du véhicule.</p>
                                                              <button type="button" class="btn btn-sm btn-secondary" id="vehAct_retirer_plaque">Retirer plaque</button>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="card h-100 border">
                                                          <div class="card-body d-flex">
                                                            <div class="flex-shrink-0 me-3"><i class="ri-time-line fs-2 text-success"></i></div>
                                                            <div class="flex-grow-1">
                                                              <h6 class="card-title mb-1">Plaque temporaire</h6>
                                                              <p class="text-muted small mb-2">Attribuer une plaque temporaire au véhicule.</p>
                                                              <button type="button" class="btn btn-sm btn-success" id="vehAct_plaque_temp">Plaque temporaire</button>
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

                                            <!-- Modals: Actions véhicule/plaque -->
                                            <div class="modal fade" id="vehTransfertModal" tabindex="-1" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Transférer le véhicule</h5>
                                                  </div>
                                                  <div class="modal-body">
                                                    <form id="vehTransfertForm">
                                                      <input type="hidden" id="vehTransfer_id" name="vehicule_id">
                                                      <div class="mb-2">
                                                        <label class="form-label">Rechercher un particulier</label>
                                                        <input type="text" class="form-control" id="vehTransfer_search" placeholder="Tapez un nom pour filtrer...">
                                                      </div>
                                                      <div class="mb-2">
                                                        <label class="form-label">Nouveau propriétaire</label>
                                                        <select class="form-select" id="vehTransfer_owner_select">
                                                          <option value="">— Sélectionnez un particulier —</option>
                                                        </select>
                                                        <div class="form-text">Saisissez quelques lettres du nom, puis choisissez la personne. La liste affiche les 20 premiers résultats.</div>
                                                      </div>
                                                      <div class="mb-2">
                                                        <label class="form-label">Motif</label>
                                                        <input type="text" class="form-control" id="vehTransfer_reason" placeholder="Motif du transfert">
                                                      </div>
                                                    </form>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-primary" id="vehTransfer_submit">Transférer</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="modal fade" id="vehRetirerModal" tabindex="-1" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Retirer le véhicule de la circulation</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <form id="vehRetirerForm">
                                                      <input type="hidden" id="vehRetirer_id" name="vehicule_id">
                                                      <div class="mb-2">
                                                        <label class="form-label">Raison</label>
                                                        <input type="text" class="form-control" id="vehRetirer_reason" placeholder="Ex: Véhicule dangereux, volé, etc.">
                                                      </div>
                                                      <div class="mb-2">
                                                        <label class="form-label">Date d'effet</label>
                                                        <input type="date" class="form-control" id="vehRetirer_date">
                                                      </div>
                                                    </form>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-danger" id="vehRetirer_submit">Retirer</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="modal fade" id="plaqueRetirerModal" tabindex="-1" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Retirer la plaque du véhicule</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <form id="plaqueRetirerForm">
                                                      <input type="hidden" id="plaqueRetirer_veh_id" name="vehicule_id">
                                                      <div class="mb-2">
                                                        <label class="form-label">Raison</label>
                                                        <input type="text" class="form-control" id="plaqueRetirer_reason" placeholder="Motif du retrait de plaque">
                                                      </div>
                                                    </form>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-warning" id="plaqueRetirer_submit">Retirer la plaque</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="modal fade" id="plaqueTempModal" tabindex="-1" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Plaque temporaire</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <form id="plaqueTempForm">
                                                      <input type="hidden" id="plaqueTemp_veh_id" name="vehicule_id">
                                                      <div class="mb-2">
                                                        <label class="form-label">Numéro de plaque temporaire</label>
                                                        <input type="text" class="form-control" id="plaqueTemp_numero" placeholder="Ex: TMP-12345">
                                                      </div>
                                                      <div class="mb-2">
                                                        <label class="form-label">Valide du</label>
                                                        <input type="date" class="form-control" id="plaqueTemp_du">
                                                      </div>
                                                      <div class="mb-2">
                                                        <label class="form-label">Au</label>
                                                        <input type="date" class="form-control" id="plaqueTemp_au">
                                                      </div>
                                                    </form>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-primary" id="plaqueTemp_submit">Enregistrer</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                            <script>
                                              (function(){
                                                // Helper to open a modal safely whether or not bootstrap JS is available
                                                function openModalSafe(el){
                                                  try {
                                                    if (window.bootstrap && bootstrap.Modal) { bootstrap.Modal.getOrCreateInstance(el).show(); return; }
                                                  } catch {}
                                                  // fallback
                                                  if (!el) return;
                                                  document.querySelectorAll('.modal-backdrop').forEach(b=>b.remove());
                                                  const bd = document.createElement('div'); bd.className = 'modal-backdrop fade show'; document.body.appendChild(bd);
                                                  el.style.display = 'block'; el.classList.add('show'); document.body.classList.add('modal-open');
                                                }

                                                // Context passing for actions modal
                                                const vehActionsModal = document.getElementById('vehActionsModal');
                                                // When opened via Bootstrap, capture vehId from the triggering button
                                                vehActionsModal.addEventListener('show.bs.modal', function(ev){
                                                  const btn = ev.relatedTarget;
                                                  const vehId = btn ? btn.getAttribute('data-veh-id') : '';
                                                  this.dataset.vehId = vehId || '';
                                                  // Ajuster le libellé du bouton Retirer/Remettre selon l'état courant
                                                  try {
                                                    const tr = getVehRowById(String(vehId));
                                                    const enCirc = (tr?.getAttribute('data-en-circulation')||'').trim();
                                                    const isOut = (enCirc === '0' || enCirc.toLowerCase() === 'false');
                                                    const btn = document.getElementById('vehAct_retirer_vehicule');
                                                    if (btn){
                                                      btn.textContent = isOut ? 'Remettre en circulation' : 'Retirer véhicule';
                                                      btn.classList.remove('btn-warning','btn-success');
                                                      btn.classList.add(isOut ? 'btn-success' : 'btn-warning');
                                                    }
                                                  } catch {}
                                                });

                                                // Fallback open if bootstrap not present
                                                document.addEventListener('click', function(e){
                                                  const plusBtn = e.target.closest && e.target.closest('.btn-veh2-plus');
                                                  if (!plusBtn) return;
                                                  if (!(window.bootstrap && bootstrap.Modal)){
                                                    e.preventDefault();
                                                    vehActionsModal.dataset.vehId = plusBtn.getAttribute('data-veh-id') || '';
                                                    openModalSafe(vehActionsModal);
                                                  }
                                                });

                                                function getVehRowById(vehId){ return document.querySelector(`tr[data-veh-id="${CSS.escape(vehId)}"]`); }
                                                function hideActionsModal(){ try { if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(vehActionsModal).hide(); } catch {} }

                                                // Buttons inside the actions modal
                                                const btnActSanction = document.getElementById('vehAct_sanctionner');
                                                const btnActTransfer = document.getElementById('vehAct_transferer');
                                                const btnActRetVeh = document.getElementById('vehAct_retirer_vehicule');
                                                const btnActRetPlaque = document.getElementById('vehAct_retirer_plaque');
                                                const btnActPlaqueTemp = document.getElementById('vehAct_plaque_temp');

                                                if (btnActSanction){ btnActSanction.addEventListener('click', function(){
                                                  const vehId = vehActionsModal.dataset.vehId || '';
                                                  const tr = getVehRowById(vehId);
                                                  if (!tr){ alert('Ligne véhicule introuvable.'); return; }
                                                  // Ouvrir directement le modal d'assignation pour le véhicule
                                                  const modalAssign = document.getElementById('assignContravModal');
                                                  if (!modalAssign){ alert("Modal d'assignation introuvable."); return; }
                                                  const typeEl = document.getElementById('ac_type_dossier');
                                                  const idEl = document.getElementById('ac_dossier_id');
                                                  const infoEl = document.getElementById('ac_target_label');
                                                  if (typeEl) typeEl.value = 'vehicule_plaque';
                                                  if (idEl) idEl.value = vehId;
                                                  const marque = tr.querySelector('.veh2-marque')?.textContent?.trim() || '';
                                                  const plaque = tr.querySelector('.veh2-plaque')?.textContent?.trim() || '';
                                                  if (infoEl) { 
                                                    const label = `Véhicule: ${marque} ${plaque}`.trim();
                                                    infoEl.textContent = label; 
                                                    infoEl.style.display = label ? 'block' : 'none';
                                                  }
                                                  try { 
                                                    if (modalAssign.parentElement && modalAssign.parentElement !== document.body) document.body.appendChild(modalAssign);
                                                    bootstrap.Modal.getOrCreateInstance(modalAssign).show(); 
                                                  } catch {}
                                                  hideActionsModal();
                                                }); }

                                                if (btnActTransfer){ btnActTransfer.addEventListener('click', function(){
                                                  const vehId = vehActionsModal.dataset.vehId || '';
                                                  document.getElementById('vehTransfer_id').value = vehId;
                                                  hideActionsModal();
                                                  const modalEl = document.getElementById('vehTransfertModal');
                                                  try { bootstrap.Modal.getOrCreateInstance(modalEl).show(); } catch { openModalSafe(modalEl); }
                                                  // Charger la liste initiale (20 premiers)
                                                  try { if (window.__loadVehTransferParticuliers) window.__loadVehTransferParticuliers(''); } catch {}
                                                }); }

                                                if (btnActRetVeh){ btnActRetVeh.addEventListener('click', function(){
                                                  const vehId = vehActionsModal.dataset.vehId || '';
                                                  const tr = getVehRowById(String(vehId));
                                                  const enCirc = (tr?.getAttribute('data-en-circulation')||'').trim();
                                                  const isOut = (enCirc === '0' || enCirc.toLowerCase() === 'false');
                                                  if (isOut){
                                                    // Remettre en circulation directement
                                                    const btn = this; btn.disabled = true;
                                                    (async ()=>{
                                                      try {
                                                        const r = await fetch(`/vehicule/${encodeURIComponent(String(vehId))}/remettre`, { method:'POST' });
                                                        const j = await r.json().catch(()=>({ok:false}));
                                                        if (j && j.ok){
                                                          alert('Véhicule remis en circulation.');
                                                          try { if (tr) tr.setAttribute('data-en-circulation','1'); } catch {}
                                                        } else {
                                                          const msg = (j && (j.error||j.message)) ? String(j.error||j.message) : (!r.ok ? `Erreur serveur (${r.status} ${r.statusText})` : 'Échec de la remise en circulation');
                                                          alert(msg);
                                                        }
                                                      } catch { alert('Erreur réseau'); }
                                                      finally {
                                                        btn.disabled = false;
                                                        hideActionsModal();
                                                      }
                                                    })();
                                                  } else {
                                                    // Retirer: ouvrir le modal de confirmation
                                                    document.getElementById('vehRetirer_id').value = vehId;
                                                    hideActionsModal();
                                                    openModalSafe(document.getElementById('vehRetirerModal'));
                                                  }
                                                }); }

                                                if (btnActRetPlaque){ btnActRetPlaque.addEventListener('click', function(){
                                                  const vehId = vehActionsModal.dataset.vehId || '';
                                                  document.getElementById('plaqueRetirer_veh_id').value = vehId;
                                                  hideActionsModal();
                                                  openModalSafe(document.getElementById('plaqueRetirerModal'));
                                                }); }

                                                if (btnActPlaqueTemp){ btnActPlaqueTemp.addEventListener('click', function(){
                                                  const vehId = vehActionsModal.dataset.vehId || '';
                                                  document.getElementById('plaqueTemp_veh_id').value = vehId;
                                                  hideActionsModal();
                                                  openModalSafe(document.getElementById('plaqueTempModal'));
                                                }); }

                                                // Submit handlers (placeholders to integrate with backend)
                                                // Gestion de la recherche et du select des particuliers (transfert véhicule)
                                                (function(){
                                                  const modalId = 'vehTransfertModal';
                                                  const searchInput = document.getElementById('vehTransfer_search');
                                                  const selectEl = document.getElementById('vehTransfer_owner_select');
                                                  if (!selectEl) return;
                                                  function renderOptions(items){
                                                    const prev = selectEl.value || '';
                                                    let html = '<option value="">— Sélectionnez un particulier —</option>';
                                                    (items||[]).forEach(it=>{
                                                      const label = (it.nom || it.name || '') + (it.numero ? ' ('+it.numero+')' : '');
                                                      const id = String(it.id || it.particulier_id || '');
                                                      html += `<option value="${id}">${label}</option>`;
                                                    });
                                                    selectEl.innerHTML = html;
                                                    if (prev) {
                                                      const opt = Array.from(selectEl.options).find(o=>o.value===prev);
                                                      if (opt) selectEl.value = prev;
                                                    }
                                                  }
                                                  async function fetchParticuliers(q){
                                                    try {
                                                      const url = `/particuliers/search?q=${encodeURIComponent(q||'')}&limit=20`;
                                                      const res = await fetch(url);
                                                      const data = await res.json().catch(()=>({items:[]}));
                                                      const items = data.items || data.results || data.data || [];
                                                      renderOptions(items);
                                                    } catch { renderOptions([]); }
                                                  }
                                                  // Expose loader for external trigger on modal open
                                                  window.__loadVehTransferParticuliers = fetchParticuliers;
                                                  // Debounced search
                                                  let t = null;
                                                  if (searchInput) {
                                                    searchInput.addEventListener('input', function(){
                                                      const q = this.value.trim();
                                                      clearTimeout(t);
                                                      t = setTimeout(()=>{ fetchParticuliers(q); }, 300);
                                                    });
                                                  }
                                                  // Load initial on modal show
                                                  try {
                                                    const modalEl = document.getElementById(modalId);
                                                    modalEl.addEventListener('show.bs.modal', function(){ fetchParticuliers(searchInput?.value?.trim()||''); });
                                                  } catch {}
                                                })();

                                                const submitTransfer = document.getElementById('vehTransfer_submit');
                                                if (submitTransfer){ submitTransfer.addEventListener('click', async function(){
                                                  const vehId = (document.getElementById('vehTransfer_id').value || '').trim();
                                                  const owner = (document.getElementById('vehTransfer_owner_select')?.value || '').trim();
                                                  const reason = document.getElementById('vehTransfer_reason').value.trim();
                                                  if (!vehId){ alert('Véhicule introuvable. Réessayez.'); return; }
                                                  if (!owner){ alert('Veuillez sélectionner le nouveau propriétaire.'); return; }
                                                  try {
                                                    const fd = new FormData(); fd.append('vehicule_id', vehId); fd.append('nouveau_proprietaire', owner); if (reason) fd.append('motif', reason);
                                                    const r = await fetch(`/vehicule/${encodeURIComponent(vehId)}/transferer`, { method:'POST', body: fd });
                                                    let j = null; try { j = await r.json(); } catch (e) { j = { ok:false, error:'Réponse invalide du serveur' }; }
                                                    if (j && j.ok){ alert('Transfert enregistré.'); try{ bootstrap.Modal.getOrCreateInstance(document.getElementById('vehTransfertModal')).hide(); }catch{} }
                                                    else {
                                                      const msg = (j && (j.error || j.message)) ? String(j.error || j.message) : 'Échec du transfert';
                                                      alert(msg);
                                                    }
                                                  } catch { alert('Erreur réseau'); }
                                                }); }

                                                const submitRetirerVeh = document.getElementById('vehRetirer_submit');
                                                if (submitRetirerVeh){ submitRetirerVeh.addEventListener('click', async function(){
                                                  const vehId = document.getElementById('vehRetirer_id').value.trim();
                                                  const reason = document.getElementById('vehRetirer_reason').value.trim();
                                                  const date = document.getElementById('vehRetirer_date').value.trim();
                                                  if (!reason){ alert('Veuillez indiquer la raison.'); return; }
                                                  try {
                                                    const fd = new FormData(); fd.append('vehicule_id', vehId); fd.append('raison', reason); if (date) fd.append('date_effet', date);
                                                    const r = await fetch(`/vehicule/${encodeURIComponent(vehId)}/retirer`, { method:'POST', body: fd });
                                                    const j = await r.json().catch(()=>({ok:false}));
                                                    if (j && j.ok){
                                                      alert('Véhicule retiré.');
                                                      try{ bootstrap.Modal.getOrCreateInstance(document.getElementById('vehRetirerModal')).hide(); }catch{}
                                                      // Mettre à jour l'état de circulation sur la ligne
                                                      try{
                                                        const tr = document.querySelector(`tr[data-veh-id="${CSS.escape(String(vehId))}"]`);
                                                        if (tr) tr.setAttribute('data-en-circulation','0');
                                                      } catch{}
                                                    }
                                                    else { alert('Échec de l\'opération'); }
                                                  } catch { alert('Erreur réseau'); }
                                                }); }

                                                const submitRetirerPlaque = document.getElementById('plaqueRetirer_submit');
                                                if (submitRetirerPlaque){ submitRetirerPlaque.addEventListener('click', async function(){
                                                  const vehId = document.getElementById('plaqueRetirer_veh_id').value.trim();
                                                  const reason = document.getElementById('plaqueRetirer_reason').value.trim();
                                                  if (!reason){ alert('Veuillez indiquer la raison.'); return; }
                                                  try {
                                                    const fd = new FormData(); fd.append('vehicule_id', vehId); fd.append('raison', reason);
                                                    const r = await fetch(`/plaque/retirer`, { method:'POST', body: fd });
                                                    let j = null; try { j = await r.json(); } catch(e){ j = null; }
                                                    if (j && j.ok){
                                                      alert('Plaque retirée.');
                                                      try{ bootstrap.Modal.getOrCreateInstance(document.getElementById('plaqueRetirerModal')).hide(); }catch{}
                                                      // Mettre à jour l'état de circulation sur la ligne pour refléter le changement
                                                      try{
                                                        const tr = document.querySelector(`tr[data-veh-id="${CSS.escape(String(vehId))}"]`);
                                                        if (tr) tr.setAttribute('data-en-circulation', '0');
                                                      } catch {}
                                                    } else {
                                                      const msg = (j && (j.error||j.message)) ? String(j.error||j.message) : (!r.ok ? `Erreur serveur (${r.status} ${r.statusText})` : 'Échec du retrait de plaque');
                                                      alert(msg);
                                                    }
                                                  } catch { alert('Erreur réseau'); }
                                                }); }

                                                async function refreshVehTempBadge(vehId){
                                                  try {
                                                    const res = await fetch(`/vehicule/${encodeURIComponent(vehId)}/permis-temporaire`);
                                                    const j = await res.json().catch(()=>({ok:false,data:[]}));
                                                    const items = (j && j.ok && Array.isArray(j.data)) ? j.data : [];
                                                    const __d = new Date();
                                                    const today = `${__d.getFullYear()}-${String(__d.getMonth()+1).padStart(2,'0')}-${String(__d.getDate()).padStart(2,'0')}`;
                                                    let activeItem = null;
                                                    for (const it of items){
                                                      const statut = String(it.statut||'').toLowerCase();
                                                      const d1 = (it.date_debut||'').slice(0,10);
                                                      const d2 = (it.date_fin||'').slice(0,10);
                                                      if (statut === 'actif' && d1 && d2 && d1 <= today && today <= d2){ activeItem = it; break; }
                                                    }
                                                    const tr = document.querySelector(`tr[data-veh-id="${CSS.escape(String(vehId))}"]`);
                                                    if (tr){
                                                      const badge = tr.querySelector('.veh2-temp-badge');
                                                      const btnView = tr.querySelector('.veh2-temp-view');
                                                      const btnClose = tr.querySelector('.veh2-temp-close');
                                                      if (activeItem){
                                                        const d1 = (activeItem.date_debut||'').slice(0,10);
                                                        const d2 = (activeItem.date_fin||'').slice(0,10);
                                                        const numero = String(activeItem.numero||'').trim();
                                                        if (badge){
                                                          badge.style.display = '';
                                                          badge.textContent = numero ? `Plaque temporaire ${numero}` : 'Plaque temporaire';
                                                          badge.setAttribute('title', (d1&&d2) ? `Valide du ${d1} au ${d2}` : 'Plaque temporaire');
                                                          try { const t = bootstrap.Tooltip.getInstance(badge); if (t) t.dispose(); } catch{}
                                                          try { new bootstrap.Tooltip(badge); } catch{}
                                                        }
                                                        if (btnView){
                                                          btnView.style.display = '';
                                                          btnView.onclick = function(){
                                                            const previewUrl = `/plaque-temporaire/display?id=${encodeURIComponent(String(activeItem.id))}&vehicule_id=${encodeURIComponent(String(vehId))}&numero=${encodeURIComponent(numero)}`;
                                                            window.open(previewUrl, '_blank');
                                                          };
                                                        }
                                                        if (btnClose){
                                                          btnClose.style.display = '';
                                                          btnClose.onclick = async function(){
                                                            if (!confirm('Clôturer cette plaque temporaire ?')) return;
                                                            btnClose.disabled = true;
                                                            try {
                                                              const r = await fetch(`/permis-temporaire/${encodeURIComponent(String(activeItem.id))}/close`, { method:'POST' });
                                                              const jr = await r.json().catch(()=>({ok:false,error:'Réponse invalide'}));
                                                              if (jr && jr.ok){
                                                                alert('Plaque temporaire clôturée.');
                                                                refreshVehTempBadge(vehId);
                                                              } else {
                                                                alert(jr && (jr.error||jr.message) ? (jr.error||jr.message) : 'Échec de clôture');
                                                              }
                                                            } catch { alert('Erreur réseau'); }
                                                            btnClose.disabled = false;
                                                          };
                                                        }
                                                      } else {
                                                        if (badge) badge.style.display = 'none';
                                                        if (btnView) btnView.style.display = 'none';
                                                        if (btnClose) btnClose.style.display = 'none';
                                                      }
                                                    }
                                                  } catch {}
                                                }

                                                // Initialiser les badges à l'ouverture de la page
                                                try {
                                                  document.querySelectorAll('tr[data-veh-id]').forEach(function(tr){
                                                    const vid = tr.getAttribute('data-veh-id');
                                                    if (vid) { refreshVehTempBadge(vid); }
                                                  });
                                                } catch {}

                                                const submitPlaqueTemp = document.getElementById('plaqueTemp_submit');
                                                if (submitPlaqueTemp){ submitPlaqueTemp.addEventListener('click', async function(){
                                                  const vehId = document.getElementById('plaqueTemp_veh_id').value.trim();
                                                  const numero = document.getElementById('plaqueTemp_numero').value.trim();
                                                  const du = document.getElementById('plaqueTemp_du').value.trim();
                                                  const au = document.getElementById('plaqueTemp_au').value.trim();
                                                  if (!numero){ alert('Veuillez saisir le numéro temporaire.'); return; }
                                                  try {
                                                    const fd = new FormData(); fd.append('vehicule_id', vehId); fd.append('numero', numero); if (du) fd.append('du', du); if (au) fd.append('au', au);
                                                    const r = await fetch(`/plaque/temporaire`, { method:'POST', body: fd });
                                                    let j = null; try { j = await r.json(); } catch(e){ j = null; }
                                                    if (j && j.ok){
                                                      alert('Plaque temporaire enregistrée.');
                                                      try{ bootstrap.Modal.getOrCreateInstance(document.getElementById('plaqueTempModal')).hide(); }catch{}
                                                      // Refresh badge state on the corresponding row
                                                      refreshVehTempBadge(vehId);
                                                      // Ouvrir la page de prévisualisation de la plaque temporaire
                                                      try {
                                                        const plaqueId = j.id || null;
                                                        const numero = j.numero || null;
                                                        if (plaqueId && numero) {
                                                          // Ouvrir la page de prévisualisation dans un nouvel onglet
                                                          const previewUrl = `/plaque-temporaire/display?id=${encodeURIComponent(plaqueId)}&numero=${encodeURIComponent(numero)}`;
                                                          window.open(previewUrl, '_blank');
                                                        } else {
                                                          // Fallback: téléchargement automatique du PDF si disponible
                                                          const pdfUrl = j.pdf || null;
                                                          if (pdfUrl) {
                                                            const a = document.createElement('a');
                                                            a.href = pdfUrl;
                                                            a.target = '_blank';
                                                            if (j.filename) { a.download = j.filename; }
                                                            document.body.appendChild(a);
                                                            a.click();
                                                            a.remove();
                                                            try { window.open(pdfUrl, '_blank'); } catch {}
                                                          }
                                                        }
                                                      } catch {}
                                                    }
                                                    else {
                                                      // Afficher le message précis côté backend si disponible
                                                      let msg = '';
                                                      if (j && (j.error || j.message)) {
                                                        msg = String(j.error || j.message);
                                                      } else if (!r.ok) {
                                                        msg = `Erreur serveur (${r.status} ${r.statusText})`;
                                                      } else {
                                                        msg = 'Échec enregistrement plaque temporaire';
                                                      }
                                                      alert(msg);
                                                    }
                                                  } catch { alert('Erreur réseau'); }
                                                }); }
                                              })();
                                            </script>

                                            <div class="tab-pane" id="particuliers">
                                                <div class="card mt-2">
                                                    <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <h5 class="card-title mb-0"><i class="ri-contacts-book-2-line me-2"></i>Particuliers <span class="badge bg-secondary ms-2"><?php echo isset($particuliers) && is_array($particuliers) ? count($particuliers) : 0; ?></span></h5>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <input type="text" id="part-filter" class="form-control form-control-sm" placeholder="Filtrer (nom, n° national, email...)" style="min-width:240px;">
                                                            <select id="part-sort" class="form-select form-select-sm" style="min-width:220px;">
                                                                <option value="">Trier...</option>
                                                                <option value="nom_asc">Nom A→Z</option>
                                                                <option value="nom_desc">Nom Z→A</option>
                                                                <option value="num_asc">N° national A→Z</option>
                                                                <option value="num_desc">N° national Z→A</option>
                                                            </select>
                                                            <?php $currPPP = isset($part_pagination['per_page']) ? (int)$part_pagination['per_page'] : 20; ?>
                                                            <select id="part-per-page" class="form-select form-select-sm" title="Par page" style="min-width:140px;">
                                                                <option value="10" <?php echo $currPPP===10?'selected':''; ?>>10 / page</option>
                                                                <option value="20" <?php echo $currPPP===20?'selected':''; ?>>20 / page</option>
                                                                <option value="50" <?php echo $currPPP===50?'selected':''; ?>>50 / page</option>
                                                                <option value="100" <?php echo $currPPP===100?'selected':''; ?>>100 / page</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0" id="particuliers-table">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width:60px;">#</th>
                                                                        <th style="width:68px;">Photo</th>
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
                                                                            data-photo="<?php echo htmlspecialchars($p['photo'] ?? '', ENT_QUOTES); ?>"
                                                                        >
                                                                            <td><?php echo $idxp++; ?></td>
                                                                            <td>
                                                                                <?php $ph = (string)($p['photo'] ?? ''); ?>
                                                                                <?php if ($ph !== ''): ?>
                                                                                    <img src="<?php echo htmlspecialchars($ph, ENT_QUOTES); ?>" alt="Photo" style="width:48px; height:48px; object-fit:cover;" class="rounded">
                                                                                <?php else: ?>
                                                                                    <div class="bg-light text-muted d-inline-flex align-items-center justify-content-center rounded" style="width:48px; height:48px;">—</div>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td class="p-nom"><?php echo htmlspecialchars($p['nom'] ?? ''); ?> <span class="badge bg-danger ms-1 d-none arrest-badge">Arrêté</span></td>
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
                                                                    <?php endforeach; } else { ?>
                                                                        <tr>
                                                                            <td colspan="9" class="text-center text-muted">Aucun particulier</td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <?php 
                                                        // Pagination Entreprises (section #entreprises)
                                                        $pg = isset($ent_pagination) && is_array($ent_pagination) ? $ent_pagination : null;
                                                        if ($pg) {
                                                            $p = (int)($pg['page'] ?? 1);
                                                            $pp = (int)($pg['per_page'] ?? 20);
                                                            $tp = (int)($pg['total_pages'] ?? 1);
                                                            $tot = (int)($pg['total'] ?? 0);
                                                            $prev = max(1, $p - 1);
                                                            $next = min($tp, $p + 1);
                                                            $qs = $_GET;
                                                            $qs['ent_per_page'] = $pp;
                                                            $qsFirst = $qs; $qsFirst['ent_page'] = 1;
                                                            $qsPrev  = $qs; $qsPrev['ent_page']  = $prev;
                                                            $qsNext  = $qs; $qsNext['ent_page']  = $next;
                                                            $qsLast  = $qs; $qsLast['ent_page']  = $tp;
                                                            $mk = function($arr){ return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($arr) . '#entreprises'); };
                                                        ?>
                                                        <div class="d-flex align-items-center justify-content-between p-2 border-top flex-wrap gap-2">
                                                            <div class="small text-muted">
                                                                Page <strong><?php echo $p; ?></strong> / <?php echo $tp; ?> ·
                                                                Affichées <strong><?php echo count($entreprises); ?></strong> sur <strong><?php echo $tot; ?></strong>
                                                            </div>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a class="btn btn-light <?php echo $p<=1?'disabled':''; ?>" href="<?php echo $mk($qsFirst); ?>">« Première</a>
                                                                <a class="btn btn-light <?php echo $p<=1?'disabled':''; ?>" href="<?php echo $mk($qsPrev); ?>">‹ Précédent</a>
                                                                <?php 
                                                                // Numérotation des pages (fenêtre autour de la page courante)
                                                                $winE = 2; 
                                                                $startE = max(1, $p - $winE);
                                                                $endE = min($tp, $p + $winE);
                                                                if ($endE - $startE < $winE*2) {
                                                                    $missingE = $winE*2 - ($endE - $startE);
                                                                    $startE = max(1, $startE - $missingE);
                                                                    $endE = min($tp, $endE + ($winE*2 - ($endE - $startE)));
                                                                }
                                                                for ($ei=$startE; $ei<=$endE; $ei++) {
                                                                    $qse = $qs; $qse['ent_page'] = $ei;
                                                                    $activeE = $ei === $p ? 'active' : '';
                                                                    echo '<a class="btn btn-light '.$activeE.'" href="'.$mk($qse).'">'.$ei.'</a>';
                                                                }
                                                                ?>
                                                                <a class="btn btn-light <?php echo $p>=$tp?'disabled':''; ?>" href="<?php echo $mk($qsNext); ?>">Suivant ›</a>
                                                                <a class="btn btn-light <?php echo $p>=$tp?'disabled':''; ?>" href="<?php echo $mk($qsLast); ?>">Dernière »</a>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
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
                                                        markArrestedRows();
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
                                                        markArrestedRows();
                                                    }
                                                    // Badge "Arrêté" sur chaque ligne si le particulier a des arrestations
                                                    const __arrestCache = new Map();
                                                    function markArrestedRows(){
                                                        if (!tbody) return;
                                                        const trs = Array.from(tbody.querySelectorAll('tr')).filter(tr=> tr.style.display !== 'none');
                                                        trs.forEach(tr=>{
                                                            const pid = tr.getAttribute('data-id');
                                                            const badge = tr.querySelector('.arrest-badge');
                                                            if (!pid || !badge) return;
                                                            if (__arrestCache.has(pid)){
                                                                if (__arrestCache.get(pid)) badge.classList.remove('d-none');
                                                                return;
                                                            }
                                                            fetch(`/particulier/${encodeURIComponent(pid)}/arrestations`)
                                                                .then(r=>r.json())
                                                                .then(j=>{
                                                                    const rows = (j && j.ok && Array.isArray(j.items)) ? j.items : [];
                                                                    const now = new Date();
                                                                    const hasActive = rows.some(r=>{
                                                                        const ds = r && r.date_sortie_prison ? new Date(r.date_sortie_prison) : null;
                                                                        return !ds || (ds instanceof Date && !isNaN(ds) && ds > now);
                                                                    });
                                                                    __arrestCache.set(pid, hasActive);
                                                                    if (hasActive) badge.classList.remove('d-none');
                                                                })
                                                                .catch(()=>{ __arrestCache.set(pid, false); });
                                                        });
                                                    }
                                                    // Détails modal
                                                    function fillParticulierModalFromRow(tr){
                                                        const get = (k)=> tr.getAttribute('data-'+k) || '';
                                                        const pid = tr.getAttribute('data-id') || '';
                                                        try { window.__currentParticulierId = pid; } catch {}
                                                        const detailsModalEl = document.getElementById('particulierDetailsModal');
                                                        if (detailsModalEl) { detailsModalEl.setAttribute('data-id', pid); }
                                                        const pnumero = get('numero_national');
                                                        const setText = (id, val)=>{ const el = document.getElementById(id); if (el) el.textContent = val || ''; };
                                                        setText('pt_nom', get('nom'));
                                                        // Photo
                                                        (function(){
                                                          const img = document.getElementById('pt_photo');
                                                          if (!img) return;
                                                          const src = get('photo');
                                                          if (src) {
                                                            img.src = src;
                                                            img.classList.remove('d-none');
                                                          } else {
                                                            img.src = '';
                                                            img.classList.add('d-none');
                                                          }
                                                        })();
                                                        setText('pt_numero_national', get('numero_national'));
                                                        try { setText('pt_date_naissance', window.formatDMY(get('date_naissance'))); } catch { setText('pt_date_naissance', get('date_naissance')); }
                                                        setText('pt_genre', get('genre'));
                                                        setText('pt_etat_civil', get('etat_civil'));
                                                        setText('pt_adresse', get('adresse'));
                                                        setText('pt_profession', get('profession'));
                                                        setText('pt_personne_contact', get('personne_contact'));
                                                        setText('pt_gsm', get('gsm'));
                                                        setText('pt_email', get('email'));
                                                        setText('pt_nationalite', get('nationalite'));
                                                        setText('pt_lieu_naissance', get('lieu_naissance'));
                                                        setText('pt_observations', get('observations'));
                                                        const tbodyCv = document.getElementById('pt_cv_tbody');
                                                        if (tbodyCv) { tbodyCv.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Chargement...</td></tr>'; }
                                                        const vehTbody = document.getElementById('pt_veh_tbody');
                                                        const vehCount = document.getElementById('pt_veh_count');
                                                        if (vehTbody) { vehTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Chargement...</td></tr>'; }
                                                        if (vehCount) { vehCount.textContent = '0'; }
                                                        const avisBanner = document.getElementById('pt_avis_banner');
                                                        const avisText = document.getElementById('pt_avis_text');
                                                        const btnCloseAvis = document.getElementById('pt_btn_close_avis');
                                                        const permisBanner = document.getElementById('pt_permis_banner');
                                                        const permisText = document.getElementById('pt_permis_text');
                                                        const permisBadge = document.getElementById('pt_permis_badge');
                                                        const btnClosePermis = document.getElementById('pt_btn_close_permis');
                                                        if (avisBanner) avisBanner.classList.add('d-none');
                                                        if (avisText) avisText.textContent = '';
                                                        if (btnCloseAvis) btnCloseAvis.setAttribute('data-avis-id','');
                                                        if (permisBanner) { permisBanner.classList.add('d-none'); permisBanner.classList.remove('alert-danger'); permisBanner.classList.add('alert-success'); }
                                                        // Badge Arrestation: masqué par défaut puis affiché si des arrestations existent
                                                        const arrestBadge = document.getElementById('pt_arrest_badge');
                                                        if (arrestBadge) arrestBadge.classList.add('d-none');
                                                        // Charger arrestations
                                                        if (pid) {
                                                            fetch(`/particulier/${encodeURIComponent(pid)}/arrestations`)
                                                              .then(r=>r.json())
                                                              .then(j=>{
                                                                try {
                                                                  const list = (j && j.ok && Array.isArray(j.items)) ? j.items : [];
                                                                  if (list.length > 0 && arrestBadge) arrestBadge.classList.remove('d-none');
                                                                  // Rendu tableau Arrestations
                                                                  const tb = document.getElementById('pt_arrest_tbody');
                                                                  if (tb) {
                                                                    if (list.length === 0) { tb.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Aucune arrestation</td></tr>'; }
                                                                    else {
                                                                      tb.innerHTML = '';
                                                                      list.forEach((ar, idx)=>{
                                                                        const trEl = document.createElement('tr');
                                                                        const dt = ar.date_arrestation || ar.date || '';
                                                                        const motif = ar.motif || '';
                                                                        const ds = ar.date_sortie_prison || '';
                                                                        const fmt = (s)=> (window.formatDMY ? (window.formatDMY(s) || s) : s);
                                                                        trEl.innerHTML = `
                                                                          <td>${idx+1}</td>
                                                                          <td>${fmt(dt)}</td>
                                                                          <td>${motif}</td>
                                                                          <td>${ds ? fmt(ds) : '<span class="badge bg-danger">En détention</span>'}</td>
                                                                        `;
                                                                        tb.appendChild(trEl);
                                                                      });
                                                                    }
                                                                  }
                                                                } catch {}
                                                              })
                                                              .catch(()=>{});
                                                        }
                                                        if (permisText) permisText.textContent = '';
                                                        if (permisBadge) permisBadge.classList.add('d-none');
                                                        if (btnClosePermis) btnClosePermis.setAttribute('data-permis-id','');
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
                                                                    const rawDate = cv.date ?? cv.date_infraction ?? cv.dateContravention ?? '';
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

                                                            // Charger permis temporaires pour ce particulier
                                                            fetch(`/particulier/${pid}/permis-temporaire`).then(r=>r.json()).then(json=>{
                                                                if (!json || !json.ok) return;
                                                                const list = Array.isArray(json.data) ? json.data : [];
                                                                const active = list.find(p=> (p.statut||'') === 'actif');
                                                                if (active && permisBanner) {
                                                                    const numero = active.numero || '';
                                                                    const dd = active.date_debut || active.dateDebut || '';
                                                                    const df = active.date_fin || active.dateFin || '';
                                                                    const ddDisp = window.formatDMY ? (window.formatDMY(dd) || dd) : dd;
                                                                    const dfDisp = window.formatDMY ? (window.formatDMY(df) || df) : df;
                                                                    if (permisText) permisText.textContent = `${numero ? 'N° ' + numero + ' — ' : ''}du ${ddDisp} au ${dfDisp}`;
                                                                    if (btnClosePermis) btnClosePermis.setAttribute('data-permis-id', String(active.id||''));
                                                                    // Determine expiration (compare date_fin to today date-only)
                                                                    let isExpired = false;
                                                                    try {
                                                                        if (df) {
                                                                            const t = new Date(); const today = new Date(t.getFullYear(), t.getMonth(), t.getDate());
                                                                            const end = new Date(df);
                                                                            const endDateOnly = new Date(end.getFullYear(), end.getMonth(), end.getDate());
                                                                            isExpired = endDateOnly < today;
                                                                        }
                                                                    } catch {}
                                                                    if (isExpired) {
                                                                        if (permisBadge) permisBadge.classList.remove('d-none');
                                                                        permisBanner.classList.remove('alert-success');
                                                                        permisBanner.classList.add('alert-danger');
                                                                    } else {
                                                                        if (permisBadge) permisBadge.classList.add('d-none');
                                                                        permisBanner.classList.remove('alert-danger');
                                                                        permisBanner.classList.add('alert-success');
                                                                    }
                                                                    permisBanner.classList.remove('d-none');
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
                                                    // Helper pour récupérer l'ID courant du particulier
                                                    window.__getCurrentParticulierId = function(){
                                                        const fromGlobal = window.__currentParticulierId || '';
                                                        const fromDetails = document.getElementById('particulierDetailsModal')?.getAttribute('data-id') || '';
                                                        const fromActions = document.getElementById('particulierActionsModal')?.getAttribute('data-dossier-id') || '';
                                                        const fromCache = (window.__lastParticulierCtx && window.__lastParticulierCtx.id) ? window.__lastParticulierCtx.id : '';
                                                        let fromStorage = '';
                                                        try { fromStorage = JSON.parse(localStorage.getItem('recent_particulier_ctx')||'{}')?.id || ''; } catch {}
                                                        return fromGlobal || fromDetails || fromActions || fromCache || fromStorage || '';
                                                    };
                                                    // Helper global pour ouvrir le modal Permis
                                                    window.__openPermisModal = function(pid){
                                                        if (!pid) { alert('Veuillez d\'abord ouvrir les détails du particulier.'); return; }
                                                        let modalEl = document.getElementById('launchPermisModal');
                                                        if (!modalEl) {
                                                            modalEl = document.createElement('div');
                                                            modalEl.id = 'launchPermisModal';
                                                            modalEl.className = 'modal fade';
                                                            modalEl.tabIndex = -1;
                                                            modalEl.setAttribute('aria-hidden','true');
                                                            modalEl.innerHTML = `
                                                              <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h5 class="modal-title"><i class=\"ri-id-card-line me-2\"></i>Émettre un permis temporaire</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form id="launchPermisForm">
                                                                  <div class="modal-body">
                                                                    <div class="mb-3">
                                                                      <label class="form-label">Motif</label>
                                                                      <textarea name="motif" class="form-control" rows="3" placeholder="Expliquer le motif" required></textarea>
                                                                    </div>
                                                                    <div class="row g-3">
                                                                      <div class="col-md-6">
                                                                        <label class="form-label">Date de début</label>
                                                                        <input type="date" name="date_debut" class="form-control" required>
                                                                      </div>
                                                                      <div class="col-md-6">
                                                                        <label class="form-label">Durée</label>
                                                                        <select name="duree" class="form-select">
                                                                          <option value="7">7 jours</option>
                                                                          <option value="14" selected>14 jours</option>
                                                                          <option value="30">30 jours</option>
                                                                        </select>
                                                                      </div>
                                                                    </div>
                                                                    <div class="alert mt-3 d-none" id="pt_feedback_permis"></div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-success" id="pt_submit_permis">Émettre</button>
                                                                  </div>
                                                                  </form>
                                                                </div>
                                                              </div>`;
                                                            document.body.appendChild(modalEl);
                                                        }
                                                        modalEl.setAttribute('data-pid', pid);
                                                        // Valeurs par défaut
                                                        const today = new Date();
                                                        const yyyy = today.getFullYear(); const mm = String(today.getMonth()+1).padStart(2,'0'); const dd = String(today.getDate()).padStart(2,'0');
                                                        const dateStr = `${yyyy}-${mm}-${dd}`;
                                                        const f = modalEl.querySelector('#launchPermisForm');
                                                        if (f) {
                                                          const d1 = f.querySelector('[name="date_debut"]'); if (d1) d1.value = dateStr;
                                                          const dur = f.querySelector('[name="duree"]'); if (dur) dur.value = '14';
                                                          const motif = f.querySelector('[name="motif"]'); if (motif) motif.value = '';
                                                          const fb = modalEl.querySelector('#pt_feedback_permis'); if (fb) { fb.className = 'alert d-none'; fb.textContent = ''; }
                                                        }
                                                        if (modalEl.parentElement && modalEl.parentElement !== document.body) document.body.appendChild(modalEl);
                                                        try { bootstrap.Modal.getOrCreateInstance(modalEl).show(); } catch {}
                                                    };
                                                    // Fallback onclick depuis le bouton
                                                    window.__launchPermisFromBtn = function(ev, el){
                                                        try { ev && ev.preventDefault(); ev && ev.stopPropagation(); } catch {}
                                                        const pid = (el && el.getAttribute && el.getAttribute('data-dossier-id')) || window.__getCurrentParticulierId();
                                                        window.__openPermisModal(pid);
                                                        return false;
                                                    };
                                                    // Ouvrir le modal de création de permis temporaire (delegation)
                                                    document.addEventListener('click', (e)=>{
                                                        const btn = e.target.closest('#pt_btn_launch_permis, #pt_btn_launch_permis_card, .btn-launch-permis, [data-action="launch-permis"]'); if (!btn) return;
                                                        e.preventDefault(); e.stopPropagation();
                                                        const pid = btn.getAttribute('data-dossier-id') || window.__getCurrentParticulierId();
                                                        window.__openPermisModal(pid);
                                                    });
                                                    // Soumission création permis
                                                    document.addEventListener('submit', async (e)=>{
                                                        const form = e.target.closest('#launchPermisForm'); if (!form) return;
                                                        e.preventDefault();
                                                        const modalEl = document.getElementById('launchPermisModal');
                                                        const pid = modalEl?.getAttribute('data-pid') || window.__currentParticulierId || '';
                                                        const fb = modalEl?.querySelector('#pt_feedback_permis');
                                                        if (!pid) { alert('Particulier introuvable'); return; }
                                                        const fd = new FormData(form);
                                                        const motif = (fd.get('motif')||'').toString().trim();
                                                        const dd = (fd.get('date_debut')||'').toString();
                                                        const duree = parseInt((fd.get('duree')||'14').toString(),10) || 14;
                                                        if (!motif || !dd) { alert('Veuillez renseigner le motif et la date de début'); return; }
                                                        // Calcul date_fin
                                                        const d = new Date(dd);
                                                        if (isNaN(d.getTime())) { alert('Date de début invalide'); return; }
                                                        const d2 = new Date(d.getTime()); d2.setDate(d2.getDate() + duree);
                                                        const yyyy2 = d2.getFullYear(); const mm2 = String(d2.getMonth()+1).padStart(2,'0'); const day2 = String(d2.getDate()).padStart(2,'0');
                                                        const df = `${yyyy2}-${mm2}-${day2}`;
                                                        // UI state
                                                        const submitBtn = modalEl?.querySelector('#pt_submit_permis'); if (submitBtn) submitBtn.disabled = true;
                                                        if (fb) { fb.className = 'alert alert-info'; fb.textContent = 'Emission en cours...'; }
                                                        try {
                                                          const body = new URLSearchParams({ cible_type: 'particulier', cible_id: String(pid), motif, date_debut: dd, date_fin: df }).toString();
                                                          const resp = await fetch('/permis-temporaire', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' }, body });
                                                          const data = await resp.json();
                                                          if (!resp.ok || !data.ok) throw new Error(data.error||'Erreur serveur');
                                                          if (fb) { fb.className = 'alert alert-success'; fb.textContent = 'Permis temporaire émis avec succès'; }
                                                          try { window.showSuccess && window.showSuccess('Permis temporaire émis'); } catch {}
                                                          // Ouvrir la page de prévisualisation du permis temporaire
                                                          try {
                                                            const permisId = data.id || data.permis_id || '';
                                                            const numero = data.numero || '';
                                                            if (permisId && numero) {
                                                              // Ouvrir la page de prévisualisation dans un nouvel onglet
                                                              const previewUrl = `/permis-temporaire/display?id=${encodeURIComponent(permisId)}&numero=${encodeURIComponent(numero)}`;
                                                              window.open(previewUrl, '_blank', 'noopener,noreferrer');
                                                            } else if (permisId) {
                                                              // Fallback avec seulement l'ID
                                                              const previewUrl = `/permis-temporaire/display?id=${encodeURIComponent(permisId)}`;
                                                              window.open(previewUrl, '_blank', 'noopener,noreferrer');
                                                            }
                                                          } catch {}
                                                          try { form.reset(); } catch {}
                                                          setTimeout(()=>{ try { bootstrap.Modal.getInstance(modalEl)?.hide(); } catch {} }, 600);
                                                          // Rafraîchir la bannière
                                                          const tr = document.querySelector(`tr[data-id="${pid}"]`);
                                                          if (tr) fillParticulierModalFromRow(tr);
                                                        } catch(err){
                                                          if (fb) { fb.className = 'alert alert-danger'; fb.textContent = err.message || 'Erreur inconnue'; }
                                                        } finally {
                                                          if (submitBtn) submitBtn.disabled = false;
                                                        }
                                                    });
                                                    // Clôture du permis temporaire actif
                                                    document.addEventListener('click', async (e)=>{
                                                        const btn = e.target.closest('#pt_btn_close_permis'); if (!btn) return;
                                                        const id = btn.getAttribute('data-permis-id')||''; if (!id) return;
                                                        if (!confirm('Confirmer la clôture de ce permis temporaire ?')) return;
                                                        try {
                                                          const resp = await fetch(`/permis-temporaire/${id}/close`, { method: 'POST' });
                                                          const data = await resp.json();
                                                          if (!resp.ok || !data.ok) throw new Error(data.error||'Erreur serveur');
                                                          const banner = document.getElementById('pt_permis_banner'); if (banner) banner.classList.add('d-none');
                                                          try { window.showSuccess && window.showSuccess('Permis temporaire clôturé'); } catch {}
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
                                                        // Also tag the action buttons inside actions modal with the dossier id
                                                        const btnPermisCard = document.getElementById('pt_btn_launch_permis_card');
                                                        if (btnPermisCard) btnPermisCard.setAttribute('data-dossier-id', id);
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
                                                    // When the Particuliers tab becomes visible, re-apply filter/sort
                                                    document.addEventListener('shown.bs.tab', (ev)=>{
                                                        try {
                                                            const tgt = ev.target?.getAttribute?.('href') || ev.target?.getAttribute?.('data-bs-target');
                                                            if (tgt === '#particuliers') { applyFilter(); applySort(); }
                                                        } catch(_) {}
                                                    });
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
                                                            <?php $currPP = isset($ent_pagination['per_page']) ? (int)$ent_pagination['per_page'] : 20; ?>
                                                            <select id="ent-per-page" class="form-select form-select-sm" title="Par page" style="min-width:140px;">
                                                                <option value="10" <?php echo $currPP===10?'selected':''; ?>>10 / page</option>
                                                                <option value="20" <?php echo $currPP===20?'selected':''; ?>>20 / page</option>
                                                                <option value="50" <?php echo $currPP===50?'selected':''; ?>>50 / page</option>
                                                                <option value="100" <?php echo $currPP===100?'selected':''; ?>>100 / page</option>
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
                                                        <?php 
                                                        // Pagination Entreprises (correct placement in #entreprises tab)
                                                        $pg = isset($ent_pagination) && is_array($ent_pagination) ? $ent_pagination : null;
                                                        if ($pg) {
                                                            $p = (int)($pg['page'] ?? 1);
                                                            $pp = (int)($pg['per_page'] ?? 20);
                                                            $tp = (int)($pg['total_pages'] ?? 1);
                                                            $tot = (int)($pg['total'] ?? 0);
                                                            $prev = max(1, $p - 1);
                                                            $next = min($tp, $p + 1);
                                                            $qs = $_GET;
                                                            $qs['ent_per_page'] = $pp;
                                                            $qsFirst = $qs; $qsFirst['ent_page'] = 1;
                                                            $qsPrev  = $qs; $qsPrev['ent_page']  = $prev;
                                                            $qsNext  = $qs; $qsNext['ent_page']  = $next;
                                                            $qsLast  = $qs; $qsLast['ent_page']  = $tp;
                                                            $mk = function($arr){ return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($arr) . '#entreprises'); };
                                                        ?>
                                                        <div class="d-flex align-items-center justify-content-between p-2 border-top flex-wrap gap-2">
                                                            <div class="small text-muted">
                                                                Page <strong><?php echo $p; ?></strong> / <?php echo $tp; ?> ·
                                                                Affichées <strong><?php echo count($entreprises); ?></strong> sur <strong><?php echo $tot; ?></strong>
                                                            </div>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a class="btn btn-light <?php echo $p<=1?'disabled':''; ?>" href="<?php echo $mk($qsFirst); ?>">« Première</a>
                                                                <a class="btn btn-light <?php echo $p<=1?'disabled':''; ?>" href="<?php echo $mk($qsPrev); ?>">‹ Précédent</a>
                                                                <?php 
                                                                $winE = 2; 
                                                                $startE = max(1, $p - $winE);
                                                                $endE = min($tp, $p + $winE);
                                                                if ($endE - $startE < $winE*2) {
                                                                    $missingE = $winE*2 - ($endE - $startE);
                                                                    $startE = max(1, $startE - $missingE);
                                                                    $endE = min($tp, $endE + ($winE*2 - ($endE - $startE)));
                                                                }
                                                                for ($ei=$startE; $ei<=$endE; $ei++) {
                                                                    $qse = $qs; $qse['ent_page'] = $ei;
                                                                    $activeE = $ei === $p ? 'active' : '';
                                                                    echo '<a class="btn btn-light '.$activeE.'" href="'.$mk($qse).'">'.$ei.'</a>';
                                                                }
                                                                ?>
                                                                <a class="btn btn-light <?php echo $p>=$tp?'disabled':''; ?>" href="<?php echo $mk($qsNext); ?>">Suivant ›</a>
                                                                <a class="btn btn-light <?php echo $p>=$tp?'disabled':''; ?>" href="<?php echo $mk($qsLast); ?>">Dernière »</a>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
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
                                                                                        <th>Document</th>
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
                                                        document.getElementById('ent_secteur').textContent = get('secteur');
                                                        document.getElementById('ent_obs').textContent = get('observations');
                                                        const tcv = document.querySelector('#ent_table_contravs tbody');
                                                        if (tcv){ tcv.innerHTML = ''; const list = CONTRAVS_ENT[eid] || []; if(list.length===0){ tcv.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Aucune contravention</td></tr>'; return; }
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
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-outline-primary view-ent-contrav-pdf" data-contrav-id="${cv.id}" title="Voir le PDF">
                                                                            <i class="ri-eye-line"></i>
                                                                        </button>
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
                                                    
                                                    // PDF viewing handler for entreprise contraventions (delegated)
                                                    document.addEventListener('click', function(e){
                                                      const btn = e.target.closest && e.target.closest('.view-ent-contrav-pdf');
                                                      if (!btn) return;
                                                      const contraventionId = btn.getAttribute('data-contrav-id');
                                                      if (!contraventionId) {
                                                        alert('ID de contravention manquant');
                                                        return;
                                                      }
                                                      // Open PDF in new window/tab
                                                      const pdfUrl = `/uploads/contraventions/contravention_${contraventionId}.pdf`;
                                                      window.open(pdfUrl, '_blank');
                                                    });
                                                    
                                                    // Initial
                                                    applyFilter();
                                                })();
                                                </script>

                                            
                                      
                                                
                                                
                                            </div><!-- end timeline content-->
    
                                        </div><!-- end tab-content -->
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
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
                          <button type="button" class="btn btn-sm btn-primary" id="pt_btn_launch_permis_card" data-action="launch-permis" onclick="return window.__launchPermisFromBtn ? window.__launchPermisFromBtn(event, this) : false;">Émettre maintenant</button>
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
                          <div>
                            <button type="button" class="btn btn-sm btn-danger" id="pa_action_arrest_btn" data-action="arrestation">Consigner une arrestation</button>
                          </div>
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

        <!-- Modal: Arrestation de l'individu -->
        <div class="modal fade" id="arrestationModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Consigner une arrestation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
              </div>
              <div class="modal-body">
                <form id="arrestationForm">
                  <input type="hidden" name="particulier_id" id="arr_particulier_id">
                  <div class="mb-3">
                    <label class="form-label">Date et heure</label>
                    <input type="datetime-local" class="form-control" name="date_arrestation" id="arr_datetime">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Date de sortie de prison (optionnel)</label>
                    <input type="datetime-local" class="form-control" name="date_sortie_prison" id="arr_date_sortie">
                    <div class="form-text">Laisser vide si la personne est toujours détenue.</div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Lieu (optionnel)</label>
                    <input type="text" class="form-control" name="lieu" id="arr_lieu" placeholder="Ex: Rond-point Victoire">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Motif</label>
                    <textarea class="form-control" name="motif" id="arr_motif" rows="3" placeholder="Décrivez le motif de l'interpellation" required></textarea>
                  </div>
                </form>
                <div class="alert d-none" id="arr_alert"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="arr_submit_btn">Enregistrer</button>
              </div>
            </div>
          </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function(){
          // Open arrestation modal with context from actions modal
          document.addEventListener('click', function(e){
            const btn = e.target.closest('#pa_action_arrest_btn');
            if (!btn) return;
            e.preventDefault();
            const modalActions = document.getElementById('particulierActionsModal');
            const pid = modalActions ? modalActions.getAttribute('data-dossier-id') : (window.__lastParticulierCtx && window.__lastParticulierCtx.id);
            if (!pid) { alert('Veuillez sélectionner un particulier.'); return; }
            const now = new Date();
            const pad = (n)=> String(n).padStart(2,'0');
            const localISO = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
            const form = document.getElementById('arrestationForm');
            if (form) { form.reset(); }
            document.getElementById('arr_particulier_id').value = String(pid);
            document.getElementById('arr_datetime').value = localISO;
            // Clear and disable release date on creation
            const sortieInput = document.getElementById('arr_date_sortie');
            if (sortieInput) { sortieInput.value = ''; sortieInput.setAttribute('disabled','disabled'); }
            const alertBox = document.getElementById('arr_alert');
            if (alertBox) { alertBox.className = 'alert d-none'; alertBox.textContent=''; }
            try { bootstrap.Modal.getOrCreateInstance(document.getElementById('arrestationModal')).show(); } catch {}
          });

          // Submit arrestation
          const arrSubmit = document.getElementById('arr_submit_btn');
          if (arrSubmit) {
            arrSubmit.addEventListener('click', async function(){
              const form = document.getElementById('arrestationForm');
              const alertBox = document.getElementById('arr_alert');
              if (!form) return;
              const fd = new FormData(form);
              // Ensure no release date is sent at creation
              try { fd.delete('date_sortie_prison'); } catch {}
              const motif = (fd.get('motif')||'').toString().trim();
              if (!motif) {
                if (alertBox) { alertBox.className='alert alert-warning'; alertBox.textContent='Veuillez saisir le motif.'; alertBox.classList.remove('d-none'); }
                return;
              }
              try {
                const resp = await fetch('/arrestation', { method:'POST', body: fd });
                const data = await resp.json().catch(()=>({ok:false,error:'Invalid response'}));
                if (data && (data.ok || data.state === true)) {
                  if (alertBox) { alertBox.className='alert alert-success'; alertBox.textContent='Arrestation enregistrée.'; alertBox.classList.remove('d-none'); }
                  setTimeout(()=>{ try { bootstrap.Modal.getOrCreateInstance(document.getElementById('arrestationModal')).hide(); } catch {} }, 600);
                } else {
                  const msg = (data && (data.error||data.message)) || 'Erreur lors de l\'enregistrement';
                  if (alertBox) { alertBox.className='alert alert-danger'; alertBox.textContent=msg; alertBox.classList.remove('d-none'); }
                }
              } catch (e) {
                if (alertBox) { alertBox.className='alert alert-danger'; alertBox.textContent='Erreur réseau'; alertBox.classList.remove('d-none'); }
              }
            });
          }
        });
        </script>

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
                    <div class="col-12">
                      <label class="form-label">Photos de preuve</label>
                      <input type="file" class="form-control" id="contrav_photos" name="photos[]" multiple accept="image/*">
                      <div class="form-text">
                        Sélectionnez une ou plusieurs photos comme preuve de la contravention (formats: JPG, PNG, GIF)
                        <span id="photo_count_indicator" class="badge bg-primary ms-2 d-none"></span>
                      </div>
                      <div id="photo_preview_container" class="mt-3 d-none">
                        <div class="row g-2" id="photo_preview_grid"></div>
                      </div>
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
              // pass friendly name shown to the user so the PDF can display it
              const targetLabel = (document.getElementById('ac_target_label')?.textContent || '').trim();
              if (targetLabel) formData.set('nom', targetLabel);
              // Add selected photos to form data
              const photos = window.getContraventionPhotos ? window.getContraventionPhotos() : [];
              photos.forEach((photo, index) => {
                formData.append('photos[]', photo);
              });
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
              // Clear photo previews
              if (window.clearContraventionPhotos) window.clearContraventionPhotos();
              feedback.classList.remove('alert-info');
              feedback.classList.add('alert-success');
              feedback.textContent = 'Contravention enregistrée avec succès';
              // Auto-download PDF if backend returned a URL
              try {
                const pdfUrl = (data && typeof data.pdf === 'string') ? data.pdf : null;
                if (pdfUrl) {
                  const a = document.createElement('a');
                  a.href = pdfUrl;
                  a.download = '';
                  a.rel = 'noopener';
                  a.target = '_blank';
                  document.body.appendChild(a);
                  a.click();
                  setTimeout(()=>{ try { document.body.removeChild(a); } catch(_){} }, 1000);
                }
              } catch(_) { /* no-op */ }
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

        // Photo preview functionality for contravention form
        (function(){
          const photoInput = document.getElementById('contrav_photos');
          const previewContainer = document.getElementById('photo_preview_container');
          const previewGrid = document.getElementById('photo_preview_grid');
          const countIndicator = document.getElementById('photo_count_indicator');
          let selectedFiles = [];

          if (!photoInput || !previewContainer || !previewGrid) return;

          function updatePhotoCount() {
            if (selectedFiles.length > 0) {
              countIndicator.textContent = `${selectedFiles.length} photo${selectedFiles.length > 1 ? 's' : ''} sélectionnée${selectedFiles.length > 1 ? 's' : ''}`;
              countIndicator.classList.remove('d-none');
              previewContainer.classList.remove('d-none');
            } else {
              countIndicator.classList.add('d-none');
              previewContainer.classList.add('d-none');
            }
          }

          photoInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
              if (file.type.startsWith('image/')) {
                selectedFiles.push(file);
                addPhotoPreview(file, selectedFiles.length - 1);
              }
            });
            
            updatePhotoCount();
            
            // Clear the input to allow re-selecting the same file
            photoInput.value = '';
          });

          function addPhotoPreview(file, index) {
            const reader = new FileReader();
            reader.onload = function(e) {
              const col = document.createElement('div');
              col.className = 'col-md-3 col-sm-4 col-6';
              col.innerHTML = `
                <div class="position-relative">
                  <img src="${e.target.result}" class="img-thumbnail w-100" style="height: 120px; object-fit: cover;">
                  <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-photo" 
                          data-index="${index}" style="width: 25px; height: 25px; padding: 0; border-radius: 50%;">
                    <i class="ri-close-line" style="font-size: 12px;"></i>
                  </button>
                  <div class="small text-muted mt-1 text-truncate">${file.name}</div>
                </div>
              `;
              previewGrid.appendChild(col);
            };
            reader.readAsDataURL(file);
          }

          // Event delegation for remove buttons
          previewGrid.addEventListener('click', function(e) {
            if (e.target.closest('.remove-photo')) {
              const index = parseInt(e.target.closest('.remove-photo').dataset.index);
              removePhoto(index);
            }
          });

          function removePhoto(index) {
            // Remove from selectedFiles array
            selectedFiles.splice(index, 1);
            
            // Remove preview element
            const previewElements = previewGrid.children;
            for (let i = 0; i <previewElements.length; i++) {
              const btn = previewElements[i].querySelector('.remove-photo');
              if (btn && parseInt(btn.dataset.index) === index) {
                previewElements[i].remove();
                break;
              }
            }
            
            // Update indices for remaining elements
            const remainingBtns = previewGrid.querySelectorAll('.remove-photo');
            remainingBtns.forEach((btn, i) => {
              btn.dataset.index = i;
            });
            
            // Update photo count
            updatePhotoCount();
          }

          // Expose selectedFiles for form submission
          window.getContraventionPhotos = function() {
            return selectedFiles;
          };

          // Clear photos function
          window.clearContraventionPhotos = function() {
            selectedFiles = [];
            previewGrid.innerHTML = '';
            updatePhotoCount();
          };

          // Clear photos when modal is hidden
          const modal = document.getElementById('assignContravModal');
          if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
              selectedFiles = [];
              previewGrid.innerHTML = '';
              previewContainer.classList.add('d-none');
            });
          }
        })();
        </script>

        <!-- Modal liberer Particulier (extrait dans un partial) -->
        <?php require_once 'partials/particulier_liberer_modal.php'; ?>
 
        <!-- Modal details Particulier (extrait dans un partial) -->
        <?php require_once 'partials/particulier_details_modal.php'; ?>
 
        <?php require_once '_partials/_modal_avis_recherche.php'; ?>

         <!-- Include Modal creation de compte agent -->
         <?php require_once '_partials/_modal_agent_account.php'; ?>
        
    </body>

<!-- Mirrored from coderthemes.com/jidox/layouts/layouts-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Jul 2025 09:13:58 GMT -->
</html>