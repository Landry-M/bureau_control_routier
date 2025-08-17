<?php
// $table, $record expected
?>
<div class="container-fluid py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Détail: <span class="text-uppercase badge bg-secondary"><?php echo htmlspecialchars($table ?? ''); ?></span></h4>
    <div>
      <a class="btn btn-outline-secondary btn-sm" href="/search?q=<?php echo urlencode($_GET['q'] ?? ''); ?>">&larr; Retour aux résultats</a>
    </div>
  </div>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <?php if (empty($record) || !is_array($record)): ?>
    <div class="alert alert-warning">Aucune donnée à afficher.</div>
  <?php else: ?>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered align-middle">
            <thead>
              <tr>
                <th style="width: 240px;">Champ</th>
                <th>Valeur</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($record as $k => $v): ?>
                <tr>
                  <td class="fw-bold"><?php echo htmlspecialchars((string)$k); ?></td>
                  <td style="white-space: pre-wrap; word-break: break-word; max-width: 1000px;">
                    <?php
                      if (is_null($v)) {
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
  <?php endif; ?>
</div>
