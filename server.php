<?php
// server.php — simple PHP + MySQL (PDO) auth API for signup/login
// Works on XAMPP/MariaDB by using utf8mb4_general_ci (not 0900 collations)

// Debug code removed
$DB_HOST = '127.0.0.1';
$DB_PORT = 3306;
$DB_NAME = 'lapzone';   // make sure this DB exists (or create it in phpMyAdmin)
$DB_USER = 'root';
$DB_PASS = '';

// CORS (allow your frontend origins; add more if needed)
$allowed_origins = [
  'http://localhost',
  'http://localhost:5500',
  'http://127.0.0.1:5500',
  'http://localhost:8000',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins, true)) {
  header("Access-Control-Allow-Origin: $origin");
  header("Access-Control-Allow-Credentials: true");
}
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

// ====== HELPERS ======
function respond($data = null, int $status = 200) {
  http_response_code($status);
  echo json_encode(['ok' => $status < 400, 'data' => $data], JSON_UNESCAPED_UNICODE);
  exit;
}
function fail($message, int $status = 400) { respond(['message' => $message], $status); }

// Use sessions if you want session-based "me" and "logout"
session_name('lapzone_session');
session_start();

// ====== CONNECT DB ======
try {
  $dsn = "mysql:host={$GLOBALS['DB_HOST']};port={$GLOBALS['DB_PORT']};dbname={$GLOBALS['DB_NAME']};charset=utf8mb4";
  $pdo = new PDO($dsn, $GLOBALS['DB_USER'], $GLOBALS['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  // Make sure connection uses utf8mb4 + MariaDB-friendly collation
  $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_general_ci");
} catch (PDOException $e) {
  fail('Database connection failed: ' . $e->getMessage(), 500);
}

// ====== SCHEMA (safe to keep for dev) ======
try {
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
      id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
      name VARCHAR(120) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      phone VARCHAR(40) NULL,
      password_hash VARCHAR(255) NOT NULL,
      role ENUM('user','admin') NOT NULL DEFAULT 'user',
      status ENUM('active','inactive','banned') NOT NULL DEFAULT 'active',
      created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
      DEFAULT CHARSET=utf8mb4
      COLLATE=utf8mb4_general_ci;
  ");
} catch (PDOException $e) {
  fail('Schema create failed: ' . $e->getMessage(), 500);
}

// ====== INPUT & ROUTING ======
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!is_array($body)) $body = [];

$action = $_GET['action'] ?? $body['action'] ?? $_POST['action'] ?? 'health';
$method = $_SERVER['REQUEST_METHOD'];

// ====== ROUTES ======

// Health check
if ($action === 'health') {
  respond(['service' => 'lapzone-php', 'time' => date('c')]);
}

// Register
if ($action === 'register' && $method === 'POST') {
  $name  = trim($body['name']  ?? $_POST['name']  ?? '');
  $email = trim($body['email'] ?? $_POST['email'] ?? '');
  $phone = trim($body['phone'] ?? $_POST['phone'] ?? '');
  $pass  = (string)($body['password'] ?? $_POST['password'] ?? '');

  if ($name === '' || $email === '' || $pass === '') fail('name, email, password are required', 422);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) fail('Invalid email', 422);
  if (strlen($pass) < 6) fail('Password too short (min 6 chars)', 422);

  try {
    $st = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $st->execute([$email]);
    if ($st->fetch()) fail('Email already exists', 409);

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $ins  = $pdo->prepare('INSERT INTO users (name, email, phone, password_hash) VALUES (?,?,?,?)');
    $ins->execute([$name, $email, $phone ?: null, $hash]);

    respond(['id' => (int)$pdo->lastInsertId(), 'name' => $name, 'email' => $email], 201);
  } catch (PDOException $e) {
    fail('Registration failed: ' . $e->getMessage(), 500);
  }
}

// Login
if ($action === 'login' && $method === 'POST') {
  $email = trim($body['email'] ?? $_POST['email'] ?? '');
  $pass  = (string)($body['password'] ?? $_POST['password'] ?? '');

  if ($email === '' || $pass === '') fail('email and password are required', 422);

  try {
    $st = $pdo->prepare('SELECT id, name, email, role, status, password_hash FROM users WHERE email = ? LIMIT 1');
    $st->execute([$email]);
    $u = $st->fetch();

    if (!$u || !password_verify($pass, $u['password_hash'])) fail('Invalid credentials', 401);
    if ($u['status'] !== 'active') fail('Account inactive', 403);

    // Set session (optional)
    $_SESSION['user_id'] = (int)$u['id'];

    respond(['user' => [
      'id' => (int)$u['id'],
      'name' => $u['name'],
      'email' => $u['email'],
      'role' => $u['role'],
    ]]);
  } catch (PDOException $e) {
    fail('Login failed: ' . $e->getMessage(), 500);
  }
}

// Current user
if ($action === 'me' && $method === 'GET') {
  $uid = $_SESSION['user_id'] ?? 0;
  if (!$uid) fail('Unauthorized', 401);
  $st = $pdo->prepare('SELECT id, name, email, phone, role, status, created_at FROM users WHERE id = ?');
  $st->execute([$uid]);
  $me = $st->fetch();
  if (!$me) fail('Not found', 404);
  respond($me);
}

// Logout
if ($action === 'logout') {
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
    );
  }
  session_destroy();
  respond(['message' => 'Logged out']);
}

// Fallback
fail('Not found', 404);
