<!-- Modal Enregistrement Conducteur -->
<div id="conducteur-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="conducteur-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="conducteur-header-modalLabel">Enregistrement d'un conducteur avec vehicule</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form id="conducteur-form" method="POST" action="/create-conducteur-vehicule" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom complet" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input type="date" name="date_naissance" id="date_naissance" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <textarea name="adresse" id="adresse" class="form-control" rows="2" placeholder="Adresse complète" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo d'identité</label>
                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                                    <small class="text-muted">Format accepté: JPG, PNG, JPEG (max 10MB)</small>
                                    <div id="photo-preview" class="mt-2" style="display: none;">
                                        <img id="photo-preview-img" src="" alt="Aperçu photo" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removePreview('photo')">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_permis" class="form-label">Numéro du permis <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_permis" id="numero_permis" class="form-control" placeholder="Numéro du permis de conduire" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="permis_valide_le" class="form-label">Date de délivrance du permis <span class="text-danger">*</span></label>
                                    <input type="date" name="permis_valide_le" id="permis_valide_le" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="permis_expire_le" class="form-label">Date d'expiration du permis <span class="text-danger">*</span></label>
                                    <input type="date" name="permis_expire_le" id="permis_expire_le" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="permis_recto" class="form-label">Permis de conduire (recto) <span class="text-danger">*</span></label>
                                    <input type="file" name="permis_recto" id="permis_recto" class="form-control" accept="image/*" required>
                                    <small class="text-muted">Format accepté: JPG, PNG, JPEG (max 10MB)</small>
                                    <div id="permis_recto-preview" class="mt-2" style="display: none;">
                                        <img id="permis_recto-preview-img" src="" alt="Aperçu permis recto" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removePreview('permis_recto')">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="permis_verso" class="form-label">Permis de conduire (verso) <span class="text-danger">*</span></label>
                                    <input type="file" name="permis_verso" id="permis_verso" class="form-control" accept="image/*" required>
                                    <small class="text-muted">Format accepté: JPG, PNG, JPEG (max 10MB)</small>
                                    <div id="permis_verso-preview" class="mt-2" style="display: none;">
                                        <img id="permis_verso-preview-img" src="" alt="Aperçu permis verso" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removePreview('permis_verso')">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Contravention (Optionnelle) -->
                        <div class="card mb-4 mt-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-file-warning-line me-2"></i>Contravention (optionnel)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_infraction" class="form-label">Date d'infraction</label>
                                            <input type="datetime-local" name="date_infraction" id="date_infraction" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lieu" class="form-label">Lieu</label>
                                            <input type="text" name="lieu" id="lieu" class="form-control" placeholder="Lieu de l'infraction">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type_infraction" class="form-label">Type d'infraction</label>
                                            <input type="text" name="type_infraction" id="type_infraction" class="form-control" placeholder="Type d'infraction">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="reference_loi" class="form-label">Référence légale</label>
                                            <input type="text" name="reference_loi" id="reference_loi" class="form-control" placeholder="Référence légale">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="amende" class="form-label">Montant de l'amende</label>
                                            <div class="input-group">
                                                <input type="number" name="amende" id="amende" class="form-control" placeholder="Montant">
                                                <span class="input-group-text">FC</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="payed" class="form-label">Statut du paiement</label>
                                            <select name="payed" id="payed" class="form-select">
                                                <option value="Non payé">Non payé</option>
                                                <option value="Payé">Payé</option>
                                                <option value="En cours">En cours</option>  
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description de l'infraction</label>
                                            <textarea name="description" id="description" class="form-control" rows="2" placeholder="Détails de l'infraction"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="contravention_photos" class="form-label">Photos de la contravention (optionnel)</label>
                                            <input type="file" name="contravention_photos[]" id="contravention_photos" class="form-control" multiple accept="image/*">
                                            <small class="text-muted">Formats acceptés: JPG, PNG, JPEG (max 2MB par fichier)</small>
                                        </div>
                                    </div>
                                </div> -->

                            </div>
                        </div>

                        <div class="px-3 pb-3 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Enregistrer le conducteur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
// Function to handle image preview
function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById(previewId + '-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        previewImg.src = '';
    }
}

// Function to remove image preview
function removePreview(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(inputId + '-preview');
    const previewImg = document.getElementById(inputId + '-preview-img');
    
    input.value = '';
    preview.style.display = 'none';
    previewImg.src = '';
}

document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for image preview
    const imageInputs = ['photo', 'permis_recto', 'permis_verso'];
    imageInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function() {
                previewImage(this, inputId + '-preview');
            });
        }
    });
    
    // Validation de la date d'expiration du permis (doit être dans le futur)
    const permisExpireInput = document.getElementById('permis_expire_le');
    if (permisExpireInput) {
        permisExpireInput.addEventListener('change', function() {
            const aujourdHui = new Date();
            aujourdHui.setHours(0, 0, 0, 0);
            const selectedDate = new Date(this.value);
            
            if (selectedDate < aujourdHui) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'La date d\'expiration du permis doit être dans le futur',
                    confirmButtonText: 'OK'
                });
                this.value = '';
            }
        });
    }
    
    // Validation du formulaire avant soumission
    const conducteurForm = document.getElementById('conducteur-form');
    if (conducteurForm) {
        conducteurForm.addEventListener('submit', function(e) {
            const aujourdHui = new Date();
            
            // Vérification de la date d'expiration du permis (doit être dans le futur)
            const dateExpiration = new Date(document.getElementById('permis_expire_le').value);
            if (dateExpiration < aujourdHui) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'La date d\'expiration du permis doit être dans le futur',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            // Vérification du fichier photo
            const photoInput = document.getElementById('photo');
            if (photoInput.files.length > 0) {
                const file = photoInput.files[0];
                const fileSize = file.size / 1024 / 1024; // Convertir en MB
                const fileType = file.type;
                
                if (fileSize > 10) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'La taille de la photo ne doit pas dépasser 10MB',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                if (!fileType.match('image.*')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Le fichier doit être une image',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            }
            
            // Si la validation est réussie, le formulaire sera soumis normalement
            return true;
        });
    }
});
</script>
