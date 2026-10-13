<section id="panel-project" class="tab-panel <?= $activeTab === 'project' ? 'active' : '' ?>">

  <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>" role="alert">
      <i class="fa-solid <?= $flash['type'] === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation' ?>"></i>
      <?= e($flash['message']) ?>
    </div>
  <?php endif; ?>

  <div class="glass-card">
    <div class="filter-header">
      <h2 class="section-title" style="margin-bottom: 0;">
        <i class="fa-solid fa-layer-group"></i> Galeri Project &amp; Karya
      </h2>
      <div class="filter-buttons">
        <button class="filter-btn active" data-filter="all" onclick="filterProjects('all', this)">Semua</button>
        <?php foreach (PROJECT_CATEGORIES as $key => $label): ?>
          <button class="filter-btn" data-filter="<?= e($key) ?>" onclick="filterProjects('<?= e($key) ?>', this)"><?= e($label) ?></button>
        <?php endforeach; ?>
        <button class="btn btn-primary btn-sm" onclick="openProjectForm()">
          <i class="fa-solid fa-plus"></i> Tambah
        </button>
      </div>
    </div>

    <?php if (!$projects): ?>
      <div class="empty-state">
        <i class="fa-solid fa-folder-open"></i>
        <p>Belum ada project. Klik <strong>Tambah</strong> untuk membuat yang pertama.</p>
      </div>
    <?php else: ?>
      <div class="projects-grid">
        <?php foreach ($projects as $p): ?>
          <article class="project-card" data-category="<?= e($p['category'] ?? 'web') ?>">
            <div class="project-img-placeholder">
              <i class="fa-solid <?= e($p['icon'] ?? 'fa-code') ?>"></i>
              <span class="project-badge"><?= e($p['status'] ?? 'Pengembangan') ?></span>
            </div>
            <div class="project-content">
              <h3 class="project-title"><?= e($p['title']) ?></h3>
              <p class="project-desc"><?= e($p['description']) ?></p>

              <div class="project-actions">
                <?php if (!empty($p['url'])): ?>
                  <a href="<?= e($p['url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="flex: 1;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka
                  </a>
                <?php endif; ?>
                <button type="button" class="btn btn-outline btn-sm" style="<?= empty($p['url']) ? 'flex: 1;' : '' ?>"
                        data-project="<?= e(json_encode($p, JSON_UNESCAPED_UNICODE)) ?>"
                        onclick="showProjectDetails(this)">
                  <i class="fa-solid fa-circle-info"></i> Detail
                </button>
                <button type="button" class="btn btn-outline btn-sm btn-icon" title="Edit"
                        data-project="<?= e(json_encode($p, JSON_UNESCAPED_UNICODE)) ?>"
                        onclick="openProjectForm(this)">
                  <i class="fa-solid fa-pen"></i>
                </button>
                <button type="button" class="btn btn-outline btn-sm btn-icon btn-danger" title="Hapus"
                        data-id="<?= (int)$p['id'] ?>" data-title="<?= e($p['title']) ?>"
                        onclick="openDeleteModal(this)">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</section>