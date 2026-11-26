<?php
/**
 * views/profil.php — halaman statis tentang aplikasi.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Profil';
?>
<section>
    <h2>Tentang Aplikasi</h2>
    <p>
        <?= e(APP_NAME) ?> adalah aplikasi pengelolaan data barang (tambah, lihat, ubah, hapus)
        yang disusun ulang secara modular pada Praktikum 9.
    </p>

    <h3>Yang diterapkan</h3>
    <ul>
        <li><strong>Template</strong>: header, navigasi, dan footer dipakai bersama oleh semua halaman (<code>views/</code>).</li>
        <li><strong>Function</strong>: format rupiah, escaping, validasi, upload, dan query database disimpan di <code>helpers/</code>.</li>
        <li><strong>Routing</strong>: semua halaman dibuka lewat <code>index.php?page=...</code> dengan daftar rute (whitelist) dan halaman 404.</li>
        <li><strong>Konfigurasi</strong>: koneksi database hanya ada di <code>config/database.php</code>.</li>
        <li><strong>Modul fitur</strong>: halaman CRUD barang dikelompokkan di <code>modules/barang/</code>.</li>
    </ul>
</section>
