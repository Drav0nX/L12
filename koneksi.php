<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dbbelajar');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $koneksi->set_charset('utf8mb4');
} catch (Exception $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    die('Koneksi database gagal. Silakan hubungi administrator.');
}

function sanitize_input($data) {
    global $koneksi;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $koneksi->real_escape_string($data);
}

/**
 * Helper function to execute prepared statements
 */
function execute_query($query, $params = [], $types = '') {
    global $koneksi;
    
    $stmt = $koneksi->prepare($query);
    if ($stmt === false) {
        error_log('Prepare failed: ' . $koneksi->error);
        return false;
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt;
}

?>