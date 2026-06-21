<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');

// Authentication check
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}

$msg = '';

// Handle adding new video
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_video'])) {
    $caption = trim($_POST['caption'] ?? '');
    $status = intval($_POST['status'] ?? 1);
    $video_url = '';
    $upload_error = '';

    if (!empty($_POST['youtube_url'])) {
        $video_url = trim($_POST['youtube_url']);
    } elseif (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['mp4', 'webm', 'ogg'];
        if (in_array($ext, $valid_ext)) {
            $video_url = 'video_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $target_dir = dirname(__DIR__) . '/assets/videos/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . $video_url;
            if (!move_uploaded_file($_FILES['video_file']['tmp_name'], $target_path)) {
                $upload_error = 'Video upload failed. Check folder permissions.';
                $video_url = '';
            } elseif (!file_exists($target_path)) {
                $upload_error = 'Video file not found after upload.';
                $video_url = '';
            }
        } else {
            $upload_error = 'Invalid video file type.';
        }
    } else {
        $upload_error = 'No video URL or file uploaded.';
    }

    if ($video_url) {
        $stmt = $conn->prepare('INSERT INTO gallery_videos (video_url, caption, status) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $video_url, $caption, $status);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Video added successfully!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Failed to add video.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Failed to add video. ' . htmlspecialchars($upload_error) . '</div>';
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $video = $conn->query("SELECT video_url FROM gallery_videos WHERE id=$id")->fetch_assoc();
    if ($video && !empty($video['video_url']) && strpos($video['video_url'], 'youtube.com') === false && strpos($video['video_url'], 'youtu.be') === false) {
        $vid_path = dirname(__DIR__) . '/assets/videos/' . $video['video_url'];
        if (file_exists($vid_path)) unlink($vid_path);
    }
    $conn->query("DELETE FROM gallery_videos WHERE id=$id");
    header('Location: gallery_videos.php');
    exit;
}

$videos = $conn->query('SELECT * FROM gallery_videos ORDER BY id DESC');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Video Gallery Management - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    main {
      padding-top: 20px;
    }
    /* Video card full height */
    .card.h-100 {
      height: 100%;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="d-none d-md-flex ms-auto">
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebarMenu" class="collapse d-md-block bg-light sidebar shadow-sm rounded-3 p-3 col-md-3 col-lg-2" style="min-height: 100vh;">
        <?php include '_sidebar.php'; ?>
      </nav>

      <!-- Main Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h3 class="mb-4 mt-4">Video Gallery Management</h3>
        <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>

        <?php echo $msg; ?>

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addVideoModal">Add New Video</button>

        <div class="row g-4">
          <?php if ($videos && $videos->num_rows > 0): ?>
            <?php while ($row = $videos->fetch_assoc()): ?>
              <div class="col-md-6 col-lg-4 d-flex">
                <div class="card h-100 w-100">
                  <div class="ratio ratio-16x9">
                    <?php if (strpos($row['video_url'], 'youtube.com') !== false || strpos($row['video_url'], 'youtu.be') !== false): ?>
                      <iframe src="<?php echo htmlspecialchars($row['video_url']); ?>" allowfullscreen loading="lazy"></iframe>
                    <?php else: ?>
                      <video src="../assets/videos/<?php echo htmlspecialchars($row['video_url']); ?>" controls preload="metadata"></video>
                    <?php endif; ?>
                  </div>
                  <div class="card-body">
                    <p class="card-text small text-muted mb-2"><?php echo htmlspecialchars($row['caption']); ?></p>
                    <p>Status: <?php echo $row['status'] ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>'; ?></p>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this video?');">Delete</a>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info text-center">No videos found.</div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Add Video Modal -->
        <div class="modal fade" id="addVideoModal" tabindex="-1" aria-labelledby="addVideoModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form class="modal-content" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="addVideoModalLabel">Add New Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="youtube_url" class="form-label">YouTube Video URL</label>
                  <input type="url" class="form-control" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." />
                  <div class="form-text">Or upload a local video file below.</div>
                </div>
                <div class="mb-3">
                  <label for="video_file" class="form-label">Local Video File (MP4/WEBM/OGG, max 20MB)</label>
                  <input type="file" class="form-control" id="video_file" name="video_file" accept=".mp4,.webm,.ogg" />
                </div>
                <div class="mb-3">
                  <label for="caption" class="form-label">Caption</label>
                  <input type="text" class="form-control" id="caption" name="caption" />
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

      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
