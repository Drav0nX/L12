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

$log_dir = __DIR__ . '/logs';
$log_file = $log_dir . '/audit.log';
$logs = [];
$log_dir_exists = is_dir($log_dir);

if (file_exists($log_file)) {
    $file = new SplFileObject($log_file, 'r');
    $file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);
    foreach ($file as $line) {
        if ($line === null || $line === '') {
            continue;
        }
        $entry = json_decode($line, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($entry)) {
            $logs[] = $entry;
        }
    }
}

$logs = array_reverse($logs);
$total_logs = count($logs);
$per_page = 50;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$total_pages = max(1, (int)ceil($total_logs / $per_page));
if ($page > $total_pages) {
    $page = $total_pages;
}
$offset = ($page - 1) * $per_page;
$logs_page = array_slice($logs, $offset, $per_page);

function format_context($context) {
    if (empty($context) || !is_array($context)) {
        return '-';
    }
    return json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function status_badge($status) {
    $status = strtolower((string)$status);
    $class = 'secondary';
    if ($status === 'success') {
        $class = 'success';
    } elseif ($status === 'fail' || $status === 'error') {
        $class = 'danger';
    } elseif ($status === 'warning') {
        $class = 'warning';
    } elseif ($status === 'info') {
        $class = 'info';
    }

    $safe = htmlspecialchars($status ?: 'info', ENT_QUOTES, 'UTF-8');
    return '<span class="badge bg-' . $class . '">' . $safe . '</span>';
}

function truncate_text($text, $limit = 120) {
    $text = (string)$text;
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return mb_substr($text, 0, $limit) . '…';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Audit Log - Sistem Manajemen Data Produk">
    <title>Audit Log - Sistem Manajemen Produk</title>
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
        .table-responsive {
            max-height: 70vh;
        }
        .context-cell {
            max-width: 360px;
            white-space: normal;
            word-break: break-word;
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
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Data Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah_produk.php">Tambah Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cetak_laporan.php">Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="audit_log.php" aria-current="page">Audit Log</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
            <div>
                <h4 class="text-danger mb-1">Audit Log</h4>
                <small class="text-muted">Menampilkan aktivitas login dan perubahan data.</small>
            </div>
            <div>
                <span class="badge bg-dark">Total: <?= number_format($total_logs, 0, ',', '.'); ?></span>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <?php if (!$log_dir_exists): ?>
                    <div class="alert alert-info mb-0">
                        Folder log belum tersedia. Buat folder <strong>logs</strong> di root aplikasi untuk mulai menyimpan audit log.
                    </div>
                <?php elseif (empty($logs_page)): ?>
                    <div class="alert alert-warning mb-0">Belum ada data audit log.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Status</th>
                                    <th>IP</th>
                                    <th>User Agent</th>
                                    <th>Context</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs_page as $entry): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($entry['time'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($entry['username'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <div class="text-muted small">ID: <?= htmlspecialchars((string)($entry['user_id'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($entry['action'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= status_badge($entry['status'] ?? 'info'); ?></td>
                                        <td><?= htmlspecialchars($entry['ip'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td title="<?= htmlspecialchars($entry['user_agent'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>">
                                            <?= htmlspecialchars(truncate_text($entry['user_agent'] ?? '-', 80), ENT_QUOTES, 'UTF-8'); ?>
                                        </td>
                                        <?php $context_text = format_context($entry['context'] ?? []); ?>
                                        <td class="context-cell" title="<?= htmlspecialchars($context_text, ENT_QUOTES, 'UTF-8'); ?>">
                                            <code><?= htmlspecialchars(truncate_text($context_text, 120), ENT_QUOTES, 'UTF-8'); ?></code>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_pages > 1): ?>
                        <nav class="mt-3" aria-label="Audit log pagination">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?= max(1, $page - 1); ?>">Sebelumnya</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i === $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?= min($total_pages, $page + 1); ?>">Berikutnya</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
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
