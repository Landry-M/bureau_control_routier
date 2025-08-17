
<!-- Modal Enregistrement Entreprise -->
<div id="entreprise-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="entreprise-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title" id="entreprise-header-modalLabel">Enregistrement d'une entreprise</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form id="entreprise-form" method="POST" action="/create-entreprise">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="designation" class="form-label">Désignation de l'entreprise <span class="text-danger">*</span></label>
                                    <input type="text" name="designation" id="designation" class="form-control" placeholder="Nom de l'entreprise" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marque_vehicule" class="form-label">Marque du véhicule</label>
                                    <input type="text" name="marque_vehicule" id="marque_vehicule" class="form-control" placeholder="Ex: Toyota, Mercedes, etc.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plaque_vehicule" class="form-label">Plaque d'immatriculation</label>
                                    <input type="text" name="plaque_vehicule" id="plaque_vehicule" class="form-control" placeholder="Ex: AB-123-CD">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="siege_social" class="form-label">Siège social <span class="text-danger">*</span></label>
                                    <textarea name="siege_social" id="siege_social" class="form-control" rows="3" placeholder="Adresse complète du siège social" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_telephone" class="form-label">Téléphone</label>
                                    <input type="tel" name="contact_telephone" id="contact_telephone" class="form-control" placeholder="+33 1 23 45 67 89">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Email</label>
                                    <input type="email" name="contact_email" id="contact_email" class="form-control" placeholder="contact@entreprise.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="contact_personne" class="form-label">Personne de contact</label>
                                    <input type="text" name="contact_personne" id="contact_personne" class="form-control" placeholder="Nom et prénom de la personne de contact">
                                </div>
                            </div>
                        </div>

                        <!-- Section informations complémentaires -->
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>Informations complémentaires</strong>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_siret" class="form-label">Numéro RCCM  <span class="text-danger">*</span></label></label>
                                    <input type="text" name="numero_siret" id="numero_siret" class="form-control" placeholder="12345678901234" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="secteur_activite" class="form-label">Secteur d'activité</label>
                                    <select name="secteur_activite" id="secteur_activite" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="transport">Transport</option>
                                        <option value="logistique">Logistique</option>
                                        <option value="construction">Construction</option>
                                        <option value="commerce">Commerce</option>
                                        <option value="industrie">Industrie</option>
                                        <option value="services">Services</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observations</label>
                                    <textarea name="observations" id="observations" class="form-control" rows="2" placeholder="Remarques ou observations particulières"></textarea>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="px-3 pb-3 text-center">
                    <button type="button" class="btn btn-success" id="enregistrer-entreprise">
                        <i class="ri-save-line me-1"></i> Enregistrer l'entreprise
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'enregistrement d'entreprise
    const enregistrerBtn = document.getElementById('enregistrer-entreprise');
    const entrepriseForm = document.getElementById('entreprise-form');

    if (enregistrerBtn && entrepriseForm) {
        enregistrerBtn.addEventListener('click', function() {
            // Validation des champs obligatoires
            const designation = document.getElementById('designation').value.trim();
            const siegeSocial = document.getElementById('siege_social').value.trim();

            if (designation === '') {
                alert('Veuillez saisir la désignation de l\'entreprise');
                document.getElementById('designation').focus();
                return;
            }

            if (siegeSocial === '') {
                alert('Veuillez saisir l\'adresse du siège social');
                document.getElementById('siege_social').focus();
                return;
            }

            // Validation de l'email si renseigné
            const email = document.getElementById('contact_email').value.trim();
            if (email !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Veuillez saisir une adresse email valide');
                    document.getElementById('contact_email').focus();
                    return;
                }
            }

            // Validation du numéro RCCM/SIRET si renseigné (alphanumérique)
            const siret = document.getElementById('numero_siret').value.trim();
            if (siret !== '' && !/^[A-Za-z0-9]+$/.test(siret)) {
                alert('Le numéro RCCM doit contenir uniquement des lettres et des chiffres');
                document.getElementById('numero_siret').focus();
                return;
            }

            // Soumettre le formulaire vers la route /create-entreprise
            entrepriseForm.submit();
        });
    }

    // Formatage automatique de la plaque d'immatriculation désactivé

    // Formatage automatique du numéro RCCM/SIRET (alphanumérique, uppercase)
    const siretInput = document.getElementById('numero_siret');
    if (siretInput) {
        siretInput.addEventListener('input', function(e) {
            // Ne garder que lettres et chiffres, et forcer en majuscules
            e.target.value = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        });
    }
});
</script>
