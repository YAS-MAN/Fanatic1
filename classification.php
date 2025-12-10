<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Classification';
$currentPage = 'classification';

// --- Ambil data mobil dari DB ---
require_once __DIR__ . '/db.php';
$pdo = db();
$stmt = $pdo->query('SELECT * FROM cars ORDER BY team ASC, year DESC');
$carsFromDB = $stmt->fetchAll();

// Konversi data dari DB ke format yang mirip dengan JS (untuk sementara)
// Ini diperlukan agar script.js Anda tidak perlu diubah total
$carsJson = json_encode($carsFromDB);

include 'header.php';
?>

    <div class="page-banner">
      <h1>Classification</h1>
    </div>

    <div class="filter-buttons">
      <button class="filter-btn outline" data-team="all">All Teams</button>
      </div>

    <div id="car-list" class="card-container"></div>

    <script>
        // Timpa array 'cars' global di script.js dengan data dari DB
        const cars = <?php echo $carsJson; ?>;

        // Beberapa kolom yang namanya di DB berbeda dari JS lama perlu disesuaikan.
        // Kita ubah nama kolom di cars (dari PHP) agar sesuai dengan yang diharapkan script.js
        cars.forEach(car => {
            car.imageList = car.images; // DB: images -> JS: imageList
            car.imageDetail = car.image_detail; // DB: image_detail -> JS: imageDetail
            // Kolom lain sudah cukup mirip (misal: power, topSpeed, raceWins, dll)
        });
    </script>
<?php include 'footer.php'; ?>
