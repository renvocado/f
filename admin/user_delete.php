<?php
require __DIR__.'/../inc/auth.php';
require_admin();
require __DIR__.'/../inc/db.php';

$type = $_GET['type'] ?? '';
$id   = (int)($_GET['id'] ?? 0);

if (!$id || !in_array($type, ['admin','user'])) {
  die('Aksi tidak valid');
}

$table = ($type === 'admin') ? 'admins' : 'users';

if ($type === 'admin') {
  $totalAdmin = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
  if ($totalAdmin <= 1) {
    die('Tidak boleh menghapus admin terakhir');
  }
}

$stmt = $pdo->prepare("DELETE FROM {$table} WHERE id=?");
$stmt->execute([$id]);

header('Location: users.php');
exit;
