<?php
$pageTitle = 'Kelola Mobil F1';
$currentPage = 'cars';
require_once __DIR__ . '/auth_admin.php';
require_admin_login();
include 'header.php';

$pdo = db();
$message = '';
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$edit_car = null;

// --- 1. HANDLE POST (Add/Update) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $name = trim($_POST['name'] ?? '');
  $team = trim($_POST['team'] ?? '');
  
  // PERBAIKAN 1: Ambil data tahun dari form
  $year = intval($_POST['year'] ?? 0); 
  
  $engine = trim($_POST['engine'] ?? '');
  $power = trim($_POST['power'] ?? '');
  $weight = trim($_POST['weight'] ?? '');
  $form_edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;

  // PERBAIKAN 2: Tambahkan validasi $year
  if ($action === 'add' && $name && $team && $year) { 
    try {
      // PERBAIKAN 3: Masukkan 'year' ke query INSERT
      $stmt = $pdo->prepare('INSERT INTO cars(name, team, year, engine, power, weight) VALUES(?, ?, ?, ?, ?, ?)');
      $stmt->execute([$name, $team, $year, $engine, $power, $weight]);
      header('Location: cars.php?status=added');
      exit;
    } catch (Exception $e) {
      $message = '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
  } elseif ($action === 'update' && $form_edit_id && $name && $team && $year) {
    try {
      // PERBAIKAN 4: Masukkan 'year' ke query UPDATE
      $stmt = $pdo->prepare('UPDATE cars SET name=?, team=?, year=?, engine=?, power=?, weight=? WHERE id=?');
      $stmt->execute([$name, $team, $year, $engine, $power, $weight, $form_edit_id]);
      header('Location: cars.php?status=updated');
      exit;
    } catch (Exception $e) {
      $message = '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
  } else {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $message = '<div class="alert alert-error">Nama, Tim, dan Tahun wajib diisi!</div>';
      }
  }
}

// --- 2. HANDLE DELETE ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $delete_id = intval($_GET['delete']);
  try {
    $stmt = $pdo->prepare('DELETE FROM cars WHERE id=?');
    $stmt->execute([$delete_id]);
    header('Location: cars.php?status=deleted');
    exit;
  } catch (Exception $e) {
    $message = '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
  }
}

// --- 3. AMBIL DATA UNTUK EDIT ---
if ($edit_id) {
  $stmt = $pdo->prepare('SELECT * FROM cars WHERE id=?');
  $stmt->execute([$edit_id]);
  $edit_car = $stmt->fetch();
}

// --- 4. AMBIL SEMUA DATA ---
$cars = $pdo->query('SELECT * FROM cars ORDER BY id DESC')->fetchAll();
?>

<div class="page-banner">
  <h1>Kelola Mobil F1</h1>
</div>

<a href="index.php" class="btn grey detail-back">← Kembali</a>

<div style="max-width:1200px; margin:0 auto; padding:0 30px;">
  
  <?php 
    // Pesan Error dari Catch atau Validasi
    if ($message) echo $message; 
    
    // Pesan Sukses dari URL Parameter
    if (isset($_GET['status'])) {
      if ($_GET['status'] == 'added') echo '<div class="alert alert-success" style="background:#155724; color:#d4edda; padding:15px; margin-bottom:20px; border-radius:5px;">Mobil berhasil ditambahkan!</div>';
      if ($_GET['status'] == 'updated') echo '<div class="alert alert-success" style="background:#155724; color:#d4edda; padding:15px; margin-bottom:20px; border-radius:5px;">Mobil berhasil diperbarui!</div>';
      if ($_GET['status'] == 'deleted') echo '<div class="alert alert-success" style="background:#155724; color:#d4edda; padding:15px; margin-bottom:20px; border-radius:5px;">Mobil berhasil dihapus!</div>';
    }
  ?>

  <div style="display:grid; grid-template-columns:1fr 1fr; gap:30px; margin-bottom:40px;">
    
    <div style="background:#1a1a1a; padding:25px; border-radius:10px; border:1px solid rgba(255,0,0,0.12);">
      <h2 style="color:#ff0000; margin-top:0;"><?php echo $edit_car ? 'Edit Mobil' : 'Tambah Mobil Baru'; ?></h2>
      
      <form method="post" class="form-crud">
        <input type="hidden" name="action" value="<?php echo $edit_car ? 'update' : 'add'; ?>">
        <?php if ($edit_car): ?>
          <input type="hidden" name="edit_id" value="<?php echo intval($edit_car['id']); ?>">
        <?php endif; ?>

        <label>Nama Mobil</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($edit_car['name'] ?? ''); ?>" required placeholder="Contoh: Red Bull RB20">

        <label>Tim</label>
        <input type="text" name="team" value="<?php echo htmlspecialchars($edit_car['team'] ?? ''); ?>" required placeholder="Contoh: Red Bull Racing">

        <label>Tahun</label>
        <input type="number" name="year" value="<?php echo htmlspecialchars($edit_car['year'] ?? '2025'); ?>" required placeholder="Contoh: 2025">

        <label>Mesin</label>
        <input type="text" name="engine" value="<?php echo htmlspecialchars($edit_car['engine'] ?? ''); ?>" placeholder="Contoh: Honda RBPTH002">

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
            <div>
                <label>Tenaga (HP)</label>
                <input type="text" name="power" value="<?php echo htmlspecialchars($edit_car['power'] ?? ''); ?>" placeholder="1000hp">
            </div>
            <div>
                <label>Berat (kg)</label>
                <input type="text" name="weight" value="<?php echo htmlspecialchars($edit_car['weight'] ?? ''); ?>" placeholder="798 kg">
            </div>
        </div>

        <button type="submit" class="btn red" style="width:100%; padding:12px; margin-top:20px; justify-content:center;">
          <?php echo $edit_car ? 'Simpan Perubahan' : 'Tambah Mobil'; ?>
        </button>

        <?php if ($edit_car): ?>
          <a href="cars.php" class="btn grey" style="display:block; width:100%; padding:12px; text-align:center; margin-top:8px; text-decoration:none;">Batal Edit</a>
        <?php endif; ?>
      </form>
    </div>

    <div style="background:#1a1a1a; padding:25px; border-radius:10px; border:1px solid rgba(255,0,0,0.12); max-height:800px; overflow-y:auto;">
      <h2 style="color:#ff0000; margin-top:0;">Daftar Mobil (<?php echo count($cars); ?>)</h2>
      
      <?php if (empty($cars)): ?>
        <p style="color:#bbb;">Belum ada mobil.</p>
      <?php else: ?>
        <?php foreach ($cars as $car): ?>
          <div style="background:#111; padding:15px; margin-bottom:10px; border-radius:6px; border-left:3px solid #ff0000;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="margin:0 0 5px 0; font-weight:600; color:#fff; font-size:16px;"><?php echo htmlspecialchars($car['name']); ?></p>
                    <p style="margin:0 0 8px 0; color:#bbb; font-size:13px;">
                        <?php echo htmlspecialchars($car['team']); ?> • <strong><?php echo htmlspecialchars($car['year']); ?></strong>
                    </p>
                </div>
                <div style="display:flex; gap:8px;">
                  <a href="cars.php?edit=<?php echo $car['id']; ?>" class="btn small" style="background:#0066ff; padding:6px 12px; text-decoration:none; font-size:12px;">Edit</a>
                  <a href="cars.php?delete=<?php echo $car['id']; ?>" class="btn small" style="background:#cc0000; padding:6px 12px; text-decoration:none; font-size:12px;" onclick="return confirm('Yakin ingin menghapus mobil ini?');">Hapus</a>
                </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>