<?php
/**
 * modules/barang/list.php — daftar barang (pengganti index.php lama).
 * Rute: index.php?page=barang/list
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Data Barang';

$daftarBarang = dbFetchAll($conn, 'SELECT * FROM data_barang ORDER BY id_barang');
?>
<section>
    <h2>Data Barang</h2>
    <p><a class="btn btn-primary" href="<?= e(url('barang/add')) ?>">Tambah Barang</a></p>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Gambar</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>Harga Beli</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
            <?php if ($daftarBarang): ?>
                <?php foreach ($daftarBarang as $row): ?>
                    <?php $gambar = gambarUrl($row['gambar']); ?>
                    <tr>
                        <td class="tengah">
                            <?php if ($gambar): ?>
                                <img src="<?= e($gambar) ?>" alt="<?= e($row['nama']) ?>">
                            <?php else: ?>
                                <span class="muted">Tidak ada gambar</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($row['nama']) ?></td>
                        <td><?= e($row['kategori']) ?></td>
                        <td class="angka"><?= formatRupiah((float) $row['harga_jual']) ?></td>
                        <td class="angka"><?= formatRupiah((float) $row['harga_beli']) ?></td>
                        <td class="tengah"><?= e($row['stok']) ?></td>
                        <td class="aksi">
                            <a href="<?= e(url('barang/edit', ['id' => $row['id_barang']])) ?>">Ubah</a>
                            <a class="link-hapus" href="<?= e(url('barang/delete', ['id' => $row['id_barang']])) ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="tengah">Belum ada data</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>
