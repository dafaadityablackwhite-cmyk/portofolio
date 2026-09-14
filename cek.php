<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah mengisi data (memiliki session 'name' dan 'email')
if (!isset($_SESSION['name']) || !isset($_SESSION['email'])) {
    header("Location: p.php?pesan=belum_login");
    exit();
}
?>