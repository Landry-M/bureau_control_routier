<?php /* Modal secondaire: Libérer avec date (utilisé par particulier_details_modal) */ ?>
<div class="modal fade" id="ptLibererModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ri-door-open-line me-2"></i>Libérer le particulier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="pt_liberer_date" class="form-label">Date de libération</label>
          <input type="date" class="form-control" id="pt_liberer_date" />
        </div>
        <div class="alert alert-info py-2">
          La date choisie sera enregistrée comme <strong>date de sortie</strong> sur l'arrestation sélectionnée.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" id="pt_liberer_confirm">Valider</button>
      </div>
    </div>
  </div>
</div>
