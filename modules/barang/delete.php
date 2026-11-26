<?php
/**
 * modules/barang/delete.php — hapus barang (pengganti hapus.php lama).
 * Rute: index.php?page=barang/delete&id=<id_barang>
 *
 * GET  -> menampilkan halaman konfirmasi (tidak menghapus apa pun).
 * POST -> menghapus data, setelah token CSRF diperiksa.
 * Data tidak dihapus hanya karena sebuah URL dibuka.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Hapus Barang';

$id = idDariUrl();
$barang = $id > 0
    ? dbFetchOne($conn, 'SELECT * FROM data_barang WHERE id_barang = ?', [$id])
    : null;

if (!$barang) {
    setFlash('error', 'Data barang tidak ditemukan.');
    redirect('barang/list');
}

if (isPost()) {
    if (!verifyCsrf()) {
        setFlash('error', 'Sesi formulir sudah kedaluwarsa. Silakan coba lagi.');
        redirect('barang/list');
    }

    dbExecute($conn, 'DELETE FROM data_barang WHERE id_barang = ?', [$id]);
    setFlash('success', 'Barang "' . $barang['nama'] . '" berhasil dihapus.');
    redirect('barang/list');
}

$gambar = gambarUrl($barang['gambar']);
?>
<section>
    <h2>Hapus Barang</h2>
    <div class="konfirmasi">
        <?php if ($gambar): ?>
            <img src="<?= e($gambar) ?>" alt="<?= e($barang['nama']) ?>">
        <?php endif; ?>
        <div>
            <p>Yakin ingin menghapus barang berikut?</p>
            <p>
                <strong><?= e($barang['nama']) ?></strong><br>
                <?= e($barang['kategori']) ?> &bull; <?= formatRupiah((float) $barang['harga_jual']) ?> &bull; stok <?= e($barang['stok']) ?>
            </p>
            <form method="post" action="<?= e(url('barang/delete', ['id' => $id])) ?>">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-danger">Ya, hapus</button>
                <a class="btn btn-secondary" href="<?= e(url('barang/list')) ?>">Batal</a>
            </form>
        </div>
    </div>
</section>
