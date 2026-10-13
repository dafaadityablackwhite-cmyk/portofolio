<?php
/**
 * Dashboard — controller tipis.
 * Markup ada di partials/dashboard/*, gaya di assets/css/dashboard.css,
 * perilaku di assets/js/dashboard.js, CRUD project di includes/projects.php
 * dan project_actions.php.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/projects.php';

no_cache_headers();
require_login();

// Default data session
$_SESSION['name']  = $_SESSION['name']  ?? 'Unknow';
$_SESSION['email'] = $_SESSION['email'] ?? 'Unknow';

// Update profil (nama & email di session)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $_SESSION['name']  = htmlspecialchars($_POST['name']  ?? $_SESSION['name']);
    $_SESSION['email'] = htmlspecialchars($_POST['email'] ?? $_SESSION['email']);
}

// Tab aktif (dipakai juga setelah redirect dari project_actions.php)
$tabs      = ['dashboard', 'project', 'skill'];
$activeTab = in_array($_GET['tab'] ?? '', $tabs, true) ? $_GET['tab'] : 'dashboard';

// Data project dari database
$projects     = [];
$projectCount = 0;
try {
    project_ensure_table($pdo);
    $projects     = project_all($pdo);
    $projectCount = count($projects);
} catch (PDOException $e) {
    error_log($e->getMessage());
    flash_set('error', 'Gagal memuat data project dari database.');
}

$flash = flash_get();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Interaktif - Dafa Aditya</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/dashboard.css" />
</head>
<body>

  <?php include __DIR__ . '/partials/dashboard/header.php'; ?>

  <main class="container">
    <?php include __DIR__ . '/partials/dashboard/panel-dashboard.php'; ?>
    <?php include __DIR__ . '/partials/dashboard/panel-project.php'; ?>
    <?php include __DIR__ . '/partials/dashboard/panel-skill.php'; ?>
  </main>

  <?php include __DIR__ . '/partials/dashboard/modal-profile.php'; ?>
  <?php include __DIR__ . '/partials/dashboard/modal-project.php'; ?>
  <?php include __DIR__ . '/partials/dashboard/footer.php'; ?>

  <script src="assets/js/dashboard.js"></script>
</body>
</html>
