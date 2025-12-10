<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Keranjang';
$currentPage = 'keranjang';

require_once __DIR__ . '/db.php';
$pdo = db();
$uid = current_user()['id'];

// --- 1. HANDLE POST ACTIONS (Remove, Update, CHECKOUT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // A. Hapus Barang
  if (isset($_POST['remove'])) {
    $pid = $_POST['product_id'];
    unset($_SESSION['cart'][$pid]);
    // Hapus dari DB agar sinkron
    $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$uid, $pid]);
    header('Location: keranjang.php'); 
    exit;
  }
  
  // B. Update Jumlah
  if (isset($_POST['update_qty'])) {
    $pid = $_POST['product_id'];
    $qty = max(1, (int)$_POST['qty']);
    $_SESSION['cart'][$pid] = $qty;
    // Update DB agar sinkron
    $pdo->prepare("INSERT INTO cart (user_id, product_id, qty) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE qty = ?")
        ->execute([$uid, $pid, $qty, $qty]);
    header('Location: keranjang.php'); 
    exit;
  }

  // C. CHECKOUT (BAGIAN PENTING YANG MUNGKIN HILANG)
  if (isset($_POST['checkout'])) {
    // 1. Pastikan keranjang tidak kosong
    if (empty($_SESSION['cart'])) {
        header('Location: store.php');
        exit;
    }

    // 2. Salin data keranjang ke sesi checkout
    // Struktur: ['items' => [id_produk => qty, id_produk => qty]]
    $_SESSION['checkout'] = ['items' => $_SESSION['cart']];

    // 3. Redirect ke halaman checkout
    header('Location: checkout.php'); 
    exit;
  }
}

// --- 2. AMBIL DATA KERANJANG UNTUK DITAMPILKAN ---
$cartItems = [];
if (!empty($_SESSION['cart'])) {
  $ids = array_keys($_SESSION['cart']);
  // Validasi jika array keys kosong (mencegah error SQL)
  if (!empty($ids)) {
      $placeholders = implode(',', array_fill(0, count($ids), '?'));
      $stmt = $pdo->prepare("SELECT id, name, price, image, stock, team FROM products WHERE id IN ($placeholders)");
      $stmt->execute($ids);
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      foreach ($products as $p) {
        $p['qty'] = $_SESSION['cart'][$p['id']];
        $cartItems[] = $p;
      }
  }
}

include 'header.php';
?>

<div class="page-banner">
  <h1>Keranjang Belanja</h1>
</div>

<div style="max-width:1200px; margin:0 auto; padding:0 30px 20px;">
  <a href="store.php" class="back-btn-fixed">← Kembali ke Store</a>
</div>

<section class="card-container">
  <?php if (empty($cartItems)): ?>
    <div style="grid-column: 1/-1; text-align:center; padding:50px; color:#777;">
      <h3>Keranjang Kosong</h3>
      <p>Ayo tambahkan beberapa merchandise F1 keren!</p>
      <a href="store.php" class="btn red" style="margin-top:20px;">Belanja Sekarang</a>
    </div>
  <?php else: ?>
    <?php foreach ($cartItems as $p): ?>
      <div class="card">
        <img src="<?php echo $p['image']; ?>" alt="Product">
        <div class="card-content">
          <h3><?php echo htmlspecialchars($p['name']); ?></h3>
          <p style="color:#aaa; font-size:12px; margin-bottom:10px;"><?php echo htmlspecialchars($p['team']); ?></p>
          <p style="font-weight:bold; color:var(--primary-red); margin-bottom:15px;">
            Rp <?php echo number_format($p['price'] * $p['qty'], 0, ',', '.'); ?>
          </p>
          
          <form method="post" style="margin-top:auto;">
            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
            
            <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
              <div class="qty-control">
                <button type="submit" name="update_qty" value="1" class="qty-btn" 
                        onclick="var i=this.form.querySelector('.qty-input'); i.value=Math.max(1, parseInt(i.value)-1);">
                    -
                </button>
                
                <input type="number" name="qty" class="qty-input" value="<?php echo $p['qty']; ?>" readonly>
                
                <button type="submit" name="update_qty" value="1" class="qty-btn" 
                        onclick="var i=this.form.querySelector('.qty-input'); i.value=parseInt(i.value)+1;">
                    +
                </button>
              </div>
              <button class="btn outline small" name="remove" value="1" style="border-color:#555; color:#aaa;">Hapus</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</section>

<?php if (!empty($cartItems)): ?>
  <div style="text-align:center; margin-bottom:60px; margin-top:30px;">
    <form method="post">
      <button class="btn red" name="checkout" value="1" style="padding:15px 40px; font-size:18px;">
        Lanjut ke Checkout →
      </button>
    </form>
  </div>
<?php endif; ?>

<?php include 'footer.php'; ?>