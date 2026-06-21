<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_photo'])) {
    $caption = trim($_POST['caption']);
    $status = (int)$_POST['status'];
    
    // Check if file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileType = $file['type'];
        
        // Get file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Validate file type
        if (in_array($fileExt, $allowedTypes)) {
            // Validate file size (2MB = 2 * 1024 * 1024 bytes)
            if ($fileSize <= 2 * 1024 * 1024) {
                // Generate unique filename
                $newFileName = 'gallery_' . time() . '_' . rand(100, 999) . '.' . $fileExt;
                $uploadPath = '../assets/images/' . $newFileName;
                
                // Move uploaded file
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    // Insert into database
                    $stmt = $conn->prepare("INSERT INTO gallery_photos (image, caption, status, created_at) VALUES (?, ?, ?, NOW())");
                    $stmt->bind_param("ssi", $newFileName, $caption, $status);
                    
                    if ($stmt->execute()) {
                        $msg = '<div class="alert alert-success">Photo uploaded successfully!</div>';
                    } else {
                        $msg = '<div class="alert alert-danger">Error saving to database: ' . $conn->error . '</div>';
                        // Remove uploaded file if database insert fails
                        unlink($uploadPath);
                    }
                    $stmt->close();
                } else {
                    $msg = '<div class="alert alert-danger">Error uploading file!</div>';
                }
            } else {
                $msg = '<div class="alert alert-danger">File size too large! Maximum size is 2MB.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Invalid file type! Only JPG, PNG, and GIF files are allowed.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Please select a valid image file!</div>';
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image filename before deleting
    $stmt = $conn->prepare("SELECT image FROM gallery_photos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $imageFile = '../assets/images/' . $row['image'];
        
        // Delete from database
        $deleteStmt = $conn->prepare("DELETE FROM gallery_photos WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        
        if ($deleteStmt->execute()) {
            // Delete physical file
            if (file_exists($imageFile)) {
                unlink($imageFile);
            }
            $msg = '<div class="alert alert-success">Photo deleted successfully!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Error deleting photo!</div>';
        }
        $deleteStmt->close();
    }
    $stmt->close();
}

$photos = $conn->query('SELECT * FROM gallery_photos ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Photo Gallery Management - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .sidebar {
      min-height: 100vh;
      position: sticky;
      top: 0;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row gx-0">
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
      <?php include '_sidebar.php'; ?>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 col-lg-10 px-md-4 py-4">
      <h3 class="mb-4">Photo Gallery Management</h3>
      <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>

      <?php echo $msg; ?>

      <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addPhotoModal">Add New Photo</button>

      <div class="row g-4">
        <?php if ($photos && $photos->num_rows > 0): while ($row = $photos->fetch_assoc()): ?>
          <div class="col-md-4 col-sm-6">
            <div class="card h-100">
              <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" 
                   class="card-img-top" style="height:220px; object-fit:cover;" alt="gallery" />
              <div class="card-body">
                <div class="card-text small text-muted mb-2"><?php echo htmlspecialchars($row['caption']); ?></div>
                <div>Status: <?php echo $row['status'] ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>'; ?></div>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Delete this photo?');">Delete</a>
              </div>
            </div>
          </div>
        <?php endwhile; else: ?>
          <div class="col-12"><div class="alert alert-info text-center">No photos found.</div></div>
        <?php endif; ?>
      </div>

      <!-- Add Photo Modal -->
      <div class="modal fade" id="addPhotoModal" tabindex="-1" aria-labelledby="addPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form class="modal-content" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="addPhotoModalLabel">Add New Photo</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="image" class="form-label">Photo (JPG/PNG/GIF, max 2MB)</label>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" required />
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
              <button type="submit" name="add_photo" class="btn btn-primary">Add Photo</button>
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
