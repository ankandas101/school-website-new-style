<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// Teacher class
class Teacher {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAll() {
        $sql = 'SELECT * FROM teachers ORDER BY id DESC';
        return $this->conn->query($sql);
    }
    public function add($name, $designation, $photo, $phone, $email, $bio, $status, $sort_order) {
        $stmt = $this->conn->prepare('INSERT INTO teachers (name, designation, photo, phone, email, bio, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssssi', $name, $designation, $photo, $phone, $email, $bio, $status, $sort_order);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM teachers WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

$teacher = new Teacher($conn);

// Handle add
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $name = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $status = intval($_POST['status'] ?? 1);
    $sort_order = isset($_POST['sort_order']) && is_numeric($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
    $photo = '';
    $upload_error = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
      $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
      $valid_ext = ['jpg', 'jpeg', 'png', 'webp'];
      $max_size = 500 * 1024; // 500KB
      if ($_FILES['photo']['size'] > $max_size) {
        $upload_error = 'Photo file is too large. Maximum size is 500KB.';
      } else {
        if (in_array($ext, $valid_ext)) {
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $mime = $finfo ? finfo_file($finfo, $_FILES['photo']['tmp_name']) : '';
          if ($finfo) finfo_close($finfo);
          $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
          if (!in_array($mime, $valid_mime)) {
            $upload_error = 'Invalid image MIME type.';
          } else {
            $photo = 'teacher_' . time() . '_' . rand(100,999) . '.' . $ext;
            $target_dir = dirname(__DIR__) . '/assets/images/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . $photo;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
              $upload_error = 'Photo upload failed. Check folder permissions.';
              $photo = '';
            } elseif (!file_exists($target_path)) {
              $upload_error = 'Photo file not found after upload.';
              $photo = '';
            }
          }
        } else {
          $upload_error = 'Invalid image file type.';
        }
      }
    }
    
    // Set default image if no photo uploaded
    if (empty($photo)) {
        $photo = 'default.png';
        $upload_error = ''; // Clear error since we're using default
    }
    if ($name && $teacher->add($name, $designation, $photo, $phone, $email, $bio, $status, $sort_order)) {
        $msg = '<div class="alert alert-success">Teacher added successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to add teacher. Name is required. ' . htmlspecialchars($upload_error) . '</div>';
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $teacher->delete($id);
    header('Location: teachers.php');
    exit;
}
$teachers = $teacher->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Teacher Management - Admin</title>
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
          <h3 class="mb-4">Teacher Management</h3>
          <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
          <?php echo $msg; ?>
          <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addTeacherModal">Add New Teacher</button>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($teachers && $teachers->num_rows > 0): $i=1; while($row = $teachers->fetch_assoc()): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><img src="../assets/images/<?php echo htmlspecialchars($row['photo']); ?>" width="60" class="rounded" alt="teacher"></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['designation']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?></td>
                <td>
                  <a href="edit_teacher.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                  <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this teacher?');">Delete</a>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="8" class="text-center">No teachers found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Add Teacher Modal -->
        <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form class="modal-content" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="name" class="form-label">Name *</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Teacher Name" required />
                </div>
                <div class="mb-3">
                  <label for="designation" class="form-label">Designation *</label>
                  <input type="text" class="form-control" id="designation" name="designation" placeholder="Assistent Teacher" required/>
                </div>
                <div class="mb-3">
                  <label for="photo" class="form-label">Photo (JPG/PNG/WebP, max 500KB) - Optional</label>
                  <div class="form-text mb-2" style="color: #0d6efd;">
                    নির্দেশনাঃ 300px X 300px সাইজের এবং 500Kb এর কম সাইজের ছবি ব্যাবহার করুন । প্রয়োজনে সাইজ কমাতে <a href="https://tinyjpg.com/" target="_blank" rel="noopener" style="color:#198754;">tinyjpg.com</a> ওয়েবসাইট ব্যাবহার করুন ।
                  </div>
                  <input type="file" class="form-control" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp" />
                  <div class="form-text mt-1" style="color: #dc3545;">
                    <i class="bi bi-info-circle"></i> যদি ছবি আপলোড না করেন তবে ডিফল্ট ছবি ব্যবহার করা হবে।
                  </div>
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="phone" name="phone" />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" />
                </div>
                <div class="mb-3">
                  <label for="bio" class="form-label">Bio / Details:</label>
                  <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Enter details such as his expriences"></textarea>
                </div>
                <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select class="form-select" id="status" name="status">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="sort_order" class="form-label">Sort Order <span class="text-muted small">(lower = higher priority, 0 = no serial)</span></label>
                  <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="0" />
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="add_teacher" class="btn btn-primary">Add Teacher</button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      var photoInput = document.getElementById('photo');
      var MAX_BYTES = 500 * 1024; // 500KB
      if (photoInput) {
        photoInput.addEventListener('change', function(){
          var f = this.files && this.files[0];
          if (f && f.size > MAX_BYTES) {
            alert('Selected photo exceeds 500KB. Please choose a smaller image.');
            this.value = '';
          }
        });
        var form = photoInput.closest('form');
        if (form) {
          form.addEventListener('submit', function(e){
            var f = photoInput.files && photoInput.files[0];
            if (f && f.size > MAX_BYTES) {
              e.preventDefault();
              alert('Photo is too large (max 500KB). Please select a smaller image.');
              return false;
            }
          });
        }
      }
    })();
  </script>
</body>
</html>
