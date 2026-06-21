<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');

// Auth check
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}

// Video class
class Video {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAll() {
        $sql = 'SELECT * FROM videos ORDER BY sort_order ASC, id DESC';
        return $this->conn->query($sql);
    }
    public function add($title, $youtube_url, $sort_order, $status) {
        $stmt = $this->conn->prepare('INSERT INTO videos (title, youtube_url, sort_order, status) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $title, $youtube_url, $sort_order, $status);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM videos WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

$video = new Video($conn);

// Handle add
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_video'])) {
    $title = trim($_POST['title'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = intval($_POST['status'] ?? 1);
    if ($title && $youtube_url && $video->add($title, $youtube_url, $sort_order, $status)) {
        $msg = '<div class="alert alert-success">Video added successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to add video. Title and YouTube URL are required.</div>';
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $video->delete($id);
    header('Location: videos.php');
    exit;
}
$videos = $video->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Gallery Management - Admin</title>
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
        <h3 class="mb-4">Video Gallery Management</h3>
        <?php echo $msg; ?>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addVideoModal">Add New Video</button>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>YouTube URL</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($videos && $videos->num_rows > 0): $i=1; while($row = $videos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($row['youtube_url']); ?>" target="_blank">View</a></td>
                    <td><?php echo $row['sort_order']; ?></td>
                    <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
                    <td>
                        <!-- Edit can be added later -->
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this video?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center">No videos found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Add Video Modal -->
    <div class="modal fade" id="addVideoModal" tabindex="-1" aria-labelledby="addVideoModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="addVideoModalLabel">Add New Video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="youtube_url" class="form-label">YouTube URL</label>
              <input type="url" class="form-control" id="youtube_url" name="youtube_url" required>
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
            <button type="submit" name="add_video" class="btn btn-primary">Add Video</button>
          </div>
        </form>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 