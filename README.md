Nama: Muhammad Faiz
Nim: 60324008

Deskripsi Aplikasi
Aplikasi ini merupakan sistem CRUD (*Create, Read, Update, Delete*) kategori buku yang mencakup:
1. Daftar Kategori: Menampilkan data kategori dengan status aktif/nonaktif.
2. Tambah Kategori: Form input dengan validasi kode kategori otomatis (prefix "KAT-") dan cek duplikasi.
3. Edit Kategori: Memperbarui informasi kategori yang sudah ada.
4. Hapus Kategori: Menghapus data kategori dengan konfirmasi keamanan.
5. Validasi Keamanan: Menggunakan *Prepared Statements* untuk mencegah SQL Injection dan sanitasi input

Cara Instalasi dan Menjalankan Aplikasi

1. Prasyarat
*   Laragon atau XAMPP terinstal .
*   MySQL/MariaDB.

 2. Persiapan Database
1.  Buka phpMyAdmin.
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

3. Konfigurasi Koneksi
1.  Buka file `config/database.php`.
2.  Sesuaikan *host*, *username*, *password*, dan *dbname* dengan pengaturan server lokal Anda.

 4. Menjalankan Aplikasi
1.  Letakkan folder proyek di dalam direktori `D:/laragon/www/` atau `C:/xampp/htdocs/`.
2.  Pastikan server Apache dan MySQL sudah menyala.
3.  Akses melalui browser di alamat: `http://localhost/nama-folder-anda/index.php`.

---

📂 Struktur Folder

uts_perpustakaan_60324008/
├── config/
│   └── database.php      # Koneksi database MySQLi
├── index.php             # Halaman utama (Daftar Kategori)
├── create.php            # Halaman tambah data & logika insert
├── edit.php              # Halaman edit data & logika update
├── delete.php            # Logika penghapusan data



# Uts_Perpustakaan_60324008
Ini merupakan sebuah projek uts pemrograman web 2 yang menyangkut tentang Create Read Update Delete dengan menggunakan Database Laragon
