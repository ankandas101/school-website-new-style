<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/classes.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// SidebarWidget class
class SidebarWidget {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getAll() {
        $sql = 'SELECT * FROM sidebar_widgets ORDER BY sort_order ASC, id DESC';
        return $this->conn->query($sql);
    }
    public function add($type, $title, $content, $sort_order, $status) {
        $stmt = $this->conn->prepare('INSERT INTO sidebar_widgets (type, title, content, sort_order, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssii', $type, $title, $content, $sort_order, $status);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM sidebar_widgets WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
$widget = new SidebarWidget($conn);
$importantLinks = new ImportantLinks($conn);
$academicInfoLinks = new AcademicInfoLinks($conn);

// Handle add
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_widget'])) {
    $type = $_POST['type'] ?? 'image';
    $title = trim($_POST['title'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = intval($_POST['status'] ?? 1);
    $content = '';
    $upload_error = '';
    if ($type === 'image') {
        if (isset($_FILES['content_image']) && $_FILES['content_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['content_image']['name'], PATHINFO_EXTENSION));
            $valid_ext = ['jpg', 'jpeg', 'png'];
            if (in_array($ext, $valid_ext)) {
                $content = 'sidebar_' . time() . '_' . rand(100,999) . '.' . $ext;
                $target_dir = dirname(__DIR__) . '/assets/images/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $target_path = $target_dir . $content;
                if (!move_uploaded_file($_FILES['content_image']['tmp_name'], $target_path)) {
                    $upload_error = 'Image upload failed. Check folder permissions.';
                    $content = '';
                } elseif (!file_exists($target_path)) {
                    $upload_error = 'Image file not found after upload.';
                    $content = '';
                }
            } else {
                $upload_error = 'Invalid image file type.';
            }
        } else {
            $upload_error = 'No image uploaded or upload error.';
        }
    } else {
        $content = trim($_POST['content_html'] ?? '');
    }
    if ($content && $widget->add($type, $title, $content, $sort_order, $status)) {
        $msg = '<div class="alert alert-success">Widget added successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to add widget. ' . htmlspecialchars($upload_error) . '</div>';
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $widget->delete($id);
    header('Location: sidebar.php');
    exit;
}

// Handle add for important links
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_important_link'])) {
    $title = trim($_POST['important_title'] ?? '');
    $url = trim($_POST['important_url'] ?? '');
    $sort_order = intval($_POST['important_sort_order'] ?? 0);
    $status = intval($_POST['important_status'] ?? 1);
    if ($title && $url && $importantLinks->add($title, $url, $sort_order, $status)) {
        $msg .= '<div class="alert alert-success">Important link added successfully!</div>';
    } else {
        $msg .= '<div class="alert alert-danger">Failed to add important link. Title and URL are required.</div>';
    }
}
// Handle delete for important links
if (isset($_GET['delete_important'])) {
    $id = intval($_GET['delete_important']);
    $importantLinks->delete($id);
    header('Location: sidebar.php');
    exit;
}

// Handle add for academic info links
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_academic_info_link'])) {
    $title = trim($_POST['academic_title'] ?? '');
    $url = trim($_POST['academic_url'] ?? '');
    $sort_order = intval($_POST['academic_sort_order'] ?? 0);
    $status = intval($_POST['academic_status'] ?? 1);
    if ($title && $url && $academicInfoLinks->add($title, $url, $sort_order, $status)) {
        $msg .= '<div class="alert alert-success">Academic info link added successfully!</div>';
    } else {
        $msg .= '<div class="alert alert-danger">Failed to add academic info link. Title and URL are required.</div>';
    }
}
// Handle delete for academic info links
if (isset($_GET['delete_academic_info'])) {
    $id = intval($_GET['delete_academic_info']);
    $academicInfoLinks->delete($id);
    header('Location: sidebar.php');
    exit;
}
$widgets = $widget->getAll();
$allImportantLinks = $importantLinks->getAll();
$allAcademicInfoLinks = $academicInfoLinks->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Widgets - Admin</title>
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
    <div class="container my-5">
        <h3 class="mb-4">Sidebar Widgets</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
        <?php echo $msg; ?>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addWidgetModal">Add New Widget</button>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($widgets && $widgets->num_rows > 0): $i=1; while($row = $widgets->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td>
                        <?php if ($row['type'] === 'image'): ?>
                            <img src="../assets/images/<?php echo htmlspecialchars($row['content']); ?>" style="max-width:80px;" alt="widget image">
                        <?php else: ?>
                            <div style="max-width:200px;overflow:auto;"><small><?php echo htmlspecialchars(mb_strimwidth(strip_tags($row['content']),0,100,'...')); ?></small></div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['sort_order']; ?></td>
                    <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
                    <td>
                        <!-- Edit can be added later -->
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this widget?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="7" class="text-center">No widgets found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Add Widget Modal -->
    <div class="modal fade" id="addWidgetModal" tabindex="-1" aria-labelledby="addWidgetModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="addWidgetModalLabel">Add New Widget</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="type" class="form-label">Type</label>
              <select class="form-select" id="type" name="type" onchange="toggleWidgetType(this.value)">
                <option value="image">Image</option>
                <option value="html">HTML Widget</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="mb-3 widget-image">
              <label for="content_image" class="form-label">Image (JPG/PNG, max 2MB)</label>
              <input type="file" class="form-control" id="content_image" name="content_image" accept=".jpg,.jpeg,.png,.webp">
            </div>
            <div class="mb-3 widget-html" style="display:none;">
              <label for="content_html" class="form-label">HTML Content</label>
              <textarea class="form-control" id="content_html" name="content_html" rows="4"></textarea>
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
            <button type="submit" name="add_widget" class="btn btn-primary">Add Widget</button>
          </div>
        </form>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function toggleWidgetType(type) {
      document.querySelector('.widget-image').style.display = (type === 'image') ? '' : 'none';
      document.querySelector('.widget-html').style.display = (type === 'html') ? '' : 'none';
    }
    document.getElementById('type').addEventListener('change', function() {
      toggleWidgetType(this.value);
    });
    </script>

    <!-- Important Links Section -->
    <div class="container my-5">
        <h3 class="mb-4">গুরুত্বপূর্ণ লিংক</h3>
        <form class="row g-3 mb-4" method="post">
            <div class="col-md-4">
                <input type="text" class="form-control" name="important_title" placeholder="Link Title" required>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="important_url" placeholder="https://example.com or routine.php" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="important_sort_order" value="0" placeholder="Order">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="important_status">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" name="add_important_link" class="btn btn-primary">Add Link</button>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($allImportantLinks && $allImportantLinks->num_rows > 0): $i=1; while($row = $allImportantLinks->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank"><?php echo htmlspecialchars($row['url']); ?></a></td>
                    <td><?php echo $row['sort_order']; ?></td>
                    <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
                    <td><a href="?delete_important=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this link?');">Delete</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center">No important links found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Academic Info Links Section -->
    <div class="container my-5">
        <h3 class="mb-4">একাডেমিক তথ্য</h3>
        <form class="row g-3 mb-4" method="post">
            <div class="col-md-4">
                <input type="text" class="form-control" name="academic_title" placeholder="Link Title" required>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="academic_url" placeholder="https://example.com or routine.php" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="academic_sort_order" value="0" placeholder="Order">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="academic_status">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" name="add_academic_info_link" class="btn btn-primary">Add Link</button>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($allAcademicInfoLinks && $allAcademicInfoLinks->num_rows > 0): $i=1; while($row = $allAcademicInfoLinks->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank"><?php echo htmlspecialchars($row['url']); ?></a></td>
                    <td><?php echo $row['sort_order']; ?></td>
                    <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
                    <td><a href="?delete_academic_info=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this link?');">Delete</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center">No academic info links found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <li><a href="admission_info.php">Manage Admission Information</a></li>
</body>
</html> 