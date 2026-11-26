<?php
/**
 * views/header.php — awal dokumen HTML, judul, navigasi, dan pesan flash.
 * Variabel dari router: $currentPage, $pageTitle (diisi oleh modul).
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>
<div class="container">
    <header>
        <h1><?= e(APP_NAME) ?></h1>
        <p>Aplikasi data barang &bull; Praktikum 9 PHP Modular</p>
    </header>
    <nav>
        <?= navLink('Home', 'dashboard', $currentPage) ?>
        <?= navLink('Data Barang', 'barang/list', $currentPage, ['barang/edit', 'barang/delete']) ?>
        <?= navLink('Tambah Barang', 'barang/add', $currentPage) ?>
        <?= navLink('Profil', 'profil', $currentPage) ?>
        <?= navLink('Kontak', 'kontak', $currentPage) ?>
    </nav>
    <main>
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>
