<?php
/**
 * modules/kontak/form.php — form kontak dengan validasi di server.
 * Rute: index.php?page=kontak
 * Catatan: pesan hanya divalidasi lalu dikonfirmasi, tidak dikirim/disimpan.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Kontak';

$errors = [];
$data = ['nama' => '', 'email' => '', 'pesan' => ''];

if (isPost()) {
    if (!verifyCsrf()) {
        setFlash('error', 'Sesi formulir sudah kedaluwarsa. Silakan isi ulang.');
        redirect('kontak');
    }

    $data = [
        'nama'  => postString('nama'),
        'email' => postString('email'),
        'pesan' => postString('pesan'),
    ];

    if ($data['nama'] === '') {
        $errors['nama'] = 'Nama wajib diisi.';
    }
    if (!validasiEmail($data['email'])) {
        $errors['email'] = 'Format email tidak valid.';
    }
    if ($data['pesan'] === '') {
        $errors['pesan'] = 'Pesan wajib diisi.';
    }

    if (!$errors) {
        setFlash('success', 'Terima kasih, ' . $data['nama'] . '. Pesan "' . ringkasTeks($data['pesan'], 40) . '" sudah kami terima.');
        redirect('kontak');
    }
}
?>
<section>
    <h2>Kontak</h2>
    <p>Ada pertanyaan tentang barang? Kirim pesan melalui form berikut.</p>

    <form method="post" action="<?= e(url('kontak')) ?>">
        <?= csrfField() ?>

        <div class="input">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?= e($data['nama']) ?>" required>
            <?= errorText($errors, 'nama') ?>
        </div>

        <div class="input">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($data['email']) ?>" required>
            <?= errorText($errors, 'email') ?>
        </div>

        <div class="input">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" rows="4" required><?= e($data['pesan']) ?></textarea>
            <?= errorText($errors, 'pesan') ?>
        </div>

        <div class="submit">
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</section>
