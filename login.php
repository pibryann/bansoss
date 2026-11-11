<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

if (!empty($_SESSION['user'])) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $u = $stmt->fetch();
    if ($u && password_verify($password, $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $u['id'], 'nama' => $u['nama']];
        redirect('index.php');
    }
    $error = 'Username atau kata sandi salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - SIBANSOS</title>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body class="masuk">
  <form class="masuk-kotak" method="post">
    <h1>SIBANSOS</h1>
    <p>Masuk untuk mengelola data penerima bantuan sosial.</p>
    <?php if ($error): ?><div class="notif err"><?= e($error) ?></div><?php endif; ?>
    <label for="username">Username</label>
    <input id="username" name="username" required autofocus>
    <label for="password" style="margin-top:.75rem">Kata sandi</label>
    <input id="password" name="password" type="password" required>
    <button class="btn" type="submit">Masuk</button>
  </form>
</body>
</html>
