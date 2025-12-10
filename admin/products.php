<?php
$pageTitle = 'Kelola Produk Toko';
$currentPage = 'products';
require_once __DIR__ . '/auth_admin.php';
require_admin_login();
include 'header.php';

$pdo = db();
$message = '';
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$edit_product = null;

// --- 1. HANDLE POST (ADD / UPDATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $team = trim($_POST['team'] ?? ''); // Tambahan: Input Tim
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $form_edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;

    // Handle Image Upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            // Simpan gambar ke folder assets
            $filename = 'prod_' . time() . '.' . $ext;
            $destination = __DIR__ . '/../assets/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $imagePath = 'assets/' . $filename;
            }
        }
    }

    if (($action === 'add' || $action === 'update') && $name && $price > 0) {
        try {
            if ($action === 'add') {
                // Default image jika tidak upload
                $finalImg = $imagePath ?? 'assets/default_product.jpg';
                
                // Tambahkan is_deleted = 0 secara default
                $stmt = $pdo->prepare('INSERT INTO products(name, team, description, price, stock, category, image, is_deleted) VALUES(?, ?, ?, ?, ?, ?, ?, 0)');
                $stmt->execute([$name, $team, $description, $price, $stock, $category, $finalImg]);
                
                header('Location: products.php?status=added');
                exit;

            } elseif ($action === 'update' && $form_edit_id) {
                // Query Update
                $sql = 'UPDATE products SET name=?, team=?, description=?, price=?, stock=?, category=?';
                $params = [$name, $team, $description, $price, $stock, $category];

                // Hanya update gambar jika user upload gambar baru
                if ($imagePath) {
                    $sql .= ', image=?';
                    $params[] = $imagePath;
                }
                
                $sql .= ' WHERE id=?';
                $params[] = $form_edit_id;

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                header('Location: products.php?status=updated');
                exit;
            }
        } catch (Exception $e) {
            $message = '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Nama dan Harga wajib diisi!</div>';
    }
}

// --- 2. HANDLE SOFT DELETE (Penyelesaian Poin 10) ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    try {
        // JANGAN DELETE FROM, TAPI UPDATE STATUS JADI HIDDEN
        $stmt = $pdo->prepare('UPDATE products SET is_deleted = 1 WHERE id=?');
        $stmt->execute([$delete_id]);
        
        header('Location: products.php?status=deleted');
        exit;
    } catch (Exception $e) {
        $message = '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Get product for editing
if ($edit_id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id=?');
    $stmt->execute([$edit_id]);
    $edit_product = $stmt->fetch();
}

// --- 3. GET ALL ACTIVE PRODUCTS ---
// Hanya ambil yang is_deleted = 0
$products = $pdo->query('SELECT * FROM products WHERE is_deleted = 0 ORDER BY id DESC')->fetchAll();
?>

<div class="page-banner">
    <h1>Kelola Produk Toko</h1>
</div>

<a href="index.php" class="btn grey detail-back">← Kembali</a>

<div style="max-width:1200px; margin:0 auto; padding:0 30px;">
    
    <?php 
        if ($message) echo $message; 
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'added') echo '<div class="alert alert-success" style="background:#155724; color:#d4edda; padding:15px; border-radius:5px; margin-bottom:20px;">✔️ Produk berhasil ditambahkan!</div>';
            if ($_GET['status'] == 'updated') echo '<div class="alert alert-success" style="background:#155724; color:#d4edda; padding:15px; border-radius:5px; margin-bottom:20px;">✔️ Produk berhasil diperbarui!</div>';
            if ($_GET['status'] == 'deleted') echo '<div class="alert alert-success" style="background:#155724; color:#d4edda; padding:15px; border-radius:5px; margin-bottom:20px;">✔️ Produk berhasil dihapus (diarsipkan)!</div>';
        }
    ?>

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:30px; margin-bottom:40px;">
        
        <div style="background:#1a1a1a; padding:25px; border-radius:10px; border:1px solid rgba(255,0,0,0.12); height:fit-content;">
            <h2 style="color:#ff0000; margin-top:0;"><?php echo $edit_product ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h2>
            
            <form method="post" class="form-crud" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $edit_product ? 'update' : 'add'; ?>">
                <?php if ($edit_product): ?>
                    <input type="hidden" name="edit_id" value="<?php echo intval($edit_product['id']); ?>">
                <?php endif; ?>

                <label>Nama Produk</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($edit_product['name'] ?? ''); ?>" required placeholder="Contoh: Red Bull Cap">

                <label>Tim F1</label>
                <input type="text" name="team" value="<?php echo htmlspecialchars($edit_product['team'] ?? ''); ?>" placeholder="Contoh: Red Bull Racing">

                <label>Deskripsi</label>
                <textarea name="description" rows="2"><?php echo htmlspecialchars($edit_product['description'] ?? ''); ?></textarea>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                    <div>
                        <label>Harga (IDR)</label>
                        <input type="number" name="price" step="100" value="<?php echo htmlspecialchars($edit_product['price'] ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>Stok</label>
                        <input type="number" name="stock" value="<?php echo htmlspecialchars($edit_product['stock'] ?? 0); ?>">
                    </div>
                </div>

                <label>Kategori</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($edit_product['category'] ?? ''); ?>" placeholder="Merchandise / Apparel">

                <label>Gambar Produk</label>
                <?php if ($edit_product && !empty($edit_product['image'])): ?>
                    <div style="margin-bottom:5px;">
                        <img src="../<?php echo htmlspecialchars($edit_product['image']); ?>" style="height:50px; border-radius:4px;">
                        <span style="font-size:11px; color:#888;">(Biarkan kosong jika tidak ganti)</span>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*" style="padding:5px;">

                <div style="margin-top:20px; display:flex; gap:10px;">
                    <button type="submit" class="btn red" style="flex-grow:1;">
                        <?php echo $edit_product ? 'Update' : 'Tambah'; ?>
                    </button>
                    <?php if ($edit_product): ?>
                        <a href="products.php" class="btn grey">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div style="background:#1a1a1a; padding:25px; border-radius:10px; border:1px solid rgba(255,0,0,0.12); overflow-x:auto;">
            <h2 style="color:#ff0000; margin-top:0;">Daftar Produk (<?php echo count($products); ?>)</h2>
            
            <?php if (empty($products)): ?>
                <p style="color:#bbb;">Belum ada produk.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th width="50">Img</th>
                            <th>Info Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <img src="../<?php echo $p['image'] ?? 'assets/default_product.jpg'; ?>" style="width:40px; height:40px; object-fit:cover; border-radius:4px;">
                                </td>
                                <td>
                                    <strong style="color:#fff;"><?php echo htmlspecialchars($p['name']); ?></strong><br>
                                    <span style="font-size:11px; color:#888;"><?php echo htmlspecialchars($p['team']); ?></span>
                                </td>
                                <td style="color:#d4edda;">Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if($p['stock'] <= 5): ?>
                                        <span style="color:#ff4d4d; font-weight:bold;"><?php echo $p['stock']; ?> (Low)</span>
                                    <?php else: ?>
                                        <?php echo $p['stock']; ?>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space:nowrap;">
                                    <a href="products.php?edit=<?php echo $p['id']; ?>" class="btn small" style="background:#0066ff; padding:6px 10px; font-size:12px; text-decoration:none;">Edit</a>
                                    <a href="products.php?delete=<?php echo $p['id']; ?>" class="btn small" style="background:#cc0000; padding:6px 10px; font-size:12px; text-decoration:none;" onclick="return confirm('Yakin hapus? Produk akan diarsipkan dan hilang dari toko.');">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>