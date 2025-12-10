<?php
$pageTitle = 'Pesan Pengguna (Admin)';
$currentPage = 'messages';
require_once __DIR__ . '/auth_admin.php';
require_admin_login();
include 'header.php';

$pdo = null;
$dbAvailable = false;
try {
  require_once __DIR__ . '/../db.php';
  $pdo = db();
  $dbAvailable = true;
} catch (Exception $e) {
  $dbAvailable = false;
}

// Ensure table exists
if ($dbAvailable) {
  $sql = "CREATE TABLE IF NOT EXISTS message_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    admin_id INT DEFAULT NULL,
    reply TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
  try { $pdo->exec($sql); } catch (Exception $e) { /* ignore */ }
}

$status = '';
// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
  $did = intval($_GET['delete']);
  if ($dbAvailable && $did) {
    try {
      $pdo->prepare('DELETE FROM message_replies WHERE message_id = ?')->execute([$did]);
      $pdo->prepare('DELETE FROM messages WHERE id = ?')->execute([$did]);
      $status = 'Pesan berhasil dihapus.';
    } catch (Exception $e) {
      $status = 'Gagal menghapus pesan.';
    }
  }
}

// Handle reply POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message_id'])) {
  $mid = intval($_POST['reply_message_id']);
  $reply = trim($_POST['reply_text'] ?? '');
  if ($reply === '') {
    $status = 'Balasan kosong.';
  } else {
    if ($dbAvailable) {
      try {
        $stmt = $pdo->prepare('INSERT INTO message_replies(message_id, admin_id, reply) VALUES(?, ?, ?)');
        $adminId = intval(current_user()['id'] ?? 0) ?: null;
        $stmt->execute([$mid, $adminId, $reply]);
        $status = 'Balasan tersimpan.';
      } catch (Exception $e) {
        $status = 'Gagal menyimpan balasan: ' . htmlspecialchars($e->getMessage());
      }
    }
  }
  // Redirect to avoid resubmit
  header('Location: messages.php');
  exit;
}

// Fetch messages
$messages = [];
if ($dbAvailable) {
  try {
    $messages = $pdo->query('SELECT id, user_id, name, content, created_at FROM messages ORDER BY created_at DESC LIMIT 20')->fetchAll();
  } catch (Exception $e) { $messages = []; }
}

// Fetch replies map
$repliesMap = [];
if ($dbAvailable) {
  try {
    $rs = $pdo->query('SELECT mr.message_id, mr.admin_id, mr.reply, mr.created_at, u.name AS author_name, u.role AS author_role FROM message_replies mr LEFT JOIN users u ON u.id = mr.admin_id ORDER BY mr.created_at ASC')->fetchAll();
    foreach ($rs as $r) {
      $repliesMap[$r['message_id']][] = $r;
    }
  } catch (Exception $e) { /* ignore */ }
}
?>

<div class="page-banner">
  <h1>Pesan Pengguna (Admin)</h1>
</div>

<a href="index.php" class="btn grey detail-back">← Kembali</a>

<div style="width:1200px; margin:0 auto; padding:0 30px 60px;">
  
  <?php if ($status): ?>
    <div class="alert" style="margin-bottom:12px; background:#155724; color:#d4edda; border:1px solid #1e7e34; padding:12px; border-radius:6px;">
      <?php echo htmlspecialchars($status); ?>
    </div>
  <?php endif; ?>

  <?php if (empty($messages)): ?>
    <div class="card" style="text-align:center; padding:40px;">
        <p style="color:#777; font-size:18px;">Belum ada pesan dari pengguna.</p>
    </div>
  <?php else: ?>
    
    <div class="card" style="width:1150px; margin-top:20px;">
      <div class="card-content">
        <h3 style="text-align:center; color:#ff0000; margin-bottom:25px;">Inbox Pesan</h3>
        
        <div style="display:flex; flex-direction:column; gap:16px;width:1000px; margin:0 auto;">
          <?php foreach ($messages as $m): ?>
            
            <div style="background:#0f0f0f; padding:20px; border-radius:8px; border-left:4px solid #ff0000; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
              
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; border-bottom:1px solid #222; padding-bottom:10px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <strong style="color:#fff; font-size:1.1rem;"><?php echo htmlspecialchars($m['name'] ?? 'Pengguna'); ?></strong>
                </div>
                <span style="color:#777; font-size:13px;"><?php echo htmlspecialchars($m['created_at'] ?? ''); ?></span>
              </div>
              
              <p style="margin:0 0 15px 0; white-space:pre-wrap; color:#ddd; line-height:1.6; font-size:15px; text-align: left;"><?php echo nl2br(htmlspecialchars($m['content'] ?? '')); ?></p>

              <?php if (!empty($repliesMap[$m['id']])): ?>
                <div style="margin-top:15px; padding:15px; background:#161616; border-radius:8px;text-align: left;">
                  <div style="color:#ff0000; font-weight:600; font-size:12px; margin-bottom:10px; text-transform:uppercase; letter-spacing:1px;text-align: left;">Balasan:</div>
                  <?php foreach ($repliesMap[$m['id']] as $rep): ?>
                    <div style="margin-bottom:10px; padding-left:10px; border-left:2px solid text-align: left;<?php echo (strtolower($rep['author_role'] ?? '') === 'admin') ? '#28a745' : '#0066ff'; ?>;">
                      <div style="color:<?php echo (strtolower($rep['author_role'] ?? '') === 'admin') ? '#28a745' : '#0066ff'; ?>; font-size:11px; margin-bottom:2px; font-weight:bold;text-align: left;">
                        <?php echo htmlspecialchars($rep['author_name'] ?? 'Pengguna'); ?> 
                        <span style="color:#555; font-weight:normal;">• <?php echo htmlspecialchars($rep['created_at'] ?? ''); ?></span>
                      </div>
                      <div style="white-space:pre-wrap; color:#bbb; font-size:14px;"><?php echo nl2br(htmlspecialchars($rep['reply'] ?? '')); ?></div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <div style="margin-top:15px; display:flex; gap:10px; justify-content: flex-start;">
                <button class="btn red small" style="padding:8px 16px;" onclick="document.getElementById('reply-form-<?php echo intval($m['id']); ?>').style.display = document.getElementById('reply-form-<?php echo intval($m['id']); ?>').style.display === 'none' ? 'block' : 'none';">
                    Balas Pesan
                </button>
                <a class="btn grey small" style="background:#721c24; padding:8px 16px; text-decoration:none;" href="messages.php?delete=<?php echo intval($m['id'] ?? 0); ?>" onclick="return confirm('Hapus pesan ini beserta balasannya?');">
                    Hapus Pesan
                </a>
              </div>

              <div id="reply-form-<?php echo intval($m['id']); ?>" style="display:none; margin-top:15px; padding:15px; background:#1a1a1a; border-radius:8px; border:1px solid #333;">
                <form method="post" style="display:flex; flex-direction:column; gap:10px;">
                  <input type="hidden" name="reply_message_id" value="<?php echo intval($m['id']); ?>">
                  <label style="color:#ccc; font-size:13px;text-align: left;">Isi Balasan Admin:</label>
                  <textarea name="reply_text" rows="3" placeholder="Tulis balasan Anda di sini..." style="width:100%; padding:10px; background:#0f0f0f; color:#fff; border:1px solid #333; border-radius:6px;"></textarea>
                  <div style="text-align:right;">
                      <button class="btn red small" type="submit" style="width:auto; padding:8px 20px;">Kirim Balasan</button>
                  </div>
                </form>
              </div>

            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>