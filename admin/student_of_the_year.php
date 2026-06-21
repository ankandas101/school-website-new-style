<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

class StudentOfTheYear {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function getAll() {
        $sql = 'SELECT * FROM student_of_the_year ORDER BY year DESC, id DESC';
        return $this->conn->query($sql);
    }
    public function add($name, $class, $year, $photo, $status) {
        $stmt = $this->conn->prepare('INSERT INTO student_of_the_year (name, class, year, photo, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('ssisi', $name, $class, $year, $photo, $status);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM student_of_the_year WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

$student = new StudentOfTheYear($conn);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $name = trim($_POST['name'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $year = intval($_POST['year'] ?? date('Y'));
    $status = intval($_POST['status'] ?? 1);
    $photo = '';
    $upload_error = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (in_array($ext, $valid_ext)) {
            if ($_FILES['photo']['size'] <= $maxFileSize) {
                $photo = 'student_of_year_' . time() . '_' . rand(100,999) . '.' . $ext;
                $target_dir = dirname(__DIR__) . '/assets/images/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $target_path = $target_dir . $photo;
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
                    $upload_error = 'Photo upload failed. Check folder permissions.';
                    $photo = '';
                }
            } else {
                $upload_error = 'Photo file size exceeds 2MB limit.';
            }
        } else {
            $upload_error = 'Invalid image file type.';
        }
    } else {
        $upload_error = 'No photo uploaded or upload error.';
    }

    if ($name && $class && $year && $photo && $student->add($name, $class, $year, $photo, $status)) {
        $msg = '<div class="alert alert-success">Student added successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to add student. Name, class, year, and photo are required. ' . htmlspecialchars($upload_error) . '</div>';
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $student->delete($id);
    header('Location: student_of_the_year.php');
    exit;
}

$students = $student->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student of the Year Management - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    .full-height-row {
      min-height: 100vh;
    }
    /* Sidebar must be sticky and full height */
    .sidebar {
      height: 100vh;
      overflow-y: auto;
      position: sticky;
      top: 0;
      z-index: 1020;
    }
    /* Remove extra padding top when sidebar is shown in collapse */
    #sidebarMenu.collapse.show {
      padding-top: 0 !important;
    }
    /* Navbar toggler spacing fix */
    .navbar-toggler {
      margin-top: 0 !important;
      margin-bottom: 0 !important;
    }
    /* Small padding top on mobile for main content */
    @media (max-width: 767.98px) {
      main {
        padding-top: 1rem !important;
      }
    }
  </style>
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

<div class="container-fluid px-0">
  <div class="row gx-0 full-height-row">
    <aside class="col-md-3 col-lg-2 p-0 sidebar">
      <?php include '_sidebar.php'; ?>
    </aside>

    <main class="col-md-9 col-lg-10 px-3 py-4">
      <h3 class="mb-4">Student of the Year Management</h3>
      <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>

      <?php echo $msg; ?>

      <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add New Student</button>

      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>Photo</th>
              <th>Name</th>
              <th>Class</th>
              <th>Year</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($students && $students->num_rows > 0): $i=1; while($row = $students->fetch_assoc()): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><img src="../assets/images/<?php echo htmlspecialchars($row['photo']); ?>" width="60" class="rounded" alt="student"></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['class']); ?></td>
                <td><?php echo htmlspecialchars($row['year']); ?></td>
                <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
                <td>
                  <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this student?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; else: ?>
              <tr><td colspan="7" class="text-center">No students found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Add Student Modal -->
      <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form class="modal-content" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required />
              </div>
              <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" class="form-control" id="class" name="class" required />
              </div>
              <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" id="year" name="year" min="2000" max="2100" value="<?php echo date('Y'); ?>" required />
              </div>
              <div class="mb-3">
                <label for="photo" class="form-label">Photo (JPG/PNG, max 2MB)</label>
                <input type="file" class="form-control" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp" required />
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
              <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
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
