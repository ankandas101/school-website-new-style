<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// Forms class
class Forms {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getAll() {
        $sql = 'SELECT * FROM forms ORDER BY id DESC';
        return $this->conn->query($sql);
    }
    public function add($title, $file, $status) {
        $stmt = $this->conn->prepare('INSERT INTO forms (title, file, status) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $title, $file, $status);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM forms WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

$form = new Forms($conn);

// Handle add
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_form'])) {
    $title = trim($_POST['title'] ?? '');
    $status = intval($_POST['status'] ?? 1);
    $file = '';
    $upload_error = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['pdf', 'doc', 'docx'];
        if (in_array($ext, $valid_ext)) {
            $file = 'form_' . time() . '_' . rand(100,999) . '.' . $ext;
            $target_dir = dirname(__DIR__) . '/assets/forms/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . $file;
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                $upload_error = 'File upload failed. Check folder permissions.';
                $file = '';
            } elseif (!file_exists($target_path)) {
                $upload_error = 'File not found after upload.';
                $file = '';
            }
        } else {
            $upload_error = 'Invalid file type.';
        }
    } else {
        $upload_error = 'No file uploaded or upload error.';
    }
    if ($title && $file && $form->add($title, $file, $status)) {
        $msg = '<div class="alert alert-success">Form uploaded successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to upload form. Title and file are required. ' . htmlspecialchars($upload_error) . '</div>';
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $form->delete($id);
    header('Location: forms.php');
    exit;
}
$forms = $form->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Management - Admin</title>
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
        <h3 class="mb-4">Download Management</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
        <?php echo $msg; ?>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addFormModal">Upload New Document</button>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>File</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($forms && $forms->num_rows > 0): $i=1; while($row = $forms->fetch_assoc()): ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($row['title']); ?></td>
              <td><a href="../assets/forms/<?php echo htmlspecialchars($row['file']); ?>" target="_blank">Download</a></td>
              <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
              <td>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this form?');">Delete</a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="5" class="text-center">No forms found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <!-- Add Form Modal -->
      <div class="modal fade" id="addFormModal" tabindex="-1" aria-labelledby="addFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form class="modal-content" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="addFormModalLabel">Upload New Form</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
              </div>
              <div class="mb-3">
                <label for="file" class="form-label">File (PDF/DOC/DOCX, max 5MB)</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx" required>
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
              <button type="submit" name="add_form" class="btn btn-primary">Upload Form</button>
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