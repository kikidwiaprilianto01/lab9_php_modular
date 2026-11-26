<?php
/**
 * modules/barang/add.php — tambah barang (pengganti tambah.php lama).
 * Rute: index.php?page=barang/add
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Tambah Barang';

$errors = [];
$data = [
    'nama'       => '',
    'kategori'   => KATEGORI_BARANG[0],
    'harga_jual' => '',
    'harga_beli' => '',
    'stok'       => '',
];

if (isPost()) {
    if (!verifyCsrf()) {
        setFlash('error', 'Sesi formulir sudah kedaluwarsa. Silakan isi ulang.');
        redirect('barang/add');
    }

    $data   = ambilInputBarang();
    $errors = validasiBarang($data);

    // Gambar baru diunggah setelah isian lain valid, agar tidak ada file yatim
    $upload = ['file' => null, 'error' => null];
    if (!$errors) {
        $upload = uploadGambar($_FILES['file_gambar'] ?? []);
        if ($upload['error']) {
            $errors['file_gambar'] = $upload['error'];
        }
    }

    if (!$errors) {
        dbExecute($conn,
            'INSERT INTO data_barang (nama, kategori, harga_jual, harga_beli, stok, gambar)
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $data['nama'],
                $data['kategori'],
                (int) $data['harga_jual'],
                (int) $data['harga_beli'],
                (int) $data['stok'],
                $upload['file'] ?? '',
            ]
        );
        setFlash('success', 'Barang "' . $data['nama'] . '" berhasil ditambahkan.');
        redirect('barang/list');
    }
}

$formAction = url('barang/add');
$gambarLama = null;
require __DIR__ . '/_form.php';
