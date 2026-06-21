<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';
$message = '';
$banner_path = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requirements = $conn->real_escape_string($_POST['requirements']);
    // Handle banner upload
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
        $filename = 'admission_banner_' . time() . '_' . rand(100,999) . '.' . $ext;
        $target = '../assets/images/' . $filename;
        if (move_uploaded_file($_FILES['banner']['tmp_name'], $target)) {
            $banner_path = 'assets/images/' . $filename;
        }
    }
    // Check if a record exists
    $result = $conn->query("SELECT id FROM admission_info LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // Update
        $sql = "UPDATE admission_info SET requirements='$requirements'";
        if ($banner_path) $sql .= ", banner='$banner_path'";
        $sql .= ", updated_at=NOW() WHERE id=1";
        $conn->query($sql);
        $message = 'Admission instruction updated.';
    } else {
        // Insert
        $sql = "INSERT INTO admission_info (requirements, banner) VALUES ('$requirements', '$banner_path')";
        $conn->query($sql);
        $message = 'Admission instruction added.';
    }
}
// Fetch current info
$requirements = '';
$banner = '';
$result = $conn->query("SELECT * FROM admission_info LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    $requirements = $row['requirements'] ?? '';
    $banner = $row['banner'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Instruction - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="container" style="margin-top:40px; margin-bottom:40px;">
    <h2>Manage Admission Instruction</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
    <hr>
    <?php if ($message) { echo '<div style="color:green;">'.$message.'</div>'; } ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="requirements" class="form-label">Admission Requirements / Instructions:</label>
            <textarea name="requirements" id="requirements" class="form-control" rows="6" required><?php echo htmlspecialchars($requirements); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="banner" class="form-label">Banner Image (optional):</label>
            <input type="file" name="banner" id="banner" class="form-control" accept="image/*">
            <?php if ($banner) { echo '<img src="../'.htmlspecialchars($banner).'" alt="Banner" class="img-fluid mt-2" style="max-height:200px;">'; } ?>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>