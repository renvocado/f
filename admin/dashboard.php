<?php 
require __DIR__.'/../inc/auth.php'; 
require_admin(); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - HealthyBite Drinks</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <header class="admin-header">
            <div class="admin-welcome">
                <h1>Admin Dashboard</h1>
                <p>Halo, <?= htmlspecialchars($_SESSION['admin_username']) ?>! Selamat datang di panel admin.</p>
            </div>
            <nav class="admin-nav">
                <a class="btn btn-secondary" href="../logout.php">Logout</a>
            </nav>
        </header>

        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <h3>Total Resep</h3>
                    <p>
                        <?php
                        require __DIR__.'/../inc/db.php';
                        $count = $pdo->query("SELECT COUNT(*) FROM recipes")->fetchColumn();
                        echo $count;
                        ?>
                    </p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👨‍🍳</div>
                <div class="stat-content">
                    <h3>Resep Baru</h3>
                    <p>
                        <?php
                        $count = $pdo->query("SELECT COUNT(*) FROM recipes WHERE created_at >= CURDATE()")->fetchColumn();
                        echo $count;
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="admin-actions">
            <h2>Kelola Konten</h2>
            <div class="action-grid">
                <a href="recipes.php" class="action-card">
                    <div class="action-icon">🍹</div>
                    <h3>Kelola Resep</h3>
                    <p>Tambah, edit, atau hapus resep minuman</p>
                </a>
                <a href="users.php" class="action-card">
  <div class="action-icon">👥</div>
  <h3>Kelola User</h3>
  <p>Admin dan pengguna aplikasi</p>
</a>


            </div>
        </div>
    </div>
</body>
</html>