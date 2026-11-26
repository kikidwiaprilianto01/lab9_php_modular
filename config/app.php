<?php
/**
 * config/app.php — pengaturan umum aplikasi.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}

date_default_timezone_set('Asia/Jakarta');

define('APP_NAME', 'Web Jual Beli');

// Folder tempat aplikasi berada, mis. "/Lab9Web" (kosong jika di root domain).
// Dipakai agar tautan dan path CSS/gambar selalu benar dari halaman mana pun.
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'));

// true  -> tautan berbentuk /Lab9Web/barang/list (butuh .htaccess + mod_rewrite)
// false -> tautan berbentuk /Lab9Web/index.php?page=barang/list
define('PRETTY_URL', false);

// Upload gambar barang
define('UPLOAD_DIR', APP_ROOT . '/assets/gambar');
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2 MB

// Pilihan kategori barang
define('KATEGORI_BARANG', ['Komputer', 'Elektronik', 'Hand Phone']);
