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

    // Elements for Permis temporaire banner in the modal
    const permisBanner = document.getElementById('pt_permis_banner');
    const permisText = document.getElementById('pt_permis_text');
    const permisBadge = document.getElementById('pt_permis_badge');
    const btnClosePermis = document.getElementById('pt_btn_close_permis');

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

  async function loadPermisTemporaire(){
    try {
      const pid = modal.getAttribute('data-pt-id') || modal.getAttribute('data-id');
      if (!pid) return;
      const resp = await fetch(`/particulier/${encodeURIComponent(pid)}/permis-temporaire`, { method: 'GET' });
      const json = await resp.json().catch(()=>({ ok:false, data:[] }));
      if (!json || json.ok !== true) return;
      const list = Array.isArray(json.data) ? json.data : [];
      const active = list.find(p => (p.statut||'') === 'actif');
      if (active && permisBanner) {
        const numero = active.numero || '';
        const dd = active.date_debut || active.dateDebut || '';
        const df = active.date_fin || active.dateFin || '';
        const ddDisp = window.formatDMY ? (window.formatDMY(dd) || dd) : dd;
        const dfDisp = window.formatDMY ? (window.formatDMY(df) || df) : df;
        if (permisText) permisText.textContent = `${numero ? 'N° ' + numero + ' — ' : ''}du ${ddDisp} au ${dfDisp}`;
        if (btnClosePermis) btnClosePermis.setAttribute('data-permis-id', String(active.id||''));
        // Determine expiration
        let isExpired = false;
        try {
          if (df) {
            const t = new Date(); const today = new Date(t.getFullYear(), t.getMonth(), t.getDate());
            const end = new Date(df);
            const endDateOnly = new Date(end.getFullYear(), end.getMonth(), end.getDate());
            isExpired = endDateOnly < today;
          }
        } catch {}
        if (isExpired) {
          if (permisBadge) permisBadge.classList.remove('d-none');
          permisBanner.classList.remove('alert-success');
          permisBanner.classList.add('alert-danger');
        } else {
          if (permisBadge) permisBadge.classList.add('d-none');
          permisBanner.classList.remove('alert-danger');
          permisBanner.classList.add('alert-success');
        }
        permisBanner.classList.remove('d-none');
      } else if (permisBanner) {
        // No active permit
        permisBanner.classList.add('d-none');
        if (btnClosePermis) btnClosePermis.removeAttribute('data-permis-id');
      }
    } catch(_){ /* no-op */ }
  }
  }

  async function loadContraventions(){
    const tbody = document.getElementById('pt_cv_tbody');
    const modalId = modal.getAttribute('data-pt-id') || modal.getAttribute('data-id') || '';
    if (!tbody || !modalId) return;
    try {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Chargement...</td></tr>';
      // Try to grab numero national already displayed in the modal (if any)
      const numeroEl = document.getElementById('pt_numero_national');
      const numero = numeroEl ? (numeroEl.textContent || '').trim() : '';
      const url = `/particulier/${encodeURIComponent(modalId)}/contraventions` + (numero ? `?numero=${encodeURIComponent(numero)}` : '');
      const resp = await fetch(url, { method: 'GET' });
      const json = await resp.json();
      if (!json || json.ok !== true) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur de chargement</td></tr>';
        return;
      }
      const rows = Array.isArray(json.data) ? json.data : [];
      if (rows.length === 0){
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Aucune contravention</td></tr>';
        return;
      }
      tbody.innerHTML = '';
      rows.forEach((cv, idx)=>{
        const tr = document.createElement('tr');
        const payed = String((cv.payed ?? cv.paye ?? cv.paid ?? '0')) === '1';
        const ref = cv.reference ?? cv.reference_loi ?? cv.ref ?? '';
        const rawDate = cv.date ?? cv.date_infraction ?? cv.dateContravention ?? '';
        const dateDisp = window.formatDMY ? (window.formatDMY(rawDate) || '') : (rawDate || '');
        const desc = cv.description ?? cv.type_infraction ?? cv.typeInfraction ?? '';
        const montant = (cv.montant ?? cv.amende ?? cv.amount ?? 0);
        const cid = (cv.id ?? cv.contravention_id ?? cv.id_contravention ?? cv.idContravention ?? '');
        tr.innerHTML = `
          <td>${idx+1}</td>
          <td>${ref}</td>
          <td>${dateDisp}</td>
          <td>${desc}</td>
          <td>${window.formatMoneyCDF ? (window.formatMoneyCDF(montant) || '') : montant}</td>
          <td>
            <div class="form-check form-switch m-0">
              <input class="form-check-input pt-cv-payed" type="checkbox" data-cv-id="${cid}" ${payed ? 'checked' : ''}>
              <span class="ms-2 pt-cv-payed-label">${payed ? 'Payé' : 'Non payé'}</span>
            </div>
          </td>
          <td>
            <button type="button" class="btn btn-sm btn-outline-primary view-pt-contrav-pdf" data-contrav-id="${cid}" title="Voir le PDF">
              <i class="ri-eye-line"></i>
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    } catch(_) {
      if (tbody) tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur réseau</td></tr>';
    }
  }

  async function loadVehicules(){
    const tbody = document.getElementById('pt_veh_tbody');
    const countEl = document.getElementById('pt_veh_count');
    const pid = modal.getAttribute('data-pt-id') || modal.getAttribute('data-id') || '';
    if (!tbody || !pid) return;
    try {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Chargement...</td></tr>';
      const resp = await fetch(`/particulier/${encodeURIComponent(pid)}/vehicules`, { method: 'GET' });
      const json = await resp.json().catch(()=>({ ok:false, data:[] }));
      if (!json || json.ok !== true) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur de chargement</td></tr>';
        if (countEl) countEl.textContent = '0';
        return;
      }
      const rows = Array.isArray(json.data) ? json.data : [];
      if (countEl) countEl.textContent = String(rows.length);
      if (rows.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun véhicule</td></tr>';
        return;
      }
      tbody.innerHTML = '';
      rows.forEach((v, idx)=>{
        const tr = document.createElement('tr');
        const plaque = v.plaque || '';
        const marque = v.marque || '';
        const modele = v.modele || '';
        const couleur = v.couleur || '';
        const annee = v.annee || '';
        const dateAssoc = v.date_assoc || v.dateAssoc || v.created_at || '';
        const dateDisp = window.formatDMY ? (window.formatDMY(dateAssoc) || dateAssoc) : dateAssoc;
        tr.innerHTML = `
          <td>${idx+1}</td>
          <td>${plaque}</td>
          <td>${marque}${modele ? (' / ' + modele) : ''}</td>
          <td>${couleur}</td>
          <td>${annee}</td>
          <td>${dateDisp || ''}</td>
        `;
        tbody.appendChild(tr);
      });
    } catch(_) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Erreur réseau</td></tr>';
      if (countEl) countEl.textContent = '0';
    }
  }

  function formatDMY(s){
    try { return (window.formatDMY ? window.formatDMY(s) : s) || ''; } catch(_) { return s || ''; }
  }

  function processArrestRows(){
    const rows = Array.from(tbody.querySelectorAll('tr'));
    let hasDetained = false;
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
      // Compute status: any non-empty date_sortie means Libéré
      let status = 'En détention', cls = 'bg-danger';
      if (rawSortie) { status = 'Libéré'; cls = 'bg-success'; } else { hasDetained = true; }
      if (statusCell){
        // If still detained, show action button to liberate this specific arrestation
        const showBtn = (status === 'En détention');
        const aid = tr.getAttribute('data-arrest-id') || '';
        const btnHtml = showBtn ? ` <button type="button" class="btn btn-xs btn-outline-success pt-row-liberer ms-2" data-bs-toggle="modal" data-bs-target="#ptLibererModal" data-aid="${aid}">Libérer</button>` : '';
        statusCell.innerHTML = `<span class="badge ${cls}">${status}</span>${btnHtml}`;
      }
    });
    // Toggle global 'Libérer' button depending on whether any row is still detained
    try {
      const gbtn = document.getElementById('pt_btn_liberer');
      if (gbtn) {
        if (hasDetained) { gbtn.classList.remove('d-none'); gbtn.disabled = false; }
        else { gbtn.classList.add('d-none'); gbtn.disabled = true; }
      }
    } catch(_){}
  }

  async function loadArrestations(){
    const pid = modal.getAttribute('data-pt-id') || modal.getAttribute('data-id');
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
  modal.addEventListener('shown.bs.modal', ()=>{ loadArrestations(); loadContraventions(); loadPermisTemporaire(); loadVehicules(); });
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
    if (e.target && e.target.getAttribute('data-bs-target') === '#pt-cv') {
      loadContraventions();
    }
    if (e.target && e.target.getAttribute('data-bs-target') === '#pt-infos') {
      loadPermisTemporaire();
      loadVehicules();
    }
  });

  // Close active permis temporaire from the modal banner
  if (btnClosePermis) {
    btnClosePermis.addEventListener('click', async (ev)=>{
      const id = btnClosePermis.getAttribute('data-permis-id')||''; if (!id) return;
      if (!confirm('Confirmer la clôture de ce permis temporaire ?')) return;
      try {
        const resp = await fetch(`/permis-temporaire/${encodeURIComponent(id)}/close`, { method: 'POST' });
        const data = await resp.json().catch(()=>({ ok:false }));
        if (!resp.ok || !data || data.ok !== true) throw new Error((data && (data.error||data.message)) || 'Erreur serveur');
        // Refresh banner
        await loadPermisTemporaire();
        alert('Permis temporaire clôturé.');
      } catch(e) {
        alert((e && e.message) || 'Erreur réseau');
      }
    });
  }

  // Expose refresh functions for external triggers (e.g., after creation)
  try { window.__refreshPtPermis = loadPermisTemporaire; } catch(_) {}
  try { window.__refreshPtVehicules = loadVehicules; } catch(_) {}

  // PDF viewing handler for particulier contraventions (delegated)
  document.addEventListener('click', function(e){
    const btn = e.target.closest && e.target.closest('.view-pt-contrav-pdf');
    if (!btn) return;
    const contraventionId = btn.getAttribute('data-contrav-id');
    if (!contraventionId) {
      alert('ID de contravention manquant');
      return;
    }
    // Open PDF in new window/tab
    const pdfUrl = `/uploads/contraventions/contravention_${contraventionId}.pdf`;
    window.open(pdfUrl, '_blank');
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
                        <div class="row g-2">
                            <div class="col-md-6"><strong>Nom:</strong> <span id="pt_nom"></span> <span class="badge bg-danger ms-2 d-none" id="pt_arrest_badge">Arrêté</span></div>
                            <div class="col-md-6"><strong>N° National:</strong> <span id="pt_numero_national"></span></div>
                            <div class="col-md-6"><strong>Date de naissance:</strong> <span id="pt_date_naissance"></span></div>
                            <div class="col-md-6"><strong>Genre:</strong> <span id="pt_genre"></span></div>
                            <div class="col-md-6"><strong>État civil:</strong> <span id="pt_etat_civil"></span></div>
                            <div class="col-md-6"><strong>Nationalité:</strong> <span id="pt_nationalite"></span></div>
                            <div class="col-md-6"><strong>Téléphone:</strong> <span id="pt_gsm"></span></div>
                            <div class="col-md-6"><strong>Email:</strong> <span id="pt_email"></span></div>
                            <div class="col-md-6"><strong>Lieu de naissance:</strong> <span id="pt_lieu_naissance"></span></div>
                            <div class="col-md-6"><strong>Profession:</strong> <span id="pt_profession"></span></div>
                            <div class="col-12"><strong>Adresse:</strong> <span id="pt_adresse"></span></div>
                            <div class="col-12"><strong>Observations:</strong> <span id="pt_observations"></span></div>
                            <div class="col-md-4"><strong>Photo:</strong><br><img id="pt_photo" src="" alt="Photo" class="img-thumbnail d-none" style="max-height:120px"></div>
                        </div>
                        <div class="row g-3 mt-3">
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
                                        <th>PDF</th>
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
