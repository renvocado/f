<?php
require __DIR__.'/../inc/auth.php';
require_admin();
require __DIR__.'/../inc/db.php';

$type = $_GET['type'] ?? '';
$id   = (int)($_GET['id'] ?? 0);

if (!$id || !in_array($type, ['admin','user'])) {
  die('Data tidak valid');
}

$table = ($type === 'admin') ? 'admins' : 'users';

$msg = $err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new = $_POST['new'] ?? '';
  $rep = $_POST['re'] ?? '';

  if (strlen($new) < 6) {
    $err = "Password minimal 6 karakter";
  } elseif ($new !== $rep) {
    $err = "Konfirmasi password tidak sama";
  } else {
    $stmt = $pdo->prepare("UPDATE {$table} SET password_hash=? WHERE id=?");
    $stmt->execute([password_hash($new, PASSWORD_DEFAULT), $id]);
    $msg = "Password berhasil diperbarui ✅";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Password</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
  <header class="admin-header">
    <h1>Edit Password (<?= strtoupper($type) ?>)</h1>
    <a href="users.php" class="btn btn-secondary">Kembali</a>
  </header>

  <?php if($msg): ?>
    <div class="alert alert-success"><?= $msg ?></div>
  <?php endif; ?>

  <?php if($err): ?>
    <div class="alert alert-error"><?= $err ?></div>
  <?php endif; ?>

  <form method="post" class="form">
    <div class="form-group">
      <label>Password Baru</label>
      <input type="password" name="new" required minlength="6">
    </div>

    <div class="form-group">
      <label>Ulangi Password</label>
      <input type="password" name="re" required minlength="6">
    </div>

    <button class="btn btn-primary">Simpan</button>
  </form>
</div>

</body>
</html>
