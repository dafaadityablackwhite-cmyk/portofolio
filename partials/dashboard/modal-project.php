<!-- Modal Tambah / Edit Project -->
<div id="projectFormModal" class="modal-overlay">
  <div class="modal-card modal-wide">
    <div class="modal-header">
      <h3 id="projectFormTitle" style="font-size: 1.1rem; color: #fff;">
        <i class="fa-solid fa-plus" style="color: var(--accent-cyan);"></i> Tambah Project
      </h3>
      <button type="button" class="modal-close" onclick="closeModal('projectFormModal')">&times;</button>
    </div>

    <form method="POST" action="project_actions.php" id="projectForm">
      <?= csrf_field() ?>
      <input type="hidden" name="action" id="pf_action" value="create">
      <input type="hidden" name="id" id="pf_id" value="">

      <div class="form-group">
        <label for="pf_title">Judul</label>
        <input type="text" id="pf_title" name="title" class="form-control" maxlength="100" required>
      </div>

      <div class="form-group">
        <label for="pf_description">Deskripsi Singkat</label>
        <input type="text" id="pf_description" name="description" class="form-control" maxlength="255" required>
      </div>

      <div class="form-group">
        <label for="pf_detail">Detail (opsional)</label>
        <textarea id="pf_detail" name="detail" class="form-control" rows="3" maxlength="2000"></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="pf_category">Kategori</label>
          <select id="pf_category" name="category" class="form-control">
            <?php foreach (PROJECT_CATEGORIES as $key => $label): ?>
              <option value="<?= e($key) ?>"><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="pf_status">Status</label>
          <select id="pf_status" name="status" class="form-control">
            <?php foreach (PROJECT_STATUSES as $s): ?>
              <option value="<?= e($s) ?>"><?= e($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="pf_icon">Ikon</label>
          <select id="pf_icon" name="icon" class="form-control">
            <?php foreach (PROJECT_ICONS as $key => $label): ?>
              <option value="<?= e($key) ?>"><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="pf_url">Link (opsional)</label>
        <input type="text" id="pf_url" name="url" class="form-control" maxlength="255" placeholder="https://... atau web.html">
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
        <button type="button" class="btn btn-outline" onclick="closeModal('projectFormModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal-overlay">
  <div class="modal-card">
    <div class="modal-header">
      <h3 style="font-size: 1.1rem; color: #fff;">
        <i class="fa-solid fa-triangle-exclamation" style="color: #f87171;"></i> Hapus Project
      </h3>
      <button type="button" class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
    </div>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">
      Yakin ingin menghapus <strong id="deleteProjectName" style="color:#fff;"></strong>? Tindakan ini tidak bisa dibatalkan.
    </p>
    <form method="POST" action="project_actions.php" style="display: flex; justify-content: flex-end; gap: 10px;">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" id="delete_id" value="">
      <button type="button" class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
      <button type="submit" class="btn btn-danger-solid"><i class="fa-solid fa-trash"></i> Hapus</button>
    </form>
  </div>
</div>

<!-- Modal Detail Project -->
<div id="projectModal" class="modal-overlay">
  <div class="modal-card">
    <div class="modal-header">
      <h3 id="projectModalTitle" style="font-size: 1.1rem; color: #fff;">Detail Project</h3>
      <button type="button" class="modal-close" onclick="closeModal('projectModal')">&times;</button>
    </div>
    <p id="projectModalDesc" style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px; white-space: pre-line;"></p>
    <div style="text-align: right;">
      <button type="button" class="btn btn-primary" onclick="closeModal('projectModal')">Tutup</button>
    </div>
  </div>
</div>

<?php if ($flash && !empty($flash['old'])): ?>
  <!-- Buka lagi form dengan isian sebelumnya bila validasi gagal -->
  <script id="project-old-input" type="application/json"><?= json_encode($flash['old'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<?php endif; ?>
