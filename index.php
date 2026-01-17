<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('koneksi.php');

$limit = 3;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// --- SEARCH FILTER ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Count total records with prepared statement
if (!empty($search)) {
    $count_query = "SELECT COUNT(*) as total FROM produk WHERE nama_produk LIKE ? OR deskripsi LIKE ?";
    $search_param = '%' . $search . '%';
    $stmt = $koneksi->prepare($count_query);
    $stmt->bind_param('ss', $search_param, $search_param);
    $stmt->execute();
    $total_data = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();
} else {
    $count_query = "SELECT COUNT(*) as total FROM produk";
    $result_count = $koneksi->query($count_query);
    $total_data = $result_count->fetch_assoc()['total'];
}

$total_pages = max(1, ceil($total_data / $limit));

// Fetch data with prepared statement
try {
    if (!empty($search)) {
        $query = "SELECT * FROM produk WHERE nama_produk LIKE ? OR deskripsi LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?";
        $search_param = '%' . $search . '%';
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('ssii', $search_param, $search_param, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $query = "SELECT * FROM produk ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
    }
} catch (Exception $e) {
    error_log('Query Error: ' . $e->getMessage());
    die('Terjadi kesalahan saat mengambil data.');
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Data Produk - Kelola produk dengan mudah">
    <meta name="author" content="Muhammaf Ridho Novriandra">
    <title>Data Produk - Sistem Manajemen</title>

    <!-- Bootstrap CSS -->
    <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        body {
            padding-bottom: 80px;
        }
        .img-thumbnail {
            object-fit: cover;
            height: 80px;
        }
        .navbar-brand img {
            transition: transform 0.3s;
        }
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        .table-responsive {
            min-height: 300px;
        }
    </style>
</head>

<body class="bg-light">

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
                        <a class="nav-link active" href="index.php" aria-current="page">Data Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah_produk.php">Tambah Produk</a>
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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">

                <h4 class="text-center text-danger mb-4">Daftar Produk</h4>

                <!-- ACTION -->
                <div class="d-flex flex-column flex-md-row justify-content-between mb-3 gap-2">
                    <a href="tambah_produk.php" class="btn btn-danger">
                        <span aria-hidden="true">+</span> Tambah Produk
                    </a>

                    <form method="get" class="d-flex gap-2" role="search">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari produk..." 
                               value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
                               aria-label="Search products">
                        <button type="submit" class="btn btn-outline-danger" title="Cari">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="index.php" class="btn btn-outline-secondary" title="Reset">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- INFO DISPLAY -->
                <?php if (!empty($search)): ?>
                    <div class="alert alert-info" role="alert">
                        Hasil pencarian untuk: <strong>"<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"</strong>
                        - Ditemukan <?= $total_data; ?> produk
                    </div>
                <?php endif; ?>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-danger text-center">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Produk</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Harga Beli</th>
                                <th scope="col">Harga Jual</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows == 0) {
                                echo "<tr><td colspan='7' class='text-center text-muted py-4'>";
                                echo empty($search) ? "Belum ada data produk" : "Tidak ada hasil yang cocok";
                                echo "</td></tr>";
                            } else {
                                $no = $offset + 1;
                                while ($row = $result->fetch_assoc()) {
                                    $image_path = 'gambar/' . htmlspecialchars($row['gambar_produk'], ENT_QUOTES, 'UTF-8');
                                    $image_exists = !empty($row['gambar_produk']) && file_exists($image_path);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['nama_produk'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $desc = $row['deskripsi'];
                                            echo htmlspecialchars(
                                                mb_strlen($desc) > 50 ? mb_substr($desc, 0, 50) . '...' : $desc,
                                                ENT_QUOTES, 
                                                'UTF-8'
                                            );
                                            ?>
                                        </td>
                                        <td class="text-nowrap">Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td>
                                        <td class="text-nowrap">
                                            <span class="badge bg-success">Rp <?= number_format($row['harga_jual'], 0, ',', '.'); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($image_exists): ?>
                                                <img src="<?= $image_path; ?>" 
                                                     alt="<?= htmlspecialchars($row['nama_produk'], ENT_QUOTES, 'UTF-8'); ?>"
                                                     class="img-thumbnail" 
                                                     width="80"
                                                     loading="lazy">
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="edit_produk.php?id=<?= (int)$row['id']; ?>" 
                                               class="btn btn-sm btn-warning mb-1"
                                               title="Edit produk">
                                                Edit
                                            </a>
                                            <a href="proses_hapus.php?id=<?= (int)$row['id']; ?>" 
                                               class="btn btn-sm btn-danger mb-1"
                                               onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                               title="Hapus produk">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Navigasi halaman" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- Previous Button -->
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" 
                                   href="<?= ($page > 1) ? '?page=' . ($page - 1) . '&search=' . urlencode($search) : '#'; ?>"
                                   aria-label="Previous"
                                   <?= ($page <= 1) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                                    &laquo; Prev
                                </a>
                            </li>

                            <?php
                            // Show limited page numbers
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            // Show first page if not in range
                            if ($start_page > 1):
                            ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1&search=<?= urlencode($search); ?>">1</a>
                                </li>
                                <?php if ($start_page > 2): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" 
                                       href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"
                                       <?= ($i == $page) ? 'aria-current="page"' : ''; ?>>
                                        <?= $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php 
                            // Show last page if not in range
                            if ($end_page < $total_pages):
                            ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $total_pages; ?>&search=<?= urlencode($search); ?>">
                                        <?= $total_pages; ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" 
                                   href="<?= ($page < $total_pages) ? '?page=' . ($page + 1) . '&search=' . urlencode($search) : '#'; ?>"
                                   aria-label="Next"
                                   <?= ($page >= $total_pages) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                                    Next &raquo;
                                </a>
                            </li>
                        </ul>
                        <p class="text-center text-muted small">
                            Halaman <?= $page; ?> dari <?= $total_pages; ?> (Total: <?= $total_data; ?> produk)
                        </p>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-danger text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y'); ?> Data Produk | Muhammaf Ridho Novriandra</p>
            <small>Sistem Manajemen Produk v1.0</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }, 5000);
            });
        });
    </script>
</body>

</html>
<?php
// Close database connection
if (isset($stmt)) $stmt->close();
if (isset($koneksi)) $koneksi->close();
?>