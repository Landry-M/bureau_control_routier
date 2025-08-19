<!DOCTYPE html>
<html lang="fr" data-layout="topnav" data-menu-color="brand">
<head>
    <meta charset="utf-8" />
    <title>Résultats de recherche | Bureau de Contrôle Routier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <link rel="shortcut icon" href="assets/images/logo.jpg">
    <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">
    <link href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/config.js"></script>
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
    <?php require_once '_partials/_topmenu.php'; ?>
    <?php require_once '_partials/_navbar.php'; ?>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box justify-content-between d-flex align-items-md-center flex-md-row flex-column">
                            <h4 class="page-title">Résultats de recherche</h4>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <form class="d-flex me-2" action="/search" method="GET">
                                    <input type="search" class="form-control form-control-sm me-2" placeholder="Rechercher..." name="q" value="<?php echo htmlspecialchars($query ?? ''); ?>" style="min-width:240px;">
                                    <button class="btn btn-sm btn-primary" type="submit"><i class="ri-search-line me-1"></i>Rechercher</button>
                                </form>
                                <div class="btn-group">
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

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <?php if (!empty($_SESSION['error'])): ?>
                                    <div class="alert alert-danger mb-3"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                                <?php endif; ?>

                                <?php if (($query ?? '') === ''): ?>
                                    <div class="alert alert-info">Saisissez un terme de recherche ci-dessus.</div>
                                <?php else: ?>
                                    <?php if (!empty($_GET['plate']) && isset($type) && $type === 'vehicule_plaque' && !empty($vehiculeRecord) && is_array($vehiculeRecord)): ?>
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <h5 class="mb-0"><i class="ri-roadster-line me-2"></i>Véhicule trouvé</h5>
                                            <div class="d-flex gap-2 align-items-center">
                                            <?php if (!empty($vehiculeCandidates) && count($vehiculeCandidates) > 1): ?>
                                                <div class="d-flex align-items-center me-2">
                                                    <label for="vehiculeCandidateSelect" class="me-2 mb-0">Sélection:</label>
                                                    <select id="vehiculeCandidateSelect" class="form-select form-select-sm" style="min-width:240px;">
                                                        <?php
                                                            $pkCol = isset($vehiculePk) && $vehiculePk ? $vehiculePk : 'id';
                                                            foreach ($vehiculeCandidates as $cand):
                                                                $idv = (string)($cand[$pkCol] ?? '');
                                                                $label = trim(($cand['plaque'] ?? '') . ' — ' . ($cand['marque'] ?? '') . ' ' . ($cand['modele'] ?? ''));
                                                        ?>
                                                            <option value="<?= htmlspecialchars($idv) ?>" <?= ($vehiculeRecord && $idv === (string)($vehiculeRecord[$pkCol] ?? '')) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                            <?php $vehId = htmlspecialchars((string)($vehiculeRecord['id'] ?? '')); ?>
                                            <?php $vehLabel = htmlspecialchars((string)($vehiculeRecord['plaque'] ?? ('vehicule #' . $vehId)), ENT_QUOTES); ?>
                                            <button type="button"
                                                class="btn btn-success btn-sm btn-assign-contrav"
                                                data-dossier-type="vehicule_plaque"
                                                data-dossier-id="<?= $vehId ?>"
                                                data-target-label="<?= $vehLabel ?>"
                                                data-bs-toggle="modal" data-bs-target="#assignContravModal">
                                                <i class="ri-police-car-line me-1"></i>Assigner une contravention
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#vehiculeContraventionsModal">
                                                <i class="ri-file-list-3-line me-1"></i>Voir les contraventions
                                            </button>
                                        </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <?php
                                                        $thumb = '';
                                                        if (!empty($vehiculeRecord['images'])) {
                                                            $decoded = json_decode((string)$vehiculeRecord['images'], true);
                                                            if (is_array($decoded) && !empty($decoded[0])) { $thumb = (string)$decoded[0]; }
                                                            elseif (is_string($vehiculeRecord['images'])) { $thumb = (string)$vehiculeRecord['images']; }
                                                        }
                                                        if ($thumb !== '' && strpos($thumb, 'http') !== 0) { $thumb = '/' . ltrim($thumb, '/'); }
                                                    ?>
                                                    <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height:220px;">
                                                        <img id="vp_image" src="<?= $thumb ? htmlspecialchars($thumb) : '' ?>" alt="aperçu" style="max-width:100%;max-height:100%;object-fit:contain; <?= $thumb ? '' : 'display:none;' ?>">
                                                        <i id="vp_icon" class="ri-car-line fs-1 text-muted" style="<?= $thumb ? 'display:none;' : '' ?>"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Plaque</label>
                                                            <input id="vp_plaque" type="text" class="form-control" value="<?= htmlspecialchars((string)($vehiculeRecord['plaque'] ?? '')) ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Marque</label>
                                                            <input id="vp_marque" type="text" class="form-control" value="<?= htmlspecialchars((string)($vehiculeRecord['marque'] ?? '')) ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Modèle</label>
                                                            <input id="vp_modele" type="text" class="form-control" value="<?= htmlspecialchars((string)($vehiculeRecord['modele'] ?? '')) ?>" disabled>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Année</label>
                                                            <input id="vp_annee" type="text" class="form-control" value="<?= htmlspecialchars((string)($vehiculeRecord['annee'] ?? '')) ?>" disabled>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Couleur</label>
                                                            <input id="vp_couleur" type="text" class="form-control" value="<?= htmlspecialchars((string)($vehiculeRecord['couleur'] ?? '')) ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">N° de châssis</label>
                                                            <input id="vp_numero_chassis" type="text" class="form-control" value="<?= htmlspecialchars((string)($vehiculeRecord['numero_chassis'] ?? '')) ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Frontière d'entrée</label>
                                                            <?php $__front = trim((string)($vehiculeRecord['frontiere_entree'] ?? '')); ?>
                                                            <input id="vp_frontiere_entree" type="text" class="form-control" value="<?= $__front !== '' ? htmlspecialchars($__front) : 'N/A' ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Date d'importation</label>
                                                            <?php $__imp = trim((string)($vehiculeRecord['date_importation'] ?? '')); ?>
                                                            <input id="vp_date_importation" type="text" class="form-control" value="<?= $__imp !== '' ? htmlspecialchars($__imp) : 'N/A' ?>" disabled>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Plaque valide le</label>
                                                            <?php $__valP = trim((string)($vehiculeRecord['plaque_valide_le'] ?? '')); ?>
                                                            <input id="vp_plaque_valide_le" type="text" class="form-control" value="<?= $__valP !== '' ? htmlspecialchars($__valP) : 'N/A' ?>" disabled>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Plaque expire le</label>
                                                            <?php $__expP = trim((string)($vehiculeRecord['plaque_expire_le'] ?? '')); ?>
                                                            <input id="vp_plaque_expire_le" type="text" class="form-control" value="<?= $__expP !== '' ? htmlspecialchars($__expP) : 'N/A' ?>" disabled>
                                                            <div class="mt-1 small" id="vp_badge_plaque_expire">
                                                                <?php
                                                                    $expP = (string)($vehiculeRecord['plaque_expire_le'] ?? '');
                                                                    if ($expP !== '') {
                                                                        $ts = strtotime($expP);
                                                                        if ($ts !== false) {
                                                                            $today = strtotime(date('Y-m-d'));
                                                                            if ($ts < $today) {
                                                                                echo '<span class="badge bg-danger">Plaque expirée</span>';
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Société d'assurance</label>
                                                            <?php $__socA = trim((string)($vehiculeRecord['societe_assurance'] ?? '')); ?>
                                                            <input id="vp_societe_assurance" type="text" class="form-control" value="<?= $__socA !== '' ? htmlspecialchars($__socA) : 'N/A' ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">N° d'assurance</label>
                                                            <?php $__numA = trim((string)($vehiculeRecord['nume_assurance'] ?? '')); ?>
                                                            <input id="vp_nume_assurance" type="text" class="form-control" value="<?= $__numA !== '' ? htmlspecialchars($__numA) : 'N/A' ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Assurance valide le</label>
                                                            <?php $__valA = trim((string)($vehiculeRecord['date_valide_assurance'] ?? '')); ?>
                                                            <input id="vp_date_valide_assurance" type="text" class="form-control" value="<?= $__valA !== '' ? htmlspecialchars($__valA) : 'N/A' ?>" disabled>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Assurance expire le</label>
                                                            <?php $__expA0 = trim((string)($vehiculeRecord['date_expire_assurance'] ?? '')); ?>
                                                            <input id="vp_date_expire_assurance" type="text" class="form-control" value="<?= $__expA0 !== '' ? htmlspecialchars($__expA0) : 'N/A' ?>" disabled>
                                                            <div class="mt-1 small" id="vp_badge_assurance_expire">
                                                                <?php
                                                                    $expA = (string)($vehiculeRecord['date_expire_assurance'] ?? '');
                                                                    if ($expA !== '') {
                                                                        $ts = strtotime($expA);
                                                                        if ($ts !== false) {
                                                                            $today = strtotime(date('Y-m-d'));
                                                                            if ($ts < $today) {
                                                                                echo '<span class="badge bg-danger">Assurance expirée</span>';
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal: Contraventions du véhicule -->
                                    <div class="modal fade" id="vehiculeContraventionsModal" tabindex="-1" aria-hidden="true">
                                      <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title">Contraventions — <?= htmlspecialchars((string)($vehiculeRecord['plaque'] ?? 'Véhicule')) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                          </div>
                                          <div class="modal-body">
                                            <?php if (!empty($vehiculeContraventions)): ?>
                                            <div class="table-responsive">
                                              <table class="table table-striped table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                  <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Infraction</th>
                                                    <th>Montant</th>
                                                    <th>Payée</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="veh-contrav-tbody">
                                                  <?php foreach ($vehiculeContraventions as $i => $cv): ?>
                                                    <tr>
                                                      <td><?= htmlspecialchars((string)($cv['id'] ?? '')) ?></td>
                                                      <td><?php $d = (string)($cv['date_infraction'] ?? ''); $ts = $d ? strtotime($d) : false; echo htmlspecialchars($ts ? date('d-m-Y', $ts) : $d); ?></td>
                                                      <td><?= htmlspecialchars((string)($cv['type_infraction'] ?? ($cv['infraction'] ?? ''))) ?></td>
                                                      <td><?= htmlspecialchars((string)($cv['amende'] ?? '')) ?></td>
                                                      <td>
                                                        <?php $isPayed = (($cv['payed'] ?? 0) == 1); ?>
                                                        <span class="badge <?= $isPayed ? 'bg-success' : 'bg-warning text-dark' ?>"><?= $isPayed ? 'Payée' : 'Non payée' ?></span>
                                                      </td>
                                                    </tr>
                                                  <?php endforeach; ?>
                                                </tbody>
                                              </table>
                                            </div>
                                            <?php else: ?>
                                                <div class="alert alert-info mb-0">Aucune contravention pour ce véhicule.</div>
                                            <?php endif; ?>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                          </div>
                                        </div>
                                      </div>
                                      </div>
                                    <?php endif; ?>
                                    <?php if (!empty($vehiculeCandidates)): ?>
                                    <script>
                                    (function(){
                                        const pk = <?= json_encode($vehiculePk ?: 'id') ?>;
                                        const candidates = <?= json_encode($vehiculeCandidates ?? []) ?>;
                                        const contravsById = <?= json_encode($vehiculeContraventionsById ?? []) ?>;
                                        const sel = document.getElementById('vehiculeCandidateSelect');
                                        if (!sel) return;
                                        const btnAssign = document.querySelector('.btn-assign-contrav');
                                        const img = document.getElementById('vp_image');
                                        const icon = document.getElementById('vp_icon');
                                        function isExpired(dateStr){
                                            if (!dateStr) return false;
                                            const d = new Date(String(dateStr));
                                            if (isNaN(d.getTime())) return false;
                                            const today = new Date(); today.setHours(0,0,0,0);
                                            const d0 = new Date(d.getFullYear(), d.getMonth(), d.getDate());
                                            return d0 < today;
                                        }
                                        function updateBadges(rec){
                                            const plaqueEl = document.getElementById('vp_badge_plaque_expire');
                                            const assuEl = document.getElementById('vp_badge_assurance_expire');
                                            if (plaqueEl){
                                                plaqueEl.innerHTML = '';
                                                if (isExpired(rec.plaque_expire_le)) {
                                                    plaqueEl.innerHTML = '<span class="badge bg-danger">Plaque expirée</span>';
                                                }
                                            }
                                            if (assuEl){
                                                assuEl.innerHTML = '';
                                                if (isExpired(rec.date_expire_assurance)) {
                                                    assuEl.innerHTML = '<span class="badge bg-danger">Assurance expirée</span>';
                                                }
                                            }
                                        }
                                        function setVal(id, val){ const el = document.getElementById(id); if (el) el.value = (val === null || val === undefined || val === '') ? 'N/A' : val; }
                                        function fmtDate(d){ return (d === null || d === undefined || String(d).trim() === '') ? 'N/A' : d; }
                                        function normSrc(src){ if (!src) return ''; try { src = String(src); } catch(e){ return ''; } if (src && !/^https?:\/\//i.test(src)) { src = '/' + src.replace(/^\/+/, ''); } return src; }
                                        function thumbFrom(rec){
                                            const v = rec && rec.images !== undefined ? rec.images : '';
                                            if (!v) return '';
                                            try {
                                                if (typeof v === 'string') {
                                                    try { const arr = JSON.parse(v); if (Array.isArray(arr) && arr.length) return normSrc(arr[0]); }
                                                    catch(e){ return normSrc(v); }
                                                } else if (Array.isArray(v) && v.length) {
                                                    return normSrc(v[0]);
                                                }
                                            } catch(e){}
                                            return '';
                                        }
                                        function renderContravs(dossierId){
                                            const body = document.getElementById('veh-contrav-tbody');
                                            if (!body) return;
                                            const list = contravsById[String(dossierId)] || [];
                                            if (list.length === 0) { body.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucune contravention pour ce véhicule.</td></tr>'; return; }
                                            body.innerHTML = list.map(cv => {
                                                const d = cv.date_infraction || '';
                                                const payed = String(cv.payed || '0') === '1';
                                                return `<tr>
                                                    <td>${cv.id ?? ''}</td>
                                                    <td>${d}</td>
                                                    <td>${cv.type_infraction || cv.infraction || ''}</td>
                                                    <td>${cv.amende || ''}</td>
                                                    <td><span class="badge ${payed ? 'bg-success' : 'bg-warning text-dark'}">${payed ? 'Payée' : 'Non payée'}</span></td>
                                                </tr>`;
                                            }).join('');
                                        }
                                        function updateImage(rec){
                                            if (!img || !icon) return;
                                            const src = thumbFrom(rec);
                                            if (src) {
                                                img.src = src;
                                                img.style.display = '';
                                                icon.style.display = 'none';
                                            } else {
                                                img.removeAttribute('src');
                                                img.style.display = 'none';
                                                icon.style.display = '';
                                            }
                                        }
                                        function onChange(){
                                            const id = sel.value;
                                            const rec = candidates.find(c => String(c[pk]) === String(id));
                                            if (!rec) return;
                                            setVal('vp_plaque', rec.plaque);
                                            setVal('vp_marque', rec.marque);
                                            setVal('vp_modele', rec.modele);
                                            setVal('vp_annee', rec.annee);
                                            setVal('vp_couleur', rec.couleur);
                                            setVal('vp_numero_chassis', rec.numero_chassis);
                                            setVal('vp_frontiere_entree', rec.frontiere_entree);
                                            setVal('vp_date_importation', fmtDate(rec.date_importation));
                                            setVal('vp_plaque_valide_le', fmtDate(rec.plaque_valide_le));
                                            setVal('vp_plaque_expire_le', fmtDate(rec.plaque_expire_le));
                                            setVal('vp_societe_assurance', rec.societe_assurance);
                                            setVal('vp_nume_assurance', rec.nume_assurance);
                                            setVal('vp_date_valide_assurance', fmtDate(rec.date_valide_assurance));
                                            setVal('vp_date_expire_assurance', fmtDate(rec.date_expire_assurance));
                                            updateBadges(rec);
                                            if (btnAssign) {
                                                btnAssign.setAttribute('data-dossier-id', String(rec[pk] ?? ''));
                                                btnAssign.setAttribute('data-target-label', rec.plaque ? String(rec.plaque) : `vehicule #${rec[pk]}`);
                                            }
                                            updateImage(rec);
                                            renderContravs(String(rec[pk] ?? ''));
                                        }
                                        sel.addEventListener('change', onChange);
                                    })();
                                    </script>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <div class="card mt-1">
                                        <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-2"></i>Liste des résultats</h5>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted">Terme:&nbsp;</span>
                                                <span class="fw-bold">"<?php echo htmlspecialchars($query); ?>"</span>
                                                <span class="text-muted">&nbsp;•&nbsp;Total:&nbsp;<span id="results-total"><?php echo count($results ?? []); ?></span></span>
                                                <input id="client-filter" type="search" class="form-control form-control-sm ms-2" placeholder="Filtrer les résultats..." oninput="filterResults()" style="min-width:220px;">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                        <div class="card-body p-0">
                                            <?php if (!empty($results)): ?>
                                                <?php
                                                    $counts = [];
                                                    foreach ($results as $r) {
                                                        $t = (string)($r['type'] ?? '');
                                                        $counts[$t] = ($counts[$t] ?? 0) + 1;
                                                    }
                                                    ksort($counts);
                                                ?>
                                                <div class="px-3 py-2 border-bottom bg-light small">
                                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                                        <span class="text-muted me-1">Par type:</span>
                                                        <?php $INITIAL_VISIBLE_PER_TYPE = 10; ?>
                                                        <?php foreach ($counts as $t => $c): ?>
                                                            <span class="badge bg-outline-secondary border text-dark d-flex align-items-center gap-1">
                                                                <i class="ri-database-2-line"></i>
                                                                <span><?php echo htmlspecialchars($t); ?></span>
                                                                <span class="ms-1 badge bg-secondary align-middle" id="count-<?php echo htmlspecialchars($t); ?>"><?php echo (int)$c; ?></span>
                                                                <label class="ms-2 small text-muted">Page:</label>
                                                                <select class="form-select form-select-sm w-auto ms-1 page-size-select" data-type="<?php echo htmlspecialchars($t); ?>" onchange="changePageSize('<?php echo htmlspecialchars($t); ?>', this.value)">
                                                                    <?php foreach ([5,10,20,50] as $opt): ?>
                                                                        <option value="<?php echo $opt; ?>" <?php echo $opt === $INITIAL_VISIBLE_PER_TYPE ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <?php if ($c > $INITIAL_VISIBLE_PER_TYPE): ?>
                                                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2 load-more-btn" data-type="<?php echo htmlspecialchars($t); ?>" onclick="loadMoreType('<?php echo htmlspecialchars($t); ?>')">
                                                                        Afficher plus
                                                                    </button>
                                                                    <a class="btn btn-sm btn-outline-secondary ms-1" href="/search?q=<?php echo urlencode($query ?? ''); ?>&type=<?php echo urlencode($t); ?>&per_type=<?php echo (int)$INITIAL_VISIBLE_PER_TYPE; ?>&page=1">
                                                                        Plus (serveur)
                                                                    </a>
                                                                <?php endif; ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <?php if (!empty($type)): ?>
                                                    <div class="px-3 py-2 border-bottom bg-light d-flex align-items-center gap-2">
                                                        <?php $currPage = isset($page_index) ? (int)$page_index : 0; $pt = isset($per_type) ? (int)$per_type : $INITIAL_VISIBLE_PER_TYPE; ?>
                                                        <span class="text-muted small">Pagination (serveur) pour <span class="badge bg-secondary text-uppercase ms-1"><?php echo htmlspecialchars($type); ?></span></span>
                                                        <div class="ms-auto d-flex align-items-center gap-2">
                                                            <form method="get" action="/search" class="d-flex align-items-center gap-2">
                                                                <input type="hidden" name="q" value="<?php echo htmlspecialchars($query ?? ''); ?>">
                                                                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                                                                <label class="small text-muted">Page size:</label>
                                                                <select name="per_type" class="form-select form-select-sm w-auto">
                                                                    <?php foreach ([5,10,20,50,100] as $opt): ?>
                                                                        <option value="<?php echo $opt; ?>" <?php echo $opt === $pt ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <input type="hidden" name="page" value="<?php echo $currPage; ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-secondary">Appliquer</button>
                                                            </form>
                                                            <div class="btn-group">
                                                                <?php if ($currPage > 0): ?>
                                                                    <a class="btn btn-sm btn-outline-primary" href="/search?q=<?php echo urlencode($query ?? ''); ?>&type=<?php echo urlencode($type); ?>&per_type=<?php echo (int)$pt; ?>&page=<?php echo (int)($currPage - 1); ?>">&laquo; Précédent</a>
                                                                <?php else: ?>
                                                                    <button class="btn btn-sm btn-outline-secondary" disabled>&laquo; Précédent</button>
                                                                <?php endif; ?>
                                                                <a class="btn btn-sm btn-outline-primary" href="/search?q=<?php echo urlencode($query ?? ''); ?>&type=<?php echo urlencode($type); ?>&per_type=<?php echo (int)$pt; ?>&page=<?php echo (int)($currPage + 1); ?>">Suivant &raquo;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0" id="search-results-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Type</th>
                                                            <th>ID</th>
                                                            <th>Aperçu</th>
                                                            <th class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="results-tbody">
                                                        <?php if (!empty($results)): ?>
                                                            <?php $typeIndexes = []; ?>
                                                            <?php foreach ($results as $r): ?>
                                                                <?php $t = (string)($r['type'] ?? ''); $idx = $typeIndexes[$t] = ($typeIndexes[$t] ?? 0); $typeIndexes[$t]++; ?>
                                                                <tr data-type="<?php echo htmlspecialchars($t); ?>" data-idx="<?php echo (int)$idx; ?>">
                                                                    <td><span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($t); ?></span></td>
                                                                    <td>#<?php echo htmlspecialchars($r['id']); ?></td>
                                                                    <td class="text-truncate" style="max-width:560px;">
                                                                        <?php echo htmlspecialchars($r['title']); ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="btn-group btn-group-sm" role="group">
                                                                            <a class="btn btn-primary" href="/search/detail?type=<?php echo urlencode($t); ?>&id=<?php echo urlencode($r['id']); ?>">
                                                                                <i class="ri-eye-line me-1"></i>Détails
                                                                            </a>
                                                                            <?php
                                                                                $assignableTypes = ['conducteur_vehicule','entreprises','particuliers','vehicule_plaque'];
                                                                                if (in_array($t, $assignableTypes, true)):
                                                                                    $label = ($r['title'] ?? ($t . ' #' . $r['id']));
                                                                                    // Pour les personnes, n'afficher que le nom si disponible
                                                                                    if ($t === 'particuliers' || $t === 'conducteur_vehicule') {
                                                                                        if (!empty($r['nom'])) {
                                                                                            $label = (string)$r['nom'];
                                                                                        } elseif (!empty($r['name'])) {
                                                                                            $label = (string)$r['name'];
                                                                                        }
                                                                                    }
                                                                            ?>
                                                                                <?php if ($t !== 'particuliers'): ?>
                                                                                <button type="button"
                                                                                    class="btn btn-success btn-assign-contrav"
                                                                                    data-dossier-type="<?php echo htmlspecialchars($t, ENT_QUOTES); ?>"
                                                                                    data-dossier-id="<?php echo htmlspecialchars($r['id'], ENT_QUOTES); ?>"
                                                                                    data-target-label="<?php echo htmlspecialchars($label, ENT_QUOTES); ?>"
                                                                                    data-bs-toggle="modal" data-bs-target="#assignContravModal">
                                                                                    Assigner
                                                                                </button>
                                                                                <?php endif; ?>
                                                                                <?php if ($t === 'particuliers'): ?>
                                                                                <button type="button"
                                                                                    class="btn btn-outline-secondary btn-particulier-actions"
                                                                                    data-particulier-id="<?php echo htmlspecialchars($r['id'], ENT_QUOTES); ?>"
                                                                                    data-particulier-nom="<?php echo htmlspecialchars(($r['nom'] ?? $r['name'] ?? $label), ENT_QUOTES); ?>"
                                                                                    data-particulier-numero="<?php echo htmlspecialchars(($r['numero'] ?? ''), ENT_QUOTES); ?>"
                                                                                    data-bs-toggle="modal" data-bs-target="#particulierActionsModal">
                                                                                    <i class="ri-more-2-line me-1"></i>Plus
                                                                                </button>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted py-4">Aucun résultat trouvé.</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php // Include vehicle registration modal so it can be triggered from this page ?>
<?php require_once '_partials/_modal_enregistrement_vehicule.php'; ?>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/vendor/daterangepicker/moment.min.js"></script>
<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/js/pages/dashboard.js"></script>
<script src="assets/js/app.min.js"></script>

<!-- Modal actions Particulier (copied from consulter-dossier2.php for reuse here) -->
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
                  <button type="button" class="btn btn-sm btn-danger" id="pa_action_arrest_btn" data-action="arrestation">Consigner une arrestation</button>
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

<!-- Modal: Associer un véhicule (copié de consulter-dossier2.php) -->
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

<?php require_once __DIR__ . '/_partials/_modal_avis_recherche.php'; ?>

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
// Wiring for Particulier Actions from search results
document.addEventListener('DOMContentLoaded', function(){
  // Open actions modal with context
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.btn-particulier-actions');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    const id = btn.getAttribute('data-particulier-id') || '';
    const nom = btn.getAttribute('data-particulier-nom') || '';
    const numero = btn.getAttribute('data-particulier-numero') || '';
    const modalEl = document.getElementById('particulierActionsModal');
    if (!modalEl) return;
    modalEl.setAttribute('data-dossier-id', id);
    const nomEl = document.getElementById('pa_nom');
    const numEl = document.getElementById('pa_numero');
    if (nomEl) nomEl.textContent = nom;
    if (numEl) numEl.textContent = numero ? `(${numero})` : '';
    const btnPermisCard = document.getElementById('pt_btn_launch_permis_card');
    if (btnPermisCard) btnPermisCard.setAttribute('data-dossier-id', id);
    // cache context
    window.__lastParticulierCtx = { id };
    try { localStorage.setItem('recent_particulier_ctx', JSON.stringify({ id })); } catch {}
    // ensure modal exists directly under body to avoid z-index issues
    if (modalEl.parentElement && modalEl.parentElement !== document.body) document.body.appendChild(modalEl);
    if (window.bootstrap) { bootstrap.Modal.getOrCreateInstance(modalEl).show(); }
  });

  // Handle sanction action -> open Assign Contravention modal
  document.addEventListener('click', function(e){
    const sBtn = e.target.closest('#pa_action_sanction_btn');
    if (!sBtn) return;
    e.preventDefault();
    e.stopPropagation();
    const actionsModalEl = document.getElementById('particulierActionsModal');
    const dossierId = actionsModalEl?.getAttribute('data-dossier-id') || (window.__lastParticulierCtx?.id || '');
    const nom = document.getElementById('pa_nom')?.textContent?.trim() || '';
    const numero = document.getElementById('pa_numero')?.textContent?.replace(/[()]/g,'').trim() || '';
    // close actions, then open assign
    try { window.bootstrap?.Modal.getInstance(actionsModalEl)?.hide(); } catch {}
    // Prefill assign contravention modal present on this page
    const mEl = document.getElementById('assignContravModal');
    if (mEl) {
      document.getElementById('ac_type_dossier').value = 'particuliers';
      document.getElementById('ac_dossier_id').value = dossierId;
      const info = document.getElementById('ac_target_label');
      if (info) info.value = nom ? (numero ? `${nom} (${numero})` : nom) : '';
      // default date today
      const d = document.getElementById('ac_date_infraction');
      if (d && !d.value) { try { d.valueAsDate = new Date(); } catch {} }
      window.bootstrap?.Modal.getOrCreateInstance(mEl).show();
    } else {
      // fallback: navigate to details to perform sanction there
      if (dossierId) window.location.href = `/search/detail?type=particuliers&id=${encodeURIComponent(dossierId)}#sanction`;
    }
  });

  // Helper: current particulier id
  window.__getCurrentParticulierId = function(){
    const fromActions = document.getElementById('particulierActionsModal')?.getAttribute('data-dossier-id') || '';
    const fromCache = window.__lastParticulierCtx?.id || '';
    let fromStorage = '';
    try { fromStorage = JSON.parse(localStorage.getItem('recent_particulier_ctx')||'{}')?.id || ''; } catch {}
    return fromActions || fromCache || fromStorage || '';
  };
  // Implement in-page Temporary Permit modal and handlers
  window.__openPermisModal = function(pid){
    if (!pid) { alert("Veuillez d'abord sélectionner un particulier."); return; }
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
  window.__launchPermisFromBtn = function(ev, el){
    try { ev && ev.preventDefault(); ev && ev.stopPropagation(); } catch {}
    const pid = (el && el.getAttribute && el.getAttribute('data-dossier-id')) || window.__getCurrentParticulierId();
    window.__openPermisModal(pid);
    return false;
  };
  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('#pt_btn_launch_permis, #pt_btn_launch_permis_card, .btn-launch-permis, [data-action="launch-permis"]'); if (!btn) return;
    e.preventDefault(); e.stopPropagation();
    const pid = btn.getAttribute('data-dossier-id') || window.__getCurrentParticulierId();
    window.__openPermisModal(pid);
  });
  document.addEventListener('submit', async (e)=>{
    const form = e.target.closest('#launchPermisForm'); if (!form) return;
    e.preventDefault();
    const modalEl = document.getElementById('launchPermisModal');
    const pid = modalEl?.getAttribute('data-pid') || '';
    const fb = modalEl?.querySelector('#pt_feedback_permis');
    if (!pid) { alert('Particulier introuvable'); return; }
    const fd = new FormData(form);
    const motif = (fd.get('motif')||'').toString().trim();
    const dd = (fd.get('date_debut')||'').toString();
    const duree = parseInt((fd.get('duree')||'14').toString(),10) || 14;
    if (!motif || !dd) { alert('Veuillez renseigner le motif et la date de début'); return; }
    const d = new Date(dd); if (isNaN(d.getTime())) { alert('Date de début invalide'); return; }
    const d2 = new Date(d.getTime()); d2.setDate(d2.getDate() + duree);
    const yyyy2 = d2.getFullYear(); const mm2 = String(d2.getMonth()+1).padStart(2,'0'); const day2 = String(d2.getDate()).padStart(2,'0');
    const df = `${yyyy2}-${mm2}-${day2}`;
    const submitBtn = modalEl?.querySelector('#pt_submit_permis'); if (submitBtn) submitBtn.disabled = true;
    if (fb) { fb.className = 'alert alert-info'; fb.textContent = 'Emission en cours...'; }
    try {
      const body = new URLSearchParams({ cible_type: 'particulier', cible_id: String(pid), motif, date_debut: dd, date_fin: df }).toString();
      const resp = await fetch('/permis-temporaire', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' }, body });
      const data = await resp.json();
      if (!resp.ok || !data.ok) throw new Error(data.error||'Erreur serveur');
      if (fb) { fb.className = 'alert alert-success'; fb.textContent = 'Permis temporaire émis avec succès'; }
      try { window.showSuccess && window.showSuccess('Permis temporaire émis'); } catch {}
      try { form.reset(); } catch {}
      setTimeout(()=>{ try { bootstrap.Modal.getInstance(modalEl)?.hide(); } catch {} }, 600);
    } catch(err){
      if (fb) { fb.className = 'alert alert-danger'; fb.textContent = err.message || 'Erreur inconnue'; }
    } finally {
      if (submitBtn) submitBtn.disabled = false;
    }
  });

  // Associer un véhicule -> ouvrir le modal local + recherche + soumission
  (function(){
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
      const id = document.getElementById('particulierActionsModal')?.getAttribute('data-dossier-id') || window.__getCurrentParticulierId();
      if (inputPid && id) inputPid.value = id;
    }
    function resetResult(){
      if (hiddenVid) hiddenVid.value = '';
      if (resTitle) resTitle.textContent = '';
      if (resSub) resSub.textContent = '';
      if (resBox) resBox.classList.add('d-none');
      if (noRes) noRes.classList.add('d-none');
    }
    function openAssocModal(){
      ensurePid(); resetResult(); if (inputPlate) inputPlate.value = ''; const notes = document.getElementById('av_notes'); if (notes) notes.value = '';
      try { if (window.bootstrap && bootstrap.Modal) { if (actionsModal) bootstrap.Modal.getOrCreateInstance(actionsModal).hide(); if (assocModal && assocModal.parentNode !== document.body) document.body.appendChild(assocModal); bootstrap.Modal.getOrCreateInstance(assocModal).show(); } } catch {}
    }
    document.addEventListener('click', function(e){ const btn = e.target.closest('#pa_action_associer_btn'); if (!btn) return; e.preventDefault(); e.stopPropagation(); openAssocModal(); });
    function renderVehicle(v){ const plaque = v.plaque || ''; const title = plaque ? ('Plaque: ' + plaque) : 'Véhicule'; const sub = [v.marque, v.modele, v.couleur, v.annee].filter(Boolean).join(' · '); if (resTitle) resTitle.textContent = title; if (resSub) resSub.textContent = sub; }
    function doSearch(){ resetResult(); const q = (inputPlate?.value || '').trim(); if (!q) return; fetch(`/api/vehicules/search?plate=${encodeURIComponent(q)}`).then(r=>r.json()).then(j=>{ if (!j.ok){ noRes?.classList.remove('d-none'); return; } const rows = j.data || []; if (!rows.length){ noRes?.classList.remove('d-none'); return; } const v = rows[0]; if (hiddenVid) hiddenVid.value = String(v.id || ''); renderVehicle(v); resBox?.classList.remove('d-none'); }).catch(()=>{ noRes?.classList.remove('d-none'); }); }
    if (btnSearch){ btnSearch.addEventListener('click', doSearch); }
    if (inputPlate){ inputPlate.addEventListener('keydown', function(e){ if (e.key==='Enter'){ e.preventDefault(); doSearch(); }}); }
    function submitAssoc(){ ensurePid(); const pid = inputPid?.value?.trim() || ''; const vid = hiddenVid?.value?.trim() || ''; if (!pid){ alert("Contexte Particulier manquant. Veuillez réouvrir depuis la liste."); return; } if (!vid){ alert("Veuillez rechercher et sélectionner un véhicule valide."); return; } const fd = new FormData(); fd.append('particulier_id', pid); fd.append('vehicule_plaque_id', vid); const notes = document.getElementById('av_notes')?.value || ''; if (notes) fd.append('notes', notes); if (btnSubmit) btnSubmit.disabled = true; fetch('/particulier/associer-vehicule', { method:'POST', body: fd }).then(r=>r.json()).then(j=>{ if (j && j.ok){ alert(j.dup ? 'Cette association existe déjà.' : 'Véhicule associé avec succès.'); try { if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance(assocModal).hide(); } catch {} } else { alert('Erreur: ' + (j && j.error ? j.error : 'Echec')); } }).catch(()=> alert('Erreur réseau')).finally(()=>{ if (btnSubmit) btnSubmit.disabled = false; }); }
    if (btnSubmit){ btnSubmit.addEventListener('click', submitAssoc); }
  })();

  // Lancer un avis de recherche -> ouvrir le modal dédié + soumission
  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('#pt_btn_launch_avis, .btn-launch-avis, [data-action="launch-avis"]'); if (!btn) return;
    e.preventDefault(); e.stopPropagation();
    try { document.activeElement?.blur?.(); btn?.blur?.(); } catch(_){}
    const launchEl = document.getElementById('launchAvisModal'); if (!launchEl) return;
    if (launchEl.parentElement && launchEl.parentElement !== document.body) { document.body.appendChild(launchEl); }
    const pid = document.getElementById('particulierActionsModal')?.getAttribute('data-dossier-id') || window.__getCurrentParticulierId() || '';
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
    } else { showLaunch(); }
  });
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
      const resp = await fetch('/avis-recherche', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' }, body: new URLSearchParams({ cible_type: 'particulier', cible_id: pid, motif, niveau }).toString() });
      const data = await resp.json();
      if (!resp.ok || !data.ok) throw new Error(data.error||'Erreur serveur');
      try { window.showSuccess && window.showSuccess('Avis de recherche créé avec succès'); } catch(_){ }
      try { const f = document.getElementById('launchAvisForm'); if (f) { f.reset(); const sel = f.querySelector('select[name="niveau"]'); if (sel) sel.value = 'moyen'; const ta = f.querySelector('textarea[name="motif"]'); if (ta) ta.value = ''; } } catch(_){ }
      try { bootstrap.Modal.getInstance(modalEl)?.hide(); } catch(_) { modalEl.classList.remove('show'); modalEl.style.display='none'; }
    } catch(err){ alert(err.message||'Erreur réseau'); }
  });

  // Arrestation: open modal prefilled
  document.addEventListener('click', function(e){
    const btn = e.target.closest('#pa_action_arrest_btn');
    if (!btn) return;
    e.preventDefault();
    const modalActions = document.getElementById('particulierActionsModal');
    const pid = modalActions ? modalActions.getAttribute('data-dossier-id') : (window.__lastParticulierCtx && window.__lastParticulierCtx.id);
    if (!pid) { alert("Veuillez sélectionner un particulier."); return; }
    const now = new Date();
    const pad = (n)=> String(n).padStart(2,'0');
    const localISO = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
    const form = document.getElementById('arrestationForm');
    if (form) { form.reset(); }
    document.getElementById('arr_particulier_id').value = String(pid);
    document.getElementById('arr_datetime').value = localISO;
    const alertBox = document.getElementById('arr_alert');
    if (alertBox) { alertBox.className = 'alert d-none'; alertBox.textContent=''; }
    try { bootstrap.Modal.getOrCreateInstance(document.getElementById('arrestationModal')).show(); } catch {}
  });

  // Arrestation: submit handler
  const arrSubmit = document.getElementById('arr_submit_btn');
  if (arrSubmit) {
    arrSubmit.addEventListener('click', async function(){
      const form = document.getElementById('arrestationForm');
      const alertBox = document.getElementById('arr_alert');
      if (!form) return;
      const fd = new FormData(form);
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

<!-- Assign Contravention Modal -->
<div class="modal fade" id="assignContravModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ri-police-car-line me-2"></i>Assigner une contravention</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <form id="assign-contrav-form">
          <input type="hidden" name="dossier_id" id="ac_dossier_id">
          <input type="hidden" name="type_dossier" id="ac_type_dossier">
          <div class="mb-2">
            <label class="form-label text-muted">Cible</label>
            <input type="text" class="form-control" id="ac_target_label" readonly>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Date d'infraction</label>
              <input type="date" class="form-control" name="date_infraction" id="ac_date_infraction" required>
            </div>
            <div class="col-md-8">
              <label class="form-label">Lieu</label>
              <input type="text" class="form-control" name="lieu" id="ac_lieu" placeholder="Ex: Avenue ...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Type d'infraction</label>
              <input type="text" class="form-control" name="type_infraction" id="ac_type_infraction" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Référence de loi</label>
              <input type="text" class="form-control" name="reference_loi" id="ac_reference_loi" placeholder="Article ...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Montant amende (CDF)</label>
              <input type="number" min="0" step="1" class="form-control" name="amende" id="ac_amende" required>
            </div>
            <div class="col-md-6 d-flex align-items-end">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="ac_payed_switch">
                <label class="form-check-label" for="ac_payed_switch">Payée</label>
              </div>
              <input type="hidden" name="payed" id="ac_payed" value="0">
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea class="form-control" name="description" id="ac_description" rows="2" placeholder="Détails..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" id="ac_submit_btn">
          <i class="ri-check-line me-1"></i>Enregistrer
        </button>
      </div>
    </div>
  </div>
  </div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<script>
// Sync with PHP-defined initial per-type visibility
const INITIAL_VISIBLE_PER_TYPE = <?php echo isset($INITIAL_VISIBLE_PER_TYPE) ? (int)$INITIAL_VISIBLE_PER_TYPE : 10; ?>;

function storageKey(type) { return `search_page_size_${type}`; }
function getPageSize(type) {
    try { const v = localStorage.getItem(storageKey(type)); return v ? Math.max(1, parseInt(v,10)) : INITIAL_VISIBLE_PER_TYPE; } catch { return INITIAL_VISIBLE_PER_TYPE; }
}
function setPageSize(type, size) {
    try { localStorage.setItem(storageKey(type), String(size)); } catch {}
}

function applyPerTypeLimit() {
    const tbody = document.getElementById('results-tbody');
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const byType = new Map();
    rows.forEach(tr => {
        const t = tr.getAttribute('data-type') || '';
        if (!byType.has(t)) byType.set(t, []);
        byType.get(t).push(tr);
    });
    byType.forEach((list, t) => {
        list.sort((a,b) => (+a.getAttribute('data-idx')||0) - (+b.getAttribute('data-idx')||0));
        const pageSize = getPageSize(t);
        list.forEach((tr, i) => {
            tr.style.display = i < pageSize ? '' : 'none';
        });
        const btn = document.querySelector(`.load-more-btn[data-type="${CSS.escape(t)}"]`);
        if (btn) btn.style.display = list.length > pageSize ? '' : 'none';
        // update server link per_type to current pageSize
        const serverLink = document.querySelector(`a.btn.btn-sm.btn-outline-secondary[href*="type=${CSS.escape(t)}"]`);
        if (serverLink) {
            try {
                const url = new URL(serverLink.href, window.location.origin);
                url.searchParams.set('per_type', String(pageSize));
                serverLink.href = url.toString();
            } catch {}
        }
    });
    updateTotals();
}

function loadMoreType(t) {
    const tbody = document.getElementById('results-tbody');
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll(`tr[data-type="${CSS.escape(t)}"]`))
        .sort((a,b) => (+a.getAttribute('data-idx')||0) - (+b.getAttribute('data-idx')||0));
    let shown = 0;
    rows.forEach(tr => { if (tr.style.display !== 'none') shown++; });
    const step = getPageSize(t);
    const nextShow = Math.min(rows.length, shown + step);
    rows.forEach((tr, i) => { tr.style.display = i < nextShow ? '' : 'none'; });
    const btn = document.querySelector(`.load-more-btn[data-type="${CSS.escape(t)}"]`);
    if (btn) btn.style.display = nextShow >= rows.length ? 'none' : '';
    updateTotals();
}

function updateTotals() {
    const tbody = document.getElementById('results-tbody');
    const totalEl = document.getElementById('results-total');
    if (!tbody || !totalEl) return;
    const visible = Array.from(tbody.querySelectorAll('tr')).filter(tr => tr.style.display !== 'none').length;
    totalEl.textContent = String(visible);
}

function filterResults() {
    const q = (document.getElementById('client-filter')?.value || '').toLowerCase().trim();
    const tbody = document.getElementById('results-tbody');
    const totalEl = document.getElementById('results-total');
    if (!tbody) return;
    let shown = 0;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const filteringActive = !!q;
    rows.forEach(tr => {
        const cells = tr.querySelectorAll('td');
        if (cells.length < 3) return;
        const typeText = cells[0].innerText.toLowerCase();
        const idText = cells[1].innerText.toLowerCase();
        const previewText = cells[2].innerText.toLowerCase();
        const match = !q || typeText.includes(q) || idText.includes(q) || previewText.includes(q);
        // When filtering, ignore per-type limits and show all matches
        tr.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    // Toggle load-more buttons visibility when filtering
    document.querySelectorAll('.load-more-btn').forEach(btn => {
        btn.style.display = filteringActive ? 'none' : '';
    });
    if (filteringActive) {
        if (totalEl) totalEl.textContent = String(shown);
    } else {
        // Re-apply per-type limits when filter is cleared
        applyPerTypeLimit();
    }
}

function exportToCSV() {
    const table = document.getElementById('search-results-table');
    if (!table) return;
    const rows = Array.from(table.querySelectorAll('tr'));
    const csv = rows.map(tr => Array.from(tr.querySelectorAll('th,td')).slice(0,3) // exclude actions
        .map(td => '"' + (td.innerText || '').replace(/"/g,'""') + '"').join(',')
    ).join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `recherche_${new Date().toISOString().split('T')[0]}.csv`;
    link.click();
}

function changePageSize(type, value) {
    const size = Math.max(1, parseInt(value, 10) || INITIAL_VISIBLE_PER_TYPE);
    setPageSize(type, size);
    // Update select value everywhere for this type
    document.querySelectorAll(`.page-size-select[data-type="${CSS.escape(type)}"]`).forEach(sel => { sel.value = String(size); });
    applyPerTypeLimit();
}

function syncSelectsFromStorage() {
    document.querySelectorAll('.page-size-select').forEach(sel => {
        const type = sel.getAttribute('data-type') || '';
        const size = getPageSize(type);
        sel.value = String(size);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    syncSelectsFromStorage();
    applyPerTypeLimit();

    // Permissions: any authenticated user can edit on this page
    window.CAN_EDIT = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

    // Prepare modal open from buttons
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.btn-assign-contrav');
        if (!btn) return;
        if (!window.CAN_EDIT) { alert("Veuillez vous connecter."); return; }
        const type = btn.getAttribute('data-dossier-type') || '';
        const id = btn.getAttribute('data-dossier-id') || '';
        const label = btn.getAttribute('data-target-label') || '';
        document.getElementById('ac_type_dossier').value = type;
        document.getElementById('ac_dossier_id').value = id;
        document.getElementById('ac_target_label').value = label;
        // defaults
        const d = document.getElementById('ac_date_infraction');
        if (d && !d.value) { try { d.valueAsDate = new Date(); } catch {} }
        document.getElementById('ac_payed_switch').checked = false;
        document.getElementById('ac_payed').value = '0';
    });

    // Sync payed switch -> hidden value
    const sw = document.getElementById('ac_payed_switch');
    if (sw) {
        sw.addEventListener('change', function(){
            document.getElementById('ac_payed').value = this.checked ? '1' : '0';
        });
    }

    // Submit
    const submitBtn = document.getElementById('ac_submit_btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(){
            if (!window.CAN_EDIT) { alert('Action non autorisée'); return; }
            const form = document.getElementById('assign-contrav-form');
            if (!form) return;
            const fd = new FormData(form);
            // Basic validation
            const required = ['dossier_id','type_dossier','date_infraction','type_infraction','amende'];
            for (const k of required) {
                if (!fd.get(k)) { alert('Veuillez remplir tous les champs obligatoires.'); return; }
            }
            // Convert to URLSearchParams for backend
            const body = new URLSearchParams();
            fd.forEach((v, k) => { body.set(k, String(v)); });
            fetch('/contravention/create', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body
            }).then(r=>r.json()).then(res=>{
                if (res && res.state === true) {
                    // success
                    const modalEl = document.getElementById('assignContravModal');
                    if (modalEl && window.bootstrap) {
                        const m = bootstrap.Modal.getOrCreateInstance(modalEl);
                        m.hide();
                    }
                    // Optional: toast
                    alert('Contravention enregistrée.');
                } else {
                    alert((res && res.message) ? res.message : 'Échec de l\'enregistrement');
                }
            }).catch(()=>{
                alert('Erreur réseau');
            });
        });
    }
});
</script>

<?php if (!empty($_GET['plate']) && (empty($results) || count($results) === 0)): ?>
<script>
// If a plate-only search returned no results, open the creation modal and prefill the plate
document.addEventListener('DOMContentLoaded', function(){
    try {
        const el = document.getElementById('vehicule-modal');
        if (el && window.bootstrap) {
            const m = bootstrap.Modal.getOrCreateInstance(el);
            m.show();
            const plateInput = document.getElementById('plaque');
            if (plateInput) {
                plateInput.value = <?php echo json_encode((string)($_GET['plate'] ?? '')); ?>;
                plateInput.dispatchEvent(new Event('input'));
            }
        }
    } catch(e) { /* no-op */ }
});
</script>
<?php endif; ?>

</body>
</html>
