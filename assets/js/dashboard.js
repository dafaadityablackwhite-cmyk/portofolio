// Tab Switching Functionality
function switchTab(tabName) {
  // Hide all panels
  const panels = document.querySelectorAll('.tab-panel');
  panels.forEach(panel => panel.classList.remove('active'));

  // Deactivate all tab buttons
  const tabBtns = document.querySelectorAll('.tab-btn');
  tabBtns.forEach(btn => btn.classList.remove('active'));

  // Activate selected panel and button
  const targetPanel = document.getElementById('panel-' + tabName);
  const targetBtn = document.getElementById('tab-' + tabName);

  if (targetPanel && targetBtn) {
    targetPanel.classList.add('active');
    targetBtn.classList.add('active');
  }

  // If switching to skill tab, trigger progress bar animation
  if (tabName === 'skill') {
    animateSkills();
  }
}

// Animate Progress Bars
function animateSkills() {
  const skillBars = document.querySelectorAll('.skill-bar-fill');
  skillBars.forEach(bar => {
    const targetWidth = bar.getAttribute('data-progress');
    if (targetWidth) {
      bar.style.width = '0%';
      setTimeout(() => {
        bar.style.width = targetWidth;
      }, 100);
    }
  });
}

// Filter Projects by Category
function filterProjects(category, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');

  document.querySelectorAll('.project-card').forEach(card => {
    const match = category === 'all' || card.dataset.category === category;
    card.style.display = match ? 'flex' : 'none';
  });
}

// Modals Handling
function openModal(id) {
  document.getElementById(id).classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

function openEditModal() { openModal('editModal'); }
function closeEditModal() { closeModal('editModal'); }

// Tutup modal saat klik area gelap atau tekan Esc
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
  }
});

// ===== CRUD Project =====
function readProject(btn) {
  try { return JSON.parse(btn.dataset.project); } catch (_) { return null; }
}

function showProjectDetails(btn) {
  const p = readProject(btn);
  if (!p) return;
  document.getElementById('projectModalTitle').innerText = p.title;
  document.getElementById('projectModalDesc').innerText = p.detail || p.description;
  openModal('projectModal');
}

// Tanpa argumen = mode tambah, dengan tombol = mode edit
function openProjectForm(btn) {
  const p = btn ? readProject(btn) : null;
  fillProjectForm(p);
  openModal('projectFormModal');
}

function fillProjectForm(p) {
  const editing = !!(p && p.id);
  document.getElementById('projectFormTitle').innerHTML = editing
    ? '<i class="fa-solid fa-pen" style="color: var(--accent-cyan);"></i> Edit Project'
    : '<i class="fa-solid fa-plus" style="color: var(--accent-cyan);"></i> Tambah Project';

  document.getElementById('pf_action').value = editing ? 'update' : 'create';
  document.getElementById('pf_id').value = editing ? p.id : '';
  document.getElementById('pf_title').value = p?.title ?? '';
  document.getElementById('pf_description').value = p?.description ?? '';
  document.getElementById('pf_detail').value = p?.detail ?? '';
  document.getElementById('pf_category').value = p?.category ?? 'web';
  document.getElementById('pf_status').value = p?.status ?? 'Pengembangan';
  document.getElementById('pf_icon').value = p?.icon ?? 'fa-code';
  document.getElementById('pf_url').value = p?.url ?? '';
}

function openDeleteModal(btn) {
  document.getElementById('delete_id').value = btn.dataset.id;
  document.getElementById('deleteProjectName').innerText = btn.dataset.title;
  openModal('deleteModal');
}

// Init
window.addEventListener('load', () => {
  animateSkills();

  // Buka lagi form dengan isian sebelumnya bila validasi server gagal
  const old = document.getElementById('project-old-input');
  if (old) {
    try {
      const data = JSON.parse(old.textContent);
      fillProjectForm(data.action === 'update' ? data : { ...data, id: null });
      openModal('projectFormModal');
    } catch (_) {}
  }

  // Alert sukses hilang otomatis
  document.querySelectorAll('.alert-success').forEach(el => {
    setTimeout(() => { el.style.display = 'none'; }, 4000);
  });
});
