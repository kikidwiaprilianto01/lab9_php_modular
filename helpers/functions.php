<?php
/**
 * helpers/functions.php — function umum yang dipakai banyak modul.
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}

/* =========================================================
 * 1. Format tampilan
 * ========================================================= */

/**
 * Escaping teks sebelum ditampilkan ke HTML (mencegah XSS).
 */
function e(?string $nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

/**
 * 2400000 -> "Rp 2.400.000"
 */
function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Memotong teks panjang dan menambahkan "..." di akhir.
 */
function ringkasTeks(string $teks, int $batas = 50): string
{
    $teks = trim($teks);
    if (mb_strlen($teks) <= $batas) {
        return $teks;
    }
    return rtrim(mb_substr($teks, 0, $batas)) . '...';
}

/**
 * Atribut "selected" untuk <option> yang nilainya cocok.
 */
function isSelected(string $nilai, string $pilihan): string
{
    return $nilai === $pilihan ? ' selected' : '';
}

/**
 * Pesan error di bawah input form, jika ada.
 */
function errorText(array $errors, string $field): string
{
    if (!isset($errors[$field])) {
        return '';
    }
    return '<small class="error-text">' . e($errors[$field]) . '</small>';
}

/**
 * URL gambar barang. basename() membuang awalan folder lama seperti "gambar/".
 */
function gambarUrl(?string $file): ?string
{
    if ($file === null || $file === '') {
        return null;
    }
    return asset('gambar/' . rawurlencode(basename($file)));
}

/* =========================================================
 * 2. Perhitungan dan validasi
 * ========================================================= */

/**
 * Besar potongan harga, mis. hitungDiskon(200000) -> 20000 (10%).
 */
function hitungDiskon(float $harga, float $persen = 10): float
{
    return $harga * ($persen / 100);
}

function validasiEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Mengambil isian form barang dari $_POST.
 */
function ambilInputBarang(): array
{
    return [
        'nama'       => postString('nama'),
        'kategori'   => postString('kategori'),
        'harga_jual' => postString('harga_jual'),
        'harga_beli' => postString('harga_beli'),
        'stok'       => postString('stok'),
    ];
}

/**
 * Memvalidasi data barang. Mengembalikan array error (kosong = valid).
 */
function validasiBarang(array $data): array
{
    $errors = [];

    if ($data['nama'] === '') {
        $errors['nama'] = 'Nama barang wajib diisi.';
    } elseif (mb_strlen($data['nama']) > 30) {
        // Sesuaikan dengan panjang kolom "nama" di tabel (varchar 30)
        $errors['nama'] = 'Nama barang maksimal 30 karakter.';
    }

    if (!in_array($data['kategori'], KATEGORI_BARANG, true)) {
        $errors['kategori'] = 'Pilih kategori yang tersedia.';
    }

    $angka = [
        'harga_jual' => ['Harga jual', 10],
        'harga_beli' => ['Harga beli', 10],
        'stok'       => ['Stok', 9],
    ];
    foreach ($angka as $field => [$label, $maksDigit]) {
        if ($data[$field] === '') {
            $errors[$field] = "$label wajib diisi.";
        } elseif (!ctype_digit($data[$field]) || strlen($data[$field]) > $maksDigit) {
            $errors[$field] = "$label harus berupa angka bulat positif (maks. $maksDigit digit).";
        }
    }

    return $errors;
}

/* =========================================================
 * 3. URL, request, dan navigasi
 * ========================================================= */

/**
 * Membuat URL ke sebuah rute, mis. url('barang/edit', ['id' => 3]).
 */
function url(string $page = 'dashboard', array $params = []): string
{
    $query = $params ? http_build_query($params) : '';

    if (PRETTY_URL) {
        $path = BASE_URL . '/' . ($page === 'dashboard' ? '' : $page);
        return $query ? $path . '?' . $query : $path;
    }

    $path = BASE_URL . '/index.php?page=' . $page;
    return $query ? $path . '&' . $query : $path;
}

/**
 * URL file statis di folder assets/, mis. asset('css/style.css').
 */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Pindah ke rute lain lalu hentikan eksekusi.
 */
function redirect(string $page, array $params = []): void
{
    header('Location: ' . url($page, $params));
    exit;
}

function isPost(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/**
 * Nilai teks dari $_POST yang sudah di-trim ('' jika tidak ada).
 */
function postString(string $key): string
{
    $nilai = $_POST[$key] ?? '';
    return is_string($nilai) ? trim($nilai) : '';
}

/**
 * Parameter ?id= dari URL sebagai bilangan bulat (0 jika tidak valid).
 */
function idDariUrl(): int
{
    $id = $_GET['id'] ?? '';
    return (is_string($id) && ctype_digit($id)) ? (int) $id : 0;
}

/**
 * Tautan menu navigasi; diberi class "active" bila rutenya sedang dibuka.
 */
function navLink(string $label, string $page, string $currentPage, array $ikutAktif = []): string
{
    $aktif = $currentPage === $page || in_array($currentPage, $ikutAktif, true);
    $attr  = $aktif ? ' class="active" aria-current="page"' : '';
    return '<a href="' . e(url($page)) . '"' . $attr . '>' . e($label) . '</a>';
}

/* =========================================================
 * 4. Session: pesan flash dan token CSRF
 * ========================================================= */

/**
 * Simpan pesan yang ditampilkan sekali di halaman berikutnya.
 * $tipe: 'success' atau 'error'.
 */
function setFlash(string $tipe, string $pesan): void
{
    $_SESSION['flash'] = ['type' => $tipe, 'message' => $pesan];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Input tersembunyi berisi token CSRF untuk setiap form POST.
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && hash_equals(csrfToken(), $token);
}

/* =========================================================
 * 5. Upload gambar
 * ========================================================= */

/**
 * Memproses upload gambar barang.
 * Mengembalikan ['file' => nama file|null, 'error' => pesan|null].
 * Jika tidak ada file yang dipilih, file = null tanpa error (gambar opsional).
 */
function uploadGambar(array $file): array
{
    $hasil = ['file' => null, 'error' => null];
    $kode  = $file['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($kode === UPLOAD_ERR_NO_FILE) {
        return $hasil;
    }
    if (!is_int($kode) || $kode !== UPLOAD_ERR_OK) {
        $terlaluBesar = in_array($kode, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true);
        $hasil['error'] = $terlaluBesar ? 'Ukuran gambar terlalu besar.' : 'Gambar gagal diunggah.';
        return $hasil;
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        $hasil['error'] = 'Ukuran gambar maksimal 2 MB.';
        return $hasil;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
        $hasil['error'] = 'Format gambar harus JPG, PNG, GIF, atau WEBP.';
        return $hasil;
    }
    if (@getimagesize($file['tmp_name']) === false) {
        $hasil['error'] = 'File yang diunggah bukan gambar yang valid.';
        return $hasil;
    }

    // Nama file aman + awalan waktu agar tidak menimpa gambar lain
    $namaDasar = trim(preg_replace('/[^A-Za-z0-9_-]+/', '_', pathinfo($file['name'], PATHINFO_FILENAME)), '_');
    $namaFile  = date('YmdHis') . '_' . ($namaDasar !== '' ? $namaDasar : 'gambar') . '.' . $ext;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . '/' . $namaFile)) {
        $hasil['error'] = 'Gambar gagal disimpan ke server.';
        return $hasil;
    }

    $hasil['file'] = $namaFile;
    return $hasil;
}
