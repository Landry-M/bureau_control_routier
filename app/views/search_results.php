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
                                                                                <button type="button"
                                                                                    class="btn btn-success btn-assign-contrav"
                                                                                    data-dossier-type="<?php echo htmlspecialchars($t, ENT_QUOTES); ?>"
                                                                                    data-dossier-id="<?php echo htmlspecialchars($r['id'], ENT_QUOTES); ?>"
                                                                                    data-target-label="<?php echo htmlspecialchars($label, ENT_QUOTES); ?>"
                                                                                    data-bs-toggle="modal" data-bs-target="#assignContravModal">
                                                                                    Assigner
                                                                                </button>
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
