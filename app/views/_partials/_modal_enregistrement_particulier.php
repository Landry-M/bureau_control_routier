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
                    <form id="particulier-form" method="POST" action="/create-particulier">
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

                        <!-- Section Contravention -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-file-warning-line me-2"></i>Contravention (optionnel)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_infraction" class="form-label">Date d'infraction</label>
                                            <input type="datetime-local" name="date_infraction" id="date_infraction" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>">
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
                                            <input type="text" name="reference_loi" id="reference_loi" class="form-control" placeholder="Ex: Article 123 du Code de la route">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Description détaillée de l'infraction"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="amende" class="form-label">Montant de l'amende</label>
                                            <div class="input-group">
                                                <input type="number" name="amende" id="amende" class="form-control" placeholder="0" min="0" step="0.01">
                                                <span class="input-group-text">Fc</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="payed" class="form-label">Statut de paiement</label>
                                            <select name="payed" id="payed" class="form-select">
                                                <option value="">-- Sélectionner --</option>
                                                <option value="Non payé">Non payé</option>
                                                <option value="Payé">Payé</option>
                                                <option value="En cours">En cours</option>
                                            </select>
                                        </div>
                                    </div>
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
            // Validation des champs obligatoires
            const name = document.getElementById('name').value.trim();
            const adresse = document.getElementById('adresse').value.trim();
            const dateNaissance = document.getElementById('date_naissance').value;
            const genre = document.getElementById('genre').value;
            const numeroNational = document.getElementById('numero_national').value.trim();

            if (name === '') {
                alert('Veuillez saisir le nom complet');
                document.getElementById('name').focus();
                return;
            }

            if (adresse === '') {
                alert('Veuillez saisir l\'adresse');
                document.getElementById('adresse').focus();
                return;
            }

            if (dateNaissance === '') {
                alert('Veuillez saisir la date de naissance');
                document.getElementById('date_naissance').focus();
                return;
            }

            if (genre === '') {
                alert('Veuillez sélectionner le genre');
                document.getElementById('genre').focus();
                return;
            }

            if (numeroNational === '') {
                alert('Veuillez saisir le numéro national');
                document.getElementById('numero_national').focus();
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
            const email = document.getElementById('email').value.trim();
            if (email !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Veuillez saisir une adresse email valide');
                    document.getElementById('email').focus();
                    return;
                }
            }

            // Soumettre le formulaire vers la route /create-particulier
            particulierForm.submit();
        });
    }

    // Formatage automatique du numéro national
    const numeroInput = document.getElementById('numero_national');
    if (numeroInput) {
        numeroInput.addEventListener('input', function(e) {
            // Ne garder que les chiffres et lettres
            e.target.value = e.target.value.replace(/[^A-Z0-9]/g, '').substring(0, 20);
        });
    }

    // Validation de la date de naissance (ne pas permettre de dates futures)
    const dateInput = document.getElementById('date_naissance');
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
});
</script>
