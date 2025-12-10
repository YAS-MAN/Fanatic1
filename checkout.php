<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Checkout';
$currentPage = 'checkout';

// --- 1. SETUP DATA & VALIDASI SESSION ---
// Cek apakah ada data checkout (dari tombol Beli Sekarang) atau Keranjang
if (!isset($_SESSION['checkout']) || empty($_SESSION['checkout']['items'])) {
  // Jika tidak ada session checkout, cek apakah ada keranjang
  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: store.php'); exit;
  }
  // Pindahkan keranjang ke sesi checkout
  $_SESSION['checkout'] = ['items' => $_SESSION['cart']];
}

$items = $_SESSION['checkout']['items'];
require_once __DIR__ . '/db.php';
$pdo = db();

// --- 2. AMBIL DETAIL PRODUK DARI DB ---
$ids = array_keys($items);
if (empty($ids)) {
    header('Location: store.php'); exit;
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, name, price, image, team, stock FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$byId = [];
foreach ($products as $p) $byId[$p['id']] = $p;

// --- 3. HITUNG TOTAL ---
$subtotal = 0;
$orderItems = [];
foreach ($items as $pid => $qty) {
  $prod = $byId[$pid] ?? null;
  if (!$prod) continue;
  
  $lineTotal = $prod['price'] * $qty;
  $orderItems[] = [
      'product' => $prod,
      'qty' => $qty,
      'line_total' => $lineTotal
  ];
  $subtotal += $lineTotal;
}

$shipping = 15000; // Ongkir Flat
$total = $subtotal + $shipping;

// --- 4. PROSES PEMBAYARAN (INSERT KE DB) ---
$orderPlaced = false;
$orderRef = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
  $address = trim($_POST['address'] ?? '');
  $notes = trim($_POST['notes'] ?? '');
  
  if ($address) {
      try {
          $pdo->beginTransaction();

          // A. Insert ke tabel orders
          // Status awal 'pending'
          $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, total, status, created_at) VALUES (?, ?, 'pending', NOW())");
          $stmtOrder->execute([current_user()['id'], $total]);
          $orderId = $pdo->lastInsertId();
          
          // B. Insert ke tabel order_items
          $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
          
          // C. Siapkan update stok
          $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

          foreach ($orderItems as $item) {
              // Simpan item
              $stmtItem->execute([
                  $orderId, 
                  $item['product']['id'], 
                  $item['qty'], 
                  $item['product']['price']
              ]);
              
              // Kurangi stok
              $stmtStock->execute([$item['qty'], $item['product']['id']]);
          }

          $pdo->commit();

          $orderRef = 'ORD-' . str_pad($orderId, 6, '0', STR_PAD_LEFT);
          $orderPlaced = true;
          
          // D. Bersihkan keranjang belanja setelah sukses order
          unset($_SESSION['cart']);
          unset($_SESSION['checkout']);
          
          // Hapus juga data keranjang di Database agar sinkron
          $stmtClearCart = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
          $stmtClearCart->execute([current_user()['id']]);

      } catch (Exception $e) {
          $pdo->rollBack();
          $error = "Gagal memproses pesanan: " . $e->getMessage();
      }
  } else {
      $error = "Alamat pengiriman wajib diisi.";
  }
}

include 'header.php';
?>

<div class="page-banner">
  <h1>Checkout</h1>
</div>

<div style="max-width:1200px; margin:0 auto; padding:0 20px 20px;">
    <a href="store.php" class="back-btn-fixed">← Kembali Belanja</a>
</div>

<?php if ($orderPlaced): ?>
    <div style="max-width:600px; margin:40px auto; text-align:center; padding:40px; background:#1b1b1b; border-radius:12px; border:1px solid #28a745; margin-top:-80px;">
        <div style="font-size:50px; margin-bottom:20px;">✔️</div>
        <h2 style="color:#28a745;">Pesanan Berhasil Dibuat!</h2>
        <p style="color:#ddd; margin-bottom:20px;">Terima kasih telah berbelanja di F1 Fanatic.</p>
        
        <div style="background:#111; padding:20px; border-radius:8px; display:inline-block; text-align:left; margin-bottom:20px;">
            <p style="margin:5px 0;"><strong>No. Order:</strong> <span style="color:#fff"><?php echo $orderRef; ?></span></p>
            <p style="margin:5px 0;"><strong>Total:</strong> <span style="color:var(--primary-red)">Rp <?php echo number_format($total, 0, ',', '.'); ?></span></p>
            <p style="margin:5px 0; font-size:12px; color:#aaa;">Status: Menunggu Konfirmasi Admin</p>
        </div>
        
        <div>
            <a href="history.php" class="btn grey">Lihat Riwayat</a>
            <a href="store.php" class="btn red">Belanja Lagi</a>
        </div>
    </div>

<?php else: ?>
    <form method="post" class="checkout-container">
        
        <div class="checkout-form-section">
            <h3>Alamat Pengiriman</h3>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?> 
            
            <div class="checkout-form-row">
                <label>Nama Penerima</label>
                <input type="text" value="<?php echo htmlspecialchars(current_user()['name']); ?>" readonly style="background:#222; color:#777; cursor:not-allowed;">
            </div>

            <div class="checkout-form-row">
                <label>Email</label>
                <input type="text" value="<?php echo htmlspecialchars(current_user()['email']); ?>" readonly style="background:#222; color:#777; cursor:not-allowed;">
            </div>

            <div class="checkout-form-row">
                <label>Alamat Lengkap</label>
                <textarea name="address" rows="4" placeholder="Jalan, Nomor Rumah, RT/RW, Kecamatan, Kota, Kode Pos" required></textarea>
            </div>

            <div class="checkout-form-row">
                <label>Catatan (Opsional)</label>
                <input type="text" name="notes" placeholder="Misal: Titip di pos satpam">
            </div>
            
            <h3 style="margin-top:30px;">Pembayaran</h3>
            <div class="qr-payment-area">
                <p class="qr-instruction">Scan QRIS di bawah ini untuk membayar</p>
                
                <img src="./assets/QR code.jpg" alt="QRIS Payment" style="width:150px; height:150px;">
                
                <p style="margin-top:10px; font-weight:bold; color:#000;">F1 FANATIC STORE</p>
                
                <div style="background:#eee; color:#333; padding:5px 10px; border-radius:4px; display:inline-block; margin-top:5px; font-family:monospace; font-size:12px; border:1px dashed #999;">
                    REF: <?php echo isset($generatedRefID) ? $generatedRefID : 'TRX-'.date('ymdHis'); ?>
                </div>
            </div>
        </div>

        <div class="order-summary-card">
            <h3>Ringkasan Pesanan</h3>
            
            <div class="summary-items-list">
                <?php foreach ($orderItems as $item): ?>
                    <div class="summary-item">
                        <img src="<?php echo $item['product']['image']; ?>" alt="Product">
                        <div class="summary-details">
                            <span class="summary-name"><?php echo htmlspecialchars($item['product']['name']); ?></span>
                            <span class="summary-meta"><?php echo $item['product']['team']; ?></span>
                            <span class="summary-meta">Qty: <?php echo $item['qty']; ?></span>
                        </div>
                        <span class="summary-price">Rp <?php echo number_format($item['line_total'], 0, ',', '.'); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="checkout-total">
                <div class="checkout-row">
                    <span>Subtotal</span>
                    <span>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                </div>
                <div class="checkout-row">
                    <span>Ongkos Kirim</span>
                    <span>Rp <?php echo number_format($shipping, 0, ',', '.'); ?></span>
                </div>
                <div class="checkout-row total">
                    <span>Total Bayar</span>
                    <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>
            </div>

            <button type="submit" name="pay_now" value="1" class="btn red" style="width:100%; margin-top:20px; font-size:18px; justify-content:center; padding:12px 0;">
                Konfirmasi Pesanan
            </button>
            <p style="font-size:11px; color:#777; text-align:center; margin-top:10px;">
                Pastikan alamat sudah benar sebelum membayar.
            </p>
        </div>

    </form>
<?php endif; ?>
<?php include 'footer.php'; ?>