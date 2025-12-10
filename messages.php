<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Messages';
$currentPage = 'messages';

// Get current user info
$currentUser = current_user();
$currentUserId = intval($currentUser['id'] ?? 0);
$userName = $currentUser['name'] ?? 'Pengguna';

// Simpan ke DB jika tersedia, fallback ke session
if (!isset($_SESSION['messages'])) $_SESSION['messages'] = [];

// Siapkan koneksi DB
$pdo = null;
try {
  require_once __DIR__ . '/db.php';
  $pdo = db();
} catch (Exception $e) { /* fallback ke sesi */ }

$status = '';
$statusType = '';

// --- LOGIKA POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // 1. BALAS PESAN
  if (isset($_POST['reply_message_id']) && isset($_POST['reply_text'])) {
    $reply = trim($_POST['reply_text'] ?? '');
    $mid = intval($_POST['reply_message_id']);
    if ($reply === '') {
      $status = 'Balasan tidak boleh kosong.'; $statusType = 'error';
    } else {
      if (isset($pdo)) {
        try {
          $stmt = $pdo->prepare('INSERT INTO message_replies(message_id, admin_id, reply) VALUES(?, ?, ?)');
          $stmt->execute([$mid, $currentUserId, $reply]); 
          $status = 'Balasan berhasil terkirim!'; $statusType = 'success';
        } catch (Exception $e) {
          $status = 'Gagal mengirim balasan.'; $statusType = 'error';
        }
      }
    }
  } 
  
  // 2. EDIT PESAN (Hanya milik sendiri)
  else if (isset($_POST['edit_message_id']) && isset($_POST['edit_text'])) {
    $editText = trim($_POST['edit_text'] ?? '');
    $mid = intval($_POST['edit_message_id']);
    if ($editText === '') {
      $status = 'Edit pesan tidak boleh kosong.'; $statusType = 'error';
    } else {
      if (isset($pdo)) {
        try {
          // VALIDASI: Hanya bisa edit jika user_id cocok dengan yang login
          $stmt = $pdo->prepare('UPDATE messages SET content = ? WHERE id = ? AND user_id = ?');
          $stmt->execute([$editText, $mid, $currentUserId]);
          
          if ($stmt->rowCount() > 0) {
              $status = 'Pesan berhasil diperbarui!'; $statusType = 'success';
          } else {
              $status = 'Gagal edit (Bukan pesan Anda).'; $statusType = 'error';
          }
        } catch (Exception $e) {
          $status = 'Gagal memperbarui pesan.'; $statusType = 'error';
        }
      }
    }
  } 
  
  // 3. PESAN BARU
  else {
    $content = trim($_POST['content'] ?? '');
    if ($content === '') {
      $status = 'Pesan tidak boleh kosong.'; $statusType = 'error';
    } else {
      try {
        require_once __DIR__ . '/db.php';
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO messages(user_id, name, content, created_at) VALUES(?, ?, ?, NOW())');
        $ok = $stmt->execute([$currentUserId, $userName, $content]);
        if ($ok) {
          $status = 'Pesan berhasil tersimpan!'; $statusType = 'success';
        }
      } catch (Exception $e) {
        $status = 'Gagal menyimpan pesan.'; $statusType = 'error';
      }
    }
  }
  $_SESSION['flash'] = ['message' => $status, 'type' => $statusType];
  header('Location: messages.php');
  exit;
} else {
  if (!empty($_SESSION['flash'])) {
    $status = $_SESSION['flash']['message'] ?? '';
    $statusType = $_SESSION['flash']['type'] ?? '';
    unset($_SESSION['flash']);
  }
}

// --- FETCH MESSAGES (GLOBAL - SEMUA USER) ---
$messages = [];
if (isset($pdo)) {
  try {
    // UBAH QUERY: Hapus "WHERE user_id = ?" agar global
    // Join users untuk dapat role (agar nama admin bisa merah)
    $sql = "SELECT m.*, u.role as sender_role 
            FROM messages m 
            LEFT JOIN users u ON m.user_id = u.id 
            ORDER BY m.created_at DESC LIMIT 50";
    $messages = $pdo->query($sql)->fetchAll();
  } catch (Exception $e) {
    $messages = [];
  }
}

// --- FETCH REPLIES ---
$repliesMap = [];
if (isset($pdo)) {
  try {
    $rs = $pdo->query('SELECT mr.message_id, mr.admin_id, mr.reply, mr.created_at, u.name AS author_name, u.role AS author_role FROM message_replies mr LEFT JOIN users u ON u.id = mr.admin_id ORDER BY mr.created_at ASC')->fetchAll();
    foreach ($rs as $r) { $repliesMap[$r['message_id']][] = $r; }
  } catch (Exception $e) { /* ignore */ }
}
?>

<?php include 'header.php'; ?>

    <div class="page-banner">
      <h1>Forum Diskusi</h1>
    </div>

    <div style="max-width:1200px; margin:20px auto; padding:0 30px 60px;">
      
      <div class="card" style="width:800px; margin-bottom:30px;">
        <div class="card-content">
            <?php if ($status): ?>
            <div class="alert" style="background:<?php echo $statusType === 'success' ? '#155724' : '#721c24'; ?>; color:<?php echo $statusType === 'success' ? '#d4edda' : '#f8d7da'; ?>; padding:12px; border-radius:6px; margin-bottom:12px;">
              <?php echo htmlspecialchars($status); ?>
            </div>
            <script>
              setTimeout(function(){ var a=document.querySelector('.alert'); if(a) a.style.display='none'; }, 4000);
            </script>
          <?php endif; ?>
          
          <form method="post" class="form-grid">
            <label>Nama Anda</label>
            <input type="text" value="<?php echo htmlspecialchars($userName); ?>" disabled style="background:#222; color:#aaa;" />
            
            <div style="display:flex; align-items:center; gap:16px; margin:8px 0 10px;">
              <label style="margin:0;">Jenis Pesan</label>
              <div style="display:flex; gap:20px;">
                <label style="font-weight:normal; color:#ccc; display:flex; align-items:center; gap:8px;">
                  <input type="radio" name="msg_type" value="kesan" checked /> Pesan & Kesan
                </label>
                <label style="font-weight:normal; color:#ccc; display:flex; align-items:center; gap:8px;">
                  <input type="radio" name="msg_type" value="request" /> Request Barang
                </label>
              </div>
            </div>

            <label>Pesan</label>
            <textarea name="content" rows="5" required placeholder="Tulis pesan, kesan, atau request barang Anda..." style="width: 100%; box-sizing: border-box; background:#1a1a1a; border:1px solid #333; color:#fff; padding:10px; border-radius:8px;"></textarea>
            <div style="display:flex; justify-content:flex-end; margin-top:20px;">
                <button class="btn red" type="submit" style="width:auto; padding:10px 40px;">Kirim Pesan</button>
            </div>
          </form>
        </div>
      </div>

      <?php if (!empty($messages)): ?>
        <div class="card" style="margin-top:20px; width:100%;">
          <div class="card-content">
            <h3 style="text-align:center; color:#ff0000; margin-bottom:30px;">Riwayat Pesan</h3>
            
            <div style="display:flex; flex-direction:column; gap:16px;">
              <?php foreach ($messages as $m): 
                  // LOGIKA VISIBILITAS
                  $isMyMessage = ($m['user_id'] == $currentUserId); // Cek kepemilikan
                  $isSenderAdmin = (($m['sender_role'] ?? '') === 'admin');
                  $nameColor = $isSenderAdmin ? '#ff4d4d' : '#fff';
              ?>
                <div style="background:#0f0f0f; padding:12px; border-radius:8px; border-left:3px solid #ff0000;">
                  
                  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <strong style="color:<?php echo $nameColor; ?>;">
                        <?php echo htmlspecialchars($m['name']); ?>
                        <?php if($isSenderAdmin) echo ' (ADMIN)'; ?>
                    </strong>
                    <span style="color:#999; font-size:12px;"><?php echo htmlspecialchars($m['created_at']); ?></span>
                  </div>
                  
                  <p style="margin:0 0 15px 0; white-space:pre-wrap; color:#ddd; text-align:left;"><?php echo nl2br(htmlspecialchars($m['content'] ?? '')); ?></p>

                  <?php if (!empty($repliesMap[$m['id']])): ?>
                    <div style="margin-top:10px; padding-top:10px; border-top:1px dashed #333;">
                      <div style="color:#ff0000; font-weight:600; font-size:12px; margin-bottom:8px;">Balasan:</div>
                      <?php foreach ($repliesMap[$m['id']] as $rep): 
                          // Warna Balasan: Admin (Hijau), User Lain (Biru)
                          $repIsAdmin = (strtolower($rep['author_role'] ?? '') === 'admin');
                          $borderColor = $repIsAdmin ? '#28a745' : '#0066ff'; 
                          $textColor = $repIsAdmin ? '#28a745' : '#0066ff';
                      ?>
                        <div style="background:#1a1a1a; padding:10px; border-radius:6px; border-left:2px solid <?php echo $borderColor; ?>; margin-bottom:8px;">
                          <div style="color:<?php echo $textColor; ?>; font-size:11px; margin-bottom:4px;">
                              <?php echo htmlspecialchars($rep['author_name'] ?? 'Pengguna'); ?> — <?php echo htmlspecialchars($rep['created_at']); ?>
                          </div>
                          <div style="white-space:pre-wrap; color:#bbb; font-size:14px; text-align:left;"><?php echo nl2br(htmlspecialchars($rep['reply'] ?? '')); ?></div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <div style="margin-top:10px; display:flex; gap:8px;">
                    <button class="btn red" style="width:auto; padding:6px 12px; font-size:12px;" onclick="document.getElementById('reply-form-<?php echo intval($m['id']); ?>').style.display = document.getElementById('reply-form-<?php echo intval($m['id']); ?>').style.display === 'none' ? 'block' : 'none';">Balas Pesan</button>
                    
                    <?php if ($isMyMessage): ?>
                        <button class="btn grey" style="width:auto; padding:6px 12px; font-size:12px;" onclick="document.getElementById('edit-form-<?php echo intval($m['id']); ?>').style.display = document.getElementById('edit-form-<?php echo intval($m['id']); ?>').style.display === 'none' ? 'block' : 'none';">Edit Pesan</button>
                    <?php endif; ?>
                  </div>

                  <div id="reply-form-<?php echo intval($m['id']); ?>" style="display:none; margin-top:12px; padding:12px; background:#111; border-radius:6px; border:1px solid #333;">
                    <form method="post" style="display:flex; flex-direction:column; gap:8px;">
                      <input type="hidden" name="reply_message_id" value="<?php echo intval($m['id']); ?>">
                      <textarea name="reply_text" rows="3" placeholder="Tulis balasan Anda..." style="padding:8px; background:#0f0f0f; color:#fff; border:1px solid #333; border-radius:4px; width:100%; box-sizing:border-box;"></textarea>
                      <div style="text-align:right;">
                          <button class="btn red" type="submit" style="width:auto; padding:8px 20px; font-size:12px;">Kirim Balasan</button>
                      </div>
                    </form>
                  </div>

                  <?php if ($isMyMessage): ?>
                  <div id="edit-form-<?php echo intval($m['id']); ?>" style="display:none; margin-top:12px; padding:12px; background:#111; border-radius:6px; border:1px solid #333;">
                    <form method="post" style="display:flex; flex-direction:column; gap:8px;">
                      <input type="hidden" name="edit_message_id" value="<?php echo intval($m['id']); ?>">
                      <textarea name="edit_text" rows="3" placeholder="Edit pesan Anda..." style="padding:8px; background:#0f0f0f; color:#fff; border:1px solid #333; border-radius:4px; width:100%; box-sizing:border-box;"><?php echo htmlspecialchars($m['content'] ?? ''); ?></textarea>
                      <div style="text-align:right;">
                          <button class="btn red" type="submit" style="width:auto; padding:8px 20px; font-size:12px;">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                  <?php endif; ?>

                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

<?php include 'footer.php'; ?>