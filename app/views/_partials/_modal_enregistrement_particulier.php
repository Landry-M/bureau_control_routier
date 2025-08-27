<!-- Modal Enregistrement Particulier -->
<div id="particulier-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="particulier-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="particulier-header-modalLabel">Enregistrement d'un particulier</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form id="particulier-form" method="POST" action="/create-particulier" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="name" class="form-control" placeholder="Nom complet" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <textarea name="adresse" id="adresse" class="form-control" rows="3" placeholder="Adresse complète de résidence" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profession" class="form-label">Profession</label>
                                    <input type="text" name="profession" id="profession" class="form-control" placeholder="Profession ou métier">
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                                    <select name="genre" id="genre" class="form-select" required>
                                        <option value="">-- Sélectionner --</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">Féminin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_national" class="form-label">Numéro national <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_national" id="numero_national" class="form-control" placeholder="Numéro de carte d'identité nationale" required>
                                </div>
                            </div>
                        </div>

                        <!-- Section informations complémentaires -->
                        <div class="alert alert-primary">
                            <i class="ri-information-line me-2"></i>
                            <strong>Informations complémentaires</strong>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="+33 1 23 45 67 89">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="exemple@email.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                                    <input type="text" name="lieu_naissance" id="lieu_naissance" class="form-control" placeholder="Ville de naissance">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nationalite" class="form-label">Nationalité</label>
                                    <input type="text" name="nationalite" id="nationalite" class="form-control" placeholder="Nationalité">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="etat_civil" class="form-label">État civil</label>
                                    <select name="etat_civil" id="etat_civil" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="celibataire">Célibataire</option>
                                        <option value="marie">Marié(e)</option>
                                        <option value="divorce">Divorcé(e)</option>
                                        <option value="veuf">Veuf/Veuve</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="personne_contact" class="form-label">Personne à contacter</label>
                                    <input type="text" name="personne_contact" id="personne_contact" class="form-control" placeholder="Nom de la personne à contacter en cas d'urgence">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="observations_particulier" class="form-label">Observations</label>
                                    <textarea name="observations" id="observations_particulier" class="form-control" rows="2" placeholder="Remarques ou observations particulières"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="photo_particulier" class="form-label">Photo (optionnel)</label>
                                    <input type="file" name="photo" id="photo_particulier" class="form-control" accept="image/*">
                                    <div class="form-text">Formats acceptés: JPG, PNG, GIF. Taille max ~5 Mo.</div>
                                    <img id="photo_particulier_preview" src="" alt="Aperçu photo" class="img-thumbnail mt-2 d-none" style="max-height: 160px;">
                                    <div id="photo_particulier_placeholder" class="text-muted small mt-2">Aucune image sélectionnée</div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="px-3 pb-3 text-center">
                    <button type="button" class="btn btn-info" id="enregistrer-particulier">
                        <i class="ri-save-line me-1"></i> Enregistrer le particulier
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'enregistrement de particulier
    const enregistrerBtn = document.getElementById('enregistrer-particulier');
    const particulierForm = document.getElementById('particulier-form');

    if (enregistrerBtn && particulierForm) {
        enregistrerBtn.addEventListener('click', function() {
            // Validation des champs obligatoires (scopée au formulaire)
            const nameEl = particulierForm.querySelector('#name');
            const adresseEl = particulierForm.querySelector('#adresse');
            const dateNaissanceEl = particulierForm.querySelector('#date_naissance');
            const genreEl = particulierForm.querySelector('#genre');
            const numeroNationalEl = particulierForm.querySelector('#numero_national');

            const name = (nameEl?.value || '').trim();
            const adresse = (adresseEl?.value || '').trim();
            const dateNaissance = (dateNaissanceEl?.value || '');
            const genre = (genreEl?.value || '');
            const numeroNational = (numeroNationalEl?.value || '').trim();

            if (name === '') {
                alert('Veuillez saisir le nom complet');
                nameEl?.focus();
                return;
            }

            if (adresse === '') {
                alert('Veuillez saisir l\'adresse');
                adresseEl?.focus();
                return;
            }

            if (dateNaissance === '') {
                alert('Veuillez saisir la date de naissance');
                dateNaissanceEl?.focus();
                return;
            }

            if (genre === '') {
                alert('Veuillez sélectionner le genre');
                genreEl?.focus();
                return;
            }

            if (numeroNational === '') {
                alert('Veuillez saisir le numéro national');
                numeroNationalEl?.focus();
                return;
            }

            // Validation de l'âge (doit être majeur)
            //const today = new Date();
            // const birthDate = new Date(dateNaissance);
            // const age = today.getFullYear() - birthDate.getFullYear();
            // const monthDiff = today.getMonth() - birthDate.getMonth();
            
            // if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            //     age--;
            // }

            // if (age < 18) {
            //     alert('La personne doit être majeure (18 ans minimum)');
            //     document.getElementById('date_naissance').focus();
            //     return;
            // }

            // Validation de l'email si renseigné
            const emailEl = particulierForm.querySelector('#email');
            const email = (emailEl?.value || '').trim();
            if (email !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Veuillez saisir une adresse email valide');
                    emailEl?.focus();
                    return;
                }
            }

            // Soumettre le formulaire vers la route /create-particulier
            particulierForm.submit();
            // Reset de l'aperçu après soumission (pour retour visuel immédiat)
            try { if (typeof resetPhotoPreview === 'function') { resetPhotoPreview(); } } catch (e) {}
        });
    }

    // Formatage automatique du numéro national
    // const numeroInput = particulierForm ? particulierForm.querySelector('#numero_national') : null;
    // if (numeroInput) {
    //     numeroInput.addEventListener('input', function(e) {
    //         // Ne garder que les chiffres et lettres
    //         e.target.value = e.target.value.replace(/[^A-Z0-9]/g, '').substring(0, 20);
    //     });
    // }

    // Validation de la date de naissance (ne pas permettre de dates futures)
    const dateInput = particulierForm ? particulierForm.querySelector('#date_naissance') : null;
    if (dateInput) {
        // Définir la date maximum à aujourd'hui
        const today = new Date();
        const maxDate = today.toISOString().split('T')[0];
        dateInput.setAttribute('max', maxDate);

        // Définir une date minimum raisonnable (120 ans en arrière)
        const minDate = new Date();
        minDate.setFullYear(today.getFullYear() - 120);
        dateInput.setAttribute('min', minDate.toISOString().split('T')[0]);
    }

    // Prévisualisation de la photo
    const photoInput = particulierForm ? particulierForm.querySelector('#photo_particulier') : null;
    const photoPreview = document.getElementById('photo_particulier_preview');
    const photoPlaceholder = document.getElementById('photo_particulier_placeholder');
    const showPlaceholder = () => { if (photoPlaceholder) photoPlaceholder.classList.remove('d-none'); };
    const hidePlaceholder = () => { if (photoPlaceholder) photoPlaceholder.classList.add('d-none'); };
    const resetPhotoPreview = () => {
        if (photoInput) photoInput.value = '';
        if (photoPreview) {
            photoPreview.src = '';
            photoPreview.classList.add('d-none');
        }
        showPlaceholder();
    };
    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                photoPreview.src = '';
                photoPreview.classList.add('d-none');
                showPlaceholder();
                return;
            }
            const maxBytes = 5 * 1024 * 1024; // 5MB
            if (!file.type.startsWith('image/')) {
                alert('Veuillez sélectionner une image valide.');
                this.value = '';
                photoPreview.src = '';
                photoPreview.classList.add('d-none');
                showPlaceholder();
                return;
            }
            if (file.size > maxBytes) {
                alert('La taille de l\'image dépasse 5 Mo. Veuillez sélectionner une image plus légère.');
                this.value = '';
                photoPreview.src = '';
                photoPreview.classList.add('d-none');
                showPlaceholder();
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target && e.target.result ? e.target.result : '';
                if (photoPreview.src) {
                    photoPreview.classList.remove('d-none');
                    hidePlaceholder();
                }
            };
            reader.readAsDataURL(file);
        });
    }

    // Reset à la fermeture du modal
    const particulierModal = document.getElementById('particulier-modal');
    if (particulierModal) {
        particulierModal.addEventListener('hidden.bs.modal', function () {
            resetPhotoPreview();
        });
    }
});
</script>
