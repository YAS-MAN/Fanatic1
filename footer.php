<?php
// --- LOGIKA DETEKSI ADMIN & PATH ---
// Cek apakah script yang berjalan ada di dalam folder '/admin/'
$isAdminPage = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;

// Tentukan prefix path (Arah jalan)
$root = $isAdminPage ? '../' : '';
?>

<footer class="site-footer">
    <div class="footer-content">
        
        <div class="footer-section">
            <h3>
                <a href="<?php echo $isAdminPage ? 'index.php' : $root.'index.php'; ?>" style="text-decoration:none; color:white;">
                    <span class="red-f">F</span>anatic <?php echo $isAdminPage ? 'Admin' : 'F1'; ?>
                </a>
            </h3>
            <p>
                <?php echo $isAdminPage 
                    ? 'Panel kontrol untuk manajemen konten dan pesanan F1 Fanatic.' 
                    : 'Platform khusus Formula 1 terlengkap. Dibuat dengan ❤︎ untuk penggemar F1.'; ?>
            </p>
            <div class="social-links">
                <a href="#" class="social-link">🅾</a>
                <a href="#" class="social-link">𝕏</a>
                <a href="#" class="social-link">▶</a>
            </div>
        </div>
        
        <div class="footer-section">
            <h3><?php echo $isAdminPage ? 'Admin Menu' : 'Quick Links'; ?></h3>
            <ul class="footer-links">
                <?php if ($isAdminPage): ?>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="cars.php">Kelola Mobil</a></li>
                    <li><a href="products.php">Kelola Produk</a></li>
                    <li><a href="orders.php">Kelola Pesanan</a></li>
                <?php else: ?>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="classification.php">Classification</a></li>
                    <li><a href="comparison.php">Comparison</a></li>
                    <li><a href="history.php">Riwayat Pesanan</a></li>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3><?php echo $isAdminPage ? 'Tools' : 'Services'; ?></h3>
            <ul class="footer-links">
                <?php if ($isAdminPage): ?>
                    <li><a href="messages.php">Cek Pesan User</a></li>
                    <li><a href="quizzes.php">Kelola Kuis</a></li>
                    <li><a href="<?php echo $root; ?>home.php" target="_blank">Lihat Website Utama ↗</a></li>
                    
                    <li><a href="../logout.php" style="color:#ff4d4d;" onclick="return confirm('Akhiri sesi Admin?');">Logout</a></li>
                    
                <?php else: ?>
                    <li><a href="messages.php">Pesan & Request</a></li>
                    <li><a href="store.php">Store & Merch</a></li>
                    <li><a href="quiz.php">Quiz F1</a></li>
                    <li><a href="wishlist.php">Wishlist Saya</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="footer-section contact-section">
            <h3>Contact Us</h3>
            <p>Email: contact@fanaticf1.com</p>
            <p>Phone: +62 897 0575 001</p>
            <div class="payment-methods">
                <span class="badge">✉︎</span>
                <span class="badge">☏</span>
            </div>
        </div>
    </div>
      
    <div class="footer-bottom">
        <p>&copy; 2025 Fanatic F1. All Rights Reserved.</p>
    </div>
</footer>
    
</body>
</html>