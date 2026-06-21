<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'school_db';


// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('ডাটাবেস সংযোগ ব্যর্থ: ' . $conn->connect_error);
}

// Create connection to school_system database
$host = 'localhost';
$user = 'root';
$password = '';
$system_db = 'school_system';

$system_conn = new mysqli($host, $user, $password, $system_db);

// Adjust according to your setup
define('APP_URL', 'https://app.kamalganjidealhighschool.edu.bd/home/');
// Use $conn for database queries
mysqli_set_charset($conn, "utf8mb4");
?>
