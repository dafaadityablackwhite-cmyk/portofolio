<?php
/**
 * Model Project — semua query CRUD ada di sini (PDO + prepared statement).
 */

const PROJECT_CATEGORIES = [
    'app'  => 'Aplikasi',
    'web'  => 'Web',
    'game' => 'Game',
];

const PROJECT_STATUSES = ['Pengembangan', 'Selesai', 'Eksperimen'];

// Ikon Font Awesome yang boleh dipilih (allowlist)
const PROJECT_ICONS = [
    'fa-cash-register' => 'Kasir',
    'fa-paw'           => 'Hewan',
    'fa-gamepad'       => 'Game',
    'fa-globe'         => 'Web',
    'fa-mobile-screen' => 'Mobile',
    'fa-database'      => 'Database',
    'fa-code'          => 'Kode',
    'fa-rocket'        => 'Roket',
];

/** Buat tabel bila belum ada, lalu isi data awal jika masih kosong. */
function project_ensure_table(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title       VARCHAR(100) NOT NULL,
        description VARCHAR(255) NOT NULL,
        detail      TEXT NULL,
        category    ENUM('app','web','game') NOT NULL DEFAULT 'web',
        status      VARCHAR(30)  NOT NULL DEFAULT 'Pengembangan',
        icon        VARCHAR(40)  NOT NULL DEFAULT 'fa-code',
        url         VARCHAR(255) NULL,
        created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    project_migrate_columns($pdo);

    if ((int)$pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn() === 0) {
        $seed = $pdo->prepare('INSERT INTO projects (title, description, detail, category, status, icon, url)
                               VALUES (?, ?, ?, ?, ?, ?, ?)');
        $rows = [
            ['App Mesin Kasir Toko',
             'Platform transaksi dan manajemen stok barang toko secara real-time yang mudah digunakan.',
             'Sistem POS untuk pencatatan penjualan dan stok barang toko keluarga.',
             'app', 'Pengembangan', 'fa-cash-register', 'web_toko.html'],
            ['Web Daftar Hewan',
             'Katalog interaktif yang memuat informasi detail dan ragam jenis fauna.',
             'Ensiklopedia mini yang memuat ragam spesies hewan dengan desain modern.',
             'web', 'Selesai', 'fa-paw', 'web.html'],
            ['Unity Game Prototype',
             'Proyek eksperimen pembelajaran mekanik pergerakan karakter dan fisik 2D/3D di Unity Engine.',
             'Simulasi mekanik game sederhana berbasis Unity C#.',
             'game', 'Eksperimen', 'fa-gamepad', null],
        ];
        foreach ($rows as $r) {
            $seed->execute($r);
        }
    }
}

/**
 * Tabel `projects` versi lama (dibuat sebelum ada kolom baru) tidak ikut berubah
 * oleh CREATE TABLE IF NOT EXISTS. Fungsi ini menambahkan kolom yang belum ada.
 */
function project_migrate_columns(PDO $pdo): void
{
    $existing = [];
    foreach ($pdo->query('SHOW COLUMNS FROM projects')->fetchAll() as $col) {
        $existing[strtolower($col['Field'])] = true;
    }

    $needed = [
        'detail'     => "TEXT NULL",
        'category'   => "ENUM('app','web','game') NOT NULL DEFAULT 'web'",
        'status'     => "VARCHAR(30) NOT NULL DEFAULT 'Pengembangan'",
        'icon'       => "VARCHAR(40) NOT NULL DEFAULT 'fa-code'",
        'url'        => "VARCHAR(255) NULL",
        'created_at' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
        'updated_at' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
    ];

    foreach ($needed as $name => $definition) {
        if (!isset($existing[$name])) {
            $pdo->exec("ALTER TABLE projects ADD COLUMN `$name` $definition");
        }
    }
}

/* ---------- READ ---------- */

function project_all(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM projects ORDER BY id DESC')->fetchAll();
}

function project_count(PDO $pdo): int
{
    return (int)$pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
}

function project_find(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

/* ---------- VALIDASI ---------- */

/**
 * @return array [data bersih, daftar error]
 */
function project_validate(array $in): array
{
    $data = [
        'title'       => trim((string)($in['title'] ?? '')),
        'description' => trim((string)($in['description'] ?? '')),
        'detail'      => trim((string)($in['detail'] ?? '')),
        'category'    => (string)($in['category'] ?? ''),
        'status'      => (string)($in['status'] ?? ''),
        'icon'        => (string)($in['icon'] ?? ''),
        'url'         => trim((string)($in['url'] ?? '')),
    ];
    $errors = [];

    if ($data['title'] === '' || mb_strlen($data['title']) > 100) {
        $errors[] = 'Judul wajib diisi (maks. 100 karakter).';
    }
    if ($data['description'] === '' || mb_strlen($data['description']) > 255) {
        $errors[] = 'Deskripsi singkat wajib diisi (maks. 255 karakter).';
    }
    if (mb_strlen($data['detail']) > 2000) {
        $errors[] = 'Detail maksimal 2000 karakter.';
    }
    if (!isset(PROJECT_CATEGORIES[$data['category']])) {
        $errors[] = 'Kategori tidak valid.';
    }
    if (!in_array($data['status'], PROJECT_STATUSES, true)) {
        $errors[] = 'Status tidak valid.';
    }
    if (!isset(PROJECT_ICONS[$data['icon']])) {
        $errors[] = 'Ikon tidak valid.';
    }
    if ($data['url'] !== '' && !project_url_ok($data['url'])) {
        $errors[] = 'URL harus berupa http(s):// atau nama file lokal (contoh: web.html).';
    }

    return [$data, $errors];
}

function project_url_ok(string $url): bool
{
    if (mb_strlen($url) > 255) {
        return false;
    }
    if (preg_match('#^https?://[^\s]+$#i', $url)) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    // path relatif lokal: tanpa skema, tanpa "..", tanpa "//" di awal
    return preg_match('#^[A-Za-z0-9_\-][A-Za-z0-9_\-./ ]*$#', $url) === 1
        && strpos($url, '..') === false;
}

/* ---------- CREATE / UPDATE / DELETE ---------- */

function project_create(PDO $pdo, array $d): int
{
    $stmt = $pdo->prepare('INSERT INTO projects (title, description, detail, category, status, icon, url)
                           VALUES (:title, :description, :detail, :category, :status, :icon, :url)');
    $stmt->execute(project_params($d));
    return (int)$pdo->lastInsertId();
}

function project_update(PDO $pdo, int $id, array $d): bool
{
    $stmt = $pdo->prepare('UPDATE projects
                           SET title = :title, description = :description, detail = :detail,
                               category = :category, status = :status, icon = :icon, url = :url
                           WHERE id = :id');
    $stmt->execute(project_params($d) + ['id' => $id]);
    return $stmt->rowCount() >= 0;
}

function project_delete(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}

function project_params(array $d): array
{
    return [
        'title'       => $d['title'],
        'description' => $d['description'],
        'detail'      => $d['detail'] !== '' ? $d['detail'] : null,
        'category'    => $d['category'],
        'status'      => $d['status'],
        'icon'        => $d['icon'],
        'url'         => $d['url'] !== '' ? $d['url'] : null,
    ];
}