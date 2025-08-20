<?php /* Modal Détails du Particulier (extrait depuis consulter-dossier2.php) */ ?>
<div class="modal fade" id="particulierDetailsModal" tabindex="-1" aria-hidden="true" data-pt-id="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-contacts-book-2-line me-2"></i>Détails du particulier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>



<script>
// Post-process arrestations rows: format dates and add status badge
(function(){
  function init(){
    const modal = document.getElementById('particulierDetailsModal');
    const tbody = document.getElementById('pt_arrest_tbody');
    if (!modal || !tbody) return;

  function parseISODate(s){
    if (!s) return null;
    // Accept formats like YYYY-MM-DD or DD/MM/YYYY; try native Date first
    let d = new Date(s);
    if (!isNaN(d.getTime())) return new Date(d.getFullYear(), d.getMonth(), d.getDate());
    // Try DD/MM/YYYY
    const m = /^\s*(\d{1,2})\/(\d{1,2})\/(\d{4})\s*$/.exec(s);
    if (m){
      const dd = parseInt(m[1],10), mm = parseInt(m[2],10)-1, yy = parseInt(m[3],10);
      const d2 = new Date(yy, mm, dd);
      if (!isNaN(d2.getTime())) return d2;
    }
    return null;
  }

  function formatDMY(s){
    try { return (window.formatDMY ? window.formatDMY(s) : s) || ''; } catch(_) { return s || ''; }
  }

  function processArrestRows(){
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.forEach((tr)=>{
      const cells = tr.querySelectorAll('td');
      // Skip placeholder rows
      if (cells.length === 1) return;
      // Ensure 5th cell exists for Status
      if (cells.length < 5){
        const td = document.createElement('td');
        tr.appendChild(td);
      }
      const dateSortieCell = tr.querySelector('td:nth-child(4)');
      const statusCell = tr.querySelector('td:nth-child(5)');
      const rawSortie = (dateSortieCell?.textContent || '').trim();
      // Format date sortie
      if (dateSortieCell){ dateSortieCell.textContent = formatDMY(rawSortie); }
      // Compute status
      let status = 'En détention', cls = 'bg-danger';
      const d = parseISODate(rawSortie);
      if (rawSortie && d){
        const today = new Date(); today.setHours(0,0,0,0);
        if (d.getTime() <= today.getTime()) { status = 'Libéré'; cls = 'bg-success'; }
      } else if (!rawSortie) {
        status = 'En détention'; cls = 'bg-danger';
      }
      if (statusCell){
        // If still detained, show action button to liberate this specific arrestation
        const showBtn = (status === 'En détention');
        const aid = tr.getAttribute('data-arrest-id') || '';
        const btnHtml = showBtn ? ` <button type="button" class="btn btn-xs btn-outline-success pt-row-liberer ms-2" data-bs-toggle="modal" data-bs-target="#ptLibererModal" data-aid="${aid}">Libérer</button>` : '';
        statusCell.innerHTML = `<span class="badge ${cls}">${status}</span>${btnHtml}`;
      }
    });
  }

  async function loadArrestations(){
    const pid = modal.getAttribute('data-pt-id');
    if (!pid) return;
    try {
      const resp = await fetch(`/particulier/${encodeURIComponent(pid)}/arrestations`, { method: 'GET' });
      const data = await resp.json();
      const items = (data && (data.items || data.data)) || [];
      // Clear tbody
      tbody.innerHTML = '';
      if (!Array.isArray(items) || items.length === 0){
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucune arrestation</td></tr>';
        processArrestRows();
        return;
      }
      let i = 1;
      items.forEach(row => {
        const tr = document.createElement('tr');
        const id = row.id || row.ID || row.arrestation_id || '';
        const dArr = row.date_arrestation || row.date || '';
        const motif = row.motif || '';
        const dSort = row.date_sortie_prison || row.date_sortie || '';
        tr.setAttribute('data-arrest-id', id);
        tr.innerHTML = `
          <td>${i++}</td>
          <td>${dArr || ''}</td>
          <td>${motif}</td>
          <td>${dSort || ''}</td>
          <td></td>
        `;
        tbody.appendChild(tr);
      });
      processArrestRows();
    } catch(_) {
      // fallback message
      tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erreur de chargement</td></tr>';
    }
  }

  // Re-process when modal is shown (rows likely injected just before)
  modal.addEventListener('shown.bs.modal', ()=>{ loadArrestations(); });
  // Expose for other scripts if needed
  try {
    window.__processPtArrestRows = processArrestRows;
    window.__setParticulierIdForModal = function(pid){
      if (!modal) return; modal.setAttribute('data-pt-id', pid || '');
    };
    window.openParticulierDetailsModal = function(pid){
      if (pid) modal.setAttribute('data-pt-id', pid);
      const bsModal = bootstrap && bootstrap.Modal ? new bootstrap.Modal(modal) : null;
      if (bsModal) { bsModal.show(); } else { modal.classList.add('show'); modal.style.display = 'block'; }
    };
  } catch(_) {}
  // Observe tbody mutations to update dynamically
  try{
    const mo = new MutationObserver(()=> processArrestRows());
    mo.observe(tbody, { childList: true, subtree: false });
  }catch(_){ /* no-op */ }

  // When switching to the Arrestations tab, ensure data is loaded
  document.addEventListener('shown.bs.tab', (e)=>{
    if (e.target && e.target.getAttribute('data-bs-target') === '#pt-arrest') {
      loadArrestations();
    }
  });

  // Row click: select an arrestation (for using the bottom 'Libérer' button if needed)
  tbody.addEventListener('click', (e)=>{
    const tr = e.target.closest('tr'); if (!tr || tr.querySelectorAll('td').length === 1) return;
    // Toggle selection style
    Array.from(tbody.querySelectorAll('tr')).forEach(r=> r.classList.remove('table-active'));
    tr.classList.add('table-active');
    const aid = tr.getAttribute('data-arrest-id') || '';
    if (aid) modal.setAttribute('data-selected-arrest-id', aid);
  });

  // Delegate: per-row 'Libérer' button opens modal with that arrestation id
  tbody.addEventListener('click', (e)=>{
    const btn = e.target.closest('.pt-row-liberer'); if (!btn) return;
    const tr = btn.closest('tr'); const aid = btn.getAttribute('data-aid') || tr?.getAttribute('data-arrest-id');
    if (!aid) return;
    modal.setAttribute('data-liberer-arrest-id', aid);
    if (libererModalEl) libererModalEl.dataset.aid = String(aid);
    const todayIso = new Date().toISOString().slice(0,10);
    if (libererDateInput) libererDateInput.value = todayIso;
    // Prefer Bootstrap data API via attributes; fallback if JS modal instance available
    if (libererModal) libererModal.show(); else if (libererModalEl && !libererModalEl.classList.contains('show')) { libererModalEl.classList.add('show'); libererModalEl.style.display = 'block'; }
  });

  // Wire "Libérer" flow with date picker modal (bottom button requires selected row)
  const btnLiberer = document.getElementById('pt_btn_liberer');
  const libererModalEl = document.getElementById('ptLibererModal');
  const libererDateInput = document.getElementById('pt_liberer_date');
  const libererConfirmBtn = document.getElementById('pt_liberer_confirm');
  let libererModal = null;
  if (libererModalEl && window.bootstrap && bootstrap.Modal) {
    libererModal = new bootstrap.Modal(libererModalEl);
  }
  function openLibererForSelected(){
      const aid = modal.getAttribute('data-selected-arrest-id');
      if (!aid) { alert('Sélectionnez d\'abord une arrestation à libérer'); return; }
      modal.setAttribute('data-liberer-arrest-id', aid);
      const todayIso = new Date().toISOString().slice(0,10);
      if (libererDateInput) libererDateInput.value = todayIso;
      // Let Bootstrap data API also open it
      if (btnLiberer){
        btnLiberer.setAttribute('data-bs-toggle', 'modal');
        btnLiberer.setAttribute('data-bs-target', '#ptLibererModal');
      }
      if (libererModal) libererModal.show();
      else if (libererModalEl && !libererModalEl.classList.contains('show')) { libererModalEl.classList.add('show'); libererModalEl.style.display = 'block'; }
  }
  if (btnLiberer) {
    btnLiberer.addEventListener('click', openLibererForSelected);
  }
  // Document-level delegation in case the button is injected after init
  document.addEventListener('click', (e)=>{
    const b = e.target.closest && e.target.closest('#pt_btn_liberer');
    if (!b) return;
    openLibererForSelected();
  });
  // On showing the liberation modal, ensure we have an arrestation id and default date set
  if (libererModalEl) {
    libererModalEl.addEventListener('show.bs.modal', (ev)=>{
      try {
        const rt = ev.relatedTarget;
        let aid = libererModalEl.dataset.aid || modal.getAttribute('data-liberer-arrest-id') || modal.getAttribute('data-selected-arrest-id') || '';
        if (!aid && rt) {
          const a = rt.getAttribute ? (rt.getAttribute('data-aid') || '') : '';
          if (a) aid = a; else {
            const tr = rt.closest ? rt.closest('tr') : null;
            aid = tr ? (tr.getAttribute('data-arrest-id') || '') : aid;
          }
        }
        if (aid) { modal.setAttribute('data-liberer-arrest-id', aid); libererModalEl.dataset.aid = String(aid); }
        const todayIso = new Date().toISOString().slice(0,10);
        if (libererDateInput && !libererDateInput.value) libererDateInput.value = todayIso;
      } catch(_) { /* no-op */ }
    });
  }
  if (libererConfirmBtn) {
    libererConfirmBtn.addEventListener('click', async ()=>{
      const aid = libererModalEl?.dataset?.aid || modal.getAttribute('data-liberer-arrest-id') || modal.getAttribute('data-selected-arrest-id');
      if (!aid) { alert('Aucune arrestation sélectionnée'); return; }
      const dateVal = (libererDateInput && libererDateInput.value) ? libererDateInput.value : '';
      libererConfirmBtn.disabled = true;
      try {
        const resp = await fetch(`/arrestation/${encodeURIComponent(aid)}/release`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
          body: new URLSearchParams({ date_sortie_prison: dateVal }).toString()
        });
        const data = await resp.json();
        if (!resp.ok || !data || data.ok !== true) {
          throw new Error((data && (data.error || data.message)) || 'Erreur lors de la libération');
        }
        // Update UI: mark the released row's date with selected date
        const targetRow = Array.from(tbody.querySelectorAll('tr')).find(tr => (tr.getAttribute('data-arrest-id') || '') === String(aid));
        if (targetRow) {
          const td4 = targetRow.querySelector('td:nth-child(4)');
          if (td4) td4.textContent = dateVal;
        }
        processArrestRows();
        modal.removeAttribute('data-liberer-arrest-id');
        if (libererModal) libererModal.hide();
        else if (libererModalEl) { libererModalEl.classList.remove('show'); libererModalEl.style.display = 'none'; }
      } catch (e) {
        alert(e.message || 'Erreur réseau');
      } finally {
        libererConfirmBtn.disabled = false;
      }
    });
  }
  }
  console.log('[PT] Init particulier modal scripts');
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ()=> setTimeout(init, 0));
  } else {
    // Defer to let the rest of this partial render before querying elements
    setTimeout(init, 0);
  }
})();
</script>

            <div class="modal-body">
                <ul class="nav nav-tabs" id="ptTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pt-infos-tab" data-bs-toggle="tab" data-bs-target="#pt-infos" type="button" role="tab">Informations</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pt-cv-tab" data-bs-toggle="tab" data-bs-target="#pt-cv" type="button" role="tab">Contraventions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pt-arrest-tab" data-bs-toggle="tab" data-bs-target="#pt-arrest" type="button" role="tab">Arrestations</button>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="pt-infos" role="tabpanel" aria-labelledby="pt-infos-tab">
                        <div class="mb-3 p-3 rounded-2 border bg-light">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="text-muted small">Nom</div>
                                    <div class="fw-semibold fs-5"><span id="pt_nom"></span><span class="badge bg-danger ms-2 d-none" id="pt_arrest_badge">Arrêté</span></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">N° National</div>
                                    <div class="fw-semibold" id="pt_numero_national"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-muted small">Date de naissance</div>
                                <div class="fw-medium" id="pt_date_naissance"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Genre</div>
                                <div class="fw-medium" id="pt_genre"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">État civil</div>
                                <div class="fw-medium" id="pt_etat_civil"></div>
                            </div>
                            <div class="col-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-12">
                                <div class="text-muted small">Adresse</div>
                                <div class="fw-medium" id="pt_adresse"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Profession</div>
                                <div class="fw-medium" id="pt_profession"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Personne de contact</div>
                                <div class="fw-medium" id="pt_personne_contact"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Téléphone</div>
                                <div class="fw-medium" id="pt_gsm"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Email</div>
                                <div class="fw-medium" id="pt_email"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Nationalité</div>
                                <div class="fw-medium" id="pt_nationalite"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Lieu de naissance</div>
                                <div class="fw-medium" id="pt_lieu_naissance"></div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted small">Observations</div>
                                <div class="fw-medium" id="pt_observations"></div>
                            </div>
                            <div class="col-12">
                                <div id="pt_avis_banner" class="alert alert-warning d-none d-flex align-items-center justify-content-between" role="alert">
                                    <div>
                                        <i class="ri-megaphone-line me-2"></i>
                                        <strong>Avis de recherche actif</strong>
                                        <span class="ms-2" id="pt_avis_text"></span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-dark" id="pt_btn_close_avis" data-avis-id="">Clore l'avis</button>
                                    </div>
                                </div>
                                <div id="pt_permis_banner" class="alert alert-success d-none d-flex align-items-center justify-content-between" role="alert">
                                    <div>
                                        <i class="ri-id-card-line me-2"></i>
                                        <strong>Permis temporaire actif</strong>
                                        <span class="ms-2" id="pt_permis_text"></span>
                                        <span class="badge bg-danger ms-2 d-none" id="pt_permis_badge">Expiré</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-dark" id="pt_btn_close_permis" data-permis-id="">Clore le permis</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end" style="position:relative; z-index: 2000;">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="pt_btn_launch_avis">
                                    <i class="ri-megaphone-line me-1"></i> Lancer un avis de recherche
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success ms-2" id="pt_btn_launch_permis" data-action="launch-permis" style="pointer-events:auto; position: relative; z-index: 2001;" onclick="return window.__launchPermisFromBtn ? window.__launchPermisFromBtn(event, this) : false;">
                                    <i class="ri-id-card-line me-1"></i> Émettre un permis temporaire
                                </button>
                            </div>
                            <div class="col-12"><hr class="my-3"></div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="mb-0"><i class="ri-car-line me-2 text-info"></i>Véhicules associés</h6>
                                    <span class="badge bg-secondary" id="pt_veh_count">0</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px;">#</th>
                                                <th>Plaque</th>
                                                <th>Marque/Modèle</th>
                                                <th>Couleur</th>
                                                <th>Année</th>
                                                <th>Depuis</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pt_veh_tbody">
                                            <tr><td colspan="6" class="text-center text-muted">Aucun véhicule</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pt-cv" role="tabpanel" aria-labelledby="pt-cv-tab">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Référence</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Montant</th>
                                        <th>Payé</th>
                                    </tr>
                                </thead>
                                <tbody id="pt_cv_tbody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pt-arrest" role="tabpanel" aria-labelledby="pt-arrest-tab">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date d'arrestation</th>
                                        <th>Motif</th>
                                        <th>Date de sortie</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="pt_arrest_tbody">
                                    <tr><td colspan="5" class="text-center text-muted">Chargement...</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-sm btn-outline-success" id="pt_btn_liberer" title="Libérer ce particulier">
                                <i class="ri-door-open-line me-1"></i> Libérer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
