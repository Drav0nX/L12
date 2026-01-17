<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('koneksi.php');

// Get filter parameters
$filter_type = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$produk_list = [];
$total_beli = 0;
$total_jual = 0;

try {
    $query = "SELECT * FROM produk ORDER BY nama_produk ASC";
    $result = $koneksi->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $produk_list[] = $row;
        $total_beli += $row['harga_beli'];
        $total_jual += $row['harga_jual'];
    }
} catch (Exception $e) {
    error_log('Report query error: ' . $e->getMessage());
}

$total_profit = $total_jual - $total_beli;
$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Laporan Produk - Sistem Manajemen Data Produk">
    <title>Laporan Produk - Sistem Manajemen Produk</title>
    <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 60px;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6;
            }
        }
        
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        
        .summary-box {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow no-print">
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
                        <a class="nav-link active" href="cetak_laporan.php" aria-current="page">Laporan</a>
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
        <div class="card shadow">
            <div class="card-body">
                <!-- Report Header -->
                <div class="report-header">
                    <h2 class="mb-2">📊 LAPORAN DATA PRODUK</h2>
                    <p class="mb-1">Sistem Manajemen Data Produk</p>
                    <small>Dicetak pada: <?= date('d F Y, H:i:s'); ?></small>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mb-4 no-print">
                    <a href="index.php" class="btn btn-secondary">
                        ← Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-danger">
                        🖨️ Cetak Laporan
                    </button>
                </div>

                <!-- Summary Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="summary-box bg-primary text-white">
                            <h6>Total Produk</h6>
                            <h3><?= count($produk_list); ?> Produk</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-box bg-warning text-dark">
                            <h6>Total Modal (Harga Beli)</h6>
                            <h3>Rp <?= number_format($total_beli, 0, ',', '.'); ?></h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-box bg-success text-white">
                            <h6>Total Potensi (Harga Jual)</h6>
                            <h3>Rp <?= number_format($total_jual, 0, ',', '.'); ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Profit Info -->
                <div class="alert alert-info">
                    <h5 class="mb-2">💰 Analisis Keuntungan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Total Potensi Keuntungan:</strong><br>
                            <span class="h4 text-success">Rp <?= number_format($total_profit, 0, ',', '.'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Persentase Profit:</strong><br>
                            <span class="h4 text-success">
                                <?= $total_beli > 0 ? number_format(($total_profit / $total_beli) * 100, 2) : 0; ?>%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Product Table -->
                <h5 class="mb-3">Detail Produk</h5>
                
                <?php if (empty($produk_list)): ?>
                    <div class="alert alert-warning">
                        Belum ada data produk untuk ditampilkan.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Harga Beli</th>
                                    <th class="text-end">Harga Jual</th>
                                    <th class="text-end">Profit</th>
                                    <th class="text-center">Margin %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($produk_list as $produk): 
                                    $profit = $produk['harga_jual'] - $produk['harga_beli'];
                                    $margin = $produk['harga_beli'] > 0 ? ($profit / $produk['harga_beli']) * 100 : 0;
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><strong><?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                        <td>
                                            <?php
                                            $desc = $produk['deskripsi'];
                                            echo htmlspecialchars(
                                                mb_strlen($desc) > 50 ? mb_substr($desc, 0, 50) . '...' : $desc,
                                                ENT_QUOTES, 
                                                'UTF-8'
                                            );
                                            ?>
                                        </td>
                                        <td class="text-end">Rp <?= number_format($produk['harga_beli'], 0, ',', '.'); ?></td>
                                        <td class="text-end">Rp <?= number_format($produk['harga_jual'], 0, ',', '.'); ?></td>
                                        <td class="text-end text-success">
                                            <strong>Rp <?= number_format($profit, 0, ',', '.'); ?></strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= number_format($margin, 1); ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="3" class="text-end">TOTAL:</th>
                                    <th class="text-end">Rp <?= number_format($total_beli, 0, ',', '.'); ?></th>
                                    <th class="text-end">Rp <?= number_format($total_jual, 0, ',', '.'); ?></th>
                                    <th class="text-end text-success">
                                        <strong>Rp <?= number_format($total_profit, 0, ',', '.'); ?></strong>
                                    </th>
                                    <th class="text-center">
                                        <span class="badge bg-success">
                                            <?= $total_beli > 0 ? number_format(($total_profit / $total_beli) * 100, 1) : 0; ?>%
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Report Footer -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Dicetak oleh:</strong> <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Tanggal:</strong> <?= date('d F Y'); ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-1"><strong>Total Produk:</strong> <?= count($produk_list); ?></p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Valid</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-danger text-white text-center py-3 mt-5 no-print">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y'); ?> Sistem Manajemen Produk | Muhammaf Ridho Novriandra</p>
        </div>
    </footer>

    <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
