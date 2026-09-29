  <?php  
  session_start();

  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  // Cek status login
  if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
      header('Location: p.php');
      exit();
  }

  // Inisialisasi data session default jika belum ada
  if (!isset($_SESSION['name'])) {
      $_SESSION['name'] = 'Unknow';
  }
  if (!isset($_SESSION['email'])) {
      $_SESSION['email'] = 'Unknow';
  }

  // Proses pembaruan data session jika form disubmit
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['update_profile'])) {
          $_SESSION['name'] = htmlspecialchars($_POST['name'] ?? $_SESSION['name']);
          $_SESSION['email'] = htmlspecialchars($_POST['email'] ?? $_SESSION['email']);
      }
  }
  ?>
  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Interaktif - Dafa Aditya</title>
    
    <!-- Font & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
      :root {
        --bg-color: #0b0f19;
        --card-bg: rgba(23, 31, 51, 0.65);
        --card-border: rgba(255, 255, 255, 0.08);
        --accent-color: #6366f1;
        --accent-hover: #4f46e5;
        --accent-cyan: #38bdf8;
        --text-main: #f3f4f6;
        --text-muted: #9ca3af;
        --radius: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }

      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: var(--bg-color);
        color: var(--text-main);
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow-x: hidden;
      }

      /* Ambient Background Glow */
      .background-glow {
        position: absolute;
        top: -120px;
        left: 50%;
        transform: translateX(-50%);
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.22) 0%, rgba(56, 189, 248, 0.08) 45%, rgba(11, 15, 25, 0) 70%);
        z-index: -1;
        pointer-events: none;
      }

      /* Header & Navigation */
      .navbar {
        position: sticky;
        top: 0;
        z-index: 100;
        background-color: rgba(11, 15, 25, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--card-border);
        padding: 0.85rem 1.5rem;
      }

      .nav-container {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
      }

      .nav-logo {
        font-weight: 700;
        font-size: 1.25rem;
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.02em;
      }

      .nav-logo span {
        color: var(--accent-cyan);
      }

      .nav-tabs {
        display: flex;
        background: rgba(255, 255, 255, 0.04);
        padding: 4px;
        border-radius: 12px;
        border: 1px solid var(--card-border);
        gap: 4px;
      }

      .tab-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
      }

      .tab-btn:hover {
        color: var(--text-main);
        background: rgba(255, 255, 255, 0.05);
      }

      .tab-btn.active {
        background: var(--accent-color);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
      }

      .nav-user {
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .user-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--card-border);
        padding: 6px 12px;
        border-radius: 20px;
      }

      .user-avatar-mini {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
        color: white;
      }

      /* Main Container & Tab Panels */
      .container {
        max-width: 1100px;
        width: 100%;
        margin: 28px auto;
        padding: 0 20px;
        flex: 1;
      }

      .tab-panel {
        display: none;
        animation: fadeIn 0.4s ease-in-out forwards;
      }

      .tab-panel.active {
        display: block;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(8px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* Shared Card Styles */
      .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--card-border);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        margin-bottom: 24px;
      }

      /* Hero / Greeting Card */
      .welcome-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        background: linear-gradient(135deg, rgba(23, 31, 51, 0.8) 0%, rgba(30, 41, 69, 0.6) 100%);
        border: 1px solid rgba(99, 102, 241, 0.2);
      }

      .welcome-text h1 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 6px;
      }

      .welcome-text p {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 650px;
      }

      /* Stats Grid */
      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 24px;
      }

      .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius);
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: var(--transition);
      }

      .stat-card:hover {
        border-color: rgba(99, 102, 241, 0.3);
        transform: translateY(-2px);
      }

      .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.15);
        color: var(--accent-cyan);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
      }

      .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: block;
      }

      .stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
      }

      /* Multi Column Layout */
      .grid-2col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
      }

      .section-title {
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
      }

      .section-title i {
        color: var(--accent-cyan);
      }

      /* Result Info Box */
      .result-box {
        background: rgba(0, 0, 0, 0.25);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 18px;
      }

      .result-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.9rem;
      }

      .result-item:last-child {
        border-bottom: none;
      }

      .result-item span {
        color: var(--text-muted);
      }

      .badge-status {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        border: 1px solid rgba(16, 185, 129, 0.3);
      }

      /* Buttons & Utilities */
      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: var(--transition);
      }

      .btn-primary {
        background: var(--accent-color);
        color: #fff;
      }

      .btn-primary:hover {
        background: var(--accent-hover);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
      }

      .btn-outline {
        background: transparent;
        border: 1px solid var(--card-border);
        color: var(--text-main);
      }

      .btn-outline:hover {
        border-color: var(--accent-cyan);
        color: var(--accent-cyan);
        background: rgba(56, 189, 248, 0.05);
      }

      .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 8px;
      }

      /* Lists */
      .history-list {
        list-style: none;
      }

      .history-list li {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 8px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid transparent;
        transition: var(--transition);
      }

      .history-list li:hover {
        border-color: var(--card-border);
        background: rgba(255, 255, 255, 0.04);
      }

      .history-list i {
        color: var(--accent-cyan);
        font-size: 1.1rem;
        margin-top: 3px;
      }

      .history-list strong {
        display: block;
        font-size: 0.95rem;
        color: #fff;
      }

      .history-list p {
        font-size: 0.82rem;
        color: var(--text-muted);
      }

      .link-action {
        color: var(--accent-cyan);
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 6px;
        transition: var(--transition);
      }

      .link-action:hover {
        transform: translateX(4px);
        color: #fff;
      }

      /* Filter Controls */
      .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
      }

      .filter-buttons {
        display: flex;
        gap: 8px;
      }

      .filter-btn {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: var(--transition);
      }

      .filter-btn.active,
      .filter-btn:hover {
        background: var(--accent-color);
        color: #fff;
        border-color: var(--accent-color);
      }

      /* Projects Grid */
      .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 20px;
      }

      .project-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--card-border);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
      }

      .project-card:hover {
        transform: translateY(-5px);
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
      }

      .project-img-placeholder {
        width: 100%;
        height: 160px;
        background: linear-gradient(135deg, #1e293b, #0f172a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--accent-cyan);
        position: relative;
      }

      .project-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(11, 15, 25, 0.8);
        backdrop-filter: blur(8px);
        font-size: 0.72rem;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid var(--card-border);
        color: var(--text-muted);
      }

      .project-content {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
      }

      .project-title {
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: #fff;
      }

      .project-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 16px;
        flex-grow: 1;
      }

      /* Skill Items */
      .skill-category {
        margin-bottom: 20px;
      }

      .skill-item {
        margin-bottom: 16px;
      }

      .skill-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.88rem;
        margin-bottom: 6px;
        color: var(--text-main);
        font-weight: 500;
      }

      .skill-bar-bg {
        background: rgba(255, 255, 255, 0.08);
        height: 10px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.05);
      }

      .skill-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-color), var(--accent-cyan));
        border-radius: 6px;
        width: 0;
        transition: width 1s cubic-bezier(0.1, 0.5, 0.1, 1);
      }

      /* Modal Styling */
      .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: var(--transition);
        padding: 20px;
      }

      .modal-overlay.open {
        opacity: 1;
        pointer-events: auto;
      }

      .modal-card {
        background: #131b2e;
        border: 1px solid var(--card-border);
        border-radius: var(--radius);
        width: 100%;
        max-width: 500px;
        padding: 24px;
        transform: scale(0.95);
        transition: var(--transition);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
      }

      .modal-overlay.open .modal-card {
        transform: scale(1);
      }

      .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
      }

      .modal-close {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 1.2rem;
        cursor: pointer;
      }

      .form-group {
        margin-bottom: 16px;
      }

      .form-group label {
        display: block;
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 6px;
      }

      .form-control {
        width: 100%;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--card-border);
        padding: 10px 14px;
        border-radius: 8px;
        color: #fff;
        font-size: 0.9rem;
        outline: none;
        transition: var(--transition);
      }

      .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
      }

      /* Footer */
      footer {
        border-top: 1px solid var(--card-border);
        padding: 24px 20px;
        text-align: center;
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: auto;
        background: rgba(11, 15, 25, 0.5);
      }

      /* Responsive adjustments */
      @media (max-width: 768px) {
        .nav-container {
          flex-wrap: wrap;
        }
        .nav-tabs {
          order: 3;
          width: 100%;
          justify-content: center;
          margin-top: 10px;
        }
        .welcome-card {
          flex-direction: column;
          align-items: flex-start;
        }
      }
    </style>
  </head>
  <body>

    <div class="background-glow"></div>

    <header class="navbar">
      <div class="nav-container">
        <a href="index.html" class="nav-logo">
          </i> Dafa Aditya</span>
        </a>

        <!-- Main Navigation Tabs -->
        <nav class="nav-tabs" role="tablist">
          <button class="tab-btn active" onclick="switchTab('dashboard')" id="tab-dashboard">
            <i class="fa-solid fa-chart-pie"></i> Dashboard
          </button>
          <button class="tab-btn" onclick="switchTab('project')" id="tab-project">
            <i class="fa-solid fa-rocket"></i> Project
          </button>
          <button class="tab-btn" onclick="switchTab('skill')" id="tab-skill">
            <i class="fa-solid fa-code"></i> Skill
          </button>
        </nav>

        <!-- User Session Info & Logout -->
        <div class="nav-user">
          <div class="user-badge">
            <div class="user-avatar-mini">
              <?php echo strtoupper(substr($_SESSION['name'] ?? 'D', 0, 1)); ?>
            </div>
            <span class="user-name-display"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Pengguna'); ?></span>
          </div>
          <a href="logout.php" class="btn btn-outline btn-sm" title="Keluar">
            <i class="fa-solid fa-right-from-bracket"></i>
          </a>
        </div>
      </div>
    </header>

    <main class="container">

      <section id="panel-dashboard" class="tab-panel active">
        
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
              <span class="stat-value">2 Aplikasi</span>
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

      <section id="panel-project" class="tab-panel">
        
        <div class="glass-card">
          <div class="filter-header">
            <h2 class="section-title" style="margin-bottom: 0;">
              <i class="fa-solid fa-layer-group"></i> Galeri Project & Karya
            </h2>
            <div class="filter-buttons">
              <button class="filter-btn active" onclick="filterProjects('all')">Semua</button>
              <button class="filter-btn" onclick="filterProjects('app')">Aplikasi</button>
              <button class="filter-btn" onclick="filterProjects('web')">Web</button>
              <button class="filter-btn" onclick="filterProjects('game')">Game</button>
            </div>
          </div>

          <div class="projects-grid">
            
            <!-- Project 1 -->
            <article class="project-card" data-category="app">
              <div class="project-img-placeholder">
                <i class="fa-solid fa-cash-register"></i>
                <span class="project-badge">Pengembangan</span>
              </div>
              <div class="project-content">
                <h3 class="project-title">App Mesin Kasir Toko</h3>
                <p class="project-desc">Platform transaksi dan manajemen stok barang toko secara real-time yang mudah digunakan.</p>
                <div style="display: flex; gap: 8px;">
                  <a href="web_toko.html" target="_blank" class="btn btn-primary btn-sm" style="flex: 1;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka
                  </a>
                  <button class="btn btn-outline btn-sm" onclick="showProjectDetails('App Mesin Kasir Toko', 'Sistem POS untuk pencatatan penjualan dan stok barang toko keluarga.')">
                    Detail
                  </button>
                </div>
              </div>
            </article>

            <!-- Project 2 -->
            <article class="project-card" data-category="web">
              <div class="project-img-placeholder">
                <i class="fa-solid fa-paw"></i>
                <span class="project-badge">Selesai</span>
              </div>
              <div class="project-content">
                <h3 class="project-title">Web Daftar Hewan</h3>
                <p class="project-desc">Katalog interaktif yang memuat informasi detail dan ragam jenis fauna.</p>
                <div style="display: flex; gap: 8px;">
                  <a href="web.html" target="_blank" class="btn btn-primary btn-sm" style="flex: 1;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka
                  </a>
                  <button class="btn btn-outline btn-sm" onclick="showProjectDetails('Web Daftar Hewan', 'Ensiklopedia mini yang memuat ragam spesies hewan dengan desain modern.')">
                    Detail
                  </button>
                </div>
              </div>
            </article>

            <!-- Project 3 (Prototype) -->
            <article class="project-card" data-category="game">
              <div class="project-img-placeholder">
                <i class="fa-solid fa-gamepad"></i>
                <span class="project-badge">Eksperimen</span>
              </div>
              <div class="project-content">
                <h3 class="project-title">Unity Game Prototype</h3>
                <p class="project-desc">Proyek eksperimen pembelajaran mekanik pergerakan karakter dan fisik 2D/3D di Unity Engine.</p>
                <div style="display: flex; gap: 8px;">
                  <button class="btn btn-outline btn-sm" style="width: 100%" onclick="showProjectDetails('Unity Game Prototype', 'Simulasi mekanik game sederhana berbasis Unity C#.')">
                    <i class="fa-solid fa-info-circle"></i> Info Prototype
                  </button>
                </div>
              </div>
            </article>

          </div>
        </div>

      </section>

      <section id="panel-skill" class="tab-panel">
        
        <div class="grid-2col">
          <!-- Progress Bar Skills -->
          <div class="glass-card">
            <h3 class="section-title">
              <i class="fa-solid fa-code"></i> Keahlian Pemrograman
            </h3>

            <div class="skill-category">
              <div class="skill-item">
                <div class="skill-info">
                  <span>TypeScript</span>
                  <span>45%</span>
                </div>
                <div class="skill-bar-bg">
                  <div class="skill-bar-fill" data-progress="45%"></div>
                </div>
              </div>

              <div class="skill-item">
                <div class="skill-info">
                  <span>HTML5</span>
                  <span>50%</span>
                </div>
                <div class="skill-bar-bg">
                  <div class="skill-bar-fill" data-progress="50%"></div>
                </div>
              </div>

              <div class="skill-item">
                <div class="skill-info">
                  <span>JavaScript</span>
                  <span>35%</span>
                </div>
                <div class="skill-bar-bg">
                  <div class="skill-bar-fill" data-progress="35%"></div>
                </div>
              </div>

              <div class="skill-item">
                <div class="skill-info">
                  <span>CSS3</span>
                  <span>40%</span>
                </div>
                <div class="skill-bar-bg">
                  <div class="skill-bar-fill" data-progress="40%"></div>
                </div>
              </div>

              <div class="skill-item">
                <div class="skill-info">
                  <span>Unity / C#</span>
                  <span>30%</span>
                </div>
                <div class="skill-bar-bg">
                  <div class="skill-bar-fill" data-progress="30%"></div>
                </div>
              </div>

              <div class="skill-item">
                <div class="skill-info">
                  <span>PHP</span>
                  <span>40%</span>
                </div>
                <div class="skill-bar-bg">
                  <div class="skill-bar-fill" data-progress="40%"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Target Belajar & Roadmap -->
          <div class="glass-card">
            <h3 class="section-title">
              <i class="fa-solid fa-chart-line"></i> Target & Roadmap
            </h3>

            <div class="result-box" style="margin-bottom: 20px;">
              <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                <span>Fullstack & Game Dev Progress</span>
                <span style="color: var(--accent-cyan); font-weight: 600;">50%</span>
              </div>
              <div class="skill-bar-bg">
                <div class="skill-bar-fill" data-progress="50%"></div>
              </div>
            </div>

            <ul class="history-list">
              <li>
                <i class="fa-solid fa-check-circle" style="color: #10b981;"></i>
                <div>
                  <strong>Penguasaan Dasar Web</strong>
                  <p>Memahami struktur HTML, CSS layout, dan aksi JavaScript sederhana.</p>
                </div>
              </li>
              <li>
                <i class="fa-solid fa-clock" style="color: #f59e0b;"></i>
                <div>
                  <strong>Pengembangan Backend PHP & Database</strong>
                  <p>Mempelajari integrasi PHP session, form handling, dan CRUD database MySQL.</p>
                </div>
              </li>
              <li>
                <i class="fa-solid fa-spinner fa-spin" style="color: var(--accent-cyan);"></i>
                <div>
                  <strong>Eksplorasi Game Mechanic Unity</strong>
                  <p>Membuat mekanik game platformer/action 2D dasar menggunakan C#.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>

      </section>

    </main>

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

    <!-- Modal Detail Project Generic -->
    <div id="projectModal" class="modal-overlay">
      <div class="modal-card">
        <div class="modal-header">
          <h3 id="projectModalTitle" style="font-size: 1.1rem; color: #fff;">Detail Project</h3>
          <button class="modal-close" onclick="closeProjectModal()">&times;</button>
        </div>
        <p id="projectModalDesc" style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;"></p>
        <div style="text-align: right;">
          <button class="btn btn-primary" onclick="closeProjectModal()">Tutup</button>
        </div>
      </div>
    </div>

    <footer>
      <p>
        &copy; 2026 Dafa Aditya &bull; Karya Siswa SMK TI AIRLANGGA
      </p>
    </footer>

    <script>
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
      function filterProjects(category) {
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        const cards = document.querySelectorAll('.project-card');
        cards.forEach(card => {
          const cardCat = card.getAttribute('data-category');
          if (category === 'all' || cardCat === category) {
            card.style.display = 'flex';
          } else {
            card.style.display = 'none';
          }
        });
      }

      // Modals Handling
      function openEditModal() {
        document.getElementById('editModal').classList.add('open');
      }

      function closeEditModal() {
        document.getElementById('editModal').classList.remove('open');
      }

      function showProjectDetails(title, description) {
        document.getElementById('projectModalTitle').innerText = title;
        document.getElementById('projectModalDesc').innerText = description;
        document.getElementById('projectModal').classList.add('open');
      }

      function closeProjectModal() {
        document.getElementById('projectModal').classList.remove('open');
      }

      // Initialize skill bars on initial load if on skill tab
      window.onload = function() {
        animateSkills();
      };
    </script>
  </body>
  </html>