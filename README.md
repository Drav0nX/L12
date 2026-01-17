# L12 - Product Management System

Sistem manajemen produk berbasis web dengan PHP dan MySQL.

## Features

- Login/Logout authentication
- Dashboard dengan statistik produk
- CRUD produk (Create, Read, Update, Delete)
- Upload gambar produk
- Pencarian dan pagination
- Laporan cetak produk
- Responsive design dengan Bootstrap 5

## Tech Stack

- PHP 7.4+
- MySQL/MariaDB
- Bootstrap 5.3.8
- XAMPP (development)

## Installation

1. Clone repository:
   ```bash
   git clone https://github.com/Drav0nX/L12.git
   cd L12
   ```

2. Import database:
   - Buat database `dbbelajar`
   - Import struktur tabel melalui phpMyAdmin atau jalankan SQL:
     ```sql
     CREATE DATABASE dbbelajar;
     USE dbbelajar;
     
     CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL,
       nama_lengkap VARCHAR(100),
       email VARCHAR(100),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     );
     
     CREATE TABLE produk (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nama_produk VARCHAR(100) NOT NULL,
       deskripsi TEXT,
       harga_beli DECIMAL(15,2) NOT NULL,
       harga_jual DECIMAL(15,2) NOT NULL,
       gambar_produk VARCHAR(255),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     );
     
     INSERT INTO users (username, password, nama_lengkap, email) 
     VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@example.com');
     ```

3. Konfigurasi database di `koneksi.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'dbbelajar');
   ```

4. Setup folder permissions:
   ```bash
   chmod 755 gambar/
   ```

5. Akses aplikasi:
   ```
   http://localhost/L12/
   ```

## Default Login

- Username: `admin`
- Password: `admin123`

## Author

Muhammad Ridho Novriandra

## License

Educational project - 2026
