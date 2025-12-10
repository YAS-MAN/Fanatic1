<?php
require_once __DIR__ . '/auth.php';
require_login();

// --- 1. API: HANDLE SAVE SCORE (AJAX) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'save_score') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input) {
        require_once __DIR__ . '/db.php';
        $pdo = db();
        try {
            $stmt = $pdo->prepare("INSERT INTO quiz_attempts (user_id, score, total_questions, duration_seconds) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                current_user()['id'],
                intval($input['score']),
                intval($input['total']),
                intval($input['duration'])
            ]);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    exit; // Stop execution here for AJAX request
}

// --- 2. PAGE VIEW ---
$pageTitle = 'Quiz F1';
$currentPage = 'quiz';

require_once __DIR__ . '/db.php';
$pdo = db();

// A. Ambil Pertanyaan (Acak 10 soal)
$stmt = $pdo->query("SELECT id, question, option_a, option_b, option_c, option_d, answer as answer FROM quiz_questions ORDER BY RAND() LIMIT 10");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// B. Ambil Leaderboard (Top 10 Unik User)
$leaderboard = [];
try {
    // 1. Ambil 100 data teratas (Skor Tertinggi -> Waktu Tercepat)
    // Kita ambil lebih dari 10 (misal 100) untuk cadangan filtering
    $sqlLB = "SELECT qa.*, u.name, u.id as uid 
              FROM quiz_attempts qa 
              JOIN users u ON qa.user_id = u.id 
              ORDER BY qa.score DESC, qa.duration_seconds ASC 
              LIMIT 100";
    $allData = $pdo->query($sqlLB)->fetchAll(PDO::FETCH_ASSOC);

    // 2. Filter di PHP: Hanya ambil 1 skor terbaik per user
    $seenUsers = []; // Array untuk mencatat ID user yang sudah masuk
    
    foreach ($allData as $row) {
        // Jika user ini BELUM ada di daftar 'seenUsers'
        // (Berarti ini adalah skor terbaik dia, karena data sudah diurutkan dari yang terbaik)
        if (!in_array($row['uid'], $seenUsers)) {
            $leaderboard[] = $row;      // Masukkan ke leaderboard final
            $seenUsers[] = $row['uid']; // Tandai user ini sudah masuk
        }
        
        // Berhenti jika sudah dapat 10 Juara
        if (count($leaderboard) >= 10) break;
    }

} catch (Exception $e) {
    // Ignore error jika tabel belum ada data
}
include 'header.php';
?>

<div class="page-banner">
  <h1>F1 Knowledge Race</h1>
</div>

<div class="quiz-container">
    
    <div id="start-screen" class="quiz-start-screen">
        <h2>Seberapa kamu tau soal F1?</h2>
        <p>Jawab 10 pertanyaan dengan cepat, sebelum timer habis!</p>
        <button id="start-btn" class="btn red" style="font-size: 20px; padding: 15px 40px;">START QUIZ</button>
    </div>

    <div id="quiz-interface" class="hidden">
        <div class="quiz-stats-container">
            <div class="quiz-stat-box">
                <div id="quiz-timer" class="quiz-stat-text" style="color:var(--primary-red);">00:00</div>
            </div>
            <div class="quiz-stat-box">
                <div id="quiz-progress" class="quiz-stat-text">Soal 1/<?php echo count($questions); ?></div>
            </div>
            <div class="quiz-stat-box">
                <div id="quiz-score" class="quiz-stat-text">Skor: 0</div>
            </div>
        </div>

        <div class="quiz-question-container">
            <h2 id="quiz-question" style="min-height:60px;"></h2>
        </div>
        
        <div id="quiz-options"></div>
        
        <div class="quiz-footer">
            <button id="next-btn" class="btn red" disabled>Berikutnya</button>
        </div>
    </div>

    <div id="leaderboard-section" class="leaderboard-container">
        <div class="leaderboard-header">
            <h3>🏆 HALL OF FAME 🏆</h3>
        </div>
        <table class="lb-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Pembalap (User)</th>
                    <th>Skor</th>
                    <th>Waktu</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($leaderboard)): ?>
                    <tr><td colspan="5">Kamu yang pertama nih!</td></tr>
                <?php else: ?>
                    <?php foreach($leaderboard as $idx => $row): ?>
                        <tr class="lb-row">
                            <td>
                                <span class="<?php echo ($idx < 3) ? 'lb-rank-'.($idx+1) : ''; ?>">
                                    <?php echo $idx + 1; ?>
                                </span>
                            </td>
                            <td style="font-weight:600;"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td style="color:var(--primary-red); font-weight:bold;">
                                <?php echo $row['score']; ?> / <?php echo $row['total_questions']; ?>
                            </td>
                            <td><?php echo $row['duration_seconds']; ?>s</td>
                            <td style="font-size:13px; color:#ffff; font-weight:600;">
                                <?php echo date('d M', strtotime($row['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    const quizQuestions = <?php echo json_encode($questions); ?>;
</script>

<?php include 'footer.php'; ?>