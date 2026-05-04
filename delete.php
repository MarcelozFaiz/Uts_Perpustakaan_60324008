<?php
session_start();
require_once 'config/database.php';

// A. Validasi ID dari GET
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "ID Kategori tidak ditemukan.";
    header("Location: index.php");
    exit();
}

// B. Cek keberadaan data & Proses Delete
// Kita langsung melakukan DELETE dengan Prepared Statement
$stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);

try {
    if ($stmt->execute()) {
        // C. Cek affected_rows untuk memastikan data benar-benar terhapus
        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Kategori berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus: Data tidak ditemukan di database.";
        }
    } else {
        $_SESSION['error'] = "Terjadi kesalahan sistem saat menghapus data.";
    }
} catch (mysqli_sql_exception $e) {
    // Menangani error jika data sedang digunakan oleh tabel lain (Foreign Key Constraint)
    $_SESSION['error'] = "Data tidak bisa dihapus karena sedang digunakan oleh data lain.";
}

$stmt->close();

// D. Redirect dengan pesan
header("Location: index.php");
exit();
?>