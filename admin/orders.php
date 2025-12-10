<?php
$pageTitle = 'Kelola Pesanan';
$currentPage = 'orders';
require_once __DIR__ . '/auth_admin.php';
require_admin_login();
include 'header.php';

$pdo = db();

// --- HANDLE ACTIONS (BUTTONS) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oid = $_POST['order_id'];
    
    if (isset($_POST['mark_paid'])) {
        $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?")->execute([$oid]);
    }
    elseif (isset($_POST['mark_shipped'])) {
        $pdo->prepare("UPDATE orders SET status = 'shipped' WHERE id = ?")->execute([$oid]);
    }
    elseif (isset($_POST['delete_order'])) {
        // Hapus item dulu baru order (FK)
        $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$oid]);
        $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$oid]);
    }
    header('Location: orders.php'); exit;
}

// Fetch Data
$sql = "SELECT o.id, o.total, o.status, o.created_at, u.name as user_name 
        FROM orders o JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";
$orders = $pdo->query($sql)->fetchAll();
?>

<div class="page-banner">
  <h1>Pesanan Masuk</h1>
</div>
<a href="index.php" class="btn grey detail-back">← Kembali</a>

<div style="max-width:1000px; margin:0 auto; padding:0 20px 60px;">
  <div class="card" style="padding:0; overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Total</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
          <td>#<?php echo $o['id']; ?></td>
          <td>
            <strong><?php echo htmlspecialchars($o['user_name']); ?></strong><br>
            <small style="color:#777"><?php echo $o['created_at']; ?></small>
          </td>
          <td style="color:var(--primary-red); font-weight:bold;">
            Rp <?php echo number_format($o['total'],0,',','.'); ?>
          </td>
          <td>
            <span class="status-pill <?php echo $o['status']; ?>">
              <?php 
                if($o['status']=='pending') echo 'Belum Bayar';
                elseif($o['status']=='paid') echo 'Sudah Bayar';
                elseif($o['status']=='shipped') echo 'Dikirim';
              ?>
            </span>
          </td>
          <td>
            <form method="post" style="display:flex; gap:5px;">
              <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
              
              <?php if($o['status'] == 'pending'): ?>
              <button name="mark_paid" title="Konfirmasi Bayar" class="btn small" style="background:#28a745; padding:8px;">
                ✔
              </button>
              <?php endif; ?>

              <?php if($o['status'] == 'paid'): ?>
              <button name="mark_shipped" title="Kirim Barang" class="btn small" style="background:#0066ff; padding:8px;">
                ✈
              </button>
              <?php endif; ?>

              <button name="delete_order" title="Hapus Pesanan" class="btn small" style="background:#dc3545; padding:8px;" onclick="return confirm('Hapus pesanan ini?')">
                🗑
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'footer.php'; ?>