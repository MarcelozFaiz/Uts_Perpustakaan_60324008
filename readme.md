# Aplikasi Manajemen Kategori Buku - UTS Pemrograman Web

Aplikasi web sederhana berbasis PHP Native untuk mengelola kategori buku dalam sistem perpustakaan atau toko buku. Proyek ini dibuat sebagai syarat pemenuhan tugas UTS.

## 👤 Identitas Mahasiswa
*   Nama: Muhammad Faiz
*   Nim: 60324008

---

## 📝 Deskripsi Aplikasi
Aplikasi ini merupakan sistem CRUD (*Create, Read, Update, Delete*) kategori buku yang mencakup:
* Daftar Kategori: Menampilkan data kategori dengan status aktif/nonaktif.
* Tambah Kategori: Form input dengan validasi kode kategori otomatis (prefix "KAT-") dan cek duplikasi.
* Edit Kategori: Memperbarui informasi kategori yang sudah ada.
* Hapus Kategori: Menghapus data kategori dengan konfirmasi keamanan.
* Validasi Keamanan: Menggunakan *Prepared Statements* untuk mencegah SQL Injection dan sanitasi input untuk mencegah XSS.

---

# Cara Instalasi dan Menjalankan Aplikasi

# 1. Prasyarat
*   Laragon atau XAMPP terinstal (PHP >= 7.4).
*   MySQL/MariaDB.

# 2. Persiapan Database
1.  Buka **phpMyAdmin**.
2.  Buat database baru dengan nama `uts_perpustakaan_60324008` (atau sesuaikan dengan file `config/database.php`).
3.  Impor tabel kategori melalui menu SQL:
    ```sql
    CREATE TABLE kategori (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kode VARCHAR(10) NOT NULL UNIQUE,
        nama_kategori VARCHAR(50) NOT NULL,
        deskripsi TEXT,
        status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif'
    );
    ```

### 3. Konfigurasi Koneksi
1.  Buka file `config/database.php`.
2.  Sesuaikan *host*, *username*, *password*, dan *dbname* dengan pengaturan server lokal Anda.

### 4. Menjalankan Aplikasi
1.  Letakkan folder proyek di dalam direktori `C:/laragon/www/` atau `C:/xampp/htdocs/`.
2.  Pastikan server Apache dan MySQL sudah menyala.
3.  Akses melalui browser di alamat: `http://localhost/nama-folder-anda/index.php`.

---

## 📂 Struktur Folder
```text
PROYEK-UTS/
├── config/
│   └── database.php      # Koneksi database MySQLi
├── index.php             # Halaman utama (Daftar Kategori)
├── create.php            # Halaman tambah data & logika insert
├── edit.php              # Halaman edit data & logika update
├── delete.php            # Logika penghapusan data
└── Readme.md             # Dokumentasi proyek