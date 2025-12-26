<?php
require __DIR__.'/../inc/auth.php'; 
require_admin();
require __DIR__.'/../inc/db.php';
require __DIR__.'/../inc/functions.php';

$id = (int)($_GET['id'] ?? 0);
$editing = $id > 0;

$name = $goal = $serving = $image = $tags = "";
$cal = 0;
$ingredients = [];
$steps = [];
$existingImage = "";

if ($editing) {
  $r = $pdo->prepare("SELECT * FROM recipes WHERE id=?");
  $r->execute([$id]);
  $row = $r->fetch(PDO::FETCH_ASSOC);
  if (!$row) die("Resep tidak ditemukan.");

  $name    = $row['name'];
  $goal    = $row['goal'];
  $cal     = (int)$row['cal_per_serv'];
  $serving = $row['serving_desc'];
  $image   = $row['image'];
  $tags    = $row['tags'];
  $existingImage = $image;

  $ing = $pdo->prepare("SELECT content FROM recipe_ingredients WHERE recipe_id=?");
  $ing->execute([$id]); 
  $ingredients = $ing->fetchAll(PDO::FETCH_COLUMN);

  $st = $pdo->prepare("SELECT content FROM recipe_steps WHERE recipe_id=? ORDER BY step_no ASC");
  $st->execute([$id]); 
  $steps = $st->fetchAll(PDO::FETCH_COLUMN);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = trim($_POST['name'] ?? '');
  $goal    = $_POST['goal'] ?? 'detox';
  $cal     = (int)($_POST['cal'] ?? 0);
  $serving = trim($_POST['serving'] ?? '');
  $image   = trim($_POST['image'] ?? ''); 
  $tags    = trim($_POST['tags'] ?? '');
  $ingredients = array_values(array_filter(array_map('trim', $_POST['ingredients'] ?? [])));
  $steps       = array_values(array_filter(array_map('trim', $_POST['steps'] ?? [])));

  if ($name === '' || $serving === '' || $cal < 0) {
    die("Data belum lengkap.");
  }

  if (!empty($_FILES['imgfile']['name']) && $_FILES['imgfile']['error'] === UPLOAD_ERR_OK) {
    $size = $_FILES['imgfile']['size'];
    if ($size > 8 * 1024 * 1024) { // 8MB
      die("File terlalu besar (>8MB).");
    }

    $ext = strtolower(pathinfo($_FILES['imgfile']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];
    if (!in_array($ext, $allowed)) {
      die("Ekstensi tidak diperbolehkan.");
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $_FILES['imgfile']['tmp_name']);
    finfo_close($finfo);
    if (strpos($mime, 'image/') !== 0) {
      die("File bukan gambar valid.");
    }

    $fname = 'img_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    $dest  = __DIR__ . '/../assets/img/' . $fname;
    if (!move_uploaded_file($_FILES['imgfile']['tmp_name'], $dest)) {
      die("Gagal menyimpan file upload.");
    }
    $image = 'assets/img/' . $fname;
  }

  if ($editing && $image === '') {
    $image = $existingImage;
  }

  if ($editing) {
    $stmt = $pdo->prepare("UPDATE recipes 
      SET name=?, goal=?, cal_per_serv=?, serving_desc=?, image=?, tags=? 
      WHERE id=?");
    $stmt->execute([$name, $goal, $cal, $serving, $image, $tags, $id]);

    $pdo->prepare("DELETE FROM recipe_ingredients WHERE recipe_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM recipe_steps WHERE recipe_id=?")->execute([$id]);

    $insI = $pdo->prepare("INSERT INTO recipe_ingredients (recipe_id,content) VALUES (?,?)");
    foreach ($ingredients as $i) {
      $insI->execute([$id, $i]);
    }

    $insS = $pdo->prepare("INSERT INTO recipe_steps (recipe_id,step_no,content) VALUES (?,?,?)");
    $no = 1;
    foreach ($steps as $s) {
      $insS->execute([$id, $no++, $s]);
    }

  } else {
    $stmt = $pdo->prepare("INSERT INTO recipes 
      (name,goal,cal_per_serv,serving_desc,image,tags) 
      VALUES (?,?,?,?,?,?)");
    $stmt->execute([$name, $goal, $cal, $serving, $image, $tags]);
    $id = (int)$pdo->lastInsertId();

    $insI = $pdo->prepare("INSERT INTO recipe_ingredients (recipe_id,content) VALUES (?,?)");
    foreach ($ingredients as $i) {
      $insI->execute([$id, $i]);
    }

    $insS = $pdo->prepare("INSERT INTO recipe_steps (recipe_id,step_no,content) VALUES (?,?,?)");
    $no = 1;
    foreach ($steps as $s) {
      $insS->execute([$id, $no++, $s]);
    }
  }

  header("Location: recipes.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $editing ? 'Edit' : 'Tambah' ?> Resep - Admin HealthyBite</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <header class="admin-header">
            <div class="admin-welcome">
                <h1><?= $editing ? 'Edit Resep' : 'Tambah Resep Baru' ?></h1>
                <p><?= $editing ? 'Perbarui informasi resep yang sudah ada' : 'Buat resep minuman sehat baru' ?></p>
            </div>
            <nav class="admin-nav">
                <a href="recipes.php" class="btn btn-secondary">← Kembali</a>
            </nav>
        </header>

        <div class="form-container-wide">
            <form method="post" class="recipe-form" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-column">
                        <div class="form-section">
                            <h3>Informasi Dasar</h3>
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Resep *</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?= h($name) ?>" 
                                       placeholder="Contoh: Smoothie Pisang Segar"
                                       class="form-input">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="goal" class="form-label">Goal *</label>
                                    <select id="goal" name="goal" class="form-select">
                                        <?php foreach (['detox'=>'🌿 Detox', 'energy'=>'⚡ Energy', 'diet'=>'🥗 Diet', 'relax'=>'😌 Relax'] as $g => $label): ?>
                                            <option value="<?= $g ?>" <?= $goal === $g ? 'selected' : '' ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="cal" class="form-label">Kalori/Porsi *</label>
                                    <input type="number" id="cal" name="cal" min="0" required 
                                           value="<?= (int)$cal ?>" 
                                           placeholder="0"
                                           class="form-input">
                                    <span class="input-suffix">kkal</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="serving" class="form-label">Deskripsi Porsi *</label>
                                <input type="text" id="serving" name="serving" required 
                                       value="<?= h($serving) ?>" 
                                       placeholder="Contoh: 1 gelas (300ml)"
                                       class="form-input">
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Gambar Resep</h3>
                            
                            <?php if (!empty($image)): ?>
                                <div class="current-image">
                                    <label class="form-label">Gambar Saat Ini</label>
                                    <div class="image-preview">
                                        <img src="<?= h($image) ?>" alt="Preview" class="preview-image">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="imgfile" class="form-label">Upload Gambar Baru</label>
                                <div class="file-upload">
                                    <input type="file" id="imgfile" name="imgfile" accept="image/*" class="file-input">
                                    <label for="imgfile" class="file-label">
                                        <span class="file-icon">📷</span>
                                        <span class="file-text">Pilih gambar...</span>
                                    </label>
                                </div>
                                <small class="form-help">Format: JPG, PNG, WebP (maks. 8MB)</small>
                            </div>

                            <div class="form-group">
                                <label for="image" class="form-label">Atau URL Gambar</label>
                                <input type="url" id="image" name="image" 
                                       value="<?= h($image) ?>" 
                                       placeholder="https://example.com/image.jpg"
                                       class="form-input">
                                <small class="form-help">Kosongkan jika menggunakan upload</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-section">
                            <h3>Bahan-bahan</h3>
                            <div class="dynamic-list" id="ing-wrap">
                                <?php if (empty($ingredients)) $ingredients = [""]; ?>
                                <?php foreach ($ingredients as $index => $iVal): ?>
                                    <div class="dynamic-item">
                                        <input type="text" name="ingredients[]" 
                                               value="<?= h($iVal) ?>" 
                                               placeholder="Contoh: Pisang 1 buah"
                                               class="form-input">
                                        <button type="button" class="btn-remove" title="Hapus bahan">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="add-ing" class="btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Tambah Bahan
                            </button>
                        </div>

                        <div class="form-section">
                            <h3>Langkah Pembuatan</h3>
                            <div class="dynamic-list" id="step-wrap">
                                <?php if (empty($steps)) $steps = [""]; ?>
                                <?php foreach ($steps as $index => $sVal): ?>
                                    <div class="dynamic-item">
                                        <input type="text" name="steps[]" 
                                               value="<?= h($sVal) ?>" 
                                               placeholder="Contoh: Blender semua bahan"
                                               class="form-input">
                                        <button type="button" class="btn-remove" title="Hapus langkah">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="add-step" class="btn-add">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Tambah Langkah
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">
                        <?= $editing ? 'Simpan Perubahan' : 'Simpan Resep' ?>
                    </button>
                    <a href="recipes.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function createDynamicItem(name, placeholder) {
            const item = document.createElement('div');
            item.className = 'dynamic-item';
            
            const input = document.createElement('input');
            input.type = 'text';
            input.name = name + '[]';
            input.placeholder = placeholder;
            input.className = 'form-input';
            item.appendChild(input);
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-remove';
            removeBtn.title = 'Hapus';
            removeBtn.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            `;
            
            removeBtn.addEventListener('click', function() {
                const container = item.parentElement;
                if (container.children.length > 1) {
                    item.remove();
                } else {
                    input.value = '';
                }
            });
            
            item.appendChild(removeBtn);
            return item;
        }
        
        document.getElementById('add-ing').addEventListener('click', function() {
            const container = document.getElementById('ing-wrap');
            container.appendChild(createDynamicItem('ingredients', 'Contoh: Pisang 1 buah'));
        });
        
        document.getElementById('add-step').addEventListener('click', function() {
            const container = document.getElementById('step-wrap');
            container.appendChild(createDynamicItem('steps', 'Contoh: Blender semua bahan'));
        });
        
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                const item = this.closest('.dynamic-item');
                const container = item.parentElement;
                if (container.children.length > 1) {
                    item.remove();
                } else {
                    item.querySelector('input').value = '';
                }
            });
        });
        
        const fileInput = document.querySelector('.file-input');
        const fileText = document.querySelector('.file-text');
        
        if (fileInput && fileText) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    fileText.textContent = this.files[0].name;
                } else {
                    fileText.textContent = 'Pilih gambar...';
                }
            });
        }
        
        const form = document.querySelector('.recipe-form');
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const serving = document.getElementById('serving').value.trim();
            const cal = document.getElementById('cal').value;
            
            if (!name || !serving || !cal) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi (*)');
                return false;
            }
        });
    });
    </script>
</body>
</html>