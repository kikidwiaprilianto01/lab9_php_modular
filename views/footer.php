<?php
/**
 * views/footer.php — penutup halaman.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}
?>
    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> - Praktikum Pemrograman Web</p>
    </footer>
</div>
<script src="<?= e(asset('js/script.js')) ?>"></script>
</body>
</html>
