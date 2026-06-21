<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/classes.php';
define('ADMIN_SESSION', 'admin_logged_in');
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}

$committee = new ManagementCommittee($conn);
$msg = '';

// Handle add (from modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $joining_date = $_POST['joining_date'] ?? '';
    $sort_order = isset($_POST['sort_order']) && is_numeric($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
    $image = '';
    $upload_error = '';

    // Handle image upload or default image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $valid_ext = ['jpg', 'jpeg', 'png'];
        if (in_array($ext, $valid_ext)) {
            $image = 'committee_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $target_dir = dirname(__DIR__) . '/assets/images/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_path = $target_dir . $image;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $upload_error = 'Image upload failed.';
                $image = '';
            }
        } else {
            $upload_error = 'Invalid image file type.';
            $image = '';
        }
    } else {
        // If no image uploaded, use default image
        $image = 'default.png';
    }

    // Now $image always has a value (either uploaded file or default.png)
    if ($full_name && $title && $joining_date && $committee->add($full_name, $title, $image, $contact_number, $joining_date, $sort_order)) {
        // Use PRG pattern: store message in session and redirect
        $_SESSION['committee_msg'] = '<div class="alert alert-success">Member added successfully!</div>';
        header('Location: management_committee_admin.php');
        exit;
    } else {
        $msg = '<div class="alert alert-danger">Failed to add member. Full name, title, and joining date are required. ' . htmlspecialchars($upload_error) . '</div>';
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $committee->delete($id);
    header('Location: management_committee_admin.php');
    exit;
}

// Show message if redirected after add
if (isset($_SESSION['committee_msg'])) {
    $msg = $_SESSION['committee_msg'];
    unset($_SESSION['committee_msg']);
}

$members = $committee->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Committee - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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

<div class="container-fluid">
    <div class="row gx-0">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">
            <?php include '_sidebar.php'; ?>
        </div>

        <!-- Main content -->
        <main class="col-md-9 col-lg-10 px-md-4 d-flex flex-column" style="min-height: 100vh;">
            <div class="my-5">
                <h3 class="mb-4">Management Committee</h3>
                <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>

                <?php echo $msg; ?>

                <!-- Add New Member Button -->
                <button type="button" class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bi bi-plus-circle"></i> Add New Member
                </button>

                <!-- Add Member Modal -->
                <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <form class="row g-3" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                          <h5 class="modal-title" id="addMemberModalLabel">Add New Committee Member</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">Full Name <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Title <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="title" placeholder="Title" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Contact Number</label>
                              <input type="text" class="form-control" name="contact_number" placeholder="Contact Number">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Joining Date <span class="text-danger">*</span></label>
                              <input type="date" class="form-control" name="joining_date" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Image</label>
                              <div class="form-text mb-2" style="color: #0d6efd;">
                                নির্দেশনাঃ 300px X 300px সাইজের এবং 100Kb সাইজের ছবি ব্যাবহার করুন । প্রয়োজনে সাইজ কমাতে <a href="https://tinyjpg.com/" target="_blank" rel="noopener" style="color:#198754;">tinyjpg.com</a> ওয়েবসাইট ব্যাবহার করুন ।
                              </div>
                              <input type="file" class="form-control" name="image" accept=".jpg,.jpeg,.png,.webp">
                              <small class="text-muted">If no image is selected, default image will be used.</small>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Sort Order</label>
                              <input type="number" class="form-control" name="sort_order" placeholder="Sort Order" min="1">
                              <small class="text-muted">Lower number = higher priority</small>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" name="add_member" class="btn btn-success">Add Member</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- End Add Member Modal -->

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Full Name</th>
                            <th>Title</th>
                            <th>Contact Number</th>
                            <th>Joining Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($members && $members->num_rows > 0): $i = 1; while($row = $members->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>
                                <?php
                                    $img_file = $row['image'] ? $row['image'] : 'default.png';
                                ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($img_file); ?>" width="60" class="rounded" alt="member">
                            </td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['joining_date']); ?></td>
                            <td>
                                <a href="edit_committee_member.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this member?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="7" class="text-center">No members found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
