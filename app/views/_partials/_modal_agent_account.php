<!-- Modal Création Compte Agent -->
<div id="agent-account-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="agent-account-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title" id="agent-account-header-modalLabel">Création de compte agent</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form id="agent-account-form" method="POST" action="/create-agent-account">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent_nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="agent_nom" class="form-control" placeholder="Nom complet de l'agent" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent_matricule" class="form-label">Matricule <span class="text-danger">*</span></label>
                                    <input type="text" name="matricule" id="agent_matricule" class="form-control" placeholder="Matricule de l'agent" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent_poste" class="form-label">Poste <span class="text-danger">*</span></label>
                                    <input type="text" name="poste" id="agent_poste" class="form-control" placeholder="Poste occupé" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent_role" class="form-label">Rôle <span class="text-danger">*</span></label>
                                    <select name="role" id="agent_role" class="form-control" required>
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="opj">OPJ</option>
                                        <option value="admin">Administrateur</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="agent_telephone" class="form-label">Numéro de téléphone</label>
                                    <input type="tel" name="telephone" id="agent_telephone" class="form-control" placeholder="Ex: +243 123 456 789">
                                    <small class="text-muted">Format recommandé: +243 XXX XXX XXX</small>
                                </div>
                            </div>
                        </div>

                        <!-- Planning de connexion -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Plage autorisée de connexion (optionnel)</label>
                                    <small class="text-muted d-block">Sélectionnez les jours et les heures pendant lesquels l'agent est autorisé à se connecter.</small>
                                </div>
                                <div class="table-responsive border rounded p-2">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 140px;">Jour</th>
                                                <th style="width: 110px;">Autoriser</th>
                                                <th style="width: 180px;">Heure début</th>
                                                <th style="width: 180px;">Heure fin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $days = [
                                                    'mon' => 'Lundi',
                                                    'tue' => 'Mardi',
                                                    'wed' => 'Mercredi',
                                                    'thu' => 'Jeudi',
                                                    'fri' => 'Vendredi',
                                                    'sat' => 'Samedi',
                                                    'sun' => 'Dimanche'
                                                ];
                                                foreach ($days as $key => $label):
                                            ?>
                                            <tr>
                                                <td class="fw-medium"><?php echo $label; ?></td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="sched_<?php echo $key; ?>" name="schedule[<?php echo $key; ?>][enabled]" value="1">
                                                        <label class="form-check-label" for="sched_<?php echo $key; ?>">Autoriser</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control form-control-sm" name="schedule[<?php echo $key; ?>][start]" value="08:00">
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control form-control-sm" name="schedule[<?php echo $key; ?>][end]" value="17:00">
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">Laissez tout décoché pour ne pas restreindre les horaires de connexion.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent_password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="agent_password" class="form-control" placeholder="Mot de passe" required minlength="6">
                                    <small class="text-muted">Minimum 6 caractères</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agent_password_confirm" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirm" id="agent_password_confirm" class="form-control" placeholder="Confirmer le mot de passe" required minlength="6">
                                </div>
                            </div>
                        </div>

                        <div class="px-3 pb-3 text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="ri-user-add-line me-1"></i> Créer le compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation du formulaire avant soumission
    const agentAccountForm = document.getElementById('agent-account-form');
    if (agentAccountForm) {
        agentAccountForm.addEventListener('submit', function(e) {
            const password = document.getElementById('agent_password').value;
            const passwordConfirm = document.getElementById('agent_password_confirm').value;
            
            // Vérification que les mots de passe correspondent
            if (password !== passwordConfirm) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Les mots de passe ne correspondent pas',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            // Vérification de la longueur du mot de passe
            if (password.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Le mot de passe doit contenir au moins 6 caractères',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            // Si la validation est réussie, le formulaire sera soumis normalement
            return true;
        });
    }
});
</script>
