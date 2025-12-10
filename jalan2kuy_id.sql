-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Des 2025 pada 13.38
-- Versi server: 8.0.43
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jalan2kuy_id`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `adminID` varchar(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`adminID`, `name`, `username`, `password`, `email`, `gender`) VALUES
('adm001', 'Dhimas HP', 'dhimas123', 'jalanDhimas', 'dhimas.hfzh375@gmail.com', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `destcategory`
--

CREATE TABLE `destcategory` (
  `destCategoryID` varchar(6) NOT NULL,
  `categoryName` varchar(20) NOT NULL,
  `categoryImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `destcategory`
--

INSERT INTO `destcategory` (`destCategoryID`, `categoryName`, `categoryImage`) VALUES
('ctg001', 'Nature', 'destcategories/Nature.png'),
('ctg002', 'History', 'destcategories/History.jpg'),
('ctg003', 'Ecotourism', 'destcategories/Ecotourism.png'),
('ctg004', 'Beach', 'destcategories/Beach.jpg'),
('ctg005', 'Culture', 'destcategories/Culture.jpg'),
('ctg006', 'Education', 'destcategories/Education.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `destination`
--

CREATE TABLE `destination` (
  `destinationID` varchar(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `openingHours` time NOT NULL,
  `closingHours` time NOT NULL,
  `timezone` varchar(15) NOT NULL,
  `openingDay` varchar(15) NOT NULL,
  `closingDay` varchar(15) NOT NULL,
  `entranceFee` int NOT NULL,
  `description` text NOT NULL,
  `imagePath` varchar(255) NOT NULL,
  `thumbnailImagePath` varchar(255) NOT NULL,
  `destCategoryID` varchar(6) NOT NULL,
  `adminID` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `destination`
--

INSERT INTO `destination` (`destinationID`, `name`, `location`, `openingHours`, `closingHours`, `timezone`, `openingDay`, `closingDay`, `entranceFee`, `description`, `imagePath`, `thumbnailImagePath`, `destCategoryID`, `adminID`) VALUES
('dst001', 'tes nautere', 'tes natire', '08:00:00', '09:00:00', 'WIB', 'senin', 'minggu', 25000, 'tes nature', 'destinations/image/ZnRrlW5QxyeH8vJREiNUtIRk1QylK3P5EUoPkX8b.png', 'destinations/thumbnailImage/B3Y1Y1Ru6UsTypxWY070dCOvWhR5TjqAa2PE3sYE.png', 'ctg001', 'adm001'),
('dst002', 'TES HISTORY', 'history', '06:00:00', '18:00:00', 'WIB', 'senin', 'MINGGU', 20000, 'tes histiry', 'destinations/image/TkPpsbsvycFQG1Bf6o2LBqUsgtPIbeG9BfsHxTje.jpg', 'destinations/thumbnailImage/0wINsdacdTThY8otdZ9lyWrOsb6qIA2DO2HaNhnE.png', 'ctg002', 'adm001');

-- --------------------------------------------------------

--
-- Struktur dari tabel `event`
--

CREATE TABLE `event` (
  `eventID` varchar(6) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `location` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `entranceFee` int NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `socialMedia` varchar(50) NOT NULL,
  `imagePath` varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `destinationID` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `adminID` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `event`
--

INSERT INTO `event` (`eventID`, `name`, `startDate`, `endDate`, `location`, `description`, `entranceFee`, `startTime`, `endTime`, `socialMedia`, `imagePath`, `destinationID`, `adminID`) VALUES
('evt001', 'tes event', '2025-12-01', '2025-12-05', 'tes lokasi event', 'test nambah event', 5000, '08:30:00', '09:30:00', 'dhmzz._375', 'events/Nvl7TfpReXhyckyUjlBFfPqnJw5L8pcNHM84S7u9.png', 'dst002', 'adm001'),
('evt003', 'baru', '4444-04-04', '7744-04-04', 'aasasas', 'asasas', 12, '12:12:00', '13:13:00', 'sasas', 'events/3sviv8SUuq6aCtZ7i912FZx2BiSPtaNlLiZPiwqu.png', NULL, 'adm001'),
('evt004', 'tes event biat destination', '1111-11-11', '2222-02-22', 'destination', 'tes event biat destination', 11, '09:00:00', '11:00:00', 'iuhicuef', 'events/cKhsXKwSCikRViico1gb1PqkCtt336N2PhnmNyWd.png', 'dst001', 'adm001');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FJg7XaBJowIEeyEFiJDjHoAv6EyKtuU7XKS0ACA3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 'YTo1OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoibnBXVkFCMEZQNkNYNjhPMjlMa3o1MEhrWm1qWHZ6RHFNVXlIc0EwSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9EZXN0aW5hdGlvbi9DYXRlZ29yeT9DYXRlZ29yeT1jdGcwMDIiO3M6NToicm91dGUiO047fXM6ODoiYWRtaW5faWQiO3M6NjoiYWRtMDAxIjtzOjEwOiJhZG1pbl9uYW1lIjtzOjk6IkRoaW1hcyBIUCI7fQ==', 1765369900);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `destcategory`
--
ALTER TABLE `destcategory`
  ADD PRIMARY KEY (`destCategoryID`);

--
-- Indeks untuk tabel `destination`
--
ALTER TABLE `destination`
  ADD PRIMARY KEY (`destinationID`),
  ADD KEY `adminID` (`adminID`),
  ADD KEY `destCategoryID` (`destCategoryID`);

--
-- Indeks untuk tabel `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventID`),
  ADD KEY `adminID` (`adminID`),
  ADD KEY `destinationID` (`destinationID`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `destination`
--
ALTER TABLE `destination`
  ADD CONSTRAINT `destination_ibfk_1` FOREIGN KEY (`adminID`) REFERENCES `admin` (`adminID`),
  ADD CONSTRAINT `destination_ibfk_2` FOREIGN KEY (`destCategoryID`) REFERENCES `destcategory` (`destCategoryID`);

--
-- Ketidakleluasaan untuk tabel `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`adminID`) REFERENCES `admin` (`adminID`),
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`destinationID`) REFERENCES `destination` (`destinationID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
