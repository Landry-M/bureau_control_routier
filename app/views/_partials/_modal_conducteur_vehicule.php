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
                        <div class="row mb-3">
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_new_conducteur" name="is_new_conducteur" value="1" checked>
                                    <label class="form-check-label ms-2" for="is_new_conducteur">Nouveau conducteur</label>
                                </div>
                            </div>
                            <div class="col-md-6" id="existing-conducteur-section" style="display: none;">
                                <label for="existing_conducteur_id" class="form-label">Sélectionner un conducteur existant</label>
                                <input type="text" id="existing_conducteur_search" class="form-control mb-2" placeholder="Rechercher (nom ou numéro de permis)...">
                                <select class="form-select" id="existing_conducteur_id" name="existing_conducteur_id">
                                    <option value="">-- Choisir --</option>
<?php 
    try {
        $conducteurs = \ORM::for_table('conducteur_vehicule')
            ->select_many('id','nom','numero_permis')
            ->order_by_asc('nom')
            ->find_many();
        foreach ($conducteurs as $c) {
            $label = trim(($c->nom ?? '') . ' - ' . ($c->numero_permis ?? ''));
            $cid = is_callable([$c,'id']) ? $c->id() : ($c->id ?? null);
?>
                                    <option value="<?= htmlspecialchars($cid, ENT_QUOTES) ?>">
                                        <?= htmlspecialchars($label, ENT_QUOTES) ?>
                                    </option>
<?php   }
    } catch (\Exception $e) { /* silencieux dans la vue */ }
?>
                                </select>
                            </div>
                        </div>

                        <div id="new-conducteur-section">
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
    
    // Toggle affichage nouveau vs existant
    const switchNew = document.getElementById('is_new_conducteur');
    const newSection = document.getElementById('new-conducteur-section');
    const existingSection = document.getElementById('existing-conducteur-section');
    const existingSelect = document.getElementById('existing_conducteur_id');

    const newFields = [
        'nom','date_naissance','adresse','numero_permis',
        'permis_valide_le','permis_expire_le','permis_recto','permis_verso'
    ];

    function setRequiredForNew(isNew) {
        newFields.forEach(function(id){
            const el = document.getElementById(id);
            if (!el) return;
            if (isNew) {
                // Rétablir required pour les champs nécessaires
                if (['nom','date_naissance','adresse','numero_permis','permis_valide_le','permis_expire_le','permis_recto','permis_verso'].includes(id)) {
                    el.setAttribute('required','required');
                }
            } else {
                el.removeAttribute('required');
            }
        });
        if (existingSelect) {
            if (isNew) {
                existingSelect.removeAttribute('required');
            } else {
                existingSelect.setAttribute('required','required');
            }
        }
    }

    function toggleSections() {
        const isNew = switchNew && switchNew.checked;
        if (isNew) {
            newSection && (newSection.style.display = 'block');
            existingSection && (existingSection.style.display = 'none');
        } else {
            newSection && (newSection.style.display = 'none');
            existingSection && (existingSection.style.display = 'block');
        }
        setRequiredForNew(isNew);
    }

    if (switchNew) {
        switchNew.addEventListener('change', toggleSections);
        // Init on load
        toggleSections();
    }

    // Recherche dans le select des conducteurs existants
    (function initExistingConducteurSearch() {
        const sel = document.getElementById('existing_conducteur_id');
        const input = document.getElementById('existing_conducteur_search');
        if (!sel) return;

        // Si Select2 est dispo, on l'utilise et on masque l'input custom
        if (window.jQuery && jQuery.fn && typeof jQuery.fn.select2 === 'function') {
            jQuery(sel).select2({
                width: '100%',
                placeholder: '-- Choisir --',
                allowClear: true
            });
            if (input) input.style.display = 'none';
            return;
        }

        // Fallback: filtre simple via input text
        if (input) {
            input.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                Array.from(sel.options).forEach(function (opt, idx) {
                    if (idx === 0) return; // garder placeholder
                    const txt = opt.text.toLowerCase();
                    opt.style.display = txt.indexOf(q) !== -1 ? '' : 'none';
                });
            });
        }
    })();

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
