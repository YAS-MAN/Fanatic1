-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for f1fanatic
CREATE DATABASE IF NOT EXISTS `f1fanatic` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `f1fanatic`;

-- Dumping structure for table f1fanatic.cars
CREATE TABLE IF NOT EXISTS `cars` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `team` varchar(100) NOT NULL,
  `engine` varchar(100) DEFAULT NULL,
  `year` year NOT NULL,
  `images` text,
  `image_detail` text,
  `power` varchar(50) DEFAULT NULL,
  `top_speed` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `chassis` text,
  `wheelbase` varchar(50) DEFAULT NULL,
  `main_drivers` text,
  `driver1` varchar(100) DEFAULT NULL,
  `driver2` varchar(100) DEFAULT NULL,
  `driver1_image` text,
  `driver2_image` text,
  `championships` int DEFAULT NULL,
  `acceleration` varchar(50) DEFAULT NULL,
  `aerodynamics` text,
  `suspension` text,
  `brakes` text,
  `transmission` varchar(50) DEFAULT NULL,
  `fuel_capacity` varchar(50) DEFAULT NULL,
  `tire_supplier` varchar(50) DEFAULT NULL,
  `race_wins` int DEFAULT NULL,
  `podiums` int DEFAULT NULL,
  `points` int DEFAULT NULL,
  `capacity` varchar(50) DEFAULT NULL,
  `rpm` varchar(50) DEFAULT NULL,
  `valves` int DEFAULT NULL,
  `track_width` varchar(50) DEFAULT NULL,
  `length` varchar(50) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_year_unique` (`team`,`year`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.cars: ~15 rows (approximately)
INSERT INTO `cars` (`id`, `name`, `team`, `engine`, `year`, `images`, `image_detail`, `power`, `top_speed`, `weight`, `chassis`, `wheelbase`, `main_drivers`, `driver1`, `driver2`, `driver1_image`, `driver2_image`, `championships`, `acceleration`, `aerodynamics`, `suspension`, `brakes`, `transmission`, `fuel_capacity`, `tire_supplier`, `race_wins`, `podiums`, `points`, `capacity`, `rpm`, `valves`, `track_width`, `length`, `width`, `height`, `description`) VALUES
	(1, 'Red Bull RB20', 'Red Bull Racing', 'Honda RBPT', '2025', 'assets/car/RB20 1.webp', 'assets/car/RB20 2.jpg', '995hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3610 mm', 'Max Verstappen NL , Yuki Tsunoda JP', 'Max Verstappen', 'Yuki Tsunoda', 'assets/driver/RB Driver 1 Max Verstappen.jpg', 'assets/driver/RB Driver 2 Yuki Tsunoda.jpg', 8, '0-100 km/h dalam 2.5 detik', 'Advanced downforce, enhanced DRS', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads with improved cooling', '8-speed semi-automatic with quick shift', '110 kg', 'Pirelli', 4, 9, 290, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '950 mm', 'Terkenal dengan strategi agresif, aerodinamika mutakhir, dan kecemerlangan Max Verstappen, Red Bull mendefinisikan ulang dominasi di era baru Formula 1.'),
	(2, 'Red Bull RB19', 'Red Bull Racing', 'Honda RBPT', '2024', 'assets/car/RB19 1.jpg', 'assets/car/RB19 2.webp', '1040-1080hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Max Verstappen NL , Yuki Tsunoda JP', 'Max Verstappen', 'Yuki Tsunoda', 'assets/driver/RB Driver 1 Max Verstappen.jpg', 'assets/driver/RB Driver 2 Yuki Tsunoda.jpg', 8, '0-100 km/h dalam 2.5 detik', 'High', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 21, 30, 589, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '950 mm', 'Terkenal dengan strategi agresif, aerodinamika mutakhir, dan kecemerlangan Max Verstappen, Red Bull mendefinisikan ulang dominasi di era baru Formula 1.'),
	(3, 'Red Bull RB18', 'Red Bull Racing', 'Honda RBPT', '2023', 'assets/car/RB18 1.jpg', 'assets/car/RB18 2.jpg', '950~hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Max Verstappen NL , Yuki Tsunoda JP', 'Max Verstappen', 'Yuki Tsunoda', 'assets/driver/RB Driver 1 Max Verstappen.jpg', 'assets/driver/RB Driver 2 Yuki Tsunoda.jpg', 8, '0-100 km/h dalam 2.6 detik', 'High', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs dan pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 17, 28, 860, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Terkenal dengan strategi agresif, aerodinamika mutakhir, dan kecemerlangan Max Verstappen, Red Bull mendefinisikan ulang dominasi di era baru Formula 1.'),
	(4, 'Ferrari SF-25', 'Scuderia Ferrari', 'Ferrari', '2025', 'assets/car/SF25 1.jpg', 'assets/car/SF25 2.jpg', '1000+hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Charles Lecrec MC , Lewis Hamilton UK', 'Charles LecLerc', 'Lewis Hamilton', 'assets/driver/SF Driver 1 Charles LecLerc.jpg', 'assets/driver/SF Driver 2 Lewis Hamilton.jpg', 16, '0-100 km/h dalam 2.6 detik', 'Medium-High', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 0, 5, 298, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Tim tertua dan paling ikonis di Formula 1, Ferrari mewujudkan hasrat, tradisi, dan pengejaran kecepatan tanpa henti, mewakili semangat balap Italia.'),
	(5, 'Ferrari SF-24', 'Scuderia Ferrari', 'Ferrari', '2024', 'assets/car/SF24 1.jpg', 'assets/car/SF24 2.png', '1080hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Charles Lecrec MC , Lewis Hamilton UK', 'Charles LecLerc', 'Lewis Hamilton', 'assets/driver/SF Driver 1 Charles LecLerc.jpg', 'assets/driver/SF Driver 2 Lewis Hamilton.jpg', 16, '0-100 km/h dalam 2.65 detik', 'Medium-High', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 5, 22, 652, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Tim tertua dan paling ikonis di Formula 1, Ferrari mewujudkan hasrat, tradisi, dan pengejaran kecepatan tanpa henti, mewakili semangat balap Italia.'),
	(6, 'Ferrari SF-23', 'Scuderia Ferrari', 'Ferrari', '2023', 'assets/car/SF23 1.jpg', 'assets/car/SF23 2.jpg', '1010hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Charles Lecrec MC , Lewis Hamilton UK', 'Charles LecLerc', 'Lewis Hamilton', 'assets/driver/SF Driver 1 Charles LecLerc.jpg', 'assets/driver/SF Driver 2 Lewis Hamilton.jpg', 16, '0-100 km/h dalam 2.68 detik', 'Medium', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 1, 9, 406, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Tim tertua dan paling ikonis di Formula 1, Ferrari mewujudkan hasrat, tradisi, dan pengejaran kecepatan tanpa henti, mewakili semangat balap Italia.'),
	(7, 'Mercedes W16', 'Mercedes-AMG Petronas', 'Mercedes', '2025', 'assets/car/W-16 1.webp', 'assets/car/W-16 2.jpg', '1000+hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'George Russel UK , Kimi Antonelli IT', 'George Russell', 'Andrea Kimi Antonelli', 'assets/driver/W Driver 1 George Russell.jpg', 'assets/driver/W Driver 2 Andrea Kimi Antonelli.jpg', 8, '0-100 km/h dalam 2.6 detik', 'Medium-High', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 2, 9, 325, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Kekuatan dominan di F1 modern, Mercedes dikenal karena keunggulan rekayasa, strategi presisi, dan rangkaian sukses era hibrida yang dipimpin oleh Lewis Hamilton dan Toto Wolff.'),
	(8, 'Mercedes W15', 'Mercedes-AMG Petronas', 'Mercedes', '2024', 'assets/car/W-15 1.jpg', 'assets/car/W-15 2.jpg', '1080hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'George Russel UK , Kimi Antonelli IT', 'George Russell', 'Andrea Kimi Antonelli', 'assets/driver/W Driver 1 George Russell.jpg', 'assets/driver/W Driver 2 Andrea Kimi Antonelli.jpg', 8, '0-100 km/h dalam 2.65 detik', 'Medium', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 4, 9, 468, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Kekuatan dominan di F1 modern, Mercedes dikenal karena keunggulan rekayasa, strategi presisi, dan rangkaian sukses era hibrida yang dipimpin oleh Lewis Hamilton dan Toto Wolff.'),
	(9, 'Mercedes W14', 'Mercedes-AMG Petronas', 'Mercedes', '2023', 'assets/car/W-14 1.jpg', 'assets/car/W-14 2.jpg', '1000hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'George Russel UK , Kimi Antonelli IT', 'George Russell', 'Andrea Kimi Antonelli', 'assets/driver/W Driver 1 George Russell.jpg', 'assets/driver/W Driver 2 Andrea Kimi Antonelli.jpg', 8, '0-100 km/h dalam 2.68 detik', 'Medium', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 0, 8, 409, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Kekuatan dominan di F1 modern, Mercedes dikenal karena keunggulan rekayasa, strategi presisi, dan rangkaian sukses era hibrida yang dipimpin oleh Lewis Hamilton dan Toto Wolff.'),
	(10, 'McLaren MCL39', 'McLaren', 'Mercedes', '2025', 'assets/car/MCL39 1.jpg', 'assets/car/MCL39 2.webp', '1000hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Lando Norris UK , Oscar Piastri AU', 'Lando Norris', 'Oscar Piastri', 'assets/driver/MCL Driver 1 Lando Norris.jpg', 'assets/driver/MCL Driver 2 Oscar Piastri.jpg', 8, '0-100 km/h dalam 2.6 detik', 'Medium-High', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 12, 28, 650, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Salah satu tim paling bersejarah di F1, McLaren menggabungkan inovasi dan warisan balap dengan fokus pada talenta muda serta desain berperforma tinggi yang berakar pada tradisi Inggris.'),
	(11, 'McLaren MCL38', 'McLaren', 'Mercedes', '2024', 'assets/car/MCL38 1.jpg', 'assets/car/MCL38 2.jpg', '1080hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Lando Norris UK , Oscar Piastri AU', 'Lando Norris', 'Oscar Piastri', 'assets/driver/MCL Driver 1 Lando Norris.jpg', 'assets/driver/MCL Driver 2 Oscar Piastri.jpg', 8, '0-100 km/h dalam 2.65 detik', 'Medium', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 4, 14, 666, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Salah satu tim paling bersejarah di F1, McLaren menggabungkan inovasi dan warisan balap dengan fokus pada talenta muda serta desain berperforma tinggi yang berakar pada tradisi Inggris.'),
	(12, 'McLaren MCL60', 'McLaren', 'Mercedes', '2023', 'assets/car/MCL60 1.jpg', 'assets/car/MCL60 2.webp', '1080hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Lando Norris UK , Oscar Piastri AU', 'Lando Norris', 'Oscar Piastri', 'assets/driver/MCL Driver 1 Lando Norris.jpg', 'assets/driver/MCL Driver 2 Oscar Piastri.jpg', 8, '0-100 km/h dalam 2.68 detik', 'Medium', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 1, 9, 302, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Salah satu tim paling bersejarah di F1, McLaren menggabungkan inovasi dan warisan balap dengan fokus pada talenta muda serta desain berperforma tinggi yang berakar pada tradisi Inggris.'),
	(13, 'Aston Martin AMR25', 'Aston Martin', 'Mercedes', '2025', 'assets/car/AMR25 1.webp', 'assets/car/AMR25 2.jpg', '1000hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Fernando Alonso ES , Lance Stroll CA', 'Fernando Alonso', 'Lance Stroll', 'assets/driver/AMR Driver 1 Fernando Alonso.jpg', 'assets/driver/AMR Driver 2 Lance Stroll.jpg', 0, '0-100 km/h dalam 2.6 detik', 'Medium-High', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 0, 0, 64, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Penantang bergaya yang berkembang pesat, memadukan identitas merek mewah dengan ambisi kompetitif, dipimpin oleh pengalaman Fernando Alonso dan visi untuk kemenangan di masa depan.'),
	(14, 'Aston Martin AMR24', 'Aston Martin', 'Mercedes', '2024', 'assets/car/AMR24 1.jpg', 'assets/car/AMR24 2.jpg', '995+hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Fernando Alonso ES , Lance Stroll CA', 'Fernando Alonso', 'Lance Stroll', 'assets/driver/AMR Driver 1 Fernando Alonso.jpg', 'assets/driver/AMR Driver 2 Lance Stroll.jpg', 0, '0-100 km/h dalam 2.65 detik', 'Medium', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 1, 6, 94, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Penantang bergaya yang berkembang pesat, memadukan identitas merek mewah dengan ambisi kompetitif, dipimpin oleh pengalaman Fernando Alonso dan visi untuk kemenangan di masa depan.'),
	(15, 'Aston Martin AMR23', 'Aston Martin', 'Mercedes', '2023', 'assets/car/AMR23 1.jpg', 'assets/car/AMR23 2.jpg', '1080hp', '378 km/h', '798 kg', 'Monocoque carbon fibre composite dengan struktur honeycomb.', '3600 mm', 'Fernando Alonso ES , Lance Stroll CA', 'Fernando Alonso', 'Lance Stroll', 'assets/driver/AMR Driver 1 Fernando Alonso.jpg', 'assets/driver/AMR Driver 2 Lance Stroll.jpg', 0, '0-100 km/h dalam 2.68 detik', 'Medium', 'Double wishbone dengan push-rod yang mengaktifkan inboard springs dan torsion bars; Double wishbone dengan pull-rod layout, disesuaikan untuk efisiensi aliran udara dari diffuser', 'Carbon-carbon discs and pads', '8-speed semi-automatic', '110 kg', 'Pirelli', 0, 8, 280, '16000cc', '15000RPM', 24, '2000 mm', '5600 mm', '2000 mm', '960 mm', 'Penantang bergaya yang berkembang pesat, memadukan identitas merek mewah dengan ambisi kompetitif, dipimpin oleh pengalaman Fernando Alonso dan visi untuk kemenangan di masa depan.');

-- Dumping structure for table f1fanatic.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `user_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.cart: ~1 rows (approximately)
INSERT INTO `cart` (`user_id`, `product_id`, `qty`) VALUES
	(6, 1, 1);

-- Dumping structure for table f1fanatic.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.messages: ~13 rows (approximately)
INSERT INTO `messages` (`id`, `user_id`, `name`, `content`, `created_at`) VALUES
	(1, 1, 'Nisa', 'Admin ganteng deh', '2025-12-03 10:33:04'),
	(2, 1, 'user', 'Admin ganteng deh', '2025-12-03 10:39:15'),
	(3, 1, 'user', 'makan makan yu bang', '2025-12-03 10:42:49'),
	(5, 1, 'user', 'makan makan yu bang', '2025-12-03 10:53:06'),
	(8, 1, 'user', 'meow meow nigga', '2025-12-03 11:14:52'),
	(9, 1, 'user', 'verstapen better', '2025-12-05 03:52:49'),
	(10, 1, 'user', 'mau tidurr', '2025-12-05 07:44:45'),
	(11, 5, 'user2', 'nah kan kalo user 2 yang buka kosong replies nya', '2025-12-05 08:47:16'),
	(12, 5, 'user2', 'sekalinya muncul riwayat sebelum sebelumnya ga muncul database nya salah nih', '2025-12-05 08:47:44'),
	(13, 1, 'user', 'p', '2025-12-05 11:24:08'),
	(14, 4, 'Axl Rafael', 'test123', '2025-12-05 11:24:42'),
	(16, 7, 'vincent rompies', 'wow keren sekali', '2025-12-06 15:28:20');

-- Dumping structure for table f1fanatic.message_replies
CREATE TABLE IF NOT EXISTS `message_replies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `admin_id` int DEFAULT NULL,
  `reply` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `parent_reply_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.message_replies: ~11 rows (approximately)
INSERT INTO `message_replies` (`id`, `message_id`, `admin_id`, `reply`, `created_at`, `parent_reply_id`) VALUES
	(1, 1, 1, 'kok kamu tau sih dia ganteng', '2025-12-03 18:21:44', NULL),
	(2, 8, 1, 'kok kamu kasar ihh', '2025-12-03 18:31:02', NULL),
	(3, 8, 3, 'tau nih kasar banget user satu ini', '2025-12-03 18:31:47', NULL),
	(4, 8, 3, 'kita blok dan banned aja ya', '2025-12-03 18:32:00', NULL),
	(5, 9, 1, 'keren', '2025-12-05 10:55:55', NULL),
	(6, 5, 3, 'oke', '2025-12-05 11:16:15', NULL),
	(7, 10, 3, 'tidur tinggal tidur, rewel amat', '2025-12-05 15:17:48', NULL),
	(8, 12, 3, 'trumin, bener banget', '2025-12-05 15:49:31', NULL),
	(9, 12, 1, 'lebay banget ah lu', '2025-12-05 15:58:56', NULL),
	(10, 13, 4, 'yeay', '2025-12-05 18:24:48', NULL),
	(11, 14, 1, 'kaga jelas axl axl ini', '2025-12-05 18:24:58', NULL),
	(12, 14, 3, 'yang spam gua blok', '2025-12-05 18:25:44', NULL),
	(13, 10, 7, 'jangan', '2025-12-06 22:28:57', NULL),
	(14, 16, 3, 'Y', '2025-12-09 21:58:45', NULL);

-- Dumping structure for table f1fanatic.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `status` enum('pending','paid','shipped','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.orders: ~5 rows (approximately)
INSERT INTO `orders` (`id`, `user_id`, `status`, `total`, `created_at`) VALUES
	(1, 1, 'shipped', 585000.00, '2025-12-04 15:07:34'),
	(2, 1, 'paid', 465000.00, '2025-12-04 17:41:23'),
	(3, 1, 'shipped', 815000.00, '2025-12-05 03:47:08'),
	(4, 1, 'pending', 465000.00, '2025-12-05 03:49:43');

-- Dumping structure for table f1fanatic.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.order_items: ~2 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
	(2, 1, 4, 1, 120000.00),
	(4, 3, 1, 1, 350000.00);

-- Dumping structure for table f1fanatic.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `team` varchar(100) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.products: ~22 rows (approximately)
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `team`, `stock`, `category`, `created_at`, `is_deleted`) VALUES
	(1, 'RB20 Team Cap', 'Topi', 350000.00, 'assets/RB20 1.webp', 'Red Bull Racing', -1, 'Merchendise', '2025-12-01 11:59:15', 1),
	(4, 'McLaren Lanyard', NULL, 120000.00, 'assets/MCL38 1.jpg', 'McLaren', 98, NULL, '2025-12-01 11:59:15', 1),
	(7, 'Aston Martin AMR23', '', 2200.00, 'assets/default_product.jpg', 'Aston Martin', 7, '', '2025-12-10 09:03:19', 1),
	(8, 'Red Bull Racing 2025 Team Full Zip Hoodie - Unisex', '', 1599000.00, 'assets/prod_1765357579.jpg', 'Red Bull Racing', 99, '', '2025-12-10 09:06:19', 0),
	(9, 'Oracle Red Bull Racing RB19 No.1 - Max Verstappen 1:43 Model', '', 1419000.00, 'assets/prod_1765357686.jpg', 'Red Bull Racing', 99, '', '2025-12-10 09:07:46', 0),
	(10, 'Red Bull Racing Heritage Polo - Unisex', '', 999000.00, 'assets/prod_1765358508.jpg', 'Red Bull Racing', 99, '', '2025-12-10 09:21:48', 0),
	(11, 'Oracle Red Bull Racing New Era 9FORTY Cap - Navy', '', 649000.00, 'assets/prod_1765358540.jpg', 'Red Bull Racing', 99, '', '2025-12-10 09:22:20', 0),
	(12, 'Scuderia Ferrari 2025 Team Hooded Sweat', '', 2299000.00, 'assets/prod_1765358834.jpg', 'Scuderia Ferrari', 99, '', '2025-12-10 09:27:14', 0),
	(13, 'Scuderia Ferrari 2025 Team Polo', '', 1619000.00, 'assets/prod_1765358917.jpg', 'Scuderia Ferrari', 99, '', '2025-12-10 09:28:37', 0),
	(14, 'Scuderia Ferrari 2025 Team Lewis Hamilton Cap - Red', '', 859000.00, 'assets/prod_1765359002.jpg', 'Scuderia Ferrari', 99, '', '2025-12-10 09:30:02', 0),
	(15, 'Scuderia Ferrari Lewis Hamilton Miami GP 2025 1:5 Helmet', '', 1299000.00, 'assets/prod_1765359051.jpg', 'Scuderia Ferrari', 99, '', '2025-12-10 09:30:51', 0),
	(16, 'Mens 2025 Team Driver Tee Black', '', 1899000.00, 'assets/prod_1765359116.jpg', 'Mercedes', 99, '', '2025-12-10 09:31:56', 0),
	(17, 'Mens DNA Puffer Jacket Black', '', 2149000.00, 'assets/prod_1765359145.jpg', 'Mercedes', 99, '', '2025-12-10 09:32:25', 0),
	(18, 'Kimi Antonelli 2025 Team Driver Cap White', '', 959000.00, 'assets/prod_1765359196.jpg', 'Mercedes', 99, '', '2025-12-10 09:33:16', 0),
	(19, '3 Pack Miami Socks Floral', '', 509000.00, 'assets/prod_1765359227.jpg', 'Mercedes', 99, '', '2025-12-10 09:33:47', 0),
	(20, 'Official Unisex McLaren Formula 1 Team Soft Shell Jacket - Papaya/Phantom', '', 1599000.00, 'assets/prod_1765359266.jpg', 'McLaren', 99, '', '2025-12-10 09:34:26', 0),
	(21, 'New Era x McLaren Formula 1 Team 2024 Champions 9Forty Cap', '', 479000.00, 'assets/prod_1765359361.jpg', 'McLaren', 99, '', '2025-12-10 09:36:01', 0),
	(22, 'Official Unisex McLaren Formula 1 Team Hoodie - Papaya/Phantom', '', 1249000.00, 'assets/prod_1765359409.jpg', 'McLaren', 99, '', '2025-12-10 09:36:49', 0),
	(23, 'Reiss Womens Bomber Jacket - Black', '', 9020000.00, 'assets/prod_1765359437.jpg', 'McLaren', 99, '', '2025-12-10 09:37:17', 0),
	(24, 'Aston Martin F1 Team 2025 Vegas GP Hoodie', '', 1999000.00, 'assets/prod_1765363067.jpg', 'Aston Martin', 99, '', '2025-12-10 10:37:47', 0),
	(25, 'Aston Martin F1 Team 2025 Vegas GP Cap', '', 599000.00, 'assets/prod_1765363097.jpg', 'Aston Martin', 99, '', '2025-12-10 10:38:17', 0),
	(26, 'Aston Martin F1 Team LEGO® Speed Champions AMR24 Set', '', 519000.00, 'assets/prod_1765363137.jpg', 'Aston Martin', 99, '', '2025-12-10 10:38:57', 0),
	(27, 'Aston Martin F1 Team 2025 Team Polo', '', 759000.00, 'assets/prod_1765363160.jpg', 'Aston Martin', 99, '', '2025-12-10 10:39:20', 0);

-- Dumping structure for table f1fanatic.quiz_attempts
CREATE TABLE IF NOT EXISTS `quiz_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `score` int NOT NULL,
  `total_questions` int NOT NULL,
  `duration_seconds` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.quiz_attempts: ~6 rows (approximately)
INSERT INTO `quiz_attempts` (`id`, `user_id`, `score`, `total_questions`, `duration_seconds`, `created_at`) VALUES
	(1, 1, 0, 10, 19, '2025-12-04 18:25:21'),
	(2, 1, 3, 10, 18, '2025-12-04 18:30:12'),
	(3, 1, 6, 10, 52, '2025-12-04 18:31:25'),
	(4, 4, 4, 10, 100, '2025-12-05 03:19:36'),
	(5, 6, 1, 10, 20, '2025-12-06 13:23:14'),
	(6, 6, 9, 10, 98, '2025-12-06 13:26:03');

-- Dumping structure for table f1fanatic.quiz_questions
CREATE TABLE IF NOT EXISTS `quiz_questions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `option_a` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `option_b` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `option_c` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `option_d` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `answer` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.quiz_questions: ~44 rows (approximately)
INSERT INTO `quiz_questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `answer`, `created_at`) VALUES
	(89, 'Tim mana yang memenangkan Konstruktor Championship terbanyak dalam sejarah F1?', 'Ferrari', 'Mercedes', 'McLaren', 'Red Bull', 'Ferrari', '2025-12-03 10:10:35'),
	(90, 'Siapa pembalap dengan gelar juara dunia terbanyak?', 'Lewis Hamilton', 'Michael Schumacher', 'Sebastian Vettel', 'Ayrton Senna', 'Lewis Hamilton', '2025-12-03 10:10:35'),
	(91, 'Sirkuit mana yang dikenal sebagai \'Temple of Speed\'?', 'Monza', 'Silverstone', 'Spa-Francorchamps', 'Monaco', 'Monza', '2025-12-03 10:10:35'),
	(92, 'Berapa poin yang didapat untuk posisi 1 di balapan F1 modern?', '25', '18', '15', '10', '25', '2025-12-03 10:10:35'),
	(93, 'Tim mana yang bermarkas di Brackley, Inggris?', 'Mercedes', 'Ferrari', 'Red Bull', 'Williams', 'Mercedes', '2025-12-03 10:10:35'),
	(94, 'Ban mana yang paling cepat namun cepat aus?', 'Soft', 'Medium', 'Hard', 'Intermediate', 'Soft', '2025-12-03 10:10:35'),
	(95, 'Apa kepanjangan DRS?', 'Drag Reduction System', 'Downforce Regulation System', 'Dual Rear Spoiler', 'Dynamic Racing Setup', 'Drag Reduction System', '2025-12-03 10:10:35'),
	(96, 'Siapa juara dunia F1 musim 2021?', 'Max Verstappen', 'Lewis Hamilton', 'Charles Leclerc', 'Fernando Alonso', 'Max Verstappen', '2025-12-03 10:10:35'),
	(97, 'Siapa pembalap yang dijuluki \'The Iceman\'?', 'Kimi Räikkönen', 'Valtteri Bottas', 'Mika Häkkinen', 'Fernando Alonso', 'Kimi Räikkönen', '2025-12-03 10:10:35'),
	(98, 'Berapa jumlah balapan dalam kalender F1 2023?', '23', '22', '24', '20', '23', '2025-12-03 10:10:35'),
	(99, 'Sirkuit mana yang merupakan tuan rumah Grand Prix Monaco?', 'Circuit de Monaco', 'Monte Carlo Circuit', 'Monaco Street Circuit', 'Circuit de la Condamine', 'Circuit de Monaco', '2025-12-03 10:10:35'),
	(100, 'Siapa pendiri tim Ferrari F1?', 'Enzo Ferrari', 'Luca di Montezemolo', 'Sergio Marchionne', 'Piero Ferrari', 'Enzo Ferrari', '2025-12-03 10:10:35'),
	(101, 'Apa warna tradisional mobil balap Italia?', 'Rosso Corsa (Merah)', 'Hijau', 'Biru', 'Kuning', 'Rosso Corsa (Merah)', '2025-12-03 10:10:35'),
	(102, 'Siapa pembalap F1 pertama yang memenangkan balapan dengan mobil turbo?', 'Jean-Pierre Jabouille', 'Alain Prost', 'Niki Lauda', 'Gilles Villeneuve', 'Jean-Pierre Jabouille', '2025-12-03 10:10:35'),
	(103, 'Apa nama sistem keselamatan yang diperkenalkan pada 2018 untuk melindungi kepala pembalap?', 'Halo', 'Shield', 'Aeroscreen', 'Canopy', 'Halo', '2025-12-03 10:10:35'),
	(104, 'Siapa pembalap Indonesia pertama yang mengikuti sesi latihan bebas F1?', 'Rio Haryanto', 'Sean Gelael', 'Ananda Mikola', 'Alexandra Asmasoebrata', 'Rio Haryanto', '2025-12-03 10:10:35'),
	(105, 'Berapa jumlah silinder pada mesin F1 modern?', '6', '8', '10', '12', '6', '2025-12-03 10:10:35'),
	(106, 'Apa nama sistem pemulihan energi pada mobil F1 modern?', 'ERS', 'KERS', 'MGU', 'Power Unit', 'ERS', '2025-12-03 10:10:35'),
	(107, 'Siapa pembalap yang memenangkan gelar juara dunia F1 pertama?', 'Giuseppe Farina', 'Juan Manuel Fangio', 'Alberto Ascari', 'Stirling Moss', 'Giuseppe Farina', '2025-12-03 10:10:35'),
	(108, 'Berapa kecepatan pit stop tercepat yang pernah dicatat dalam F1?', '1.82 detik', '2.05 detik', '1.91 detik', '2.10 detik', '1.82 detik', '2025-12-03 10:10:35'),
	(109, 'Sirkuit mana yang memiliki trek terpanjang di kalender F1?', 'Spa-Francorchamps', 'Baku City Circuit', 'Silverstone', 'Circuit of the Americas', 'Spa-Francorchamps', '2025-12-03 10:10:35'),
	(110, 'Apa nama teknologi yang memungkinkan mobil F1 menghasilkan downforce dari lantai mobil?', 'Ground Effect', 'Diffuser', 'Venturi Tunnels', 'Floor Aero', 'Ground Effect', '2025-12-03 10:10:35'),
	(111, 'Siapa pembalap yang mendapat julukan \'El Matador\'?', 'Fernando Alonso', 'Carlos Sainz', 'Pedro de la Rosa', 'Marc Gene', 'Fernando Alonso', '2025-12-03 10:10:35'),
	(112, 'Berapa kapasitas tangki bahan bakar maksimum pada mobil F1 modern?', '110 kg', '100 kg', '120 kg', '105 kg', '110 kg', '2025-12-03 10:10:35'),
	(113, 'Tim mana yang menggunakan mobil dengan warna Orange khas yang dijuluki Papaya?', 'Red Bull Racing', 'McLaren', 'Haas F1 Team', 'Alpine', 'McLaren', '2025-12-03 10:10:35'),
	(114, 'Siapa pembalap F1 yang dikenal dengan julukan Profesor karena pendekatan teknisnya?', 'Ayrton Senna', 'Niki Lauda', 'Alain Prost', 'Jackie Stewart', 'Alain Prost', '2025-12-03 10:10:35'),
	(115, 'Sirkuit jalanan mana di Asia Tenggara yang terkenal dengan balapan malamnya?', 'Shanghai International Circuit', 'Marina Bay Street Circuit', 'Sepang International Circuit', 'Suzuka Circuit', 'Marina Bay Street Circuit', '2025-12-03 10:10:35'),
	(116, 'Apa batas kecepatan (speed limit) di jalur pitlane saat Grand Prix?', '60 km/h', '80 km/h', '100 km/h', '50 km/h', '80 km/h', '2025-12-03 10:10:35'),
	(117, 'Komponen mobil F1 mana yang bertugas mengubah energi panas gas buang menjadi daya yang dapat digunakan?', 'MGU-H', 'MGU-K', 'ERS', 'Turbocharger', 'MGU-H', '2025-12-03 10:10:35'),
	(118, 'Siapa pembalap wanita terakhir yang berpartisipasi dalam sesi Grand Prix F1?', 'Lella Lombardi', 'Susie Wolff', 'Maria Teresa de Filippis', 'Desiré Wilson', 'Lella Lombardi', '2025-12-03 10:10:35'),
	(119, 'Apa yang dimaksud dengan istilah \'Dirty Air\' dalam balapan F1?', 'Udara kotor dari knalpot mobil', 'Udara turbulen yang mengurangi downforce mobil di belakang', 'Udara yang tercampur debu di pit lane', 'Udara bertekanan tinggi di bawah mobil', 'Udara turbulen yang mengurangi downforce mobil di belakang', '2025-12-03 10:10:35'),
	(120, 'Sebutkan pembalap yang memenangkan Formula 1 World Championship sebagai rookie (pemula)?', 'Michael Schumacher', 'Juan Manuel Fangio', 'Lewis Hamilton', 'Tidak ada yang pernah', 'Tidak ada yang pernah', '2025-12-03 10:10:35'),
	(121, 'Dari mana asal Tim AlphaTauri/Racing Bulls?', 'Italia', 'Jerman', 'Inggris', 'Prancis', 'Italia', '2025-12-03 10:10:35'),
	(122, 'Apa fungsi utama dari sayap belakang mobil F1?', 'Menghasilkan downforce', 'Membantu pendinginan mesin', 'Mengurangi hambatan udara', 'Menjaga stabilitas saat pit stop', 'Menghasilkan downforce', '2025-12-03 10:10:35'),
	(123, 'Siapa pembalap yang dijuluki \'The Flying Finn\'?', 'Kimi Räikkönen', 'Mika Häkkinen', 'Valtteri Bottas', 'Heikki Kovalainen', 'Mika Häkkinen', '2025-12-03 10:10:35'),
	(124, 'Berapa panjang lintasan Grand Prix Monaco (dalam km)?', '3.337 km', '5.451 km', '4.381 km', '6.220 km', '3.337 km', '2025-12-03 10:10:35'),
	(125, 'Apa nama bagian mobil yang membantu memulihkan energi kinetik saat pengereman?', 'MGU-K', 'MGU-H', 'ERS', 'Turbocharger', 'MGU-K', '2025-12-03 10:10:35'),
	(126, 'Siapa pembalap pertama yang mencapai 100 kemenangan balapan F1?', 'Michael Schumacher', 'Lewis Hamilton', 'Alain Prost', 'Ayrton Senna', 'Lewis Hamilton', '2025-12-03 10:10:35'),
	(127, 'Aturan F1 mana yang mewajibkan pembalap menggunakan setidaknya dua kompon ban kering yang berbeda dalam balapan?', 'Aturan Pit Stop Wajib', 'Aturan Kompon Ban', 'Aturan Berat Minimum', 'Aturan DRS', 'Aturan Kompon Ban', '2025-12-03 10:10:35'),
	(128, 'Apa singkatan dari FIA, badan pengatur Formula 1?', 'Federation of International Automobile', 'Fédération Internationale de l\'Automobile', 'Formula One Association', 'Ferrari International Agency', 'Fédération Internationale de l\'Automobile', '2025-12-03 10:10:35'),
	(129, 'Tim mana yang sebelumnya dikenal sebagai Brawn GP?', 'Aston Martin', 'Mercedes', 'Red Bull Racing', 'Renault', 'Mercedes', '2025-12-03 10:10:35'),
	(130, 'Sirkuit F1 di Amerika Serikat mana yang dikenal dengan tikungan \'S\' Cepat (S curves) pertamanya?', 'Circuit of the Americas', 'Indianapolis', 'Miami', 'Las Vegas', 'Circuit of the Americas', '2025-12-03 10:10:35'),
	(131, 'Apa yang terjadi jika balapan F1 mencapai batas waktu dua jam?', 'Balapan berakhir pada lap berikutnya', 'Balapan dilanjutkan hingga selesai', 'Balapan dihentikan segera', 'Balapan diperpanjang 30 menit', 'Balapan berakhir pada lap berikutnya', '2025-12-03 10:10:35'),
	(132, 'Siapa yang memegang rekor untuk jumlah Pole Position terbanyak?', 'Ayrton Senna', 'Michael Schumacher', 'Sebastian Vettel', 'Lewis Hamilton', 'Lewis Hamilton', '2025-12-03 10:10:35');

-- Dumping structure for table f1fanatic.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table f1fanatic.users: ~5 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
	(1, 'user', 'user@gmail.com', '$2y$10$KYdCT.EJMRmNREATsIv0vuGm.S4KIGzJPrxaBPigmu7cLy.LXZtuq', '2025-12-02 14:23:03', 'user'),
	(3, 'admin', 'admin@gmail.com', '$2y$10$K7nyFwmonADjAa0fnkDmT.8dyQZteL9QOHESOFsza3ejrA7pDh3cG', '2025-12-03 06:41:06', 'admin'),
	(4, 'Axl Rafael', '24091397019@mhs.unesa.ac.id', '$2y$10$UEFBhkC/YyvmskaJKnGdH.1pvvmYRG/zEE/tkmwtwObOpJTSEZFxC', '2025-12-05 03:15:29', 'user'),
	(5, 'user2', 'user2@gmail.com', '$2y$10$pD637mlLkLEAEFBN5Qom/uBoqP.s5S67kYygKByZvka6.kDBpRDXe', '2025-12-05 08:46:15', 'user'),
	(6, 'Rafi', 'f1speedy123@gmail.com', '$2y$10$9RLRHO7iIDRp8ZsbgzOSyelItLIvCRCBSqMO.16uQE3DROKbL/0uC', '2025-12-06 13:12:19', 'user'),
	(7, 'vincent rompies', 'rompies@gmail.com', '$2y$10$R7Lt7AtJifajNcE3F.FVheL2OQyreXCshygmRTaFLws8BXQrMJ5AC', '2025-12-06 15:25:15', 'user'),
	(8, 'user3', 'user3@gmail.com', '$2y$10$1N4hdh5Fl8Jh6dUGoxtW.egnFyX85qhmsYO3n00LqjuUUSktLfVt.', '2025-12-09 15:11:11', 'user');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
