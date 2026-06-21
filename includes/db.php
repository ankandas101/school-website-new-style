<?php
require_once __DIR__ . '/security.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection settings from environment variables
$host = env('DB_HOST', 'localhost');
$user = env('DB_USER', 'root');
$password = env('DB_PASSWORD', '');
$dbname = env('DB_NAME', '');

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    http_response_code(500);
    die('Database connection failed. Please check your environment configuration.');
}

// Create connection to the alternate/system database if configured
$system_host = env('DB_SYSTEM_HOST', $host);
$system_user = env('DB_SYSTEM_USER', $user);
$system_password = env('DB_SYSTEM_PASSWORD', $password);
$system_db = env('DB_SYSTEM_NAME', 'school_system');
try {
    $system_conn = new mysqli($system_host, $system_user, $system_password, $system_db);
    $system_conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    $system_conn = null;
}

// Adjust according to your setup
define('APP_URL', env('APP_URL', 'https://app.kamalganjidealhighschool.edu.bd/home/'));
?>
