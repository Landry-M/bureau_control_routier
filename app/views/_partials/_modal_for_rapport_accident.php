

<!-- Modal Rapport d'Accident -->
<div id="compose-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="compose-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Rapport d'Accident</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form id="accident-form" enctype="multipart/form-data">
                        <!-- Informations de base de l'accident -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_accident" class="form-label">Date de l'accident *</label>
                                    <input type="datetime-local" name="date_accident" id="date_accident" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gravite" class="form-label">Gravité *</label>
                                    <select name="gravite" id="gravite" class="form-select" required>
                                        <option value="">-- Sélectionner la gravité --</option>
                                        <option value="leger">Léger</option>
                                        <option value="grave">Grave</option>
                                        <option value="mortel">Mortel</option>
                                        <option value="materiel">Matériel uniquement</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="lieu" class="form-label">Lieu de l'accident *</label>
                            <input type="text" name="lieu" id="lieu" class="form-control" placeholder="Adresse complète du lieu de l'accident" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description de l'accident *</label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Décrivez les circonstances de l'accident..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="accident-images" class="form-label">Images de l'accident</label>
                            <input type="file" name="images[]" id="accident-images" class="form-control" multiple accept="image/*">
                            <small class="form-text text-muted">Vous pouvez sélectionner plusieurs images (formats acceptés: JPG, PNG, GIF)</small>
                            <div id="accident-images-preview" class="mt-2"></div>
                        </div>

                        <hr class="my-4">

                        <!-- Section Témoins -->
                        <div class="alert alert-info text-center">
                            <i class="ri-user-line me-2"></i>Témoins de l'accident
                        </div>

                        <div id="temoins-container">
                            <!-- Les témoins seront ajoutés ici dynamiquement -->
                        </div>

                        <div class="text-center mb-3">
                            <button type="button" class="btn btn-outline-primary" id="ajouter-temoin-btn">
                                <i class="ri-add-line me-1"></i> Ajouter un témoin
                            </button>
                        </div>
                    </form>
                </div>
                <div class="px-3 pb-3 text-center">
                    <button type="button" class="btn btn-primary" id="enregistrer-accident">
                        <i class="ri-save-line me-1"></i> Enregistrer l'accident
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un témoin -->
<div id="temoin-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h5 class="modal-title">Ajouter un témoin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="temoin-form">
                    <div class="mb-3">
                        <label for="temoin_nom" class="form-label">Nom complet *</label>
                        <input type="text" id="temoin_nom" class="form-control" placeholder="Nom et prénom du témoin" required>
                    </div>
                    <div class="mb-3">
                        <label for="temoin_telephone" class="form-label">Téléphone *</label>
                        <input type="tel" id="temoin_telephone" class="form-control" placeholder="Numéro de téléphone" required>
                    </div>
                    <div class="mb-3">
                        <label for="temoin_age" class="form-label">Âge *</label>
                        <input type="number" id="temoin_age" class="form-control" min="1" max="120" placeholder="Âge du témoin" required>
                    </div>
                    <div class="mb-3">
                        <label for="temoin_lien" class="form-label">Lien avec l'accident *</label>
                        <select id="temoin_lien" class="form-select" required>
                            <option value="">-- Sélectionner le lien --</option>
                            <option value="temoin_direct">Témoin direct</option>
                            <option value="temoin_indirect">Témoin indirect</option>
                            <option value="passant">Passant</option>
                            <option value="resident">Résident du quartier</option>
                            <option value="automobiliste">Automobiliste présent</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="temoin_temoignage" class="form-label">Témoignage</label>
                        <textarea id="temoin_temoignage" class="form-control" rows="3" placeholder="Description du témoignage (optionnel)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="confirmer-temoin">
                    <i class="ri-check-line me-1"></i> Ajouter le témoin
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let temoinsData = []; // Stockage des données des témoins
    let temoinCounter = 0; // Compteur pour les IDs uniques

    // Gestion de l'aperçu des images
    const imagesInput = document.getElementById('accident-images');
    const imagesPreview = document.getElementById('accident-images-preview');

    console.log('Images input:', imagesInput);
    console.log('Images preview:', imagesPreview);

    if (imagesInput && imagesPreview) {
        imagesInput.addEventListener('change', function(e) {
            console.log('Files selected:', e.target.files);
            imagesPreview.innerHTML = '';
            const files = Array.from(e.target.files);
            
            if (files.length > 0) {
                console.log('Processing', files.length, 'files');
                const previewHeader = document.createElement('div');
                previewHeader.className = 'mb-2';
                previewHeader.innerHTML = `<small class="text-muted"><strong>${files.length} image(s) sélectionnée(s):</strong></small>`;
                imagesPreview.appendChild(previewHeader);
            }
            
            files.forEach((file, index) => {
                console.log('Processing file:', file.name, 'Type:', file.type);
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        console.log('File loaded:', file.name);
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'position-relative d-inline-block me-2 mb-2';
                        imageDiv.setAttribute('data-image-index', index);
                        
                        imageDiv.innerHTML = `
                            <div class="image-preview-container" style="position: relative;">
                                <img src="${e.target.result}" class="img-thumbnail preview-image" 
                                     style="width: 120px; height: 120px; object-fit: cover; cursor: pointer; border: 2px solid #007bff;" 
                                     onclick="openImageModal('${e.target.result.replace(/'/g, "\\'")}', '${file.name.replace(/'/g, "\\'")}')"
                                     title="Cliquer pour agrandir">
                                <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                        onclick="removeImage(${index})" 
                                        style="top: -8px; right: -8px; border-radius: 50%; width: 24px; height: 24px; padding: 0; z-index: 10;">
                                    <i class="ri-close-line" style="font-size: 12px;"></i>
                                </button>
                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-1" 
                                     style="font-size: 10px; border-radius: 0 0 6px 6px;">
                                    ${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}
                                </div>
                            </div>
                        `;
                        imagesPreview.appendChild(imageDiv);
                        console.log('Image preview added to DOM');
                    };
                    reader.onerror = function(error) {
                        console.error('Error reading file:', error);
                    };
                    reader.readAsDataURL(file);
                } else {
                    console.log('File ignored (not an image):', file.name);
                    // Afficher un message pour les fichiers non-image
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-warning alert-dismissible fade show mt-2';
                    errorDiv.innerHTML = `
                        <small><strong>Fichier ignoré:</strong> ${file.name} (format non supporté)</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    imagesPreview.appendChild(errorDiv);
                }
            });
        });
    } else {
        console.error('Images input or preview container not found!');
        if (!imagesInput) console.error('Element with ID "accident-images" not found');
        if (!imagesPreview) console.error('Element with ID "accident-images-preview" not found');
    }

    // Fonction pour supprimer une image
    window.removeImage = function(index) {
        const dt = new DataTransfer();
        const files = Array.from(imagesInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        imagesInput.files = dt.files;
        imagesInput.dispatchEvent(new Event('change'));
    };

    // Fonction pour ouvrir l'image en grand
    window.openImageModal = function(imageSrc, fileName) {
        // Créer le modal d'image s'il n'existe pas
        let imageModal = document.getElementById('image-preview-modal');
        if (!imageModal) {
            imageModal = document.createElement('div');
            imageModal.id = 'image-preview-modal';
            imageModal.className = 'modal fade';
            imageModal.innerHTML = `
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Aperçu de l'image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="modal-image" src="" class="img-fluid" style="max-height: 70vh;">
                            <div class="mt-2">
                                <small class="text-muted" id="modal-image-name"></small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(imageModal);
        }
        
        // Mettre à jour le contenu du modal
        document.getElementById('modal-image').src = imageSrc;
        document.getElementById('modal-image-name').textContent = fileName;
        
        // Afficher le modal
        const modal = new bootstrap.Modal(imageModal);
        modal.show();
    };

    // Gestion du modal témoin
    const ajouterTemoinBtn = document.getElementById('ajouter-temoin-btn');
    const temoinModal = new bootstrap.Modal(document.getElementById('temoin-modal'));
    const confirmerTemoinBtn = document.getElementById('confirmer-temoin');
    const temoinForm = document.getElementById('temoin-form');
    const temoinsContainer = document.getElementById('temoins-container');

    // Ouvrir le modal témoin
    if (ajouterTemoinBtn) {
        ajouterTemoinBtn.addEventListener('click', function() {
            // Réinitialiser le formulaire
            temoinForm.reset();
            temoinModal.show();
        });
    }

    // Confirmer l'ajout du témoin
    if (confirmerTemoinBtn) {
        confirmerTemoinBtn.addEventListener('click', function() {
            const nom = document.getElementById('temoin_nom').value.trim();
            const telephone = document.getElementById('temoin_telephone').value.trim();
            const age = document.getElementById('temoin_age').value;
            const lien = document.getElementById('temoin_lien').value;
            const temoignage = document.getElementById('temoin_temoignage').value.trim();

            // Validation
            if (!nom || !telephone || !age || !lien) {
                alert('Veuillez remplir tous les champs obligatoires');
                return;
            }

            // Ajouter le témoin aux données
            const temoinId = ++temoinCounter;
            const temoinData = {
                id: temoinId,
                nom: nom,
                telephone: telephone,
                age: age,
                lien_avec_accident: lien,
                temoignage: temoignage
            };

            temoinsData.push(temoinData);
            ajouterTemoinAuDOM(temoinData);

            // Fermer le modal et réinitialiser
            temoinModal.hide();
            temoinForm.reset();

            // Message de confirmation
            showToast('success', `Témoin "${nom}" ajouté avec succès`);
        });
    }

    // Fonction pour ajouter un témoin au DOM
    function ajouterTemoinAuDOM(temoin) {
        const temoinDiv = document.createElement('div');
        temoinDiv.className = 'card mb-3';
        temoinDiv.setAttribute('data-temoin-id', temoin.id);
        
        const temoignageHtml = temoin.temoignage ? 
            `<div class="mt-2">
                <small class="text-muted"><strong>Témoignage:</strong></small>
                <p class="small mb-0">${temoin.temoignage}</p>
            </div>` : '';
        
        temoinDiv.innerHTML = `
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-md-8">
                        <h6 class="card-title mb-1">${temoin.nom}</h6>
                        <small class="text-muted">
                            <i class="ri-phone-line me-1"></i>${temoin.telephone} | 
                            <i class="ri-user-line me-1"></i>${temoin.age} ans | 
                            <i class="ri-link me-1"></i>${getLienLabel(temoin.lien_avec_accident)}
                        </small>
                        ${temoignageHtml}
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="supprimerTemoin(${temoin.id})">
                            <i class="ri-delete-bin-line me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
                <input type="hidden" name="temoins[${temoin.id}][nom]" value="${temoin.nom}">
                <input type="hidden" name="temoins[${temoin.id}][telephone]" value="${temoin.telephone}">
                <input type="hidden" name="temoins[${temoin.id}][age]" value="${temoin.age}">
                <input type="hidden" name="temoins[${temoin.id}][lien_avec_accident]" value="${temoin.lien_avec_accident}">
                <input type="hidden" name="temoins[${temoin.id}][temoignage]" value="${temoin.temoignage || ''}">
            </div>
        `;
        
        temoinsContainer.appendChild(temoinDiv);
    }

    // Fonction pour supprimer un témoin
    window.supprimerTemoin = function(temoinId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce témoin ?')) {
            // Supprimer des données
            temoinsData = temoinsData.filter(t => t.id !== temoinId);
            
            // Supprimer du DOM
            const temoinDiv = document.querySelector(`[data-temoin-id="${temoinId}"]`);
            if (temoinDiv) {
                temoinDiv.remove();
            }
            
            showToast('info', 'Témoin supprimé');
        }
    };

    // Fonction pour obtenir le label du lien
    function getLienLabel(lien) {
        const liens = {
            'temoin_direct': 'Témoin direct',
            'temoin_indirect': 'Témoin indirect',
            'passant': 'Passant',
            'resident': 'Résident du quartier',
            'automobiliste': 'Automobiliste présent',
            'autre': 'Autre'
        };
        return liens[lien] || lien;
    }

    // Fonction pour afficher les toasts
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }

    // Gestion de l'enregistrement de l'accident
    const enregistrerBtn = document.getElementById('enregistrer-accident');
    const accidentForm = document.getElementById('accident-form');

    if (enregistrerBtn && accidentForm) {
        enregistrerBtn.addEventListener('click', function() {
            // Validation du formulaire principal
            if (!accidentForm.checkValidity()) {
                accidentForm.reportValidity();
                return;
            }

            // Désactiver le bouton pendant l'envoi
            enregistrerBtn.disabled = true;
            enregistrerBtn.innerHTML = '<i class="ri-loader-2-line me-1"></i> Enregistrement...';

            // Préparer les données pour l'envoi
            const formData = new FormData(accidentForm);
            
            // Ajouter les données des témoins
            formData.append('temoins_data', JSON.stringify(temoinsData));

            // Envoyer les données via AJAX
            fetch('/create-accident', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.text().then(text => {
                    console.log('Raw response text:', text);
                    console.log('Response text length:', text.length);
                    console.log('First 200 chars:', text.substring(0, 200));
                    console.log('Last 200 chars:', text.substring(text.length - 200));
                    
                    // Vérifier s'il y a du contenu HTML avant le JSON
                    const jsonStart = text.indexOf('{');
                    if (jsonStart > 0) {
                        console.log('Content before JSON:', text.substring(0, jsonStart));
                        text = text.substring(jsonStart);
                        console.log('Cleaned text:', text);
                    }
                    
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Response text that failed to parse:', text);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                console.log('Parsed data:', data);
                
                if (data.state === true) {
                    showToast('success', data.message || 'Accident enregistré avec succès !');
                    
                    // Réinitialiser le formulaire
                    accidentForm.reset();
                    temoinsData = [];
                    temoinCounter = 0;
                    temoinsContainer.innerHTML = '';
                    imagesPreview.innerHTML = '';
                    
                    // Fermer le modal après un délai
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('compose-modal'));
                        if (modal) {
                            modal.hide();
                        }
                    }, 2000);
                    
                } else {
                    console.log('Server returned state=false:', data);
                    showToast('danger', data.message || 'Erreur lors de l\'enregistrement');
                }
            })
            .catch(error => {
                console.error('Fetch error details:', error);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                showToast('danger', 'Erreur de connexion. Veuillez réessayer.');
            })
            .finally(() => {
                // Réactiver le bouton
                enregistrerBtn.disabled = false;
                enregistrerBtn.innerHTML = '<i class="ri-save-line me-1"></i> Enregistrer l\'accident';
            });
        });
    }
});
</script>