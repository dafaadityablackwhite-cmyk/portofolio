<?php
require_once 'koneksi.php';

$pesan = "";

// Memeriksa apakah form telah di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form
    $nama   = trim($_POST['nama']);
    $detail = trim($_POST['detail']);

    // Validasi sederhana
    if (!empty($nama) && !empty($detail)) {
        try {
            // 1. Menyiapkan statement SQL INSERT dengan Placeholders (:nama, :detail)
            $sql = "INSERT INTO nama_tabel (nama, detail) VALUES (:nama, :detail)";
            $stmt = $pdo->prepare($sql);

            // 2. Binds parameter dan eksekusi kueri
            $simpan = $stmt->execute([
                ':nama'   => $nama,
                ':detail' => $detail
            ]);

            if ($simpan) {
                // Redirect ke halaman utama jika berhasil
                header("Location: index.php");
                exit();
            }
        } catch (PDOException $e) {
            $pesan = "Gagal menyimpan data: " . $e->getMessage();
        }
    } else {
        $pesan = "Semua kolom input wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Baru</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 8px 15px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        .alert { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h2>Form Tambah Data</h2>

    <?php if (!empty($pesan)): ?>
        <div class="alert"><?= htmlspecialchars($pesan); ?></div>
    <?php endif; ?>

    <form action="tambah.php" method="POST">
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>
        </div>

        <div class="form-group">
            <label for="detail">Kategori / Detail:</label>
            <textarea id="detail" name="detail" rows="4" required></textarea>
        </div>

        <button type="submit">Simpan Data</button>
        <a href="index.php">Batal</a>
    </form>

</body>
</html>