<?php
/**
 * modules/barang/_form.php — form barang yang dipakai bersama oleh add.php dan edit.php.
 *
 * Variabel yang harus disiapkan sebelum file ini dimuat:
 *   $pageTitle, $formAction, $data (isian form), $errors, $gambarLama (null jika tidak ada)
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$gambarLamaUrl = gambarUrl($gambarLama);
?>
<section>
    <h2><?= e($pageTitle) ?></h2>

    <?php if ($errors): ?>
        <div class="alert alert-error">Data belum tersimpan. Periksa kembali isian yang ditandai.</div>
    <?php endif; ?>

    <form method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data">
        <?= csrfField() ?>

        <div class="input">
            <label for="nama">Nama Barang</label>
            <input type="text" id="nama" name="nama" value="<?= e($data['nama']) ?>" maxlength="30" required>
            <?= errorText($errors, 'nama') ?>
        </div>

        <div class="input">
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori">
                <?php foreach (KATEGORI_BARANG as $kategori): ?>
                    <option value="<?= e($kategori) ?>"<?= isSelected($kategori, $data['kategori']) ?>><?= e($kategori) ?></option>
                <?php endforeach; ?>
            </select>
            <?= errorText($errors, 'kategori') ?>
        </div>

        <div class="input">
            <label for="harga_jual">Harga Jual</label>
            <input type="number" id="harga_jual" name="harga_jual" value="<?= e($data['harga_jual']) ?>" min="0" step="1" required>
            <?= errorText($errors, 'harga_jual') ?>
        </div>

        <div class="input">
            <label for="harga_beli">Harga Beli</label>
            <input type="number" id="harga_beli" name="harga_beli" value="<?= e($data['harga_beli']) ?>" min="0" step="1" required>
            <?= errorText($errors, 'harga_beli') ?>
        </div>

        <div class="input">
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" value="<?= e($data['stok']) ?>" min="0" step="1" required>
            <?= errorText($errors, 'stok') ?>
        </div>

        <div class="input">
            <label for="file_gambar">File Gambar</label>
            <input type="file" id="file_gambar" name="file_gambar" accept="image/*" data-preview="preview-gambar">
            <?= errorText($errors, 'file_gambar') ?>
        </div>

        <div class="input-extra">
            <img id="preview-gambar" class="preview" alt="Pratinjau gambar baru" hidden>
            <?php if ($gambarLamaUrl): ?>
                <figure class="gambar-lama">
                    <img class="preview" src="<?= e($gambarLamaUrl) ?>" alt="Gambar saat ini">
                    <figcaption>Gambar saat ini. Kosongkan pilihan file jika tidak ingin menggantinya.</figcaption>
                </figure>
            <?php endif; ?>
        </div>

        <div class="submit">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="<?= e(url('barang/list')) ?>">Batal</a>
        </div>
    </form>
</section>
