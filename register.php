<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/inc/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";

    if ($username === "" || $password === "") {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO users (username, password_hash) VALUES (?, ?)"
            );

            if ($stmt->execute([$username, $hash])) {
                $_SESSION['register_success'] = "Pendaftaran berhasil! Silakan login menggunakan akun Anda.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Member - HealthyBite Drinks</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">

            <div class="login-logo">
                <h1>Daftar Member</h1>
                <p>Buat akun untuk mengakses semua resep</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <div class="alert-icon">⚠️</div>
                <div class="alert-content">
                    <strong>Pendaftaran Gagal</strong>
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" class="login-form">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <div class="input-wrapper">
                    <div class="input-icon">👤</div>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        placeholder="Masukkan username baru"
                        class="form-input"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <div class="input-icon">🔒</div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="Masukkan password"
                        class="form-input"
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <span class="btn-text">Daftar Sekarang</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
                        <a href="index.php" class="btn btn-secondary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Beranda
            </a>
        </form>

        <div class="login-footer">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</div>

<!-- Form behavior moved to assets/script.js to avoid duplication -->

</body>
</html>