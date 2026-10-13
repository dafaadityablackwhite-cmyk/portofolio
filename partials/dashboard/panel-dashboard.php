<section id="panel-dashboard" class="tab-panel <?= $activeTab === 'dashboard' ? 'active' : '' ?>">
  
  <!-- Greeting Card -->
  <div class="glass-card welcome-card">
    <div class="welcome-text">
      <h1>Selamat Datang Kembali, <span style="color: var(--accent-cyan);"><?php echo htmlspecialchars($_SESSION['name']); ?></span>! 👋</h1>
      <p>Anda telah masuk ke Panel Kontrol Portofolio. Di sini Anda dapat melihat ringkasan performa web, progres pembelajaran, serta karya terbaru.</p>
    </div>
    <div class="welcome-actions">
      <a href="index.html" class="btn btn-primary">
        <i class="fa-solid fa-globe"></i> Lihat Utama
      </a>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon"><i class="fa-solid fa-user-check"></i></div>
      <div>
        <span class="stat-label">Status Akun</span>
        <span class="stat-value">Aktif</span>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon"><i class="fa-solid fa-envelope"></i></div>
      <div style="overflow: hidden;">
        <span class="stat-label">Email Terdaftar</span>
        <span class="stat-value" style="font-size: 0.9rem; word-break: break-all;">
          <?php echo htmlspecialchars($_SESSION['email']); ?>
        </span>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon"><i class="fa-solid fa-diagram-project"></i></div>
      <div>
        <span class="stat-label">Proyek Berjalan</span>
        <span class="stat-value"><?= (int)$projectCount ?> Project</span>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon"><i class="fa-solid fa-graduation-cap"></i></div>
      <div>
        <span class="stat-label">Rata-rata Skill</span>
        <span class="stat-value">40% Mastery</span>
      </div>
    </div>
  </div>

  <!-- Grid 2 Kolom untuk Informasi Sesi & Aktivitas -->
  <div class="grid-2col">
    <!-- Informasi Sesi -->
    <div class="glass-card">
      <h3 class="section-title">
        <i class="fa-solid fa-id-card"></i> Informasi Sesi Anda
      </h3>
      <div class="result-box">
        <div class="result-item">
          <span>Nama Lengkap:</span>
          <strong id="session-name-display"><?php echo htmlspecialchars($_SESSION['name']); ?></strong>
        </div>
        <div class="result-item">
          <span>Alamat Email:</span>
          <strong id="session-email-display"><?php echo htmlspecialchars($_SESSION['email']); ?></strong>
        </div>
        <div class="result-item">
          <span>Akses Status:</span>
          <span class="badge-status">Pengunjung Terverifikasi</span>
        </div>
      </div>
      <button class="btn btn-outline" onclick="openEditModal()">
        <i class="fa-solid fa-pen-to-square"></i> Edit Data Sesi
      </button>
    </div>

    <!-- Pintasan Akses Cepat -->
    <div class="glass-card">
      <h3 class="section-title">
        <i class="fa-solid fa-rocket"></i> Akses Cepat Karya
      </h3>
      <ul class="history-list">
        <li>
          <i class="fa-solid fa-cash-register"></i>
          <div>
            <strong>App Mesin Kasir Toko</strong>
            <p>Status: Dalam Pengembangan (Web Toko)</p>
            <a href="web_toko.html" class="link-action" target="_blank">Buka Aplikasi &rarr;</a>
          </div>
        </li>
        <li>
          <i class="fa-solid fa-paw"></i>
          <div>
            <strong>Web Daftar Hewan</strong>
            <p>Status: Selesai</p>
            <a href="web.html" class="link-action" target="_blank">Buka Website &rarr;</a>
          </div>
        </li>
      </ul>
    </div>
  </div>

</section>
