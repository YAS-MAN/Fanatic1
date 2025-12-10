<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Store';
$currentPage = 'store';

require_once __DIR__ . '/db.php';
$pdo = db();
$uid = current_user()['id']; // Ambil ID user

// --- LOGIC ADD TO CART & BUY NOW ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['qty']);
    
    if ($pid) {
        // 1. ADD TO CART
        if (isset($_POST['add_to_cart'])) {
            // Update Session
            $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
            
            // Update Database (Persistent)
            $newQty = $_SESSION['cart'][$pid];
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, qty) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE qty = ?");
            $stmt->execute([$uid, $pid, $newQty, $newQty]); // Insert or Update
            
            header('Location: store.php'); exit;
        }
        
        // 2. BUY NOW (LANGSUNG CHECKOUT)
        if (isset($_POST['buy_now'])) {
            // "Beli Sekarang" biasanya tidak masuk keranjang database permanen, 
            // hanya sesi checkout sementara.
            $_SESSION['checkout'] = ['items' => [$pid => $qty]];
            header('Location: checkout.php'); // Pastikan ini jalan
            exit;
        }
    }
}

// Fetch Products... (Kode ambil produk biarkan sama)
$stmt = $pdo->query('SELECT id, name, price, image, stock, team FROM products WHERE is_deleted = 0 ORDER BY id ASC');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$teams = [];
try {
  $tstmt = $pdo->query('SELECT DISTINCT team FROM products WHERE team IS NOT NULL AND team <> "" ORDER BY team');
  $teams = $tstmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) { }

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cartCount = array_sum($_SESSION['cart']);

// --- LOGIC ADD TO CART ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
  $pid = (int)$_POST['product_id'];
  $qty = max(1, (int)$_POST['qty']);
  if ($pid) {
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
  }
  header('Location: store.php'); exit;
}
// Logic Buy Now tetap sama...

include 'header.php';
?>

<div class="page-banner">
  <h1>Official F1 Merchandise</h1>
</div>

<a href="keranjang.php" class="floating-cart-btn">
  🛒
  <?php if($cartCount > 0): ?>
    <span class="cart-badge"><?php echo $cartCount; ?></span>
  <?php endif; ?>
</a>

<div class="filter-buttons">
  <button class="filter-btn outline active" data-team="all">All Teams</button>
  <?php foreach ($teams as $tm): ?>
    <button class="filter-btn" data-team="<?php echo htmlspecialchars($tm); ?>"><?php echo htmlspecialchars($tm); ?></button>
  <?php endforeach; ?>
</div>

<section class="card-container" id="store-grid">
  <?php foreach ($products as $p): ?>
    <div class="card store-card" data-team="<?php echo htmlspecialchars($p['team']); ?>">
      <img src="<?php echo $p['image']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
      <div class="card-content">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <div class="card-description" style="color:#aaa; font-size:12px;"><?php echo htmlspecialchars($p['team']); ?></div>
        
        <div style="display:flex; justify-content:space-between; align-items:center; margin: 10px 0;">
           <span style="font-weight:bold; color:var(--primary-red);">Rp <?php echo number_format($p['price'],0,',','.'); ?></span>
           <span style="font-size:12px; color:<?php echo $p['stock']>0?'#28a745':'#dc3545'; ?>">
             <?php echo $p['stock']>0 ? "Stok: {$p['stock']}" : "Habis"; ?>
           </span>
        </div>

        <form method="post">
          <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
          
          <div class="qty-control-wrapper">
              <button type="button" class="qty-btn" onclick="updateQty(this, -1)">-</button>
              <input type="number" name="qty" class="qty-input" value="1" min="1" max="<?php echo max(1, intval($p['stock'])); ?>" readonly>
              <button type="button" class="qty-btn" onclick="updateQty(this, 1)">+</button>
          </div>

          <div class="store-actions">
              <button class="btn grey" name="add_to_cart" value="1" <?php echo ($p['stock'] <= 0) ? 'disabled' : ''; ?>>
                + Keranjang
              </button>
              
              <button class="btn red" name="buy_now" value="1" <?php echo ($p['stock'] <= 0) ? 'disabled' : ''; ?>>
                Beli Sekarang
              </button>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</section>

<script>
// Filter Logic yang Benar (Tidak merusak Grid)
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    // 1. Set Active Class
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // 2. Filter Display
    const team = btn.dataset.team;
    document.querySelectorAll('.store-card').forEach(card => {
      if (team === 'all' || card.dataset.team === team) {
        card.style.display = ''; // Kembalikan ke flex (karena .card display:flex)
      } else {
        card.style.display = 'none';
      }
    });
  });
});

// Quantity JS
function updateQty(btn, change) {
  const input = btn.parentElement.querySelector('input');
  let val = parseInt(input.value) + change;
  if (val < 1) val = 1;
  if (val > parseInt(input.max)) val = parseInt(input.max);
  input.value = val;
}
</script>

<?php include 'footer.php'; ?>