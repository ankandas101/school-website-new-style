<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// Slider class
class Slider {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAll() {
        $sql = 'SELECT * FROM sliders ORDER BY sort_order ASC, id DESC';
        return $this->conn->query($sql);
    }
    public function add($image, $caption_title, $caption_text, $sort_order, $status) {
        $stmt = $this->conn->prepare('INSERT INTO sliders (image, caption_title, caption_text, sort_order, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssii', $image, $caption_title, $caption_text, $sort_order, $status);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM sliders WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

$slider = new Slider($conn);

// Handle add
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slider'])) {
    $caption_title = trim($_POST['caption_title'] ?? '');
    $caption_text = trim($_POST['caption_text'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = intval($_POST['status'] ?? 1);
    $image = '';
    $upload_error = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png'];
        $max_size = 0.5 * 1024 * 1024; // 0.5MB
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);
        $valid_mime = ['image/jpeg', 'image/png'];
        if (!in_array($ext, $valid_ext)) {
            $upload_error = 'Invalid image file type.';
        } elseif (!in_array($mime, $valid_mime)) {
            $upload_error = 'Invalid image MIME type.';
        } elseif ($_FILES['image']['size'] > $max_size) {
            $upload_error = 'স্লাইডার যোগ করা সম্ভব হয়নি। ছবির ফাইলটি অনেক বড়। সর্বোচ্চ আকার  500 KB । অনুগ্রহ করে 1200x700px একটি ছোট আকারের ছবি আপলোড করুন।';
        } elseif (@getimagesize($_FILES['image']['tmp_name']) === false) {
            $upload_error = 'Uploaded file is not a valid image.';
        } else {
            $image = 'slider_' . time() . '_' . rand(100,999) . '.' . $ext;
            $target_dir = dirname(__DIR__) . '/assets/images/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . $image;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $upload_error = 'Image upload failed. Check folder permissions.';
                $image = '';
            } elseif (!file_exists($target_path)) {
                $upload_error = 'Image file not found after upload.';
                $image = '';
            }
        }
    } else {
        $upload_error = 'No image uploaded or upload error.';
    }
    if ($image && $slider->add($image, $caption_title, $caption_text, $sort_order, $status)) {
        $msg = '<div class="alert alert-success">Slider added successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to add slider. ' . htmlspecialchars($upload_error) . '</div>';
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $slider->delete($id);
    header('Location: slider.php');
    exit;
}
$sliders = $slider->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider Management - Admin</title>
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
    <div class="container-fluid" style="margin-top: 0;">
      <div class="row gx-0">
        <?php include '_sidebar.php'; ?>
        <main class="col-md-9 col-lg-10 px-md-4 d-flex flex-column" style="min-height: 100vh;">
          <div class="my-5">
            <h3 class="mb-4">Slider Management</h3>
            <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
            <?php echo $msg; ?>
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSliderModal">Add New Slider</button>
            <div class="form-text mb-2" style="color: #0d6efd;">
                    নির্দেশনাঃ 1200px X 700px সাইজের এবং 200Kb এর কম সাইজের ছবি ব্যাবহার করুন । প্রয়োজনে সাইজ কমাতে <a href="https://tinyjpg.com/" target="_blank" rel="noopener" style="color:#198754;">tinyjpg.com</a> ওয়েবসাইট ব্যাবহার করুন ।
                  </div>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Text</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($sliders && $sliders->num_rows > 0): $i=1; while($row = $sliders->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" width="80" alt="slider"></td>
                  <td><?php echo htmlspecialchars($row['caption_title']); ?></td>
                  <td><?php echo htmlspecialchars($row['caption_text']); ?></td>
                  <td><?php echo $row['sort_order']; ?></td>
                  <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
                  <td>
                    <a href="edit_slider.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this slider?');">Delete</a>
                  </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="7" class="text-center">No sliders found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <!-- Add Slider Modal -->
          <div class="modal fade" id="addSliderModal" tabindex="-1" aria-labelledby="addSliderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title" id="addSliderModalLabel">Add New Slider</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="image" class="form-label">Slider Image (JPG/PNG, max 2MB)</label>
                    <div class="form-text mb-2" style="color: #0d6efd;">
                    নির্দেশনাঃ 1200px X 700px সাইজের এবং 200Kb এর কম সাইজের ছবি ব্যাবহার করুন । প্রয়োজনে সাইজ কমাতে <a href="https://tinyjpg.com/" target="_blank" rel="noopener" style="color:#198754;">tinyjpg.com</a> ওয়েবসাইট ব্যাবহার করুন ।
                  </div>
                    <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" required>
                  </div>
                  <div class="mb-3">
                    <label for="caption_title" class="form-label">Caption Title</label>
                    <input type="text" class="form-control" id="caption_title" name="caption_title">
                  </div>
                  <div class="mb-3">
                    <label for="caption_text" class="form-label">Caption Text</label>
                    <textarea class="form-control" id="caption_text" name="caption_text"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                  </div>
                  <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                      <option value="1" selected>Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" name="add_slider" class="btn btn-primary">Add Slider</button>
                </div>
              </form>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 