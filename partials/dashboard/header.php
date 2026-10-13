<div class="background-glow"></div>

<header class="navbar">
  <div class="nav-container">
    <a href="index.html" class="nav-logo">
      <i class="fa-solid fa-code"></i> <span>Dafa</span> Aditya
    </a>

    <!-- Main Navigation Tabs -->
    <nav class="nav-tabs" role="tablist">
      <button class="tab-btn <?= $activeTab === 'dashboard' ? 'active' : '' ?>" onclick="switchTab('dashboard')" id="tab-dashboard">
        <i class="fa-solid fa-chart-pie"></i> Dashboard
      </button>
      <button class="tab-btn <?= $activeTab === 'project' ? 'active' : '' ?>" onclick="switchTab('project')" id="tab-project">
        <i class="fa-solid fa-rocket"></i> Project
      </button>
      <button class="tab-btn <?= $activeTab === 'skill' ? 'active' : '' ?>" onclick="switchTab('skill')" id="tab-skill">
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