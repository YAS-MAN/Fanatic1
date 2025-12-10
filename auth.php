<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function is_logged_in(): bool {
  return isset($_SESSION['user_id']);
}

function current_user() {
  if (!is_logged_in()) return null;
  return [
    'id' => $_SESSION['user_id'] ?? null,
    'name' => $_SESSION['user_name'] ?? null,
    'email' => $_SESSION['user_email'] ?? null,
    'role' => $_SESSION['user_role'] ?? null,
  ];
}

function require_login() {
  if (!is_logged_in()) {
    header('Location: index.php');
    exit;
  }
}

function register_user(string $name, string $email, string $password): array {
  $email = trim($email);
  $name = trim($name);
  if ($name === '' || $email === '' || $password === '') {
    return ['ok' => false, 'error' => 'Semua field wajib diisi'];
  }

  $pdo = db();
  $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
  $stmt->execute([$email]);
  if ($stmt->fetch()) {
    return ['ok' => false, 'error' => 'Email sudah terdaftar'];
  }

  $hash = password_hash($password, PASSWORD_BCRYPT);
  // Pastikan users table punya kolom `role`. Set default registrasi sebagai 'user'.
  $ins = $pdo->prepare('INSERT INTO users(name, email, password, role) VALUES(?, ?, ?, ?)');
  $ok = $ins->execute([$name, $email, $hash, 'user']);
  if (!$ok) {
    return ['ok' => false, 'error' => 'Gagal mendaftar'];
  }
  return ['ok' => true];
}

function login_user(string $identifier, string $password): array {
  $identifier = trim($identifier);
  $pdo = db();
  // Izinkan login menggunakan email ATAU nama
  $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? OR name = ? LIMIT 1');
  $stmt->execute([$identifier, $identifier]);
  $row = $stmt->fetch();
  if (!$row) {
    return ['ok' => false, 'error' => 'Email tidak ditemukan'];
  }
  if (!password_verify($password, $row['password'])) {
    return ['ok' => false, 'error' => 'Password salah'];
  }
  $_SESSION['user_id'] = $row['id'];
  $_SESSION['user_name'] = $row['name'];
  $_SESSION['user_email'] = $row['email'];
  $_SESSION['user_role'] = $row['role'] ?? 'user';
  $stmtCart = $pdo->prepare("SELECT product_id, qty FROM cart WHERE user_id = ?");
  $stmtCart->execute([$row['id']]);
  $dbCart = $stmtCart->fetchAll(PDO::FETCH_KEY_PAIR); // Format: [id => qty]
  
  // Gabungkan dengan cart session saat ini (jika ada)
  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
  foreach ($dbCart as $pid => $qty) {
      $_SESSION['cart'][$pid] = $qty; // Prioritas DB
  }
  return ['ok' => true];
}

function logout_user() {
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
  session_destroy();
}

// Handler sederhana
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'register') {
    $out = register_user($_POST['name'] ?? '', $_POST['email'] ?? '', $_POST['password'] ?? '');
    if ($out['ok']) {
      header('Location: index.php?registered=1');
      exit;
    }
    header('Location: index.php?error=' . urlencode($out['error']));
    exit;
  } elseif ($action === 'login') {
    $out = login_user($_POST['identifier'] ?? ($_POST['email'] ?? ''), $_POST['password'] ?? '');
    if ($out['ok']) {
      // Redirect berdasarkan role yang disimpan di session
      $role = $_SESSION['user_role'] ?? 'user';
      if ($role === 'admin') {
        header('Location: admin/index.php');
        exit;
      }
      header('Location: home.php');
      exit;
    }
    header('Location: index.php?error=' . urlencode($out['error']));
    exit;
  }
}

?>
