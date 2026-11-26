# Lab9Web — PHP Modular

Praktikum 9 Pemrograman Web: **Modularisasi Program, Function, Template, dan Routing**.

## Tujuan Project

Merombak aplikasi CRUD Data Barang dari praktikum sebelumnya agar:

- header, navigasi, dan footer ditulis sekali lalu dipakai bersama oleh semua halaman (template);
- proses yang berulang dipindahkan ke function dengan parameter dan nilai kembalian (helpers);
- koneksi database hanya dikelola di satu file konfigurasi;
- halaman CRUD dikelompokkan per fitur di folder `modules/`;
- semua halaman dibuka lewat satu pintu masuk `index.php?page=...` dengan whitelist rute dan halaman 404.

## Kebutuhan Perangkat

- XAMPP / Laragon (Apache + MySQL/MariaDB)
- PHP 7.4 atau lebih baru (ekstensi `mysqli` dan `mbstring` aktif — bawaan XAMPP)
- Browser dan Visual Studio Code

## Cara Menjalankan

1. Salin folder `Lab9Web` ke docroot, misalnya `C:\xampp\htdocs\Lab9Web`.
2. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Database:
   - Jika database `latihan1` dari praktikum sebelumnya sudah ada, langkah ini dilewati.
   - Jika belum, import `database.sql` lewat phpMyAdmin (tab **Import**).
4. Jika user/password MySQL berbeda, ubah di `config/database.php`.
5. Buka `http://localhost/Lab9Web/` di browser.

### URL ringkas (opsional)

File `.htaccess` sudah disiapkan. Jika `mod_rewrite` aktif dan `AllowOverride All`, ubah
`PRETTY_URL` menjadi `true` di `config/app.php`. Tautan akan berubah dari
`/Lab9Web/index.php?page=barang/list` menjadi `/Lab9Web/barang/list`.
Bentuk `index.php?page=...` tetap bisa dipakai.

## Struktur Direktori

```
Lab9Web/
├── index.php              # titik masuk aplikasi + router (whitelist rute)
├── .htaccess              # URL rewrite (opsional)
├── database.sql           # struktur tabel + data contoh
├── config/
│   ├── app.php            # nama aplikasi, BASE_URL, kategori, batas upload
│   └── database.php       # koneksi MySQLi (pengganti koneksi.php)
├── views/                 # tampilan bersama dan halaman statis
│   ├── header.php
│   ├── footer.php
│   ├── dashboard.php
│   ├── profil.php
│   └── 404.php
├── helpers/
│   ├── functions.php      # format, validasi, URL, flash, CSRF, upload
│   └── db.php             # function query dengan prepared statement
├── modules/
│   ├── barang/            # fitur CRUD barang
│   │   ├── list.php
│   │   ├── add.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   └── _form.php      # form bersama untuk add & edit
│   └── kontak/
│       └── form.php
└── assets/
    ├── css/style.css
    ├── js/script.js       # pratinjau gambar sebelum upload
    └── gambar/            # gambar barang (hasil upload)
```

Folder fitur diberi nama `barang` (bukan `user` seperti contoh di modul) karena data yang
dikelola aplikasi ini adalah data barang.

## Daftar Rute

| Rute (`?page=`) | File | Keterangan |
|---|---|---|
| `dashboard` (default) | `views/dashboard.php` | Ringkasan stok, nilai stok, barang terbaru |
| `barang/list` | `modules/barang/list.php` | Daftar barang |
| `barang/add` | `modules/barang/add.php` | Form + proses tambah barang |
| `barang/edit&id=N` | `modules/barang/edit.php` | Form + proses ubah barang |
| `barang/delete&id=N` | `modules/barang/delete.php` | Konfirmasi (GET) dan hapus (POST) |
| `profil` | `views/profil.php` | Tentang aplikasi |
| `kontak` | `modules/kontak/form.php` | Form kontak dengan validasi email |
| selain di atas | `views/404.php` | Status HTTP 404 |

## Pemetaan File Lama ke Struktur Baru

| File lama | File baru |
|---|---|
| `koneksi.php` | `config/database.php` |
| `index.php` (tabel data) | `modules/barang/list.php` |
| `tambah.php` | `modules/barang/add.php` + `modules/barang/_form.php` |
| `ubah.php` | `modules/barang/edit.php` + `modules/barang/_form.php` |
| `hapus.php` | `modules/barang/delete.php` |
| `style.css` | `assets/css/style.css` |
| `gambar/` | `assets/gambar/` |
| HTML `<head>`/penutup di tiap file | `views/header.php`, `views/footer.php` |
| function `is_select()` | `isSelected()` di `helpers/functions.php` |
| — (baru) | `index.php` sebagai router |

## Langkah Praktikum

1. Menyalin project CRUD praktikum sebelumnya menjadi project baru `Lab9Web`.
2. Membuat direktori `config`, `views`, `helpers`, `modules`, dan `assets`.
3. Memindahkan koneksi database ke `config/database.php` dan memuatnya dengan `require_once`.
4. Memindahkan awal dokumen HTML + navigasi ke `views/header.php` dan penutupnya ke `views/footer.php`.
5. Memindahkan function umum ke `helpers/functions.php`.
6. Mengelompokkan halaman CRUD ke `modules/barang/`.
7. Membuat `index.php` sebagai router dan mendaftarkan seluruh rute.
8. Mengubah semua tautan menjadi pola `index.php?page=...` lewat function `url()`.
9. Menguji setiap fitur CRUD dan routing.

## Penerapan Materi

### Template
Router memuat `views/header.php` dan `views/footer.php` sekali untuk setiap halaman. File modul
hanya berisi konten utamanya dan mengisi variabel `$pageTitle` untuk judul tab.

### Function (`helpers/functions.php`)

| Function | Kegunaan | Dipakai di |
|---|---|---|
| `formatRupiah(float $angka): string` | 2400000 → `Rp 2.400.000` | dashboard, list, delete |
| `hitungDiskon(float $harga, float $persen = 10): float` | besar potongan harga | dashboard |
| `validasiEmail(string $email): bool` | cek format email | kontak |
| `ringkasTeks(string $teks, int $batas = 50): string` | potong teks panjang | dashboard, kontak |
| `e(?string $nilai): string` | escaping HTML (cegah XSS) | semua halaman |
| `validasiBarang(array $data): array` | validasi isian barang, kembalikan daftar error | add, edit |
| `uploadGambar(array $file): array` | cek tipe/ukuran gambar lalu simpan | add, edit |
| `url()`, `asset()`, `redirect()` | membuat URL rute / aset dan pindah halaman | semua halaman |
| `navLink()` | menu dengan penanda halaman aktif | header |
| `setFlash()`, `getFlash()` | pesan sukses/gagal setelah operasi CRUD | modul + header |
| `csrfField()`, `verifyCsrf()` | token pengaman form POST | semua form |

`helpers/db.php` berisi `dbFetchAll()`, `dbFetchOne()`, dan `dbExecute()` yang menjalankan query
dengan prepared statement MySQLi.

### Routing
- `$_GET['page']` hanya dicocokkan dengan kunci array `$routes`; tidak pernah digabung menjadi path file.
- Parameter kosong diarahkan ke dashboard; parameter tidak terdaftar atau bukan string (`page[]=x`)
  menghasilkan halaman 404 dengan status HTTP 404.
- Setiap file selain `index.php` memeriksa konstanta `APP_ROOT`, sehingga tidak bisa dibuka langsung
  tanpa melalui router.

### Tantangan mandiri yang dikerjakan
- Menu aktif sesuai rute yang dibuka.
- Pesan sukses/gagal (flash message) setelah tambah, ubah, dan hapus.
- URL rewrite lewat `.htaccess` (opsional, lihat bagian Cara Menjalankan).

### Keamanan tambahan
- Semua query memakai prepared statement (sebelumnya nilai `$_GET`/`$_POST` langsung masuk ke SQL).
- Hapus data tidak lagi lewat tautan GET: tautan **Hapus** membuka halaman konfirmasi, dan data
  baru dihapus setelah form POST dengan token CSRF dikirim.
- Upload hanya menerima file gambar asli (JPG/PNG/GIF/WEBP, maks. 2 MB) dan diberi nama unik.
- Semua data yang ditampilkan di-escape dengan `e()`.

## Pengujian

| Uji | Hasil yang diharapkan |
|---|---|
| `index.php` | Dashboard tampil |
| `index.php?page=barang/list` | Tabel data barang tampil |
| `index.php?page=barang/add` → isi form → Simpan | Kembali ke daftar, pesan sukses, data bertambah |
| Simpan form dengan isian kosong / harga huruf | Form tampil lagi dengan pesan error per kolom |
| `index.php?page=barang/edit&id=1` → ubah → Simpan | Data berubah; gambar lama tetap bila tidak memilih file |
| Klik **Hapus** → **Ya, hapus** | Data terhapus, pesan sukses |
| `index.php?page=admin/rahasia` | Halaman 404 |
| `index.php?page=` dan `index.php?page[]=x` | Dashboard / 404, tanpa error PHP |
| Buka `modules/barang/list.php` langsung | "Akses langsung tidak diizinkan." |

### Screenshot

Tambahkan screenshot ke folder `screenshots/` lalu tautkan di sini, misalnya:

```
![Struktur direktori](screenshots/struktur.png)
![Daftar barang](screenshots/list.png)
![Tambah barang](screenshots/tambah.png)
![Ubah barang](screenshots/ubah.png)
![Konfirmasi hapus](screenshots/hapus.png)
![Halaman 404](screenshots/404.png)
```

## Kendala dan Solusi

| Kendala | Solusi |
|---|---|
| `Cannot modify header information - headers already sent` saat redirect setelah simpan, karena header template sudah tercetak sebelum modul memanggil `header('Location: ...')` | Router menjalankan modul lebih dulu di dalam `ob_start()`, menampung keluarannya, lalu baru mencetak header + konten + footer |
| CSS dan gambar tidak tampil saat URL berubah (misalnya memakai URL ringkas) | Semua path dibuat dengan `asset()` dan `url()` yang berbasis `BASE_URL` |
| Sebagian data lama menyimpan nama gambar dengan awalan `gambar/` | `gambarUrl()` memakai `basename()` sehingga kedua format tetap tampil |
| Data bisa terhapus hanya dengan membuka URL `hapus.php?id=...` | Hapus dipindah ke form POST + token CSRF dengan halaman konfirmasi |
| Pada PHP 8.1+, kegagalan koneksi MySQLi berupa exception sehingga `if ($conn == false)` tidak pernah tercapai | Koneksi dibungkus `try/catch (mysqli_sql_exception)` |
