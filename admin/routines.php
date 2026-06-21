<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

$msg = '';
// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_routine'])) {
    $class_name = trim($_POST['class_name'] ?? '');
    $file_type = $_POST['file_type'] ?? '';
    $file_name = '';
    $upload_error = '';
    if ($file_type === 'pdf' && isset($_FILES['routine_file']) && $_FILES['routine_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['routine_file']['name'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            $file_name = 'routine_' . time() . '_' . rand(100,999) . '.pdf';
            $target_dir = dirname(__DIR__) . '/assets/routines/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . $file_name;
            if (!move_uploaded_file($_FILES['routine_file']['tmp_name'], $target_path)) {
                $upload_error = 'PDF upload failed.';
                $file_name = '';
            }
        } else {
            $upload_error = 'Only PDF files allowed.';
        }
    } elseif ($file_type === 'image' && isset($_FILES['routine_file']) && $_FILES['routine_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['routine_file']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $valid_ext)) {
            $file_name = 'routine_' . time() . '_' . rand(100,999) . '.' . $ext;
            $target_dir = dirname(__DIR__) . '/assets/routines/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . $file_name;
            if (!move_uploaded_file($_FILES['routine_file']['tmp_name'], $target_path)) {
                $upload_error = 'Image upload failed.';
                $file_name = '';
            }
        } else {
            $upload_error = 'Only JPG, PNG, GIF images allowed.';
        }
    } else {
        $upload_error = 'No file uploaded or invalid file type.';
    }
    if ($class_name && $file_type && $file_name) {
        $stmt = $conn->prepare('INSERT INTO routines (class_name, file_type, file_name) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $class_name, $file_type, $file_name);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Routine added!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Failed to add routine.</div>';
        }
    } elseif ($upload_error) {
        $msg = '<div class="alert alert-danger">' . htmlspecialchars($upload_error) . '</div>';
    } else {
        $msg = '<div class="alert alert-danger">All fields are required.</div>';
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT file_name FROM routines WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $routine = $result->fetch_assoc();
    $stmt->close();
    if ($routine && !empty($routine['file_name'])) {
        $file_path = dirname(__DIR__) . '/assets/routines/' . $routine['file_name'];
        if (file_exists($file_path)) unlink($file_path);
    }
    $stmt = $conn->prepare("DELETE FROM routines WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: routines.php');
    exit;
}
$routines = $conn->query('SELECT * FROM routines ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Routine Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    <div class="col-md-3 col-lg-2 p-0">
      <?php include '_sidebar.php'; ?>
    </div>
    <main class="col-md-9 col-lg-10 px-md-4 d-flex flex-column" style="min-height: 100vh;">
      <div class="my-5">
        <h3 class="mb-4">Routine Management</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
        <?php echo $msg; ?>
        <form class="row g-3 mb-4" method="post" enctype="multipart/form-data">
          <div class="col-md-3">
            <input type="text" class="form-control" name="class_name" placeholder="Class Name" required />
          </div>
          <div class="col-md-2">
            <select class="form-select" name="file_type" required>
              <option value="">Select Type</option>
              <option value="pdf">PDF</option>
              <option value="image">Image</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="file" class="form-control" name="routine_file" accept=".pdf,.jpg,.jpeg,.png,.webp,.gif" required />
          </div>
          <div class="col-md-2">
            <button type="submit" name="add_routine" class="btn btn-success w-100">Add</button>
          </div>
        </form>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-primary">
              <tr>
                <th>#</th>
                <th>Class</th>
                <th>Type</th>
                <th>File</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($routines && $routines->num_rows > 0): $i=1; while($row = $routines->fetch_assoc()): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                <td><?php echo strtoupper($row['file_type']); ?></td>
                <td>
                  <?php if ($row['file_type'] === 'pdf'): ?>
                    <a href="../assets/routines/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">View PDF</a>
                  <?php else: ?>
                    <a href="../assets/routines/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank">
                      <img src="../assets/routines/<?php echo htmlspecialchars($row['file_name']); ?>" alt="Routine" style="height:40px;width:auto;" />
                    </a>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this routine?');">Delete</a>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="5" class="text-center">No routines found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
