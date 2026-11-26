<?php
/**
 * config/database.php — satu-satunya tempat pengaturan koneksi database.
 * Dimuat sekali oleh index.php menggunakan require_once.
 * (Pengganti koneksi.php pada praktikum sebelumnya; tetap memakai MySQLi.)
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'latihan1';

// Jadikan error MySQLi sebagai exception agar bisa ditangkap try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($host, $user, $pass, $db);
    mysqli_set_charset($conn, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Detail error dicatat di log server, tidak ditampilkan kepada pengguna
    error_log($e->getMessage());
    http_response_code(500);
    exit('Koneksi ke server database gagal.');
}
