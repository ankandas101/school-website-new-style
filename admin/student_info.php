<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

$msg = '';
// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_info'])) {
    $class_name = trim($_POST['class_name'] ?? '');
    $total = intval($_POST['total_students'] ?? 0);
    $male = intval($_POST['male_students'] ?? 0);
    $female = intval($_POST['female_students'] ?? 0);
    if ($class_name && $total >= 0 && $male >= 0 && $female >= 0) {
        $stmt = $conn->prepare('INSERT INTO student_info (class_name, total_students, male_students, female_students) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('siii', $class_name, $total, $male, $female);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Student info added!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Failed to add info.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">All fields are required and must be non-negative.</div>';
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM student_info WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: student_info.php');
    exit;
}
$infos = $conn->query('SELECT * FROM student_info ORDER BY id ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Info Management - Admin</title>
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
          <h3 class="mb-4">Student Information Management</h3>
          <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
          <?php echo $msg; ?>
          <form class="row g-3 mb-4" method="post">
            <div class="col-md-3">
              <input type="text" class="form-control" name="class_name" placeholder="Class Name" required />
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control" name="total_students" placeholder="Total" min="0" required />
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control" name="male_students" placeholder="Male" min="0" required />
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control" name="female_students" placeholder="Female" min="0" required />
            </div>
            <div class="col-md-2">
              <button type="submit" name="add_info" class="btn btn-success w-100">Add</button>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="table-primary">
                <tr>
                  <th>#</th>
                  <th>Class</th>
                  <th>Total</th>
                  <th>Male</th>
                  <th>Female</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($infos && $infos->num_rows > 0): $i=1; while($row = $infos->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                  <td><?php echo $row['total_students']; ?></td>
                  <td><?php echo $row['male_students']; ?></td>
                  <td><?php echo $row['female_students']; ?></td>
                  <td>
                    <a href="edit_student_info.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this info?');">Delete</a>
                  </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center">No data found.</td></tr>
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
