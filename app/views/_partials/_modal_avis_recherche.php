<?php
// Partial: Modal - Lancer un avis de recherche
?>
<!-- Modal: Lancer un avis de recherche -->
<div class="modal fade" id="launchAvisModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ri-megaphone-line me-2"></i>Lancer un avis de recherche</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="launchAvisForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Motif <span class="text-danger">*</span></label>
            <textarea class="form-control" name="motif" rows="3" placeholder="Renseignez le motif" required></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">Niveau</label>
            <select class="form-select" name="niveau">
              <option value="faible">Faible</option>
              <option value="moyen" selected>Moyen</option>
              <option value="eleve">Élevé</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-danger">Lancer l'avis</button>
        </div>
      </form>
    </div>
  </div>
</div>
