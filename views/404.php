<?php
/**
 * views/404.php — ditampilkan jika rute tidak ada di whitelist.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Halaman tidak ditemukan';
?>
<section>
    <h2>404 - Halaman tidak ditemukan</h2>
    <p>Halaman yang diminta tidak tersedia.</p>
    <a href="<?= e(url('dashboard')) ?>">Kembali ke dashboard</a>
</section>
