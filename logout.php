<?php
session_start();

// 1. Kosongkan semua variabel session
$_SESSION = array();

// 2. Hapus session dari memori server
session_unset();
session_destroy();

// 3. Hapus cookie session di browser (opsional tapi sangat disarankan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Mencegah cache browser agar tidak bisa di-back
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 5. Dialihkan kembali ke p.php
header('Location: p.php');
exit();
?>