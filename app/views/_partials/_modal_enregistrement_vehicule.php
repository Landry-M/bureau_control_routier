<!-- Modal Enregistrement Véhicule -->
<div id="vehicule-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vehicule-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="vehicule-header-modalLabel">Enregistrement d'un véhicule</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form id="vehicule-form" method="POST" action="/create-vehicule-plaque" enctype="multipart/form-data">
                        
                        <!-- Section Informations Véhicule -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-car-line me-2"></i>Informations du véhicule</h5>
                            </div>
                            <div class="card-body">
                                <!-- Images du véhicule -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Images du véhicule <span class="text-danger">*</span></label>
                                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" required>
                                            <div class="form-text">Vous pouvez sélectionner plusieurs images (formats acceptés: JPG, PNG, GIF)</div>
                                            <div id="image-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="marque" class="form-label">Marque <span class="text-danger">*</span></label>
                                            <input type="text" name="marque" id="marque" class="form-control" placeholder="Ex: Toyota, Peugeot, Mercedes..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="annee" class="form-label">Année <span class="text-danger">*</span></label>
                                            <input type="number" name="annee" id="annee" class="form-control" min="1900" max="2030" placeholder="2023" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="couleur" class="form-label">Couleur <span class="text-danger">*</span></label>
                                            <input type="text" name="couleur" id="couleur" class="form-control" placeholder="Ex: Blanc, Noir, Rouge..." required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="modele" class="form-label">Modèle</label>
                                            <input type="text" name="modele" id="modele" class="form-control" placeholder="Ex: Corolla, 308, C-Class">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="numero_chassis" class="form-label">Numéro de châssis</label>
                                            <input type="text" name="numero_chassis" id="numero_chassis" class="form-control" placeholder="Ex: VF3XXXXXXXXXXXXXX">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="frontiere_entree" class="form-label">Frontière d'entrée</label>
                                            <input type="text" name="frontiere_entree" id="frontiere_entree" class="form-control" placeholder="Ex: Kasumbalesa, Goma, etc.">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_importation" class="form-label">Date d'importation</label>
                                            <input type="date" name="date_importation" id="date_importation" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations plaque d'immatriculation -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="plaque" class="form-label">Plaque d'immatriculation <span class="text-danger">*</span></label>
                                            <input type="text" name="plaque" id="plaque" class="form-control" placeholder="Ex: AB-123-CD" style="text-transform: uppercase;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="plaque_valide_le" class="form-label">Plaque valide le</label>
                                            <input type="date" name="plaque_valide_le" id="plaque_valide_le" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="plaque_expire_le" class="form-label">Plaque expire le</label>
                                            <input type="date" name="plaque_expire_le" id="plaque_expire_le" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Assurance -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-shield-check-line me-2"></i>Informations d'assurance</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nume_assurance" class="form-label">Numéro d'assurance</label>
                                            <input type="text" name="nume_assurance" id="nume_assurance" class="form-control" placeholder="Numéro de police d'assurance">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="societe_assurance" class="form-label">Société d'assurance</label>
                                            <input type="text" name="societe_assurance" id="societe_assurance" class="form-control" placeholder="Nom de la compagnie d'assurance">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_valide_assurance" class="form-label">Assurance valide le</label>
                                            <input type="date" name="date_valide_assurance" id="date_valide_assurance" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_expire_assurance" class="form-label">Assurance expire le</label>
                                            <input type="date" name="date_expire_assurance" id="date_expire_assurance" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="px-3 pb-3 text-center">
                    <button type="button" class="btn btn-primary" id="enregistrer-vehicule">
                        <i class="ri-save-line me-1"></i> Enregistrer le véhicule
                    </button>
                    <button type="button" class="btn btn-light ms-1" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Annuler
                    </button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Constantes
    const MAX_FILE_SIZE = 8 * 1024 * 1024; // 8MB in bytes
    
    // Gestion de l'aperçu des images multiples avec validation de taille
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            imagePreview.innerHTML = '';
            const files = Array.from(e.target.files);
            const validFiles = [];
            let hasInvalidFile = false;
            
            // Vérifier chaque fichier
            files.forEach((file, index) => {
                if (!file.type.startsWith('image/')) {
                    showError('Seuls les fichiers images sont acceptés');
                    hasInvalidFile = true;
                    return;
                }
                
                if (file.size > MAX_FILE_SIZE) {
                    showError(`Le fichier ${file.name} dépasse la taille maximale de 8 Mo`);
                    hasInvalidFile = true;
                    return;
                }
                
                validFiles.push(file);
                
                // Afficher l'aperçu
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative';
                    imgContainer.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle" 
                                style="width: 25px; height: 25px; padding: 0;" onclick="removeImage(${validFiles.length - 1})">
                            <i class="ri-close-line" style="font-size: 12px;"></i>
                        </button>
                    `;
                    imagePreview.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            });
            
            // Mettre à jour les fichiers sélectionnés
            if (hasInvalidFile) {
                const dt = new DataTransfer();
                validFiles.forEach(file => dt.items.add(file));
                imageInput.files = dt.files;
                
                if (validFiles.length === 0) {
                    imagePreview.innerHTML = '';
                }
            }
        });
    }
    
    // Fonction pour afficher les messages d'erreur
    function showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: message,
                confirmButtonText: 'OK'
            });
        } else {
            alert(message);
        }
    }


    // Validation des dates d'expiration
    const plaqueValideLe = document.getElementById('plaque_valide_le');
    const plaqueExpireLe = document.getElementById('plaque_expire_le');
    const assuranceValideLe = document.getElementById('date_valide_assurance');
    const assuranceExpireLe = document.getElementById('date_expire_assurance');

    function validateDates(startDate, endDate, errorMessage) {
        if (startDate.value && endDate.value) {
            if (new Date(startDate.value) >= new Date(endDate.value)) {
                endDate.setCustomValidity(errorMessage);
            } else {
                endDate.setCustomValidity('');
            }
        }
    }

    if (plaqueValideLe && plaqueExpireLe) {
        plaqueValideLe.addEventListener('change', () => {
            validateDates(plaqueValideLe, plaqueExpireLe, 'La date d\'expiration doit être postérieure à la date de validité');
        });
        plaqueExpireLe.addEventListener('change', () => {
            validateDates(plaqueValideLe, plaqueExpireLe, 'La date d\'expiration doit être postérieure à la date de validité');
        });
    }

    if (assuranceValideLe && assuranceExpireLe) {
        assuranceValideLe.addEventListener('change', () => {
            validateDates(assuranceValideLe, assuranceExpireLe, 'La date d\'expiration de l\'assurance doit être postérieure à la date de validité');
        });
        assuranceExpireLe.addEventListener('change', () => {
            validateDates(assuranceValideLe, assuranceExpireLe, 'La date d\'expiration de l\'assurance doit être postérieure à la date de validité');
        });
    }

    // Gestion de l'enregistrement de véhicule avec validation avancée
    const enregistrerBtn = document.getElementById('enregistrer-vehicule');
    const vehiculeForm = document.getElementById('vehicule-form');

    if (enregistrerBtn && vehiculeForm) {
        enregistrerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Vérifier si des images sont sélectionnées
            const imageInput = document.getElementById('images');
            if (imageInput && (!imageInput.files || imageInput.files.length === 0)) {
                showError('Veuillez sélectionner au moins une image du véhicule');
                return;
            }
            
            // Vérifier la taille des fichiers
            if (imageInput) {
                const files = Array.from(imageInput.files);
                const oversizedFiles = files.filter(file => file.size > MAX_FILE_SIZE);
                
                if (oversizedFiles.length > 0) {
                    showError(`Certains fichiers dépassent la taille maximale de 8 Mo`);
                    return;
                }
            }
            
            // Vérifier la validité du formulaire
            if (vehiculeForm.checkValidity()) {
                // Envoi classique (POST vers la route déjà créée)
                vehiculeForm.submit();
            } else {
                // Afficher les erreurs de validation
                vehiculeForm.reportValidity();
            }
        });
    }
});

// Fonction pour supprimer une image de l'aperçu
function removeImage(index) {
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    
    // Créer un nouveau FileList sans le fichier à supprimer
    const dt = new DataTransfer();
    const files = Array.from(imageInput.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    imageInput.files = dt.files;
    
    // Mettre à jour l'aperçu
    imagePreview.innerHTML = '';
    
    // Recréer l'aperçu avec les fichiers restants
    if (dt.files.length > 0) {
        Array.from(dt.files).forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'position-relative';
                imgContainer.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle" 
                            style="width: 25px; height: 25px; padding: 0;" onclick="removeImage(${i})">
                        <i class="ri-close-line" style="font-size: 12px;"></i>
                    </button>
                `;
                imagePreview.appendChild(imgContainer);
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
