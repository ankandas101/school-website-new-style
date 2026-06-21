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
    header('Location: slider.php');
    exit;
}
$slider_id = intval($_GET['id']);

// Fetch slider data
$stmt = $conn->prepare('SELECT * FROM sliders WHERE id = ?');
$stmt->bind_param('i', $slider_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    header('Location: slider.php');
    exit;
}
$slider = $result->fetch_assoc();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_slider'])) {
    $caption_title = trim($_POST['caption_title'] ?? '');
    $caption_text = trim($_POST['caption_text'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = intval($_POST['status'] ?? 1);
    $image = $slider['image'];
    $upload_error = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png'];
        $max_size = 2 * 1024 * 1024; // 2MB
        if (in_array($ext, $valid_ext)) {
            if ($_FILES['image']['size'] > $max_size) {
                $upload_error = 'Image file is too large. Maximum size is 2MB. Please upload a smaller image.';
            } else {
                $image = 'slider_' . time() . '_' . rand(100,999) . '.' . $ext;
                $target_dir = dirname(__DIR__) . '/assets/images/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $target_path = $target_dir . $image;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    $upload_error = 'Image upload failed. Check folder permissions.';
                    $image = $slider['image'];
                } elseif (!file_exists($target_path)) {
                    $upload_error = 'Image file not found after upload.';
                    $image = $slider['image'];
                }
            }
        } else {
            $upload_error = 'Invalid image file type.';
        }
    }
    if ($caption_title) {
        $stmt = $conn->prepare('UPDATE sliders SET image=?, caption_title=?, caption_text=?, sort_order=?, status=? WHERE id=?');
        $stmt->bind_param('sssiii', $image, $caption_title, $caption_text, $sort_order, $status, $slider_id);
        if ($stmt->execute()) {
            header('Location: slider.php');
            exit;
        } else {
            $msg = '<div class="alert alert-danger">Failed to update slider.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Title is required. ' . htmlspecialchars($upload_error) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Slider - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h3 class="mb-4">Edit Slider</h3>
        <?php echo htmlspecialchars($msg); ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="image" class="form-label">Slider Image (JPG/PNG, max 2MB)</label><br>
                <img src="../assets/images/<?php echo htmlspecialchars($slider['image']); ?>" width="120" class="mb-2 rounded" alt="Current Image"><br>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">Leave blank to keep current image.</small>
            </div>
            <div class="mb-3">
                <label for="caption_title" class="form-label">Caption Title</label>
                <input type="text" class="form-control" id="caption_title" name="caption_title" value="<?php echo htmlspecialchars($slider['caption_title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="caption_text" class="form-label">Caption Text</label>
                <textarea class="form-control" id="caption_text" name="caption_text"><?php echo htmlspecialchars($slider['caption_text']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="sort_order" class="form-label">Sort Order</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo htmlspecialchars($slider['sort_order']); ?>">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="1" <?php if($slider['status']) echo 'selected'; ?>>Active</option>
                    <option value="0" <?php if(!$slider['status']) echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" name="update_slider" class="btn btn-primary">Update Slider</button>
            <a href="slider.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 