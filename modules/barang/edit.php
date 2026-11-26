<?php
/**
 * modules/barang/edit.php — ubah barang (pengganti ubah.php lama).
 * Rute: index.php?page=barang/edit&id=<id_barang>
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$pageTitle = 'Ubah Barang';

$id = idDariUrl();
$barang = $id > 0
    ? dbFetchOne($conn, 'SELECT * FROM data_barang WHERE id_barang = ?', [$id])
    : null;

if (!$barang) {
    setFlash('error', 'Data barang tidak ditemukan.');
    redirect('barang/list');
}

$errors = [];
$data = [
    'nama'       => (string) $barang['nama'],
    'kategori'   => (string) $barang['kategori'],
    'harga_jual' => (string) (int) $barang['harga_jual'],
    'harga_beli' => (string) (int) $barang['harga_beli'],
    'stok'       => (string) (int) $barang['stok'],
];

if (isPost()) {
    if (!verifyCsrf()) {
        setFlash('error', 'Sesi formulir sudah kedaluwarsa. Silakan isi ulang.');
        redirect('barang/edit', ['id' => $id]);
    }

    $data   = ambilInputBarang();
    $errors = validasiBarang($data);

    $upload = ['file' => null, 'error' => null];
    if (!$errors) {
        $upload = uploadGambar($_FILES['file_gambar'] ?? []);
        if ($upload['error']) {
            $errors['file_gambar'] = $upload['error'];
        }
    }

    if (!$errors) {
        $sql = 'UPDATE data_barang
                SET nama = ?, kategori = ?, harga_jual = ?, harga_beli = ?, stok = ?';
        $params = [
            $data['nama'],
            $data['kategori'],
            (int) $data['harga_jual'],
            (int) $data['harga_beli'],
            (int) $data['stok'],
        ];

        // Kolom gambar hanya diubah jika pengguna mengunggah gambar baru
        if ($upload['file']) {
            $sql .= ', gambar = ?';
            $params[] = $upload['file'];
        }

        $sql .= ' WHERE id_barang = ?';
        $params[] = $id;

        dbExecute($conn, $sql, $params);
        setFlash('success', 'Barang "' . $data['nama'] . '" berhasil diperbarui.');
        redirect('barang/list');
    }
}

$formAction = url('barang/edit', ['id' => $id]);
$gambarLama = $barang['gambar'];
require __DIR__ . '/_form.php';
