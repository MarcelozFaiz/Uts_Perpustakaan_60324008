<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    session_start();
    require_once 'config/database.php';

    $errors = [];
    $id = $_GET['id'] ?? null;

    // A. Retrieve Data Berdasarkan ID
    if (!$id) {
        $_SESSION['error'] = "ID Kategori tidak ditemukan.";
        header("Location: index.php");
        exit();
    }

    $stmt_get = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();
    $data = $result->fetch_assoc();

    // Validasi: Cek apakah data ada di database
    if (!$data) {
        $_SESSION['error'] = "Data kategori tidak ditemukan di database.";
        header("Location: index.php");
        exit();
    }
    $stmt_get->close();

    // Inisialisasi variabel untuk form (pre-filled dari DB atau dari POST jika error)
    $kode      = $data['kode_kategori'];
    $nama      = $data['nama_kategori'];
    $deskripsi = $data['deskripsi'];
    $status    = $data['status'];

    // C. Proses Update Jika Ada POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitasi
        $kode      = strtoupper(trim($_POST['kode'] ?? ''));
        $nama      = trim($_POST['nama'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $status    = $_POST['status'] ?? 'Aktif';

        // Validasi Kode
        if (empty($kode)) {
            $errors['kode'] = "Kode wajib diisi.";
        } elseif (substr($kode, 0, 4) !== "KAT-") {
            $errors['kode'] = "Kode harus diawali 'KAT-'.";
        } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
            $errors['kode'] = "Panjang kode 4-10 karakter.";
        } else {
            // Cek Duplikasi (Exclude ID yang sedang diedit)
            $stmt_check = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ? AND id_kategori != ?");
            $stmt_check->bind_param("si", $kode, $id);
            $stmt_check->execute();
            if ($stmt_check->get_result()->num_rows > 0) {
                $errors['kode'] = "Kode sudah digunakan oleh kategori lain.";
            }
            $stmt_check->close();
        }

        // Validasi Nama
        if (empty($nama)) {
            $errors['nama'] = "Nama kategori wajib diisi.";
        } elseif (strlen($nama) < 3 || strlen($nama) > 50) {
            $errors['nama'] = "Nama harus 3-50 karakter.";
        }

        // Validasi Deskripsi
        if (!empty($deskripsi) && strlen($deskripsi) > 200) {
            $errors['deskripsi'] = "Deskripsi maksimal 200 karakter.";
        }

        // D. Eksekusi Update
        if (empty($errors)) {
            $nama_safe = htmlspecialchars($nama);
            $desk_safe = htmlspecialchars($deskripsi);

            $stmt_update = $conn->prepare("UPDATE kategori SET kode_kategori = ?, nama_kategori = ?, deskripsi = ?, status = ? WHERE id_kategori = ?");
            $stmt_update->bind_param("ssssi", $kode, $nama_safe, $desk_safe, $status, $id);

            if ($stmt_update->execute()) {
                $_SESSION['success'] = "Kategori berhasil diperbarui!";
                header("Location: index.php");
                exit();
            } else {
                $errors['db'] = "Gagal memperbarui data: " . $conn->error;
            }
            $stmt_update->close();
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">Edit Kategori</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($errors['db'])): ?>
                            <div class="alert alert-danger"><?= $errors['db'] ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <!-- Kode Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Kode Kategori</label>
                                <input type="text" name="kode" class="form-control <?= isset($errors['kode']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($kode) ?>">
                                <div class="invalid-feedback"><?= $errors['kode'] ?? '' ?></div>
                            </div>

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($nama) ?>">
                                <div class="invalid-feedback"><?= $errors['nama'] ?? '' ?></div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control <?= isset($errors['deskripsi']) ? 'is-invalid' : '' ?>" 
                                          rows="3"><?= htmlspecialchars($deskripsi) ?></textarea>
                                <div class="invalid-feedback"><?= $errors['deskripsi'] ?? '' ?></div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusA" value="Aktif" 
                                           <?= $status == 'Aktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusA">Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusN" value="Nonaktif" 
                                           <?= $status == 'Nonaktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="statusN">Nonaktif</label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">Perbarui Data</button>
                                <a href="index.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>