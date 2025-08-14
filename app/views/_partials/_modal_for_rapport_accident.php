


<!-- Compose Modal -->
<div id="compose-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="compose-header-modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header modal-colored-header bg-primary">
                                <h4 class="modal-title" id="compose-header-modalLabel">Informations sur l'accident</h4>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="p-1">
                                <div class="modal-body px-3 pt-3 pb-0">
                                    <form>
                                        <div class="mb-2">
                                            <label for="msgto" class="form-label">Description de l'accident</label>
                                            <textarea name="description" id="msgto" class="form-control" >

                                            </textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="mailsubject" class="form-label">Adresse de l'accident</label>
                                            <input type="text" name="adresse" id="mailsubject" class="form-control" 
                                        </div>

                                        <br/>

                                        <div class="alert alert-primary text-center">
                                            Temoins de l'accident
                                        </div>
                                        
                                        <br/>

                                        <div class="mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nouveau-temoin" placeholder="Nom et prénom du nouveau témoin">
                                                <button class="btn btn-outline-primary" type="button" id="ajouter-temoin">
                                                    <i class="ri-add-line"></i> Ajouter
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="temoins-select" class="form-label">Sélectionner ou ajouter des témoins</label>
                                            <select class="form-select" id="temoins-select" name="temoins[]" multiple>
                                                <option value="">-- Sélectionner des témoins --</option>
                                            </select>
                                            <small class="form-text text-muted">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs témoins</small>
                                        </div>

                                        <br/>

                                        <div class="alert alert-warning text-center">
                                            Personnes impliquées
                                        </div>
                                        
                                        <br/>

                                        <div class="mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nouvelle-personne" placeholder="Nom et prénom de la personne impliquée">
                                                <button class="btn btn-outline-warning" type="button" id="ajouter-personne">
                                                    <i class="ri-add-line"></i> Ajouter
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="personnes-select" class="form-label">Sélectionner ou ajouter des personnes impliquées</label>
                                            <select class="form-select" id="personnes-select" name="personnes[]" multiple>
                                                <option value="">-- Sélectionner des personnes impliquées --</option>
                                            </select>
                                            <small class="form-text text-muted">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs personnes</small>
                                        </div>

                                       
                                    </form>
                                </div>
                                <div class="px-3 pb-3 text-center">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="ri-save-line me-1"></i> Enregistrer</button>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'ajout de témoins
    const ajouterTemoinBtn = document.getElementById('ajouter-temoin');
    const nouveauTemoinInput = document.getElementById('nouveau-temoin');
    const temoinsSelect = document.getElementById('temoins-select');

    if (ajouterTemoinBtn && nouveauTemoinInput && temoinsSelect) {
        ajouterTemoinBtn.addEventListener('click', function() {
            const nomTemoin = nouveauTemoinInput.value.trim();
            
            if (nomTemoin === '') {
                alert('Veuillez saisir le nom du témoin');
                return;
            }

            // Vérifier si le témoin existe déjà
            const optionsExistantes = Array.from(temoinsSelect.options);
            const temoinExiste = optionsExistantes.some(option => 
                option.value.toLowerCase() === nomTemoin.toLowerCase()
            );

            if (temoinExiste) {
                alert('Ce témoin est déjà dans la liste');
                return;
            }

            // Ajouter le nouveau témoin à la liste
            const nouvelleOption = document.createElement('option');
            nouvelleOption.value = nomTemoin;
            nouvelleOption.textContent = nomTemoin;
            nouvelleOption.selected = true; // Sélectionner automatiquement le nouveau témoin
            
            temoinsSelect.appendChild(nouvelleOption);
            
            // Vider le champ de saisie
            nouveauTemoinInput.value = '';
            
            // Message de confirmation
            const toast = document.createElement('div');
            toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <strong>Témoin ajouté :</strong> ${nomTemoin}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(toast);
            
            // Supprimer le toast après 3 secondes
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 3000);
        });

        // Permettre l'ajout avec la touche Entrée
        nouveauTemoinInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterTemoinBtn.click();
            }
        });
    }

    // Gestion de l'ajout de personnes impliquées
    const ajouterPersonneBtn = document.getElementById('ajouter-personne');
    const nouvellePersonneInput = document.getElementById('nouvelle-personne');
    const personnesSelect = document.getElementById('personnes-select');

    if (ajouterPersonneBtn && nouvellePersonneInput && personnesSelect) {
        ajouterPersonneBtn.addEventListener('click', function() {
            const nomPersonne = nouvellePersonneInput.value.trim();
            
            if (nomPersonne === '') {
                alert('Veuillez saisir le nom de la personne impliquée');
                return;
            }

            // Vérifier si la personne existe déjà
            const optionsExistantes = Array.from(personnesSelect.options);
            const personneExiste = optionsExistantes.some(option => 
                option.value.toLowerCase() === nomPersonne.toLowerCase()
            );

            if (personneExiste) {
                alert('Cette personne est déjà dans la liste');
                return;
            }

            // Ajouter la nouvelle personne à la liste
            const nouvelleOption = document.createElement('option');
            nouvelleOption.value = nomPersonne;
            nouvelleOption.textContent = nomPersonne;
            nouvelleOption.selected = true; // Sélectionner automatiquement la nouvelle personne
            
            personnesSelect.appendChild(nouvelleOption);
            
            // Vider le champ de saisie
            nouvellePersonneInput.value = '';
            
            // Message de confirmation
            const toast = document.createElement('div');
            toast.className = 'alert alert-warning alert-dismissible fade show position-fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <strong>Personne impliquée ajoutée :</strong> ${nomPersonne}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(toast);
            
            // Supprimer le toast après 3 secondes
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 3000);
        });

        // Permettre l'ajout avec la touche Entrée
        nouvellePersonneInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                ajouterPersonneBtn.click();
            }
        });
    }
});
</script>