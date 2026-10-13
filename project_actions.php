<?php
/**
 * Handler CRUD Project (create / update / delete).
 * Hanya menerima POST + CSRF token, lalu redirect balik ke dashboard.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/projects.php';

require_login();

$back = 'dashboard.php?tab=project';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $back");
    exit();
}

if (!csrf_valid($_POST['csrf_token'] ?? null)) {
    flash_set('error', 'Sesi form tidak valid. Muat ulang halaman lalu coba lagi.');
    header("Location: $back");
    exit();
}

$action = $_POST['action'] ?? '';
$id     = (int)($_POST['id'] ?? 0);

try {
    project_ensure_table($pdo);

    switch ($action) {
        case 'create':
        case 'update':
            [$data, $errors] = project_validate($_POST);

            if ($errors) {
                // Kembalikan input + error agar modal terbuka lagi dengan isian sebelumnya
                flash_set('error', implode(' ', $errors), [
                    'old' => $data + ['id' => $id, 'action' => $action],
                ]);
                break;
            }

            if ($action === 'create') {
                project_create($pdo, $data);
                flash_set('success', 'Project berhasil ditambahkan.');
            } else {
                if (!project_find($pdo, $id)) {
                    flash_set('error', 'Project tidak ditemukan.');
                    break;
                }
                project_update($pdo, $id, $data);
                flash_set('success', 'Project berhasil diperbarui.');
            }
            break;

        case 'delete':
            if (project_delete($pdo, $id)) {
                flash_set('success', 'Project berhasil dihapus.');
            } else {
                flash_set('error', 'Project tidak ditemukan.');
            }
            break;

        default:
            flash_set('error', 'Aksi tidak dikenal.');
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    flash_set('error', 'Terjadi kesalahan database. Silakan coba lagi.');
}

header("Location: $back");
exit();
