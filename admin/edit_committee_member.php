<?php
session_start();
require_once '../includes/db.php';

// Debugging mode চালু
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ADMIN_SESSION', 'admin_logged_in');
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: management_committee_admin.php');
    exit;
}

$member_id = intval($_GET['id']);

// Fetch member data
$stmt = $conn->prepare('SELECT * FROM management_committee WHERE id = ?');
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('i', $member_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    header('Location: management_committee_admin.php');
    exit;
}
$member = $result->fetch_assoc();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_member'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $joining_date = $_POST['joining_date'] ?? '';
    $sort_order = isset($_POST['sort_order']) && is_numeric($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
    $image = $member['image'];
    $upload_error = '';

    // Validate joining_date
    $date_valid = preg_match('/^\d{4}-\d{2}-\d{2}$/', $joining_date) && strtotime($joining_date);
    if (!$joining_date || !$date_valid) {
        $msg = '<div class="alert alert-danger">Joining date is required and must be a valid date.</div>';
    } else {
        // Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $valid_ext = ['jpg', 'jpeg', 'png'];
            $max_size = 2 * 1024 * 1024; // 2MB
            if (in_array($ext, $valid_ext)) {
                if ($_FILES['image']['size'] > $max_size) {
                    $upload_error = 'Image file is too large. Maximum size is 2MB. Please upload a smaller image.';
                } else {
                    $image = 'committee_' . time() . '_' . rand(100, 999) . '.' . $ext;
                    $target_dir = dirname(__DIR__) . '/assets/images/';
                    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                    $target_path = $target_dir . $image;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                        $upload_error = 'Image upload failed.';
                        $image = $member['image'];
                    }
                }
            } else {
                $upload_error = 'Invalid image file type.';
            }
        }

        if ($full_name && $title && $joining_date && $date_valid) {
            $stmt = $conn->prepare('UPDATE management_committee 
                                    SET full_name=?, title=?, image=?, contact_number=?, joining_date=?, sort_order=?, updated_at=NOW() 
                                    WHERE id=?');
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            // ফিক্সড bind_param (joining_date কে string ধরা হয়েছে)
            $stmt->bind_param('ssssssi', $full_name, $title, $image, $contact_number, $joining_date, $sort_order, $member_id);

            if ($stmt->execute()) {
                header('Location: management_committee_admin.php');
                exit;
            } else {
                $msg = '<div class="alert alert-danger">Failed to update member. MySQL error: ' . htmlspecialchars($stmt->error) . '</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Full name, title, and joining date are required. ' . htmlspecialchars($upload_error) . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Committee Member - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h3 class="mb-4">Edit Committee Member</h3>
        <?php echo $msg; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($member['full_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($member['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($member['contact_number']); ?>">
            </div>
            <div class="mb-3">
                <label for="joining_date" class="form-label">Joining Date</label>
                <input type="date" class="form-control" id="joining_date" name="joining_date" value="<?php echo htmlspecialchars($member['joining_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sort_order" class="form-label">Sort Order <span class="text-muted small">(lower = higher priority, blank = no serial)</span></label>
                <input type="text" class="form-control" id="sort_order" name="sort_order"
                    value="<?php echo isset($member['sort_order']) ? htmlspecialchars($member['sort_order']) : '100'; ?>">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image (JPG/PNG, max 2MB)</label><br>
                <?php if ($member['image']): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($member['image']); ?>" width="80" class="mb-2 rounded" alt="Current Image"><br>
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">Leave blank to keep current image.</small>
            </div>
            <button type="submit" name="update_member" class="btn btn-primary">Update Member</button>
            <a href="management_committee_admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
