<?php
require __DIR__.'/../inc/auth.php';
require_admin();
require __DIR__.'/../inc/db.php';
require __DIR__.'/../inc/functions.php';

$admins = $pdo->query("SELECT id, username, created_at FROM admins ORDER BY created_at ASC")
              ->fetchAll(PDO::FETCH_ASSOC);

$users  = $pdo->query("SELECT id, username, created_at FROM users ORDER BY created_at ASC")
              ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola User - Admin HealthyBite</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
<div class="container">

<header class="admin-header">
    <div class="admin-welcome">
        <h1>Kelola User</h1>
        <p>Manajemen akun admin & pengguna</p>
    </div>

    <nav class="admin-nav">
        <a href="dashboard.php" class="btn btn-secondary">← Kembali</a>
    </nav>
</header>


<div class="admin-section">
    <h2>👑 Admin</h2>

    <?php if(!$admins): ?>
        <div class="empty-state">
            <div class="empty-icon">🙍‍♂️</div>
            <h3>Belum ada admin</h3>
        </div>
    <?php else: ?>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach($admins as $a): ?>
                <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><strong><?= h($a['username']) ?></strong></td>
                    <td><?= h($a['created_at']) ?></td>

                    <td>
                        <div class="action-buttons">
                            <a class="btn btn-sm btn-secondary"
                               href="user_password.php?type=admin&id=<?= (int)$a['id'] ?>">
                               Edit Password
                            </a>

                            <a class="btn btn-sm btn-danger"
                               href="user_delete.php?type=admin&id=<?= (int)$a['id'] ?>"
                               onclick="return confirm('Hapus admin ini?')">
                               Hapus
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
    <?php endif; ?>
</div>


<div class="admin-section" style="margin-top:24px">
    <h2>👤 User</h2>

    <?php if(!$users): ?>
        <div class="empty-state">
            <div class="empty-icon">🙂</div>
            <h3>Belum ada user</h3>
        </div>
    <?php else: ?>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach($users as $u): ?>
                <tr>
                    <td><?= (int)$u['id'] ?></td>
                    <td><strong><?= h($u['username']) ?></strong></td>
                    <td><?= h($u['created_at']) ?></td>

                    <td>
                        <div class="action-buttons">
                            <a class="btn btn-sm btn-secondary"
                               href="user_password.php?type=user&id=<?= (int)$u['id'] ?>">
                               Edit Password
                            </a>

                            <a class="btn btn-sm btn-danger"
                               href="user_delete.php?type=user&id=<?= (int)$u['id'] ?>"
                               onclick="return confirm('Hapus user ini?')">
                               Hapus
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
    <?php endif; ?>
</div>

</div>
</body>
</html>
