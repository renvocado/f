<?php
require __DIR__.'/inc/db.php'; 
require __DIR__.'/inc/functions.php';
require __DIR__.'/inc/header.php';

// Batasi akses halaman rekomendasi hanya untuk member yang sudah login
if (!isset($_SESSION['user_id'])) {
  // arahkan ke halaman login; setelah login user bisa kembali ke rekomendasi
  header('Location: login.php');
  exit;
}

$goal = $_GET['goal'] ?? null;
$q    = trim($_GET['q'] ?? "");
$all  = isset($_GET['all']) && $_GET['all'] == '1';
$sort = $_GET['sort'] ?? 'created_desc';

function orderClause($sort){
  switch ($sort) {
    case 'name_asc':  return 'name ASC';
    case 'name_desc': return 'name DESC';
    default:          return 'created_at DESC'; 
  }
}

$mode = 'all';
$order = orderClause($sort);

if ($all) {
  $stmt = $pdo->query("SELECT * FROM recipes ORDER BY $order");
  $mode = 'all';
} elseif ($goal) {
  $stmt = $pdo->prepare("SELECT * FROM recipes WHERE goal=? ORDER BY $order");
  $stmt->execute([$goal]);
  $mode = 'goal';
} elseif ($q !== "") {
  $qLike = '%'.$q.'%';
  $stmt = $pdo->prepare("SELECT * FROM recipes
    WHERE name LIKE ? OR tags LIKE ?
      OR id IN (SELECT recipe_id FROM recipe_ingredients WHERE content LIKE ?)
    ORDER BY $order");
  $stmt->execute([$qLike, $qLike, $qLike]);
  $mode = 'search';
} else {
  header("Location: index.php");
  exit;
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Rekomendasi";
if ($mode === 'goal') {
  $goalTitles = [
    'detox' => 'Detox',
    'energy' => 'Energy Booster', 
    'diet' => 'Diet & Weight Loss',
    'relax' => 'Relax & Calm'
  ];
  $pageTitle = $goalTitles[$goal] ?? 'Rekomendasi';
} elseif ($mode === 'search') {
  $pageTitle = "Hasil Pencarian: " . h($q);
} elseif ($mode === 'all') {
  $pageTitle = "Semua Resep";
}
?>

<main class="container">
  <div class="page-header">
    <div class="back-nav">
      <a href="index.php" class="btn btn-primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Kembali ke Beranda
      </a>
    </div>
    <h1><?= h($pageTitle) ?></h1>
    <?php if ($mode === 'search'): ?>
      <p class="search-results-info">Ditemukan <?= count($rows) ?> resep untuk "<?= h($q) ?>"</p>
    <?php endif; ?>
  </div>

  <div class="filters-section">
    <div class="filters-row">
      <nav class="goals-nav">
        <a href="?goal=detox" class="goal-filter <?= ($goal==='detox')?'active':'' ?>">
          <span class="goal-icon">🌿</span> Detox
        </a>
        <a href="?goal=energy" class="goal-filter <?= ($goal==='energy')?'active':'' ?>">
          <span class="goal-icon">⚡</span> Energy
        </a>
        <a href="?goal=diet" class="goal-filter <?= ($goal==='diet')?'active':'' ?>">
          <span class="goal-icon">🥗</span> Diet
        </a>
        <a href="?goal=relax" class="goal-filter <?= ($goal==='relax')?'active':'' ?>">
          <span class="goal-icon">😌</span> Relax
        </a>
        <a href="?all=1" class="goal-filter <?= $mode==='all'?'active':'' ?>">
          <span class="goal-icon">📚</span> Semua
        </a>
      </nav>

      <form method="get" class="sort-form">
        <?php if ($mode === 'all'): ?>
          <input type="hidden" name="all" value="1">
        <?php elseif ($mode === 'goal'): ?>
          <input type="hidden" name="goal" value="<?= h($goal) ?>">
        <?php elseif ($mode === 'search'): ?>
          <input type="hidden" name="q" value="<?= h($q) ?>">
        <?php endif; ?>

        <label class="sort-label">Urutkan:</label>
        <select name="sort" onchange="this.form.submit()" class="sort-select">
          <option value="created_desc" <?= $sort==='created_desc'?'selected':'' ?>>Terbaru</option>
          <option value="name_asc" <?= $sort==='name_asc'?'selected':'' ?>>Nama A–Z</option>
          <option value="name_desc" <?= $sort==='name_desc'?'selected':'' ?>>Nama Z–A</option>
        </select>
      </form>
    </div>
  </div>

  <?php if (empty($rows)): ?>
    <div class="empty-state">
      <div class="empty-icon">🍹</div>
      <h3>Tidak ada resep ditemukan</h3>
      <p>Silakan coba dengan kata kunci lain atau lihat semua resep yang tersedia.</p>
      <a href="?all=1" class="btn btn-primary">Lihat Semua Resep</a>
    </div>
  <?php else: ?>
    <div class="recipes-grid">
      <?php foreach($rows as $r): ?>
        <article class="recipe-card" data-aos="fade-up">
          <div class="card-image">
            <img src="<?= h($r['image']) ?>" alt="<?= h($r['name']) ?>" loading="lazy">
            <div class="card-badge"><?= strtoupper($r['goal']) ?></div>
          </div>
          <div class="card-content">
            <h3 class="card-title"><?= h($r['name']) ?></h3>
            <p class="card-meta"><?= h($r['serving_desc']) ?></p>
            <div class="card-calories">
              <span class="calories-badge"><?= (int)$r['cal_per_serv'] ?> kkal</span>
            </div>
            <div class="card-actions">
              <?php $srcQuery = "src=".$mode . ($mode==='goal' ? "&goal=".urlencode($goal) : ""); ?>
              <a href="recipe.php?id=<?= (int)$r['id'] ?>&<?= $srcQuery ?>" class="btn btn-primary btn-sm">
                Lihat Resep
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php require __DIR__.'/inc/footer.php'; ?>