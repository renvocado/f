<?php 
require __DIR__.'/inc/db.php'; 
require __DIR__.'/inc/functions.php'; 
require __DIR__.'/inc/header.php'; 
?>

<main class="container">
  <div class="hero-section">
    <div class="hero-content">
      <h2>Minuman sehat, mood oke, hidup asik ✨</h2>
      <p>Nikmati berbagai resep jus dan smoothie sehat untuk berbagai kebutuhan. Anda bisa login/daftar untuk melihat resep.</p>
<div class="hero-actions">
  <?php if (isset($_SESSION['user_id'])): ?>
    <a class="btn btn-primary" href="logout.php">Logout</a>
    <a class="btn btn-primary" href="recommend.php?all=1">Lihat Semua Resep</a>
  <?php else: ?>
    <a class="btn btn-primary" href="login.php">Login</a>
    <a class="btn btn-secondary" href="register.php">Register</a>
    <a class="btn btn-primary" href="register.php">Daftar untuk melihat resep</a>
  <?php endif; ?>
</div>


    </div>
  </div>

  <section class="featured-section">
    <h3>Pilih Tujuan Kesehatan Anda</h3>
    <div class="goals-grid">
      <a href="recommend.php?goal=detox" class="goal-card" data-aos="fade-up">
        <div class="goal-icon">🌿</div>
        <h4>Detox</h4>
        <p>Bersihkan tubuh dari racun</p>
      </a>
      <a href="recommend.php?goal=energy" class="goal-card" data-aos="fade-up" data-aos-delay="100">
        <div class="goal-icon">⚡</div>
        <h4>Energy</h4>
        <p>Tingkatkan energi harian</p>
      </a>
      <a href="recommend.php?goal=diet" class="goal-card" data-aos="fade-up" data-aos-delay="200">
        <div class="goal-icon">🥗</div>
        <h4>Diet</h4>
        <p>Bantu program penurunan berat badan</p>
      </a>
      <a href="recommend.php?goal=relax" class="goal-card" data-aos="fade-up" data-aos-delay="300">
        <div class="goal-icon">😌</div>
        <h4>Relax</h4>
        <p>Tenangkan pikiran dan tubuh</p>
      </a>
    </div>
  </section>
  
    <!-- Informasi umum tentang kesehatan (tampil di beranda sebelum login) -->
    <section class="info-section">
      <div class="container">
        <h3>Tentang Kesehatan & Pola Hidup Sehat</h3>
        <p>HealthyBite menyediakan informasi dan resep minuman sehat yang membantu mendukung pola hidup seimbang. Minuman berbahan buah, sayur, dan biji-bijian dapat menjadi sumber vitamin, serat, dan antioksidan. Penting untuk mengonsumsi variasi bahan alami, menjaga hidrasi, serta menggabungkan minuman sehat dengan pola makan dan aktivitas fisik yang teratur.</p>
        <p>Daftar sebagai member untuk menyimpan resep favorit, melihat panduan porsi, dan mendapatkan rekomendasi yang disesuaikan dengan tujuan kesehatan Anda.</p>
      </div>
    </section>
</main>

<?php require __DIR__.'/inc/footer.php'; ?>