<?php
require_once 'includes/db.php';

// Create result_archives table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS result_archives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_name VARCHAR(255) NOT NULL,
    exam_year INT NOT NULL,
    total_students INT NOT NULL,
    total_pass INT NOT NULL,
    pass_rate DECIMAL(5,2) NOT NULL,
    total_gpa5 INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'result_archives' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>