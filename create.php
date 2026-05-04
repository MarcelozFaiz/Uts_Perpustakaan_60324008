<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    session_start();
    require_once 'config/database.php';
        
    $errors = [];
    $kode = '';
    $nama = '';
    $deskripsi = '';
    $status = 'Aktif';
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil dan sanitasi data dari form
        $kode = strtoupper(trim($_POST['kode'] ?? ''));
        $nama = trim($_POST['nama'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $status = $_POST['status'] ?? 'Aktif';

        // Validasi kode kategori
        if (empty($kode)) {
            $errors['kode'] = "Kode kategori wajib diisi.";
        } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
            $errors['kode'] = "Kode harus memiliki panjang 4-10 karakter.";
        } elseif (substr($kode, 0, 4) !== "KAT-") {
            $errors['kode'] = "Kode harus diawali dengan 'KAT-'.";
        }

        // Validasi nama kategori
        if (empty($nama)) {
            $errors['nama'] = "Nama kategori wajib diisi.";
        } elseif (strlen($nama) < 3 || strlen($nama) > 50) {
            $errors['nama'] = "Nama minimal 3 dan maksimal 50 karakter.";
        }

        // Validasi deskripsi
        if (!empty($deskripsi) && strlen($deskripsi) > 200) {
            $errors['deskripsi'] = "Deskripsi maksimal 200 karakter.";
        }

        // Validasi Status
        if (!in_array($status, ['Aktif', 'Nonaktif'])) {
            $errors['status'] = "Status tidak valid.";
        }

        // Cek duplikasi kode (Hanya dilakukan jika belum ada error pada field kode)
        if (!isset($errors['kode'])) {
            $stmt_check = $conn->prepare("SELECT kode_kategori FROM kategori WHERE kode_kategori = ?");
            $stmt_check->bind_param("s", $kode);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $errors['kode'] = "Kode kategori sudah terdaftar.";
            }
            $stmt_check->close();
        }

        // Jika tidak ada error, insert data
        if (empty($errors)) {
            // Sanitasi output untuk keamanan HTML
            $nama_safe = htmlspecialchars($nama);
            $desk_safe = htmlspecialchars($deskripsi);

            $stmt_insert = $conn->prepare("INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $kode, $nama_safe, $desk_safe, $status);

            if ($stmt_insert->execute()) {
                $_SESSION['success'] = "Kategori berhasil ditambahkan!";
                header("Location: index.php");
                exit();
            } else {
                $errors['db'] = "Gagal menyimpan ke database: " . $conn->error;
            }
            $stmt_insert->close();
        }
    }
    ?>
        
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah Kategori Baru</h4>
                    </div>
                    <div class="card-body">
                        <!-- Tampilkan error global jika ada kegagalan DB -->
                        <?php if (isset($errors['db'])): ?>
                            <div class="alert alert-danger"><?= $errors['db'] ?></div>
                        <?php endif; ?>
                                                
                        <form method="POST">
                            <!-- Kode Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Kode Kategori</label>
                                <input type="text" name="kode" 
                                       class="form-control <?= isset($errors['kode']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($kode) ?>" placeholder="Contoh: KAT-001">
                                <div class="invalid-feedback"><?= $errors['kode'] ?? '' ?></div>
                            </div>

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="nama" 
                                       class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($nama) ?>">
                                <div class="invalid-feedback"><?= $errors['nama'] ?? '' ?></div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label class="form-label">Deskripsi (Opsional)</label>
                                <textarea name="deskripsi" 
                                          class="form-control <?= isset($errors['deskripsi']) ? 'is-invalid' : '' ?>" 
                                          rows="3"><?= htmlspecialchars($deskripsi) ?></textarea>
                                <div class="invalid-feedback"><?= $errors['deskripsi'] ?? '' ?></div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusAktif" value="Aktif" 
                                           <?= $status == 'Aktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusAktif">Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusNon" value="Nonaktif" 
                                           <?= $status == 'Nonaktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusNon">Nonaktif</label>
                                </div>
                                <?php if(isset($errors['status'])): ?>
                                    <div class="text-danger small"><?= $errors['status'] ?></div>
                                <?php endif; ?>
                            </div>
                                                        
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                                <a href="index.php" class="btn btn-outline-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>