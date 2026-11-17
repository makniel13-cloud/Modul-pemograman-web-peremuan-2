-- ============================================================
-- SIMRS - Sistem Informasi Manajemen Rumah Sakit
-- Database Dump: simrs
-- ============================================================

CREATE DATABASE IF NOT EXISTS `simrs` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `simrs`;

-- ------------------------------------------------------------
-- Struktur Tabel `users` (Fitur Login & Register)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Default Admin (Password: admin123)
INSERT INTO `users` (`id`, `username`, `nama`, `password`, `role`) VALUES
(1, 'admin', 'Administrator SIMRS', '$2y$10$iMh.OQpWvM31R2Z0qO0DSu4tV17vW68lJ7O88hB6fA7nN4E7k6Mey', 'admin')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- ------------------------------------------------------------
-- Struktur Tabel `alat` (Data Alat Elektromedis)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `alat` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_alat` VARCHAR(100) NOT NULL,
    `tahun` VARCHAR(20) NOT NULL,
    `merek` VARCHAR(100) NOT NULL,
    `lokasi` VARCHAR(100) NOT NULL,
    `no_seri` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal Alat Elektromedis
INSERT INTO `alat` (`id`, `nama_alat`, `tahun`, `merek`, `lokasi`, `no_seri`) VALUES
(1, 'EKG 12-Lead Digital', '2022', 'GE Healthcare', 'Poli Jantung & Pembuluh Darah', 'GE-EKG-9921'),
(2, 'USG 4D Mindray DC-70', '2023', 'Mindray', 'Poli Kebidanan & Kandungan', 'MND-USG-4012'),
(3, 'Defibrillator BeneHeart D3', '2021', 'Mindray', 'Instalasi Gawat Darurat (IGD)', 'DEF-MND-1102'),
(4, 'Patient Monitor B40 Pro', '2022', 'GE Healthcare', 'Intensive Care Unit (ICU)', 'GE-PM-7731'),
(5, 'Mobile X-Ray MobileArt', '2020', 'Shimadzu', 'Instalasi Radiologi', 'SHM-XR-0043'),
(6, 'Syringe Pump TE-SS700', '2023', 'Terumo', 'Ruang Rawat Inap VIP Melati', 'TRM-SP-8832')
ON DUPLICATE KEY UPDATE `id`=`id`;
