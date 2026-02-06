<?php
$secure_cookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure_cookie,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('koneksi.php');

$total_produk = 0;
$total_nilai_beli = 0;
$total_nilai_jual = 0;
$produk_terbaru = [];
$profit = 0;
$profit_percentage = 0;

try {
    $query = "SELECT COUNT(*) as total FROM produk";
    $result = $koneksi->query($query);
    $total_produk = $result->fetch_assoc()['total'];
    
    $query = "SELECT SUM(harga_beli) as total_beli, SUM(harga_jual) as total_jual FROM produk";
    $result = $koneksi->query($query);
    $values = $result->fetch_assoc();
    $total_nilai_beli = $values['total_beli'] ?? 0;
    $total_nilai_jual = $values['total_jual'] ?? 0;
    $profit = $total_nilai_jual - $total_nilai_beli;
    $profit_percentage = $total_nilai_beli > 0 ? ($profit / $total_nilai_beli) * 100 : 0;
    
    $query = "SELECT * FROM produk ORDER BY id DESC LIMIT 5";
    $result = $koneksi->query($query);
    while ($row = $result->fetch_assoc()) {
        $produk_terbaru[] = $row;
    }
} catch (Exception $e) {
    error_log('Dashboard query error: ' . $e->getMessage());
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard - Sistem Manajemen Data Produk">
    <title>Dashboard - Sistem Manajemen Produk</title>
    <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 90px;
        }
        .fixed-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
        }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .quick-action {
            transition: all 0.3s;
        }
        .quick-action:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
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
                        <a class="nav-link active" href="home.php" aria-current="page">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Data Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cetak_laporan.php">Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="audit_log.php">Audit Log</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="welcome-card shadow">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>! 👋</h2>
            <p class="mb-0">Dashboard Sistem Manajemen Data Produk</p>
            <small>Login terakhir: <?= date('d F Y, H:i', $_SESSION['login_time']); ?></small>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5>Total Produk</h5>
                    <h3><?= number_format($total_produk, 0, ',', '.'); ?></h3>
                    <p class="mb-0">Produk terdaftar</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card shadow" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h5>Total Nilai Beli</h5>
                    <h3>Rp <?= number_format($total_nilai_beli, 0, ',', '.'); ?></h3>
                    <p class="mb-0">Modal investasi</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card shadow" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h5>Total Nilai Jual</h5>
                    <h3>Rp <?= number_format($total_nilai_jual, 0, ',', '.'); ?></h3>
                    <p class="mb-0">Potensi pendapatan</p>
                </div>
            </div>
        </div>

        <div class="alert alert-info shadow">
            <h5>💰 Informasi Keuntungan</h5>
            <p class="mb-1">
                Potensi Keuntungan: 
                <strong class="text-success">Rp <?= number_format($profit, 0, ',', '.'); ?></strong>
                (<?= number_format($profit_percentage, 1); ?>%)
            </p>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">⚡ Quick Actions</h5>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="index.php" class="btn btn-primary w-100 quick-action">
                            📦 Lihat Produk
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="tambah_produk.php" class="btn btn-success w-100 quick-action">
                            ➕ Tambah Produk
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="cetak_laporan.php" class="btn btn-info w-100 quick-action">
                            📊 Laporan
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="audit_log.php" class="btn btn-secondary w-100 quick-action">
                            🧾 Audit Log
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="logout.php" class="btn btn-danger w-100 quick-action" onclick="return confirm('Yakin ingin logout?')">
                            🚪 Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-3">🆕 Produk Terbaru</h5>
                <?php if (empty($produk_terbaru)): ?>
                    <p class="text-muted">Belum ada produk. <a href="tambah_produk.php">Tambah produk pertama</a></p>
                <?php else: ?>
                    <div class="row g-3 mb-4">
                        <?php foreach ($produk_terbaru as $produk):
                            $image_path = 'gambar/' . htmlspecialchars($produk['gambar_produk'], ENT_QUOTES, 'UTF-8');
                            $image_exists = !empty($produk['gambar_produk']) && file_exists($image_path);
                        ?>
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <?php if ($image_exists): ?>
                                        <img src="<?= $image_path; ?>" class="card-img-top" alt="<?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8'); ?>" style="height: 180px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 180px;">
                                            <span class="text-muted">No Image</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h6 class="card-title mb-1"><?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                        <p class="card-text text-muted small mb-2">
                                            <?php
                                            $desc = $produk['deskripsi'] ?? '';
                                            echo htmlspecialchars(
                                                mb_strlen($desc) > 60 ? mb_substr($desc, 0, 60) . '...' : $desc,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-success">Rp <?= number_format($produk['harga_jual'], 0, ',', '.'); ?></span>
                                            <a href="edit_produk.php?id=<?= (int)$produk['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Profit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produk_terbaru as $produk): 
                                    $profit_item = $produk['harga_jual'] - $produk['harga_beli'];
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </td>
                                        <td>Rp <?= number_format($produk['harga_beli'], 0, ',', '.'); ?></td>
                                        <td>Rp <?= number_format($produk['harga_jual'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge bg-success">
                                                Rp <?= number_format($profit_item, 0, ',', '.'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit_produk.php?id=<?= $produk['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="index.php" class="btn btn-outline-primary">Lihat Semua Produk</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-danger text-white text-center py-3 fixed-footer">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y'); ?> Sistem Manajemen Produk | Muhammad Ridho Novriandra</p>
        </div>
    </footer>

    <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
