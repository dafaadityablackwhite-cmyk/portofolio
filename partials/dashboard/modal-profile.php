<!-- Modal Edit Data Sesi -->
<div id="editModal" class="modal-overlay">
  <div class="modal-card">
    <div class="modal-header">
      <h3 style="font-size: 1.1rem; color: #fff;">
        <i class="fa-solid fa-pen-to-square" style="color: var(--accent-cyan);"></i> Edit Data Sesi
      </h3>
      <button class="modal-close" onclick="closeEditModal()">&times;</button>
    </div>
    <form method="POST" action="">
      <input type="hidden" name="update_profile" value="1">
      <div class="form-group">
        <label for="name_input">Nama Lengkap</label>
        <input type="text" id="name_input" name="name" class="form-control" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required>
      </div>
      <div class="form-group">
        <label for="email_input">Alamat Email</label>
        <input type="email" id="email_input" name="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
      </div>
      <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
        <button type="button" class="btn btn-outline" onclick="closeEditModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </div>
</div>