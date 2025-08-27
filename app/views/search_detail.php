<!DOCTYPE html>
<html lang="fr" data-layout="topnav" data-menu-color="brand">
<head>
    <meta charset="utf-8" />
    <title>Détail de l'enregistrement | Bureau de Contrôle Routier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <link rel="shortcut icon" href="/assets/images/logo.jpg">
    <script src="/assets/js/config.js"></script>
    
    <link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
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
                            <h4 class="page-title">Détail: <span class="text-uppercase badge bg-secondary"><?php echo htmlspecialchars($table ?? ''); ?></span></h4>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <a class="btn btn-sm btn-outline-secondary me-2" href="/search?q=<?php echo urlencode($_GET['q'] ?? ''); ?>">
                                    <i class="ri-arrow-left-line me-1"></i>Retour
                                </a>
                                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                    <i class="ri-printer-line me-1"></i>Imprimer
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <?php
                    // Heuristiques pour un en-tête convivial (titre et identifiant)
                    $tableName = isset($table) ? (string)$table : '';
                    $displayId = '';
                    $idKeys = ['id', $tableName . '_id', 'ID', 'Id'];
                    foreach ($idKeys as $ik) {
                        if (isset($record[$ik]) && (is_scalar($record[$ik]) || is_null($record[$ik]))) { $displayId = (string)$record[$ik]; break; }
                    }
                    $titleKeys = ['nom','name','titre','title','plaque','immatriculation','numero','matricule','description'];
                    $displayTitle = '';
                    foreach ($titleKeys as $tk) {
                        if (!empty($record[$tk]) && is_scalar($record[$tk])) { $displayTitle = (string)$record[$tk]; break; }
                    }
                    if ($displayTitle === '') { $displayTitle = ucfirst($tableName) . ($displayId !== '' ? " #{$displayId}" : ''); }

                    // Détection d'images à afficher (galerie)
                    $imageUrl = '';
                    $allImages = [];
                    $pushImg = function($p) use (&$allImages) {
                        if (!is_string($p) || $p === '') return;
                        // Accepte seulement extensions d'image
                        if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $p)) return;
                        // Normaliser
                        if (!preg_match('/^https?:\/\//i', $p)) {
                            if ($p[0] !== '/') $p = '/' . ltrim($p, '/');
                        }
                        if (!in_array($p, $allImages, true)) $allImages[] = $p;
                    };
                    $imgCandidates = ['photo','image','image_url','avatar','picture','profil','photo_url','url_image','img','thumbnail','cover'];
                    foreach ($imgCandidates as $ck) {
                        if (!empty($record[$ck])) {
                            $val = $record[$ck];
                            if (is_string($val)) { $pushImg($val); if ($imageUrl==='') $imageUrl = $val; }
                        }
                    }
                    // champs "images" (JSON ou csv)
                    if ($imageUrl === '' && !empty($record['images'])) {
                        $val = $record['images'];
                        if (is_string($val)) {
                            $decoded = json_decode($val, true);
                            if (is_array($decoded)) { foreach ($decoded as $p) { $pushImg($p); } if ($imageUrl==='') { $imageUrl = $allImages[0] ?? ''; } }
                            elseif (strpos($val, ',') !== false) { $parts = array_map('trim', explode(',', $val)); foreach ($parts as $p) { $pushImg($p); } if ($imageUrl==='') { $imageUrl = $allImages[0] ?? ''; } }
                            elseif (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $val)) { $pushImg($val); if ($imageUrl==='') $imageUrl = $val; }
                        } elseif (is_array($val) && isset($val[0]) && is_string($val[0])) {
                            foreach ($val as $p) { $pushImg($p); }
                            if ($imageUrl==='') $imageUrl = $allImages[0] ?? '';
                        }
                    }
                    // recherche générale d'une valeur ressemblant à une image
                    if ($imageUrl === '' && is_array($record)) {
                        foreach ($record as $rk => $rv) {
                            if (is_string($rv) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $rv)) { $pushImg($rv); if ($imageUrl==='') $imageUrl = $rv; }
                        }
                    }
                    // Normaliser l'URL (préfixer par / si chemin relatif uploads/...)
                    if ($imageUrl !== '') {
                        if (!preg_match('/^https?:\/\//i', $imageUrl) && $imageUrl[0] !== '/') { $imageUrl = '/' . ltrim($imageUrl, '/'); }
                    }
                    // S'assurer que l'image principale est dans la liste
                    if ($imageUrl !== '') { $pushImg($imageUrl); }

                    // Données liées si c'est un particulier: véhicules, état détention et avis de recherche
                    $isParticulier = in_array(strtolower($tableName), ['particulier','particuliers'], true);
                    $particulierId = 0;
                    if ($isParticulier) {
                        if (isset($record['id']) && is_scalar($record['id'])) {
                            $particulierId = (int)$record['id'];
                        } elseif ($displayId !== '') {
                            $particulierId = (int)$displayId;
                        }
                    }
                    $pv_list = [];
                    $arrestations_list = [];
                    $avis_list = [];
                    $enDetention = false;
                    if ($isParticulier && $particulierId > 0) {
                        try { $pv_list = (new \Control\ParticulierVehiculeController())->listByParticulier($particulierId); } catch (\Throwable $e) { $pv_list = []; }
                        try { $arrestations_list = (new \Control\ArrestationController())->listByParticulier($particulierId); } catch (\Throwable $e) { $arrestations_list = []; }
                        try { $avis_list = (new \Control\AvisRechercheController())->listByParticulier($particulierId); } catch (\Throwable $e) { $avis_list = []; }
                        if (is_array($arrestations_list)) {
                            foreach ($arrestations_list as $ar) {
                                $ds = $ar['date_sortie_prison'] ?? null;
                                if ($ds === null || $ds === '' ) { $enDetention = true; break; }
                            }
                        }
                    }
                ?>

                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="d-flex justify-content-center mb-3">
                                    <?php if (!empty($allImages)): ?>
                                        <div class="rounded-circle border overflow-hidden" style="width:120px;height:120px;">
                                            <img id="main-preview-img" src="<?php echo htmlspecialchars($allImages[0]); ?>" alt="aperçu" style="width:100%;height:100%;object-fit:cover;cursor:pointer;" role="button" title="Voir en grand" data-current-index="0">
                                        </div>
                                    <?php elseif ($imageUrl !== ''): ?>
                                        <div class="rounded-circle border overflow-hidden" style="width:120px;height:120px;">
                                            <img id="main-preview-img" src="<?php echo htmlspecialchars($imageUrl); ?>" alt="aperçu" style="width:100%;height:100%;object-fit:cover;cursor:pointer;" role="button" title="Voir en grand" data-current-index="0">
                                        </div>
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width:120px;height:120px;">
                                            <i class="ri-file-text-line fs-1 text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (count($allImages) > 1): ?>
                                <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
                                    <?php foreach ($allImages as $idx => $src): ?>
                                        <button type="button" class="p-0 border-0 bg-transparent" onclick="setMainPreview('<?php echo htmlspecialchars($src); ?>')" title="Aperçu <?php echo $idx+1; ?>">
                                            <div class="border rounded overflow-hidden" style="width:58px;height:58px;">
                                                <img src="<?php echo htmlspecialchars($src); ?>" alt="thumb" style="width:100%;height:100%;object-fit:cover;">
                                            </div>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <h4 class="mb-0 mt-2" title="<?php echo htmlspecialchars($displayTitle); ?>">
                                    <?php echo htmlspecialchars(mb_strimwidth($displayTitle, 0, 36, '…', 'UTF-8')); ?>
                                </h4>
                                <p class="text-muted fs-14 mb-2">
                                    <span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($tableName); ?></span>
                                    <?php if ($displayId !== ''): ?>
                                        <span class="badge bg-outline-secondary border text-dark ms-1">ID: <?php echo htmlspecialchars($displayId); ?></span>
                                    <?php endif; ?>
                                    <?php if ($isParticulier && $enDetention): ?>
                                        <span class="badge bg-danger ms-1">En détention</span>
                                    <?php endif; ?>
                                </p>

                                <div class="text-start mt-3">
                                    <h4 class="fs-13 text-uppercase">À propos :</h4>
                                    <p class="text-muted fs-13 mb-2">Données de la table <strong class="text-uppercase"><?php echo htmlspecialchars($tableName); ?></strong>.</p>
                                    <p class="text-muted fs-13 mb-0">Contient <?php echo isset($record) && is_array($record) ? count($record) : 0; ?> champs.</p>
                                </div>

                                <div class="mt-3 d-flex gap-2 justify-content-center">
                                    <a class="btn btn-sm btn-outline-secondary" href="/search?q=<?php echo urlencode($_GET['q'] ?? ''); ?>">
                                        <i class="ri-arrow-left-line me-1"></i>Retour
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                        <i class="ri-printer-line me-1"></i>Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($contraventions) && is_array($contraventions)): ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="header-title mb-0">Contraventions liées</h4>
                                    <span class="badge bg-secondary"><?php echo count($contraventions); ?></span>
                                </div>
                                <div class="table-responsive border rounded">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Infraction</th>
                                                <th>Voir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($contraventions as $idx => $cv): ?>
                                                <tr>
                                                    <td class="text-muted">
                                                        <?php 
                                                          $d = $cv['date_infraction'] ?? '';
                                                          $fmt = '';
                                                          if ($d) { $ts = strtotime((string)$d); if ($ts) { $fmt = date('d-m-Y', $ts); } }
                                                          echo htmlspecialchars($fmt ?: (string)$d);
                                                        ?>
                                                    </td>
                                                    <td style="max-width:240px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo htmlspecialchars((string)($cv['type_infraction'] ?? ($cv['infraction'] ?? ''))); ?>">
                                                        <?php echo htmlspecialchars((string)($cv['type_infraction'] ?? ($cv['infraction'] ?? ''))); ?>
                                                    </td>
                                                    <td>
                                                        <?php $modalId = 'cvModal-' . htmlspecialchars((string)($cv['id'] ?? $idx)); ?>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>" title="Voir">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php // Rendre les modals EN DEHORS du tableau pour HTML valide ?>
                        <?php foreach ($contraventions as $idx => $cv): ?>
                            <?php $modalId = 'cvModal-' . htmlspecialchars((string)($cv['id'] ?? $idx)); ?>
                            <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Détails de la contravention<?php if (!empty($cv['id'])) echo ' #' . htmlspecialchars((string)$cv['id']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="table-responsive border rounded">
                                      <table class="table table-striped table-bordered align-middle mb-0">
                                        <thead class="table-light">
                                          <tr>
                                            <th style="width:240px;">Champ</th>
                                            <th>Valeur</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php foreach ($cv as $ck => $cvv): ?>
                                            <?php if (in_array($ck, ['id','dossier_id'], true)) continue; ?>
                                            <tr>
                                              <td class="fw-bold text-uppercase small text-muted">
                                                <?php echo htmlspecialchars((string)str_replace('_',' ', $ck)); ?>
                                              </td>
                                              <td style="white-space: pre-wrap; word-break: break-word; max-width: 1000px;">
                                                <?php
                                                  if ($ck === 'date_infraction') {
                                                      $d = (string)$cvv;
                                                      $fmt = '';
                                                      if ($d) { $ts = strtotime($d); if ($ts) { $fmt = date('d-m-Y', $ts); } }
                                                      echo htmlspecialchars($fmt ?: $d);
                                                  } elseif ($ck === 'payed') {
                                                      $isPayed = ($cvv === 1 || $cvv === '1' || $cvv === true || $cvv === 'true');
                                                      echo '<span class="cv-payed-value badge ' . ($isPayed ? 'bg-success' : 'bg-warning text-dark') . '">' . ($isPayed ? 'Payée' : 'Non payée') . '</span>';
                                                      $idForJs = (int)($cv['id'] ?? 0);
                                                      $switchId = 'cv-switch-' . $idForJs;
                                                      echo ' <span class="ms-3 align-middle">'
                                                        . '<div class="form-check form-switch d-inline-flex align-items-center m-0">'
                                                        . '<input class="form-check-input cv-payed-switch" type="checkbox" role="switch" id="' . htmlspecialchars($switchId) . '" '
                                                        . ($isPayed ? 'checked ' : '')
                                                        . 'onchange="onContraventionPayedSwitchChange(' . $idForJs . ', this, \'" . $modalId . "\')" />'
                                                        . '</div>'
                                                      . '</span>';
                                                  } elseif (is_null($cvv)) {
                                                      echo '<span class="text-muted">NULL</span>';
                                                  } elseif (is_scalar($cvv)) {
                                                      echo htmlspecialchars((string)$cvv);
                                                  } else {
                                                      echo '<code>' . htmlspecialchars(json_encode($cvv, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)) . '</code>';
                                                  }
                                                ?>
                                              </td>
                                            </tr>
                                          <?php endforeach; ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-primary view-cv-detail-pdf" data-contrav-id="<?php echo htmlspecialchars((string)($cv['id'] ?? '')); ?>" title="Voir le PDF">
                                      <i class="ri-eye-line me-1"></i> Voir le PDF
                                    </button>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if ($isParticulier && !empty($pv_list)): ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="header-title mb-0">Véhicules associés</h4>
                                    <span class="badge bg-secondary"><?php echo count($pv_list); ?></span>
                                </div>
                                <div class="table-responsive border rounded">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Plaque</th>
                                                <th>Marque</th>
                                                <th>Modèle</th>
                                                <th>Couleur</th>
                                                <th>Année</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pv_list as $i => $pv): ?>
                                            <tr>
                                                <td class="text-muted"><?php echo $i+1; ?></td>
                                                <td class="fw-semibold"><?php echo htmlspecialchars((string)($pv['plaque'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars((string)($pv['marque'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars((string)($pv['modele'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars((string)($pv['couleur'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars((string)($pv['annee'] ?? '')); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($isParticulier && !empty($avis_list)): ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="header-title mb-0">Avis de recherche</h4>
                                    <span class="badge bg-secondary"><?php echo count($avis_list); ?></span>
                                </div>
                                <div class="table-responsive border rounded">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Motif</th>
                                                <th>Niveau</th>
                                                <th>Statut</th>
                                                <th>Créé le</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($avis_list as $i => $av): ?>
                                            <tr>
                                                <td class="text-muted"><?php echo $i+1; ?></td>
                                                <td style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?php echo htmlspecialchars((string)($av['motif'] ?? '')); ?>"><?php echo htmlspecialchars((string)($av['motif'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars((string)($av['niveau'] ?? '')); ?></td>
                                                <td>
                                                    <?php $st = (string)($av['statut'] ?? '');
                                                    $cls = $st === 'actif' ? 'bg-danger' : 'bg-success';
                                                    echo '<span class="badge ' . $cls . '">' . htmlspecialchars($st) . '</span>'; ?>
                                                </td>
                                                <td>
                                                    <?php $d = (string)($av['created_at'] ?? ''); $fmt = '';
                                                    if ($d) { $ts = strtotime($d); if ($ts) { $fmt = date('d-m-Y', $ts); } }
                                                    echo htmlspecialchars($fmt ?: $d);
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-xl-8 col-lg-7">
                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger mb-3"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <?php if (empty($record) || !is_array($record)): ?>
                            <div class="alert alert-warning">Aucune donnée à afficher.</div>
                        <?php else: ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="header-title mb-0">Détails</h4>
                                    </div>
                                    <div class="table-responsive border rounded">
                                        <table class="table table-striped table-bordered align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 260px;">Champ</th>
                                                    <th>Valeur</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($record as $k => $v): ?>
                                                <?php
                                                    $skipKeys = ['photo','image','image_url','avatar','picture','profil','photo_url','url_image','img','thumbnail','cover','images','temoins'];
                                                    $keyLower = strtolower((string)$k);
                                                    $isImgLike = is_string($v) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $v);
                                                    if (in_array($keyLower, $skipKeys, true) || $isImgLike) { continue; }
                                                ?>
                                                <tr>
                                                    <td class="fw-bold text-uppercase small text-muted"><?php 
                                                        $lowerKey = strtolower((string)$k);
                                                        if ($lowerKey === 'created_at') {
                                                            $labelKey = 'creer le';
                                                        } elseif ($lowerKey === 'updated_at') {
                                                            $labelKey = 'modifié le';
                                                        } else {
                                                            $labelKey = (string)$k;
                                                        }
                                                        echo htmlspecialchars($labelKey);
                                                    ?></td>
                                                    <td style="white-space: pre-wrap; word-break: break-word; max-width: 1000px;">
                                                        <?php
                                                          if ($k === 'date_expire_assurance') {
                                                              $d = (string)$v;
                                                              $fmt = '';
                                                              if ($d) { $ts = strtotime($d); if ($ts) { $fmt = date('d-m-Y', $ts); } }
                                                              echo htmlspecialchars($fmt ?: $d);
                                                              $isExpired = false;
                                                              if (!empty($d)) {
                                                                  $ts = strtotime($d);
                                                                  if ($ts !== false) { $isExpired = ($ts < strtotime(date('Y-m-d'))); }
                                                              }
                                                              if ($isExpired) {
                                                                  echo ' <span class="badge bg-danger ms-2">Assurance expirée</span>';
                                                              }
                                                          } elseif (is_null($v)) {
                                                              echo '<span class="text-muted">NULL</span>';
                                                          } elseif (is_scalar($v)) {
                                                              echo htmlspecialchars((string)$v);
                                                          } else {
                                                              echo '<code>' . htmlspecialchars(json_encode($v, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)) . '</code>';
                                                          }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php if ($tableName === 'accidents' && !empty($record['temoins']) && is_array($record['temoins'])): ?>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="header-title mb-0">Témoins</h4>
                                        <span class="badge bg-secondary"><?php echo count($record['temoins']); ?></span>
                                    </div>
                                    <div class="table-responsive border rounded">
                                        <table class="table table-sm table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Téléphone</th>
                                                    <th>Âge</th>
                                                    <th>Lien</th>
                                                    <th>Témoignage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($record['temoins'] as $idx => $t): ?>
                                                    <tr>
                                                        <td class="text-muted"><?php echo $idx + 1; ?></td>
                                                        <td><?php echo htmlspecialchars((string)($t['nom'] ?? '')); ?></td>
                                                        <td><?php echo htmlspecialchars((string)($t['telephone'] ?? '')); ?></td>
                                                        <td><?php echo htmlspecialchars((string)($t['age'] ?? '')); ?></td>
                                                        <td><?php echo htmlspecialchars((string)($t['lien_avec_accident'] ?? '')); ?></td>
                                                        <td style="white-space: pre-wrap; word-break: break-word; max-width: 420px;">
                                                            <?php echo htmlspecialchars((string)($t['temoignage'] ?? '')); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Lightbox modal -->
<div class="modal fade" id="imageLightbox" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0 position-relative">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
        <img id="lightbox-img" src="" alt="image" class="w-100" style="max-height:80vh;object-fit:contain;">
        <button type="button" class="btn btn-light position-absolute top-50 start-0 translate-middle-y opacity-75" onclick="prevImage()" aria-label="Précédent">
          <i class="ri-arrow-left-s-line"></i>
        </button>
        <button type="button" class="btn btn-light position-absolute top-50 end-0 translate-middle-y opacity-75" onclick="nextImage()" aria-label="Suivant">
          <i class="ri-arrow-right-s-line"></i>
        </button>
      </div>
    </div>
  </div>
  </div>

<script src="/assets/js/vendor.min.js"></script>
<script src="/assets/js/app.min.js"></script>
<script>
// Liste des images exposée depuis PHP
var imagesList = <?php echo json_encode(array_values($allImages), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); ?>;
var currentIndex = 0;

function updateCurrentIndexBySrc(src) {
    if (!Array.isArray(imagesList)) return;
    var idx = imagesList.indexOf(src);
    if (idx >= 0) currentIndex = idx;
}

function setMainPreview(src) {
    var img = document.getElementById('main-preview-img');
    if (img && typeof src === 'string' && src.length > 0) {
        img.src = src;
        updateCurrentIndexBySrc(src);
        img.setAttribute('data-current-index', String(currentIndex));
    }
}

function showIndex(i) {
    if (!Array.isArray(imagesList) || imagesList.length === 0) return;
    var len = imagesList.length;
    currentIndex = ((i % len) + len) % len; // wrap
    var src = imagesList[currentIndex];
    setMainPreview(src);
    var lb = document.getElementById('lightbox-img');
    if (lb) lb.src = src;
}

function openLightbox(idx) {
    if (!Array.isArray(imagesList) || imagesList.length === 0) return;
    if (typeof idx === 'number') currentIndex = idx;
    else {
        var img = document.getElementById('main-preview-img');
        var src = img ? img.src : null;
        updateCurrentIndexBySrc(src);
    }
    showIndex(currentIndex);
    var modalEl = document.getElementById('imageLightbox');
    if (modalEl && window.bootstrap && bootstrap.Modal) {
        var m = bootstrap.Modal.getOrCreateInstance(modalEl);
        m.show();
    }
}

function nextImage() { showIndex(currentIndex + 1); }
function prevImage() { showIndex(currentIndex - 1); }

document.addEventListener('DOMContentLoaded', function() {
    var img = document.getElementById('main-preview-img');
    if (img) {
        img.addEventListener('click', function() { openLightbox(); });
        // Initialiser l'index si possible
        if (imagesList && imagesList.length) updateCurrentIndexBySrc(img.src);
    }
    // Navigation clavier quand le modal est ouvert
    var modalEl = document.getElementById('imageLightbox');
    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', function() {
            document.addEventListener('keydown', keyNav);
        });
        modalEl.addEventListener('hidden.bs.modal', function() {
            document.removeEventListener('keydown', keyNav);
        });
    }
});

function keyNav(e) {
    if (e.key === 'ArrowRight') nextImage();
    else if (e.key === 'ArrowLeft') prevImage();
}

// Met à jour le statut de paiement d'une contravention et rafraîchit l'UI du modal
function updateContraventionPayed(id, payed, modalId, btn) {
    try {
        if (!id) return;
        if (btn) { btn.setAttribute('disabled', 'disabled'); btn.classList.add('disabled'); if (btn.tagName === 'INPUT') { btn.style.cursor = 'not-allowed'; } }

        var body = new URLSearchParams();
        body.set('id', String(id));
        body.set('payed', String(payed));

        return fetch('/contravention/update-payed', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: body
        }).then(function(r){ return r.json(); }).then(function(res){
            if (res && res.ok) {
                var modal = document.getElementById(modalId);
                if (modal) {
                    var badge = modal.querySelector('.cv-payed-value');
                    var isPayed = String(res.payed) === '1';
                    if (badge) {
                        badge.textContent = isPayed ? 'Payée' : 'Non payée';
                        badge.className = 'cv-payed-value badge ' + (isPayed ? 'bg-success' : 'bg-warning text-dark');
                    }
                    if (btn && btn.tagName === 'BUTTON') {
                        btn.textContent = isPayed ? 'Marquer non payée' : 'Marquer payée';
                        btn.setAttribute('onclick', 'updateContraventionPayed(' + id + ', ' + (isPayed ? 0 : 1) + ', \'" + modalId + "\', this)');
                    }
                }
            } else {
                var msg = (res && res.error) ? res.error : 'Erreur lors de la mise à jour.';
                alert(msg);
                throw new Error(msg);
            }
        }).catch(function(){
            alert('Erreur réseau, réessayez.');
            throw new Error('network');
        }).finally(function(){
            if (btn) { btn.removeAttribute('disabled'); btn.classList.remove('disabled'); if (btn.tagName === 'INPUT') { btn.style.cursor = ''; } }
        });
    } catch (e) {
        if (btn) { btn.removeAttribute('disabled'); btn.classList.remove('disabled'); if (btn.tagName === 'INPUT') { btn.style.cursor = ''; }
        }
        alert('Erreur inattendue.');
    }
}

// Handler du switch: en cas d'erreur, on rétablit la valeur précédente
function onContraventionPayedSwitchChange(id, checkbox, modalId) {
    var desired = checkbox && checkbox.checked ? 1 : 0;
    var previous = desired ? 0 : 1;
    updateContraventionPayed(id, desired, modalId, checkbox)
      .catch(function(){
          // revert on failure
          if (checkbox) checkbox.checked = previous === 1;
      });
}

// PDF viewing handler for contravention details modal
document.addEventListener('click', function(e){
  const btn = e.target.closest && e.target.closest('.view-cv-detail-pdf');
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
</script>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

</body>
</html>
