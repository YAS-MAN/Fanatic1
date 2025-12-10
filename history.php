<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Riwayat Pesanan';
$currentPage = 'history';

require_once __DIR__ . '/db.php';
$pdo = db();
$uid = current_user()['id'];

// Query untuk mengambil Order + Gambar Produk Pertama + Nama Produk Pertama
$sql = "SELECT o.*, 
        (SELECT p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = o.id LIMIT 1) as prod_image,
        (SELECT p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = o.id LIMIT 1) as prod_name,
        (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as total_items
        FROM orders o 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$uid]);
$myOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="page-banner">
  <h1>Riwayat Pesanan</h1>
</div>

<div style="max-width:1000px; margin:0 auto; padding:0 20px 60px;">
  
  <?php if(empty($myOrders)): ?>
    <div class="card" style="text-align:center; padding:40px;">
        <div style="font-size:40px; margin-bottom:10px;">📦</div>
        <p>Belum ada riwayat pesanan.</p>
        <a href="store.php" class="btn red">Mulai Belanja</a>
    </div>
  <?php else: ?>
    
    <div class="history-container">
        <table class="history-table">
            <thead>
                <tr style="background:transparent; box-shadow:none;">
                    <th width="45%" style="text-align:left; color:#888; padding-bottom:10px;">Produk</th>
                    <th width="20%" style="text-align:left; color:#888; padding-bottom:10px;">Tanggal</th>
                    <th width="20%" style="text-align:left; color:#888; padding-bottom:10px;">Total</th>
                    <th width="15%" style="text-align:right; color:#888; padding-bottom:10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($myOrders as $order): ?>
                    <tr>
                        <td>
                            <div class="product-preview">
                                <img src="<?php echo $order['prod_image'] ?? 'assets/default.jpg'; ?>" alt="Product">
                                <div>
                                    <div style="font-weight:bold; color:#fff; margin-bottom:4px;">
                                        <?php echo htmlspecialchars($order['prod_name']); ?>
                                    </div>
                                    <?php if($order['total_items'] > 1): ?>
                                        <div style="font-size:11px; color:#aaa;">
                                            + <?php echo $order['total_items'] - 1; ?> barang lainnya
                                        </div>
                                    <?php endif; ?>
                                    <div style="font-size:11px; color:#777;">
                                        ID: #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="color:#ccc;">
                            <?php echo date('d M Y H:i', strtotime($order['created_at'])); ?>
                        </td>
                        <td style="font-weight:bold; color:#fff;">
                            Rp <?php echo number_format($order['total'], 0, ',', '.'); ?>
                        </td>
                        <td style="text-align:right;">
                            <span class="status-pill <?php echo $order['status']; ?>">
                                <?php 
                                    if($order['status']=='pending') echo 'Menunggu';
                                    elseif($order['status']=='paid') echo 'Dikemas';
                                    elseif($order['status']=='shipped') echo 'Dikirim';
                                    elseif($order['status']=='cancelled') echo 'Dibatalkan';
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>