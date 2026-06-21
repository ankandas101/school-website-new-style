<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// Handle add event
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $title = trim($_POST['title'] ?? '');
    $event_date = $_POST['event_date'] ?? date('Y-m-d');
    $description = trim($_POST['description'] ?? '');
    $image = '';
    $upload_error = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png'];
        if (in_array($ext, $valid_ext)) {
            $image = 'event_' . time() . '_' . rand(100,999) . '.' . $ext;
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
        } else {
            $upload_error = 'Invalid image file type.';
        }
    }
    if ($title && $event_date && $description) {
        $stmt = $conn->prepare('INSERT INTO events (title, event_date, description, image) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $title, $event_date, $description, $image);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Event added successfully!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Failed to add event.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">All fields are required. ' . htmlspecialchars($upload_error) . '</div>';
    }
}
// Handle delete event
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM events WHERE id=$id");
    header('Location: events.php');
    exit;
}
$events = $conn->query('SELECT * FROM events ORDER BY event_date DESC, id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events/Blog Management - Admin</title>
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
        <h3 class="mb-4">Events/Blog Management</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
        <?php echo $msg; ?>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">Add New Event</button>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($events && $events->num_rows > 0): $i=1; while($row = $events->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                    <td><div style="max-width:200px;overflow:auto;"><small><?php echo htmlspecialchars(mb_strimwidth(strip_tags($row['description']),0,100,'...')); ?></small></div></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" style="max-width:80px;" alt="event image">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center">No events found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="event_date" class="form-label">Date</label>
              <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Image (JPG/PNG, max 2MB)</label>
              <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="add_event" class="btn btn-primary">Add Event</button>
          </div>
        </form>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 