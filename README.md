# 🏎️ F1 Fanatic - The Ultimate Formula 1 Ecosystem

<div align="center">

![PHP](https://img.shields.io/badge/Backend-PHP%20Native%208.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/Database-MySQL%208.0-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Frontend](https://img.shields.io/badge/Frontend-HTML5%20%26%20CSS3-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![Status](https://img.shields.io/badge/Status-Completed-success?style=for-the-badge)

**Platform All-in-One untuk Pecinta Formula 1: Berita, E-Commerce, Gamifikasi, dan Komunitas.**
*Dibangun dengan arsitektur PHP Native yang kuat, aman, dan scalable.*

[🚀 Lihat Demo Live](https://datolitic-uncontributive-piper.ngrok-free.dev/Fanatic1) · [🐛 Laporkan Bug](../../issues) · [✨ Request Fitur](../../issues)

</div>

---

---

## 📖 Tentang Proyek

**F1 Fanatic** bukan sekadar website informasi. Ini adalah ekosistem digital lengkap yang menggabungkan sensasi balapan dengan pengalaman belanja online dan interaksi sosial.

Proyek ini dikembangkan untuk menyelesaikan masalah fragmentasi informasi F1, di mana fans biasanya harus membuka banyak tab untuk melihat klasemen, membeli merchandise, dan berdiskusi. F1 Fanatic menyatukan semuanya dalam satu antarmuka **Dark Mode** yang *sporty*, responsif, dan elegan.

### ✨ Mengapa F1 Fanatic Spesial?
* **Data Intelligence:** Sistem otomatis yang mendeteksi ketersediaan API. Jika API F1 sedang down, sistem secara cerdas beralih ke *Fallback Data* lokal tanpa merusak tampilan user (Zero Downtime).
* **E-Commerce Logic:** Keranjang belanja berbasis Database (bukan Session sementara), sehingga barang user tetap tersimpan meski mereka logout atau berganti perangkat.
* **Data Integrity:** Menggunakan mekanisme *Soft Delete* pada manajemen produk untuk memastikan riwayat transaksi masa lalu tidak rusak meskipun barang sudah tidak dijual.

---

## 🔥 Fitur Unggulan

### 1. 🏁 Core User Experience
| Fitur | Deskripsi Teknis |
| :--- | :--- |
| **Live Standings** | Integrasi data klasemen pembalap & konstruktor real-time. Dilengkapi foto dinamis yang menyesuaikan nama pembalap. |
| **Car Comparison** | Fitur komparasi *side-by-side* untuk membandingkan spesifikasi teknis mobil (Mesin, Power, Berat) antar tim. |
| **F1 Quiz Arena** | Gamifikasi interaktif dengan timer. Sistem leaderboard cerdas yang hanya mencatat **High Score unik** per user (algoritma filter duplikasi). |
| **Global Forum** | Ruang diskusi real-time (Chat Style) dengan sistem *role-badge* (Admin/User) dan validasi kepemilikan pesan untuk fitur Edit. |

### 2. 🛒 Advanced Store & Checkout
* **Smart Cart System:** CRUD Keranjang belanja yang terikat dengan User ID di database.
* **Dynamic Transaction ID:** Menghasilkan kode referensi unik dan informatif saat checkout.
    * *Format:* `[KODE TIM]-[YYMMDD]-[JAM]-[USER_ID]`
    * *Contoh:* `MCL-251205-1430-001` (Membeli item McLaren, tgl 5 Des 25, User ID 1).
* **Payment Simulation:** Integrasi UI QRIS statis dengan total tagihan dinamis.
* **Order History:** Pelacakan status pesanan (*Pending, Paid, Shipped, Cancelled*) dengan indikator warna visual (`status-pill`).

### 3. 🛡️ Administrator Dashboard (Back-Office)
* **Secure Authentication:** Login Admin terpisah dengan validasi sesi ketat.
* **Full CRUD Management:**
    * **Cars:** Tambah/Edit/Hapus data mobil balap.
    * **Quiz:** Manajemen bank soal kuis.
    * **Products:** Manajemen inventaris toko dengan fitur *Image Upload* dan *Soft Delete*.
* **Order Processing:** Admin dapat memverifikasi pembayaran dan mengubah status pengiriman yang langsung tersinkronisasi ke sisi User.
* **Content Moderation:** Kemampuan untuk memantau dan menghapus pesan di forum diskusi global.

---

## 📸 Galeri Aplikasi

<details>
<summary><b>Klik untuk melihat Tampilan User (Frontend)</b></summary>

| Sign in & Sign Up | Homepage & Standings | Classification Car | Detail Car | Car Comparison | 
|:---:|:---:|:---:|:---:|:---:|
| <img src="/screenshots/.login1.png" width="100%" alt="Login1"> <img src="/screenshots/.login2.png" width="100%" alt="Login2"> | <img src="/screenshots/.home1.png" width="100%" alt="home1"> <img src="/screenshots/.home2.png" width="100%" alt="home2"> | <img src="/screenshots/.classification1.png" width="100%" alt="classification1"> <img src="/screenshots/.classification2.png" width="100%" alt="classification2"> | <img src="/screenshots/.detail1.png" width="100%" alt="detail1"> <img src="/screenshots/.detail2.png" width="100%" alt="detail2"> | <img src="/screenshots/.comparison1.png" width="100%" alt="comparison1"> <img src="/screenshots/.comparison2.png" width="100%" alt="comparison2"> |

|Quiz F1 | F1 Store & Filter | Cart | Checkout | Order | Message |
|:---:|:---:|:---:|:---:|:---:|:---:|
| <img src="/screenshots/.quiz1.png" width="100%" alt="quiz1"> <img src="/screenshots/.quiz2.png" width="100%" alt="quiz2"> | <img src="/screenshots/.store.png" width="100%" alt="store"> | <img src="/screenshots/.cart.png" width="100%" alt="cart"> | <img src="/screenshots/.chechkout.png" width="100%" alt="checkout"> | <img src="/screenshots/.pesanan.png" width="100%" alt="pesanan"> | <img src="/screenshots/.pesan1.png" width="100%" alt="pesan1"> <img src="/screenshots/.pesan2.png" width="100%" alt="pesan2"> |

</details>

<details>
<summary><b>Klik untuk melihat Tampilan Admin (Backend)</b></summary>

| Admin Dashboard | Manajemen Kendaraan | Manajemen Produk |
|:---:|:---:|:---:|
| <img src="/screenshots/.admdashboard.png" width="100%" alt="dashboard"> | <img src="/screenshots/.editcar.png" width="100%" alt="car"> | <img src="/screenshots/.editproduct.png" width="100%" alt="product"> |

| Manajemen Quiz | Pesan Pengguna | Riwayat Order |
|:---:|:---:|:---:|
| <img src="/screenshots/.editquiz.png" width="100%" alt="quiz"> | <img src="/screenshots/.managepesan.png" width="100%" alt="pesan"> | <img src="/screenshots/.managerorder.png" width="100%" alt="pesanan">

</details>

---

## 🛠️ Arsitektur & Teknologi

Proyek ini dibangun dengan prinsip **Clean Code** dan **Security First**.

* **Bahasa:** PHP 8.0+ (Native)
* **Database:** MySQL / MariaDB (Relational)
* **Keamanan Database:** Menggunakan **PDO Prepared Statements** secara menyeluruh untuk mencegah serangan *SQL Injection*.
* **Password Hashing:** Menggunakan `password_hash()` (Bcrypt) untuk keamanan data pengguna.
* **Frontend:**
    * CSS3 Variables (Root colors) untuk konsistensi tema.
    * CSS Grid & Flexbox untuk layout responsif.
    * JavaScript (Vanilla) untuk interaksi DOM dan AJAX-like experience.
* **Assets:** Penanganan upload gambar produk secara dinamis ke server.

---

## 💻 Instalasi & Menjalankan

Ikuti langkah mudah ini untuk menjalankan F1 Fanatic di komputer lokal Anda (Localhost) menggunakan **Laragon**:

### Prasyarat
* Web Server (Laragon direkomendasikan).
* PHP versi 7.4 atau lebih baru.
* MySQL Database.

### Langkah-langkah

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/username-anda/f1-fanatic.git](https://github.com/username-anda/f1-fanatic.git)
    ```

2.  **Setup Database**
    * Buka **HeidiSQL** (bawaan Laragon) atau phpMyAdmin.
    * Buat database baru dengan nama `f1fanatic`.
    * Import file **`f1fanatic_db.sql`** yang sudah tersedia di dalam folder root proyek ini.

3.  **Konfigurasi Koneksi**
    * Buka file `db.php` di text editor.
    * Pastikan konfigurasi sesuai dengan default Laragon:
        ```php
        $host = 'localhost';
        $db   = 'f1fanatic';
        $user = 'root';      // Default Laragon
        $pass = '';          // Default Laragon (Kosong)
        ```

4.  **Jalankan!**
    * Pastikan folder proyek bernama `Fanatic1` (atau sesuaikan link di browser).
    * Klik tombol **Start All** di Laragon.
    * Akses melalui browser: `http://localhost/Fanatic1/`

---

## 📂 Struktur Direktori

## 📂 Struktur Direktori

Berikut adalah struktur file proyek **F1 Fanatic** berdasarkan *deployment* saat ini:

```text
FANATIC1/
├── admin/                  # Panel Kendali Admin (Back-End)
│   ├── auth_admin.php      # Autentikasi khusus admin
│   ├── cars.php            # CRUD Mobil F1
│   ├── products.php        # CRUD Produk Toko
│   ├── orders.php          # Manajemen Pesanan
│   ├── messages.php        # Moderasi Pesan
│   └── quizzes.php         # Manajemen Soal Kuis
├── assets/                 # Folder Media (Gambar Mobil, Produk, & Video)
├── auth.php                # Middleware Cek Login User
├── db.php                  # Koneksi Database (PDO)
├── f1fanatic_db.sql        # ⚠️ FILE DATABASE (Import file ini ke MySQL)
├── style.css               # Styling Utama (Dark Theme)
├── script.js               # Logika Frontend (JS)
├── index.php               # Halaman Login & Register
├── home.php                # Halaman Utama (Klasemen & Jadwal)
├── store.php               # Katalog Belanja (Toko)
├── keranjang.php           # Logika Keranjang Belanja
├── checkout.php            # Halaman Pembayaran & QRIS
├── history.php             # Riwayat Transaksi User
├── classification.php      # Menu Klasifikasi Tim
├── comparison.php          # Fitur Komparasi Mobil
├── detail.php              # Detail Spesifikasi Mobil
├── quiz.php                # Halaman Kuis Interaktif
└── messages.php            # Forum Diskusi Global
