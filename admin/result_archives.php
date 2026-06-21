<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

$msg = '';
// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_archive'])) {
    $exam_name = trim($_POST['exam_name'] ?? '');
    $exam_year = intval($_POST['exam_year'] ?? 0);
    $total_students = intval($_POST['total_students'] ?? 0);
    $total_pass = intval($_POST['total_pass'] ?? 0);
    $total_gpa5 = !empty($_POST['total_gpa5']) ? intval($_POST['total_gpa5']) : null;
    
    // Calculate pass rate
    $pass_rate = 0;
    if ($total_students > 0) {
        $pass_rate = ($total_pass / $total_students) * 100;
    }
    
    // Validate inputs
    if (empty($exam_name) || $exam_year <= 0 || $total_students <= 0) {
        $msg = '<div class="alert alert-danger">Please fill all required fields with valid values.</div>';
    } else {
        // Insert into database
        $stmt = $conn->prepare('INSERT INTO result_archives (exam_name, exam_year, total_students, total_pass, pass_rate, total_gpa5) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('siiidd', $exam_name, $exam_year, $total_students, $total_pass, $pass_rate, $total_gpa5);
        
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Result archive added successfully!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Failed to add result archive: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM result_archives WHERE id = ?');
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Result archive deleted successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to delete result archive: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Fetch all result archives
$archives = $conn->query('SELECT * FROM result_archives ORDER BY exam_year DESC, id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Result Archives Management - Admin</title>
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
        <h3 class="mb-4">Result Archives Management</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
        <?php echo $msg; ?>
        <form class="row g-3 mb-4" method="post">
          <div class="col-md-3">
            <label for="exam_name" class="form-label">Exam Name</label>
            <input type="text" class="form-control" id="exam_name" name="exam_name" placeholder="e.g. SSC/HSC/JSC" required />
          </div>
          <div class="col-md-2">
            <label for="exam_year" class="form-label">Exam Year</label>
            <input type="number" class="form-control" id="exam_year" name="exam_year" min="2000" max="2099" required />
          </div>
          <div class="col-md-2">
            <label for="total_students" class="form-label">Total Students</label>
            <input type="number" class="form-control" id="total_students" name="total_students" min="1" required />
          </div>
          <div class="col-md-2">
            <label for="total_pass" class="form-label">Total Pass</label>
            <input type="number" class="form-control" id="total_pass" name="total_pass" min="0" required />
          </div>
          <div class="col-md-2">
            <label for="total_gpa5" class="form-label">Total GPA 5 (Optional)</label>
            <input type="number" class="form-control" id="total_gpa5" name="total_gpa5" min="0" />
          </div>
          <div class="col-md-12 mt-4">
            <button type="submit" name="add_archive" class="btn btn-success">Add Result Archive</button>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-primary">
              <tr>
                <th>#</th>
                <th>Exam Name</th>
                <th>Year</th>
                <th>Total Students</th>
                <th>Total Pass</th>
                <th>Pass Rate (%)</th>
                <th>GPA 5</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($archives && $archives->num_rows > 0): $i=1; while($row = $archives->fetch_assoc()): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row['exam_name']); ?></td>
                <td><?php echo htmlspecialchars($row['exam_year']); ?></td>
                <td><?php echo htmlspecialchars($row['total_students']); ?></td>
                <td><?php echo htmlspecialchars($row['total_pass']); ?></td>
                <td><?php echo number_format($row['pass_rate'], 2); ?>%</td>
                <td><?php echo isset($row['total_gpa5']) && $row['total_gpa5'] !== null ? htmlspecialchars($row['total_gpa5']) : '-'; ?></td>
                <td>
                  <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this result archive?');">Delete</a>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="8" class="text-center">No result archives found.</td></tr>
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