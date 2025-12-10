<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Comparison';
$currentPage = 'comparison';

// --- Ambil data mobil dari DB ---
require_once __DIR__ . '/db.php';
$pdo = db();
$stmt = $pdo->query('SELECT * FROM cars ORDER BY team ASC, year DESC');
$carsFromDB = $stmt->fetchAll();

// Konversi data dari DB ke format JSON untuk diakses JS
$carsJson = json_encode($carsFromDB);

include 'header.php';
?>

    <div class="page-banner">
      <h1>Comparison</h1>
    </div>

    <div class="compare-select-grid">
      <div class="card select-card">
        <div class="select-card-title">Mobil 1</div>
        <div class="select-box"><select id="car1"></select></div>
      </div>
      <div class="card select-card">
        <div class="select-card-title">Mobil 2</div>
        <div class="select-box"><select id="car2"></select></div>
      </div>
    </div>

    <div id="comparison-result"></div>

<script>
        // Timpa array 'cars' global di script.js dengan data dari DB
        const cars = <?php echo $carsJson; ?>;

        // Penyesuaian nama kolom agar script.js mengenali (images -> imageList, image_detail -> imageDetail)
        cars.forEach(car => {
            car.imageList = car.images;
            car.imageDetail = car.image_detail;
            
            // PENTING: Jika script.js mengharapkan nama kolom dengan underscore dihilangkan:
            car.topSpeed = car.top_speed; // Jika kolom di DB adalah top_speed
            car.mainDrivers = car.main_drivers; // Jika kolom di DB adalah main_drivers
            // Lakukan ini untuk semua kolom yang digunakan di tabel perbandingan jika nama JS lama berbeda dari DB
        });
    </script>
<?php include 'footer.php'; ?>
