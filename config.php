<?php
// ============================================================
// config.php — Database & Site Configuration
// EDIT these values before uploading to Hostinger
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'thakshi_photography');
define('DB_USER', 'your_db_username');       // Set in Hostinger → Databases
define('DB_PASS', 'your_db_password');       // Set in Hostinger → Databases
define('DB_CHARSET', 'utf8mb4');

define('SITE_URL', 'https://www.thakshiphotography.com'); // Your domain
define('SITE_NAME', 'Thakshi Photography');
define('ADMIN_EMAIL', 'thakshiphotography@gmail.com');

define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Cookie / session settings
define('ACCESS_EXPIRY_DAYS', 10);

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting (set to 0 on production)
error_reporting(0);
ini_set('display_errors', 0);

// ============================================================
// Database connection (PDO)
// ============================================================
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
