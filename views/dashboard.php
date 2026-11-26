<?php
/**
 * views/dashboard.php — halaman Home: ringkasan data barang.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Dashboard';

$ringkasan = dbFetchOne($conn,
    'SELECT COUNT(*) AS jumlah,
            COALESCE(SUM(stok), 0) AS total_stok,
            COALESCE(SUM(harga_beli * stok), 0) AS nilai_stok,
            COALESCE(SUM((harga_jual - harga_beli) * stok), 0) AS potensi_laba
     FROM data_barang'
);
$terbaru = dbFetchAll($conn, 'SELECT * FROM data_barang ORDER BY id_barang DESC LIMIT 3');
$persenDiskon = 10;
?>
<section>
    <h2>Dashboard</h2>
    <p>Ringkasan data barang saat ini.</p>

    <div class="cards">
        <div class="card">
            <span class="card-label">Jenis barang</span>
            <strong><?= number_format((float) $ringkasan['jumlah'], 0, ',', '.') ?></strong>
        </div>
        <div class="card">
            <span class="card-label">Total stok</span>
            <strong><?= number_format((float) $ringkasan['total_stok'], 0, ',', '.') ?></strong>
        </div>
        <div class="card">
            <span class="card-label">Nilai stok (harga beli)</span>
            <strong><?= formatRupiah((float) $ringkasan['nilai_stok']) ?></strong>
        </div>
        <div class="card">
            <span class="card-label">Potensi laba</span>
            <strong><?= formatRupiah((float) $ringkasan['potensi_laba']) ?></strong>
        </div>
    </div>

    <h3>Barang terbaru</h3>
    <?php if ($terbaru): ?>
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga Jual</th>
                    <th>Simulasi diskon <?= $persenDiskon ?>%</th>
                </tr>
                <?php foreach ($terbaru as $row): ?>
                    <?php $hargaJual = (float) $row['harga_jual']; ?>
                    <tr>
                        <td title="<?= e($row['nama']) ?>"><?= e(ringkasTeks((string) $row['nama'], 20)) ?></td>
                        <td class="angka"><?= formatRupiah($hargaJual) ?></td>
                        <td class="angka"><?= formatRupiah($hargaJual - hitungDiskon($hargaJual, $persenDiskon)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <p><a href="<?= e(url('barang/list')) ?>">Lihat semua data barang &rarr;</a></p>
    <?php else: ?>
        <p>Belum ada data. <a href="<?= e(url('barang/add')) ?>">Tambah barang pertama</a>.</p>
    <?php endif; ?>
</section>
