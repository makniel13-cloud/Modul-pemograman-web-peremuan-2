<?php
/**
 * SIMRS - Sistem Informasi Manajemen Rumah Sakit
 * Database Configuration & Helper Functions
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'simrs';

// Connect to MySQL server
$mysqli = @mysqli_connect($databaseHost, $databaseUsername, $databasePassword);

if (!$mysqli) {
    die("<div style='font-family:sans-serif;padding:30px;background:#fee2e2;color:#991b1b;border-radius:10px;margin:20px;'>
        <h3>Koneksi MySQL Gagal</h3>
        <p>Pastikan MySQL di XAMPP / EXAMPP sudah di-START.</p>
        <p>Detail Error: " . mysqli_connect_error() . "</p>
    </div>");
}

// Auto-create database if not exists
$createDbSql = "CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
mysqli_query($mysqli, $createDbSql);

// Select database
mysqli_select_db($mysqli, $databaseName);

// Auto-create users table
$tableUsersSql = "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($mysqli, $tableUsersSql);

// Auto-create alat table
$tableAlatSql = "CREATE TABLE IF NOT EXISTS `alat` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_alat` VARCHAR(100) NOT NULL,
    `tahun` VARCHAR(20) NOT NULL,
    `merek` VARCHAR(100) NOT NULL,
    `lokasi` VARCHAR(100) NOT NULL,
    `no_seri` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($mysqli, $tableAlatSql);

// Seed default admin if users table is empty
$userCountRes = mysqli_query($mysqli, "SELECT COUNT(*) FROM `users`");
if ($userCountRes) {
    $userCount = mysqli_fetch_row($userCountRes)[0];
    if ($userCount == 0) {
        $defaultPass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($mysqli, "INSERT INTO `users` (`username`, `nama`, `password`, `role`) VALUES ('admin', 'Administrator SIMRS', ?, 'admin')");
        mysqli_stmt_bind_param($stmt, "s", $defaultPass);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Seed sample equipment if alat table is empty
$alatCountRes = mysqli_query($mysqli, "SELECT COUNT(*) FROM `alat`");
if ($alatCountRes) {
    $alatCount = mysqli_fetch_row($alatCountRes)[0];
    if ($alatCount == 0) {
        $sampleSql = "INSERT INTO `alat` (`nama_alat`, `tahun`, `merek`, `lokasi`, `no_seri`) VALUES
        ('EKG 12-Lead Digital', '2022', 'GE Healthcare', 'Poli Jantung & Pembuluh Darah', 'GE-EKG-9921'),
        ('USG 4D Mindray DC-70', '2023', 'Mindray', 'Poli Kebidanan & Kandungan', 'MND-USG-4012'),
        ('Defibrillator BeneHeart D3', '2021', 'Mindray', 'Instalasi Gawat Darurat (IGD)', 'DEF-MND-1102'),
        ('Patient Monitor B40 Pro', '2022', 'GE Healthcare', 'Intensive Care Unit (ICU)', 'GE-PM-7731'),
        ('Mobile X-Ray MobileArt', '2020', 'Shimadzu', 'Instalasi Radiologi', 'SHM-XR-0043'),
        ('Syringe Pump TE-SS700', '2023', 'Terumo', 'Ruang Rawat Inap VIP Melati', 'TRM-SP-8832')";
        mysqli_query($mysqli, $sampleSql);
    }
}

/**
 * Authentication Helper Functions
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php?msg=unauthorized");
        exit();
    }
}

function current_user() {
    if (is_logged_in()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'nama' => $_SESSION['nama'] ?? $_SESSION['username'],
            'role' => $_SESSION['role'] ?? 'user'
        ];
    }
    return null;
}

/**
 * Theme Helper Functions
 * Supported: theme-1 (Hopewell Modern), theme-2 (Hopewell Heritage), theme-dark
 */
function get_active_theme() {
    if (isset($_GET['set_theme']) && in_array($_GET['set_theme'], ['theme-1', 'theme-2', 'theme-dark'])) {
        $_SESSION['app_theme'] = $_GET['set_theme'];
        setcookie('app_theme', $_GET['set_theme'], time() + (86400 * 30), "/");
        return $_GET['set_theme'];
    }
    if (isset($_SESSION['app_theme'])) {
        return $_SESSION['app_theme'];
    }
    if (isset($_COOKIE['app_theme']) && in_array($_COOKIE['app_theme'], ['theme-1', 'theme-2', 'theme-dark'])) {
        return $_COOKIE['app_theme'];
    }
    return 'theme-1';
}

function sanitize($mysqli, $data) {
    return mysqli_real_escape_string($mysqli, trim($data));
}
?>