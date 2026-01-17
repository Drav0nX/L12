<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('koneksi.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = trim($_POST['nama_produk'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga_beli = trim($_POST['harga_beli'] ?? '');
    $harga_jual = trim($_POST['harga_jual'] ?? '');
    
    // Validation
    if (empty($nama_produk)) {
        $error = 'Nama produk harus diisi!';
    } elseif (empty($harga_beli) || !is_numeric($harga_beli)) {
        $error = 'Harga beli harus berupa angka!';
    } elseif (empty($harga_jual) || !is_numeric($harga_jual)) {
        $error = 'Harga jual harus berupa angka!';
    } elseif ($harga_jual < $harga_beli) {
        $error = 'Harga jual tidak boleh lebih kecil dari harga beli!';
    } else {
        // Handle image upload
        $gambar_produk = '';
        
        // Check if gambar folder exists, create if not
        $gambar_dir = __DIR__ . '/gambar';
        if (!is_dir($gambar_dir)) {
            if (!@mkdir($gambar_dir, 0755, true)) {
                $error = 'Folder gambar tidak dapat dibuat. Hubungi admin!';
            }
        }
        
        // Make sure folder is writable - try to fix permission
        if (empty($error)) {
            if (!is_writable($gambar_dir)) {
                // Try to fix permission
                @chmod($gambar_dir, 0777);
                
                // Check again
                if (!is_writable($gambar_dir)) {
                    $error = 'Folder gambar tidak dapat ditulis (permission denied). <a href="fix_permission.php" style="color: #dc3545; font-weight: bold;">Fix Permission</a> atau coba manual: <code style="color: #666;">chmod 777 /Applications/XAMPP/xamppfiles/htdocs/L12/gambar</code>';
                }
            }
        }
        
        if (empty($error) && isset($_FILES['gambar_produk'])) {
            $file = $_FILES['gambar_produk'];
            
            // Check for upload errors
            if ($file['error'] != 0) {
                $error_messages = [
                    1 => 'File terlalu besar (max: ' . ini_get('upload_max_filesize') . ')',
                    2 => 'File terlalu besar',
                    3 => 'File hanya terupload sebagian',
                    4 => 'Pilih file terlebih dahulu',
                    6 => 'Folder temporary tidak ada',
                    7 => 'Gagal menulis file ke disk',
                    8 => 'Extension PHP menghentikan upload',
                ];
                $error = 'Error upload: ' . ($error_messages[$file['error']] ?? 'Error code ' . $file['error']);
            } elseif ($file['size'] == 0) {
                $error = 'File kosong!';
            } elseif ($file['size'] > 5242880) { // 5MB
                $error = 'File terlalu besar! Max 5MB.';
            } else {
                // Check file type
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $file['name'];
                $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $mime_type = mime_content_type($file['tmp_name']);
                
                // Validate extension
                if (!in_array($file_ext, $allowed)) {
                    $error = 'Format gambar tidak valid! Gunakan JPG, JPEG, PNG, atau GIF.';
                } 
                // Validate MIME type
                elseif (!in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                    $error = 'File bukan gambar yang valid!';
                }
                // Upload file
                else {
                    $new_filename = 'prod_' . uniqid() . '_' . time() . '.' . $file_ext;
                    $upload_path = $gambar_dir . '/' . $new_filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                        $gambar_produk = $new_filename;
                        chmod($upload_path, 0644);
                    } else {
                        $error = 'Gagal menyimpan gambar! <a href="check_upload.php">Check upload settings</a>';
                    }
                }
            }
        }
        
        // Insert to database if no error
        if (empty($error)) {
            try {
                $query = "INSERT INTO produk (nama_produk, deskripsi, harga_beli, harga_jual, gambar_produk) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $koneksi->prepare($query);
                $stmt->bind_param('ssdds', $nama_produk, $deskripsi, $harga_beli, $harga_jual, $gambar_produk);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Produk berhasil ditambahkan!';
                    header('Location: index.php');
                    exit();
                } else {
                    $error = 'Gagal menambahkan produk!';
                }
                $stmt->close();
            } catch (Exception $e) {
                error_log('Insert error: ' . $e->getMessage());
                $error = 'Terjadi kesalahan saat menambahkan produk!';
            }
        }
    }
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tambah Produk - Sistem Manajemen Data Produk">
    <title>Tambah Produk - Sistem Manajemen Produk</title>
    <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 60px;
        }
        .form-label {
            font-weight: 600;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="home.php" title="Beranda">
                <img src="./gambar/logo.png" alt="Logo" height="40" class="me-2 rounded" onerror="this.style.display='none'">
                <strong>Sistem Manajemen Produk</strong>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Data Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cetak_laporan.php">Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">➕ Tambah Produk Baru</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                                       placeholder="Masukkan nama produk" required
                                       value="<?= isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" 
                                          placeholder="Masukkan deskripsi produk"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="harga_beli" class="form-label">Harga Beli (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" 
                                           placeholder="0" min="0" step="1000" required
                                           value="<?= isset($_POST['harga_beli']) ? htmlspecialchars($_POST['harga_beli'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="harga_jual" class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" 
                                           placeholder="0" min="0" step="1000" required
                                           value="<?= isset($_POST['harga_jual']) ? htmlspecialchars($_POST['harga_jual'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="gambar_produk" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" id="gambar_produk" name="gambar_produk" 
                                       accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted">Format: JPG, JPEG, PNG, GIF (Max 2MB)</small>
                                <img id="imagePreview" class="preview-image img-thumbnail" alt="Preview">
                            </div>

                            <div class="alert alert-info">
                                <small><strong>Info:</strong> Pastikan harga jual lebih besar dari harga beli untuk mendapatkan profit.</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger">
                                    💾 Simpan Produk
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    ❌ Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-danger text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y'); ?> Sistem Manajemen Produk | Muhammad Ridho Novriandra</p>
        </div>
    </footer>

    <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        // Auto-calculate profit
        const hargaBeli = document.getElementById('harga_beli');
        const hargaJual = document.getElementById('harga_jual');
        
        function calculateProfit() {
            const beli = parseFloat(hargaBeli.value) || 0;
            const jual = parseFloat(hargaJual.value) || 0;
            const profit = jual - beli;
            
            if (profit < 0) {
                hargaJual.classList.add('is-invalid');
            } else {
                hargaJual.classList.remove('is-invalid');
            }
        }
        
        hargaBeli.addEventListener('input', calculateProfit);
        hargaJual.addEventListener('input', calculateProfit);
    </script>
</body>
</html>
