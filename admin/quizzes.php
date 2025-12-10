<?php
$pageTitle = 'Kelola Kuis';
$currentPage = 'quizzes';
require_once __DIR__ . '/auth_admin.php';
require_admin_login();
include 'header.php';

$pdo = db();
$message = '';
$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$edit_quiz = null;

// Handle POST (Add/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $question = trim($_POST['question'] ?? '');
  $option_a = trim($_POST['option_a'] ?? '');
  $option_b = trim($_POST['option_b'] ?? '');
  $option_c = trim($_POST['option_c'] ?? '');
  $option_d = trim($_POST['option_d'] ?? '');
  $correct = trim($_POST['correct'] ?? ''); // Ini nilainya A, B, C, atau D
  $form_edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;

  // PERBAIKAN: Mapping huruf (A,B,C,D) ke teks jawaban yang sebenarnya
  // Karena DB menyimpan Teks Jawaban Penuh, bukan hurufnya.
  $answerText = '';
  if ($correct === 'A') $answerText = $option_a;
  elseif ($correct === 'B') $answerText = $option_b;
  elseif ($correct === 'C') $answerText = $option_c;
  elseif ($correct === 'D') $answerText = $option_d;

  if (($action === 'add' || $action === 'update') && $question && $option_a && $option_b && $option_c && $option_d && $answerText) {
    try {
        if ($action === 'add') {
            // PERBAIKAN: Ganti 'correct_answer' menjadi 'answer'
            $stmt = $pdo->prepare('INSERT INTO quiz_questions(question, option_a, option_b, option_c, option_d, answer) VALUES(?, ?, ?, ?, ?, ?)');
            $stmt->execute([$question, $option_a, $option_b, $option_c, $option_d, $answerText]);
            $message = '<div class="alert alert-success">Pertanyaan berhasil ditambahkan!</div>';
        } elseif ($action === 'update' && $form_edit_id) {
            // PERBAIKAN: Ganti 'correct_answer' menjadi 'answer'
            $stmt = $pdo->prepare('UPDATE quiz_questions SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, answer=? WHERE id=?');
            $stmt->execute([$question, $option_a, $option_b, $option_c, $option_d, $answerText, $form_edit_id]);
            $message = '<div class="alert alert-success">Pertanyaan berhasil diperbarui!</div>';
        }
        
    } catch (Exception $e) {
      $message = '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
  } else {
      if (!$answerText) $message = '<div class="alert alert-error">Harap pilih kunci jawaban yang valid.</div>';
  }
}

// Handle DELETE
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $delete_id = intval($_GET['delete']);
  try {
    $stmt = $pdo->prepare('DELETE FROM quiz_questions WHERE id=?');
    $stmt->execute([$delete_id]);
    $message = '<div class="alert alert-success">Pertanyaan berhasil dihapus!</div>';
  } catch (Exception $e) {
    $message = '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
  }
}

// Get quiz for editing
if ($edit_id) {
  $stmt = $pdo->prepare('SELECT * FROM quiz_questions WHERE id=?');
  $stmt->execute([$edit_id]);
  $edit_quiz = $stmt->fetch();
  
  // Reverse Engineering: Cari tahu kunci jawabannya A/B/C/D berdasarkan teks
  // Karena DB menyimpan "Max Verstappen", bukan "A".
  $currentKey = '';
  if ($edit_quiz) {
      if ($edit_quiz['answer'] === $edit_quiz['option_a']) $currentKey = 'A';
      elseif ($edit_quiz['answer'] === $edit_quiz['option_b']) $currentKey = 'B';
      elseif ($edit_quiz['answer'] === $edit_quiz['option_c']) $currentKey = 'C';
      elseif ($edit_quiz['answer'] === $edit_quiz['option_d']) $currentKey = 'D';
  }
}

// Get all quizzes
$quizzes = $pdo->query('SELECT * FROM quiz_questions ORDER BY id DESC')->fetchAll();
?>

<div class="page-banner">
  <h1>Kelola Pertanyaan Kuis</h1>
</div>

<a href="index.php" class="btn grey detail-back">← Kembali</a>

<?php if ($message) echo $message; ?>

<div style="max-width:1000px; margin:0 auto; padding:0 30px;">
  <div style="background:#1a1a1a; padding:25px; border-radius:10px; border:1px solid rgba(255,0,0,0.12); margin-bottom:30px;">
    <h2 style="color:#ff0000; margin-top:0;"><?php echo $edit_quiz ? 'Edit Pertanyaan' : 'Tambah Pertanyaan Baru'; ?></h2>
    
    <form method="post" class="form-crud">
      <input type="hidden" name="action" value="<?php echo $edit_quiz ? 'update' : 'add'; ?>">
      <?php if ($edit_quiz): ?><input type="hidden" name="edit_id" value="<?php echo intval($edit_quiz['id']); ?>"><?php endif; ?>

      <label>Pertanyaan</label>
      <textarea name="question" rows="2" required><?php echo htmlspecialchars($edit_quiz['question'] ?? ''); ?></textarea>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label>Pilihan A</label>
          <input type="text" name="option_a" value="<?php echo htmlspecialchars($edit_quiz['option_a'] ?? ''); ?>" required>
        </div>
        <div>
          <label>Pilihan B</label>
          <input type="text" name="option_b" value="<?php echo htmlspecialchars($edit_quiz['option_b'] ?? ''); ?>" required>
        </div>
        <div>
          <label>Pilihan C</label>
          <input type="text" name="option_c" value="<?php echo htmlspecialchars($edit_quiz['option_c'] ?? ''); ?>" required>
        </div>
        <div>
          <label>Pilihan D</label>
          <input type="text" name="option_d" value="<?php echo htmlspecialchars($edit_quiz['option_d'] ?? ''); ?>" required>
        </div>
      </div>

      <label>Jawaban Benar</label>
      <select name="correct" required>
        <option value="">Pilih jawaban benar</option>
        <option value="A" <?php echo (isset($currentKey) && $currentKey === 'A') ? 'selected' : ''; ?>>A</option>
        <option value="B" <?php echo (isset($currentKey) && $currentKey === 'B') ? 'selected' : ''; ?>>B</option>
        <option value="C" <?php echo (isset($currentKey) && $currentKey === 'C') ? 'selected' : ''; ?>>C</option>
        <option value="D" <?php echo (isset($currentKey) && $currentKey === 'D') ? 'selected' : ''; ?>>D</option>
      </select>

      <div style="margin-top:20px; display:flex; gap:10px;">
          <button type="submit" class="btn red" style="padding:10px 20px;">
            <?php echo $edit_quiz ? 'Simpan Perubahan' : 'Tambah Pertanyaan'; ?>
          </button>
          
          <?php if ($edit_quiz): ?>
            <a href="quizzes.php" class="btn grey" style="padding:10px 20px; text-decoration:none;">Batal</a>
          <?php endif; ?>
      </div>
    </form>
  </div>

  <div style="background:#1a1a1a; padding:25px; border-radius:10px; border:1px solid rgba(255,0,0,0.12);">
    <h2 style="color:#ff0000; margin-top:0;">Daftar Pertanyaan (<?php echo count($quizzes); ?>)</h2>
    
    <?php if (empty($quizzes)): ?>
      <p style="color:#bbb;">Belum ada pertanyaan.</p>
    <?php else: ?>
      
      <div style="display:grid; gap:12px;">
        <?php foreach ($quizzes as $q): ?>
          <div style="background:#111; padding:15px; border-radius:6px; border-left:3px solid #ff0000; display:flex; justify-content:space-between; align-items:center;">
            <div style="flex-grow:1; margin-right:20px;">
                <p style="margin:0 0 5px 0; font-weight:600; color:#fff; font-size:15px;"><?php echo htmlspecialchars($q['question']); ?></p>
                <div style="font-size:13px; color:#888;">
                    Jawaban: <span style="color:#4caf50; font-weight:bold;"><?php echo htmlspecialchars($q['answer']); ?></span>
                </div>
            </div>
            
            <div style="display:flex; gap:8px; flex-shrink:0;">
              <a href="quizzes.php?edit=<?php echo $q['id']; ?>" class="btn small" style="background:#0066ff; padding:6px 12px; text-decoration:none; font-size:12px;">Edit</a>
              <a href="quizzes.php?delete=<?php echo $q['id']; ?>" class="btn small" style="background:#cc0000; padding:6px 12px; text-decoration:none; font-size:12px;" onclick="return confirm('Hapus pertanyaan ini?');">Hapus</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>