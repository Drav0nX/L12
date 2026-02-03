<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dbbelajar');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $temp_conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if (!$temp_conn->connect_error) {
        $temp_conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $temp_conn->select_db(DB_NAME);
        
        echo "<h2>✅ Database created/selected successfully</h2>";
        
        $users_table = "
        CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            nama_lengkap VARCHAR(100) NOT NULL,
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $temp_conn->query($users_table);
        echo "✅ Table 'users' created/verified<br>";
        
        $produk_table = "
        CREATE TABLE IF NOT EXISTS produk (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nama_produk VARCHAR(100) NOT NULL,
            deskripsi TEXT,
            harga_beli DECIMAL(12,2) NOT NULL,
            harga_jual DECIMAL(12,2) NOT NULL,
            stok INT DEFAULT 0,
            gambar_produk VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_nama (nama_produk),
            INDEX idx_stok (stok)
        )";
        
        $temp_conn->query($produk_table);
        echo "✅ Table 'produk' created/verified<br>";
        
        $check_user = $temp_conn->query("SELECT id FROM users WHERE username = 'admin'");
        
        if ($check_user->num_rows == 0) {
            $default_password = password_hash('admin123', PASSWORD_BCRYPT);
            $insert_user = "INSERT INTO users (username, password, nama_lengkap, email) 
                           VALUES ('admin', '$default_password', 'Administrator', 'admin@system.local')";
            $temp_conn->query($insert_user);
            echo "✅ Default admin user created (username: admin, password: admin123)<br>";
        } else {
            echo "ℹ️ Admin user already exists<br>";
        }
        
        $temp_conn->close();
        
        echo "<div style='margin-top: 20px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px;'>";
        echo "<h3 style='color: #155724;'>🎉 Setup Berhasil!</h3>";
        echo "<p>Database dan tabel sudah disiapkan.</p>";
        echo "<p><strong>Default Login:</strong></p>";
        echo "<ul>";
        echo "<li>Username: <code>admin</code></li>";
        echo "<li>Password: <code>admin123</code></li>";
        echo "</ul>";
        echo "<p><a href='login.php' style='color: #0c5460; text-decoration: underline;'>Ke halaman login →</a></p>";
        echo "</div>";
        
    } else {
        throw new Exception("Koneksi database gagal: " . $temp_conn->connect_error);
    }
    
} catch (Exception $e) {
    echo "<div style='padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;'>";
    echo "<h3>❌ Error:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><small>Pastikan XAMPP sudah running dan MySQL aktif</small></p>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Sistem Manajemen Produk</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            padding: 20px;
            max-width: 600px;
            margin: 50px auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .info {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #004085;
        }
    </style>
</head>
<body>
    <h1>🔧 Database Setup</h1>
    <div class="info">
        <p><strong>ℹ️ Catatan:</strong> Script ini hanya perlu dijalankan sekali untuk membuat tabel database.</p>
        <p>Jika sudah berhasil, Anda bisa menghapus file ini atau mengamankannya dengan password.</p>
    </div>
</body>
</html>
