<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
if (!isset($currentPage)) $currentPage = '';

// --- Base Path Correction (PENTING untuk semua link aset) ---
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Jika diakses dari subfolder admin, path akan menjadi /fanatic1/admin. Kita ingin /fanatic1.
if (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) {
    $basePath = rtrim(str_replace('/admin', '', $basePath), '/');
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Fanatic F1' : 'Fanatic F1'; ?></title>
    
    <link rel="stylesheet" href="<?php echo $basePath; ?>/style.css" />
    <script src="<?php echo $basePath; ?>/script.js" defer></script>
  </head>
  <body>
    <nav>
      <a href="<?php echo $basePath; ?>/home.php" class="logo">
        <span class="logo-text"><span class="red-f">F</span>anatic</span>
        <span class="logo-f1">1</span>
      </a>
      <ul>
        <?php if (is_logged_in()): ?>
          <li><a href="<?php echo $basePath; ?>/home.php" class="<?php echo ($currentPage === 'home') ? 'active' : ''; ?>">Home</a></li>
          <li><a href="<?php echo $basePath; ?>/classification.php" class="<?php echo ($currentPage === 'classification') ? 'active' : ''; ?>">Classification</a></li>
          <li><a href="<?php echo $basePath; ?>/comparison.php" class="<?php echo ($currentPage === 'comparison') ? 'active' : ''; ?>">Comparison</a></li>
          <li><a href="<?php echo $basePath; ?>/quiz.php" class="<?php echo ($currentPage === 'quiz') ? 'active' : ''; ?>">Quiz</a></li>
          <li><a href="<?php echo $basePath; ?>/store.php" class="<?php echo ($currentPage === 'store') ? 'active' : ''; ?>">Store</a></li>
          <li><a href="<?php echo $basePath; ?>/history.php" class="<?php echo ($currentPage === 'history') ? 'active' : ''; ?>">Pesanan</a></li>
          <li><a href="<?php echo $basePath; ?>/messages.php" class="<?php echo ($currentPage === 'messages') ? 'active' : ''; ?>">Messages</a></li>
          
          <li><a href="<?php echo $basePath; ?>/logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">Logout</a></li>
        
        <?php else: ?>
          <li><a href="<?php echo $basePath; ?>/index.php" class="active">Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>
    
    <?php
    // Inject JS Filter jika ada parameter team
    if (isset($_GET['team'])) {
      $teamParamJs = json_encode($_GET['team']);
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          const teamParam = " . $teamParamJs . ";
          if (teamParam && document.querySelector('.filter-buttons')) {
            const teamMapping = {
              'red-bull': 'Red Bull Racing',
              'ferrari': 'Scuderia Ferrari',
              'mercedes': 'Mercedes-AMG Petronas',
              'mclaren': 'McLaren',
              'aston-martin': 'Aston Martin'
            };
            const teamName = teamMapping[teamParam];
            if (teamName) {
              setTimeout(() => {
                const selector = \"[data-team='\" + teamName + \"']\";
                const teamButton = document.querySelector(selector);
                if (teamButton) { teamButton.click(); }
              }, 100);
            }
          }
        });
      </script>";
    }
    ?>