<?php
// $query, $results expected
?>
<div class="container-fluid py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Résultats de recherche</h4>
    <form class="d-flex" action="/search" method="GET" style="max-width:420px;">
      <input type="search" class="form-control me-2" placeholder="Rechercher..." name="q" value="<?php echo htmlspecialchars($query ?? ''); ?>">
      <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
    </form>
  </div>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <?php if (($query ?? '') === ''): ?>
    <div class="alert alert-info">Saisissez un terme de recherche ci-dessus.</div>
  <?php else: ?>
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <span class="text-muted">Terme: </span>
            <span class="fw-bold">"<?php echo htmlspecialchars($query); ?>"</span>
          </div>
          <div class="text-muted">Total: <?php echo count($results ?? []); ?></div>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr>
                <th>Type</th>
                <th>ID</th>
                <th>Aperçu</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($results)): ?>
                <?php foreach ($results as $r): ?>
                  <tr>
                    <td><span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($r['type']); ?></span></td>
                    <td><?php echo htmlspecialchars($r['id']); ?></td>
                    <td class="text-truncate" style="max-width:480px;">
                      <?php echo htmlspecialchars($r['title']); ?>
                    </td>
                    <td class="text-end">
                      <a class="btn btn-outline-primary btn-sm" href="/search/detail?type=<?php echo urlencode($r['type']); ?>&id=<?php echo urlencode($r['id']); ?>">
                        Détails
                      </a>
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
  <?php endif; ?>
</div>
