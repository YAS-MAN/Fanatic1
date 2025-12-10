<?php
// Konfigurasi database (Laragon default: 127.0.0.1, user root, tanpa password)
const DB_HOST = '127.0.0.1';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'f1fanatic';
const DB_CHARSET = 'utf8mb4';

// Koneksi PDO singleton
function pdo(): PDO {
  static $pdo;
  if ($pdo instanceof PDO) return $pdo;
  $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
  $pdo = new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
  return $pdo;
}

// Back-compat: alias db() mengembalikan PDO
function db(): PDO { return pdo(); }

?>
