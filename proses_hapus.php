<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once('koneksi.php');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $_SESSION['error'] = 'ID produk tidak valid!';
    header('Location: index.php');
    exit();
}
try {
    $query = "SELECT gambar_produk FROM produk WHERE id = ? LIMIT 1";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $produk = $result->fetch_assoc();
        $delete_query = "DELETE FROM produk WHERE id = ?";
        $delete_stmt = $koneksi->prepare($delete_query);
        $delete_stmt->bind_param('i', $id);
        if ($delete_stmt->execute()) {
            if (!empty($produk['gambar_produk']) && file_exists('gambar/' . $produk['gambar_produk'])) {
                unlink('gambar/' . $produk['gambar_produk']);
            }
            $_SESSION['success'] = 'Produk berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus produk!';
        }
        $delete_stmt->close();
    } else {
        $_SESSION['error'] = 'Produk tidak ditemukan!';
    }
    $stmt->close();
} catch (Exception $e) {
    error_log('Delete error: ' . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan saat menghapus produk!';
}
$koneksi->close();
header('Location: index.php');
exit();
?>
