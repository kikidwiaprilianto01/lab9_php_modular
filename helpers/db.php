<?php
/**
 * helpers/db.php — function bantu untuk query database.
 *
 * Semua query memakai prepared statement: nilai dari pengguna dikirim
 * terpisah dari teks SQL, sehingga aman dari SQL injection.
 * Contoh:
 *     dbFetchOne($conn, 'SELECT * FROM data_barang WHERE id_barang = ?', [$id]);
 */
if (!defined('APP_ROOT')) {
    exit('Akses langsung tidak diizinkan.');
}

/**
 * Menyiapkan dan menjalankan query, lalu mengembalikan statement-nya.
 */
function dbQuery(mysqli $conn, string $sql, array $params = []): mysqli_stmt
{
    $stmt = mysqli_prepare($conn, $sql);

    if ($params) {
        $params = array_values($params);
        $types  = '';
        foreach ($params as $nilai) {
            // i = integer, d = desimal, s = string
            $types .= is_int($nilai) ? 'i' : (is_float($nilai) ? 'd' : 's');
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    return $stmt;
}

/**
 * Mengambil semua baris hasil SELECT sebagai array asosiatif.
 */
function dbFetchAll(mysqli $conn, string $sql, array $params = []): array
{
    $stmt   = dbQuery($conn, $sql, $params);
    $result = mysqli_stmt_get_result($stmt);
    $rows   = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

/**
 * Mengambil satu baris hasil SELECT, atau null jika tidak ada.
 */
function dbFetchOne(mysqli $conn, string $sql, array $params = []): ?array
{
    $stmt   = dbQuery($conn, $sql, $params);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ?: null;
}

/**
 * Menjalankan INSERT / UPDATE / DELETE, mengembalikan jumlah baris yang terpengaruh.
 */
function dbExecute(mysqli $conn, string $sql, array $params = []): int
{
    $stmt     = dbQuery($conn, $sql, $params);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected;
}
