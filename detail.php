<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Detail Mobil F1';
$currentPage = 'classification'; // Karena detail termasuk dalam kategori classification

// --- Ambil data mobil dari DB ---
$carName = $_GET['name'] ?? '';
$carFromDB = null;

if ($carName) {
    require_once __DIR__ . '/db.php';
    $pdo = db();
    // Gunakan parameter query untuk keamanan
    $stmt = $pdo->prepare('SELECT * FROM cars WHERE name = ? LIMIT 1');
    $stmt->execute([$carName]);
    $carFromDB = $stmt->fetch();
}

// Data mobil yang ditemukan (atau array kosong jika tidak ditemukan)
$carJson = json_encode($carFromDB ?: (object)[]);

include 'header.php';
?>

    <a href="classification.php" class="btn grey detail-back">← Kembali</a>

    <div id="car-detail"></div>
    
    <script>
        // Timpa array 'cars' global di script.js dengan data dari DB
        // Karena detail hanya butuh 1 mobil, kita bungkus dalam array agar konsisten dengan logika script.js
        const carDetailData = <?php echo $carJson; ?>;

        // Kita modifikasi array 'cars' di script.js secara sementara
        // agar bagian DETAIL PAGE di script.js tetap berfungsi
        if (carDetailData && Object.keys(carDetailData).length > 0) {
            // Sesuaikan nama kolom agar sesuai dengan JS lama
            carDetailData.imageList = carDetailData.images;
            carDetailData.imageDetail = carDetailData.image_detail;
            carDetailData.driver1Image = carDetailData.driver1_image;
            carDetailData.driver2Image = carDetailData.driver2_image;
            
            // Set array cars global yang baru, hanya berisi 1 mobil ini
            // Ini akan memastikan bagian detail page di script.js menemukan data yang benar
            window.cars = [carDetailData];
            
            // Ubah parameter URL agar script.js dapat menemukannya (jika diperlukan)
            const url = new URL(window.location.href);
            url.searchParams.set("name", carDetailData.name);
            window.history.replaceState({}, '', url.toString());
        } else {
             window.cars = []; // Kosongkan jika tidak ada data
        }
    </script>

<?php include 'footer.php'; ?>