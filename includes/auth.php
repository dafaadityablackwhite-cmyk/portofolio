<?php
/**
 * Auth sederhana berbasis session (login via p.php).
 * Dipakai oleh dashboard.php dan project_actions.php.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(): void
{
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: p.php');
        exit();
    }
}

function no_cache_headers(): void
{
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
}
