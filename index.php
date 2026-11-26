<?php
/**
 * index.php — titik masuk aplikasi (router).
 *
 * Semua halaman diakses lewat file ini dengan pola:
 *     index.php?page=<nama-rute>
 * Router hanya memuat file yang terdaftar di $routes (whitelist),
 * sehingga input URL tidak pernah dipakai langsung sebagai path file.
 */
define('APP_ROOT', __DIR__);

session_start();

// File inti: wajib ada dan cukup dimuat satu kali
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/helpers/db.php';

// Daftar rute yang boleh diakses
$routes = [
    'dashboard'     => __DIR__ . '/views/dashboard.php',
    'barang/list'   => __DIR__ . '/modules/barang/list.php',
    'barang/add'    => __DIR__ . '/modules/barang/add.php',
    'barang/edit'   => __DIR__ . '/modules/barang/edit.php',
    'barang/delete' => __DIR__ . '/modules/barang/delete.php',
    'profil'        => __DIR__ . '/views/profil.php',
    'kontak'        => __DIR__ . '/modules/kontak/form.php',
];

$currentPage = $_GET['page'] ?? 'dashboard';
if (is_string($currentPage)) {
    $currentPage = trim($currentPage, '/');
    if ($currentPage === '') {
        $currentPage = 'dashboard';
    }
}

if (!is_string($currentPage) || !array_key_exists($currentPage, $routes)) {
    http_response_code(404);
    $currentPage = '';
    $contentFile = __DIR__ . '/views/404.php';
} else {
    $contentFile = $routes[$currentPage];
}

// Modul dijalankan lebih dulu dan keluarannya ditampung.
// Dengan begitu modul masih bisa memanggil redirect() (header Location)
// setelah proses tambah/ubah/hapus, sebelum template dicetak.
ob_start();
require $contentFile;
$pageContent = ob_get_clean();

// Template bersama membungkus konten halaman
require_once __DIR__ . '/views/header.php';
echo $pageContent;
require_once __DIR__ . '/views/footer.php';
