<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// Notice class
class Notice {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAll() {
        $sql = 'SELECT * FROM notices ORDER BY notice_date DESC, id DESC';
        return $this->conn->query($sql);
    }
    public function add($title, $description, $notice_date, $status, $attachment = null) {
        if ($attachment) {
            $stmt = $this->conn->prepare('INSERT INTO notices (title, description, notice_date, status, attachment) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssis', $title, $description, $notice_date, $status, $attachment);
        } else {
            $stmt = $this->conn->prepare('INSERT INTO notices (title, description, notice_date, status) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('sssi', $title, $description, $notice_date, $status);
        }
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM notices WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

$notice = new Notice($conn);

// Handle add
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_notice'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $notice_date = $_POST['notice_date'] ?? date('Y-m-d');
    $status = intval($_POST['status'] ?? 1);
    $attachment = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])) {
            $attachment = 'notice_' . time() . '_' . rand(100,999) . '.' . $ext;
            $uploadPath = '../assets/notices/' . $attachment;
            if (!is_dir('../assets/notices')) { mkdir('../assets/notices', 0777, true); }
            move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadPath);
        }
    }
    if ($title && $notice->add($title, $description, $notice_date, $status, $attachment)) {
        $msg = '<div class="alert alert-success">Notice added successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to add notice. Title is required.</div>';
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $notice->delete($id);
    header('Location: notices.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_ticker'])) {
    $id = intval($_POST['id']);
    $show_in_ticker = intval($_POST['show_in_ticker']);
    $stmt = $conn->prepare('UPDATE notices SET show_in_ticker=? WHERE id=?');
    $stmt->bind_param('ii', $show_in_ticker, $id);
    $stmt->execute();
    exit;
}

// Helper function to make URLs clickable
function make_links_clickable($text) {
    $pattern = '/(https?:\/\/[\w\-\.\/?&=;%#@!\+~:,]+)/i';
    return preg_replace($pattern, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>', htmlspecialchars($text));
}

$notices = $notice->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Board Management - Admin</title>
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

<!-- Main Layout -->
<div class="container-fluid">
  <div class="row gx-0">
    <div class="col-md-3 col-lg-2 p-0">
      <?php include '_sidebar.php'; ?>
    </div>
    <main class="col-md-9 col-lg-10 px-md-4 d-flex flex-column" style="min-height: 100vh;">
      
      <div class="my-5">
        <h3 class="mb-4">Notice Board Management</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
        <?php echo $msg; ?>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addNoticeModal">Add New Notice</button>
        
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Description</th>
              <th>Date</th>
              <th>Status</th>
              <th>Show in Ticker</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($notices && $notices->num_rows > 0): $i=1; while($row = $notices->fetch_assoc()): ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($row['title']); ?></td>
              <td>
                <?php echo nl2br(make_links_clickable($row['description'])); ?>
                <?php if (!empty($row['attachment'])): ?>
                  <br>
                  <?php 
                    $ext = strtolower(pathinfo($row['attachment'], PATHINFO_EXTENSION));
                    $fileUrl = '../assets/notices/' . $row['attachment'];
                  ?>
                  <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <img src="<?php echo $fileUrl; ?>" alt="Attachment" style="max-width:100px;max-height:100px;display:block;margin-top:5px;">
                  <?php elseif ($ext === 'pdf'): ?>
                    <a href="<?php echo $fileUrl; ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">View PDF</a>
                  <?php else: ?>
                    <a href="<?php echo $fileUrl; ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-1">Download Attachment</a>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($row['notice_date']); ?></td>
              <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
              <td>
                <div class="form-check form-switch">
                  <input class="form-check-input ticker-toggle" type="checkbox" data-id="<?php echo $row['id']; ?>" <?php echo ($row['show_in_ticker'] ? 'checked' : ''); ?>>
                  <label class="form-check-label">Ticker</label>
                </div>
              </td>
              <td>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this notice?');">Delete</a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="7" class="text-center">No notices found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Add Notice Modal -->
      <div class="modal fade" id="addNoticeModal" tabindex="-1" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form class="modal-content" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="addNoticeModalLabel">Add New Notice</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label for="notice_date" class="form-label">Notice Date</label>
                <input type="date" class="form-control" id="notice_date" name="notice_date" value="<?php echo date('Y-m-d'); ?>">
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                  <option value="1" selected>Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="attachment" class="form-label">Attachment (PDF/Image)</label>
                <input type="file" class="form-control" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp,.gif">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" name="add_notice" class="btn btn-primary">Add Notice</button>
            </div>
          </form>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.ticker-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        var noticeId = this.getAttribute('data-id');
        var showInTicker = this.checked ? 1 : 0;
        fetch('notices.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'toggle_ticker=1&id=' + noticeId + '&show_in_ticker=' + showInTicker
        });
    });
});
</script>
</body>
</html>
