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
    header('Location: teachers.php');
    exit;
}
$teacher_id = intval($_GET['id']);

// Fetch teacher data
$stmt = $conn->prepare('SELECT * FROM teachers WHERE id = ?');
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    header('Location: teachers.php');
    exit;
}
$teacher = $result->fetch_assoc();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_teacher'])) {
    $name = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $status = intval($_POST['status'] ?? 1);
    $sort_order = isset($_POST['sort_order']) && is_numeric($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
    $photo = $teacher['photo'];
    $upload_error = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png'];
        $max_size = 2 * 1024 * 1024; // 2MB
        if (in_array($ext, $valid_ext)) {
            if ($_FILES['photo']['size'] > $max_size) {
                $upload_error = 'Photo file is too large. Maximum size is 2MB. Please upload a smaller image.';
            } else {
                $photo = 'teacher_' . time() . '_' . rand(100,999) . '.' . $ext;
                $target_dir = dirname(__DIR__) . '/assets/images/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $target_path = $target_dir . $photo;
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
                    $upload_error = 'Photo upload failed. Check folder permissions.';
                    $photo = $teacher['photo'];
                } elseif (!file_exists($target_path)) {
                    $upload_error = 'Photo file not found after upload.';
                    $photo = $teacher['photo'];
                }
            }
        } else {
            $upload_error = 'Invalid image file type.';
        }
    }
    if ($name) {
        $stmt = $conn->prepare('UPDATE teachers SET name=?, designation=?, bio=?, photo=?, phone=?, email=?, status=?, sort_order=? WHERE id=?');
        $stmt->bind_param('sssssssii', $name, $designation, $bio, $photo, $phone, $email, $status, $sort_order, $teacher_id);
        if ($stmt->execute()) {
            header('Location: teachers.php');
            exit;
        } else {
            $msg = '<div class="alert alert-danger">Failed to update teacher.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Name is required. ' . htmlspecialchars($upload_error) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h3 class="mb-4">Edit Teacher</h3>
        <?php echo htmlspecialchars($msg); ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="designation" class="form-label">Designation</label>
                <input type="text" class="form-control" id="designation" name="designation" value="<?php echo htmlspecialchars($teacher['designation']); ?>">
            </div>
            <div class="mb-3">
                <label for="bio" class="form-label">Bio / Details:</label>
                <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Enter details about the teacher"><?php echo htmlspecialchars($teacher['bio'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo (JPG/PNG, max 2MB)</label><br>
                <img src="../assets/images/<?php echo htmlspecialchars($teacher['photo']); ?>" width="80" class="mb-2 rounded" alt="Current Photo"><br>
                <input type="file" class="form-control" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">Leave blank to keep current photo.</small>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($teacher['phone']); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($teacher['email']); ?>">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="1" <?php if($teacher['status']) echo 'selected'; ?>>Active</option>
                    <option value="0" <?php if(!$teacher['status']) echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="sort_order" class="form-label">Sort Order <span class="text-muted small">(lower = higher priority, 0 = no serial)</span></label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="<?php echo htmlspecialchars($teacher['sort_order']); ?>">
            </div>
            <button type="submit" name="update_teacher" class="btn btn-primary">Update Teacher</button>
            <a href="teachers.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 