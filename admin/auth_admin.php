<?php
// Pisahkan session admin agar bisa dibuka bersamaan dengan session user
// 1) Ambil session default jika ada
if (session_status() === PHP_SESSION_NONE) session_start();
$defaultSess = $_SESSION;
session_write_close();

// 2) Gunakan session khusus ADMIN
session_name('ADMINSESS');
session_start();

// 3) Sinkronisasi awal: jika default ada user admin, salin ke ADMINSESS
if (!isset($_SESSION['user_id']) && isset($defaultSess['user_id']) && (($defaultSess['user_role'] ?? '') === 'admin')) {
    $_SESSION['user_id'] = $defaultSess['user_id'];
    $_SESSION['user_name'] = $defaultSess['user_name'] ?? null;
    $_SESSION['user_email'] = $defaultSess['user_email'] ?? null;
    $_SESSION['user_role'] = 'admin';
}

// Gunakan fungsi auth dari root, yang akan membaca session aktif (ADMINSESS)
require_once __DIR__ . '/../auth.php';

function require_admin_login() {
    require_login();
    $user = current_user();
    if (!$user || (($user['role'] ?? '') !== 'admin')) {
        header('Location: ../home.php');
        exit;
    }
}

?>
