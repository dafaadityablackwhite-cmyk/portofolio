<?php
session_start();
$_SESSION = array(); // kosongkan semua data session
session_unset(); // hapus variabel session
session_destroy(); // hancurkan session di server
header('Location: p.php');
exit();
