<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');

// Auth check
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: student_info.php');
    exit;
}
$info_id = intval($_GET['id']);

// Fetch info data
$stmt = $conn->prepare('SELECT * FROM student_info WHERE id = ?');
$stmt->bind_param('i', $info_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    header('Location: student_info.php');
    exit;
}
$info = $result->fetch_assoc();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $class_name = trim($_POST['class_name'] ?? '');
    $total = intval($_POST['total_students'] ?? 0);
    $male = intval($_POST['male_students'] ?? 0);
    $female = intval($_POST['female_students'] ?? 0);
    if ($class_name && $total >= 0 && $male >= 0 && $female >= 0) {
        $stmt = $conn->prepare('UPDATE student_info SET class_name=?, total_students=?, male_students=?, female_students=? WHERE id=?');
        $stmt->bind_param('siiii', $class_name, $total, $male, $female, $info_id);
        if ($stmt->execute()) {
            header('Location: student_info.php');
            exit;
        } else {
            $msg = '<div class="alert alert-danger">Failed to update info.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">All fields are required and must be non-negative.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Info - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h3 class="mb-4">Edit Student Info</h3>
        <?php echo htmlspecialchars($msg); ?>
        <form method="post">
            <div class="mb-3">
                <label for="class_name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo htmlspecialchars($info['class_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="total_students" class="form-label">Total Students</label>
                <input type="number" class="form-control" id="total_students" name="total_students" min="0" value="<?php echo htmlspecialchars($info['total_students']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="male_students" class="form-label">Male Students</label>
                <input type="number" class="form-control" id="male_students" name="male_students" min="0" value="<?php echo htmlspecialchars($info['male_students']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="female_students" class="form-label">Female Students</label>
                <input type="number" class="form-control" id="female_students" name="female_students" min="0" value="<?php echo htmlspecialchars($info['female_students']); ?>" required>
            </div>
            <button type="submit" name="update_info" class="btn btn-primary">Update Info</button>
            <a href="student_info.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 