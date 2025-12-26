<?php
require __DIR__.'/inc/db.php'; 
require __DIR__.'/inc/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require __DIR__.'/inc/header.php';


$id = (int)($_GET['id'] ?? 0);
$recipe = find_recipe($pdo, $id);
if (!$recipe) die("Resep tidak ditemukan.");

$serv = max(1, (int)($_GET['serv'] ?? 1));
$total = calories_for_servings($recipe['cal_per_serv'], $serv);

$goalParam = $_GET['goal'] ?? ($recipe['goal'] ?? 'detox');
$backUrl   = "recommend.php?goal=" . urlencode($goalParam);

$goalIcons = [
    'detox' => '🌿',
    'energy' => '⚡', 
    'diet' => '🥗',
    'relax' => '😌'
];
$goalIcon = $goalIcons[$goalParam] ?? '🍹';
?>

<main class="container">
  <div class="recipe-header">
    <div class="back-nav">
      <a href="<?= $backUrl ?>" class="btn btn-primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Kembali ke <?= ucfirst($goalParam) ?>
      </a>
    </div>
  </div>

  <div class="recipe-detail">
    <div class="recipe-image-section">
      <div class="recipe-image-container">
        <img src="<?= h($recipe['image']) ?>" alt="<?= h($recipe['name']) ?>" class="recipe-image">
        <div class="recipe-badge">
          <span class="badge-icon"><?= $goalIcon ?></span>
          <?= strtoupper($goalParam) ?>
        </div>
      </div>
      
      <div class="recipe-meta">
        <div class="meta-item">
          <div class="meta-icon">🥄</div>
          <div class="meta-content">
            <span class="meta-label">Porsi</span>
            <span class="meta-value"><?= h($recipe['serving_desc']) ?></span>
          </div>
        </div>
        
        <div class="meta-item">
          <div class="meta-icon">🔥</div>
          <div class="meta-content">
            <span class="meta-label">Kalori per Porsi</span>
            <span class="meta-value"><?= (int)$recipe['cal_per_serv'] ?> kkal</span>
          </div>
        </div>
      </div>
    </div>

    <div class="recipe-content">
      <div class="recipe-title-section">
        <h1 class="recipe-title"><?= h($recipe['name']) ?></h1>
        <p class="recipe-description">Resep sehat dan menyegarkan untuk tujuan <?= $goalParam ?> Anda.</p>
      </div>  

      <div class="ingredients-section">
        <div class="section-header">
          <h2 class="section-title">Bahan-bahan</h2>
          <div class="section-icon">🥬</div>
        </div>
        <ul class="ingredients-list">
          <?php foreach($recipe['ingredients'] as $i): ?>
            <li class="ingredient-item">
              <span class="ingredient-bullet">•</span>
              <span class="ingredient-text"><?= h($i) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="steps-section">
  <div class="section-header">
    <h2 class="section-title">Langkah Pembuatan</h2>
    <div class="section-icon">👩‍🍳</div>
  </div>
  <ul class="steps-list">
    <?php foreach($recipe['steps'] as $step): ?>
      <li class="step-item">
        <span class="step-bullet">•</span>
        <div class="step-content"><?= h($step['content']) ?></div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
    </div>
  </div>
</main>



<div class="container">
  <div class="print-toolbar print-card-box">
    
    <div class="print-left">
      <h3 class="print-title">Cetak Resep</h3>
      <p class="print-sub">Cetak resep dalam format versi catatan</p>
    </div>

    <button class="btn-print" onclick="window.print()">
      🖨 Cetak Versi Catatan
    </button>

  </div>
</div>


<div class="print-note only-print">
  <div class="print-card">

    <h1 class="pn-name"><?= h($recipe['name']) ?></h1>

    <div class="pn-meta">
      <span>🍽 Porsi: <?= h($recipe['serving_desc']) ?></span>
      <span>🔥 Kalori: <?= (int)$recipe['cal_per_serv'] ?> kkal</span>
      <span>🎯 Goal: <?= strtoupper($goalParam) ?></span>
    </div>

    <h2>🧾 Bahan-bahan</h2>
    <ul>
      <?php foreach($recipe['ingredients'] as $i): ?>
        <li><?= h($i) ?></li>
      <?php endforeach; ?>
    </ul>

    <h2>👩‍🍳 Langkah Pembuatan</h2>
    <ol>
      <?php foreach($recipe['steps'] as $step): ?>
        <li><?= h($step['content']) ?></li>
      <?php endforeach; ?>
    </ol>

    <div class="pn-footer">
      Dicetak dari HealthyBite — <?= date('d M Y') ?>
    </div>

  </div>
</div>

<?php require __DIR__.'/inc/footer.php'; ?>