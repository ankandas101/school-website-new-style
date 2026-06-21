<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// Message class
class Message {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function get($type) {
        $stmt = $this->conn->prepare('SELECT * FROM messages WHERE type = ? LIMIT 1');
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function update($type, $name, $message, $photo = null) {
        if ($photo) {
            $stmt = $this->conn->prepare('UPDATE messages SET name=?, message=?, photo=?, updated_at=NOW() WHERE type=?');
            $stmt->bind_param('ssss', $name, $message, $photo, $type);
        } else {
            $stmt = $this->conn->prepare('UPDATE messages SET name=?, message=?, updated_at=NOW() WHERE type=?');
            $stmt->bind_param('sss', $name, $message, $type);
        }
        return $stmt->execute();
    }
    public function createIfNotExists($type) {
        $stmt = $this->conn->prepare('SELECT id FROM messages WHERE type = ?');
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            $stmt2 = $this->conn->prepare('INSERT INTO messages (type, name, message, photo) VALUES (?, "", "", "")');
            $stmt2->bind_param('s', $type);
            $stmt2->execute();
            $stmt2->close();
        }
        $stmt->close();
    }
}

$msg = new Message($conn);
$msg->createIfNotExists('head_teacher');
$msg->createIfNotExists('chairman');
$msg->createIfNotExists('about_school');

$success = $error = '';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    
    // Handle about_school type separately
    if ($type === 'about_school') {
        $name = ''; // Not used for about_school
        $messageText = trim($_POST['about_short'] ?? '');
        $photo = null;
        $max_size = 2 * 1024 * 1024; // 2MB
        if (isset($_FILES['about_banner']) && $_FILES['about_banner']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['about_banner']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                if ($_FILES['about_banner']['size'] > $max_size) {
                    $error = 'Banner file is too large. Maximum size is 2MB. Please upload a smaller image.';
                } else {
                    $photo = 'about_banner_' . time() . '_' . rand(100,999) . '.' . $ext;
                    move_uploaded_file($_FILES['about_banner']['tmp_name'], '../assets/images/' . $photo);
                }
            }
        }
        if (!$error && $msg->update($type, $name, $messageText, $photo)) {
            $success = 'About School section updated successfully!';
        } elseif (!$error) {
            $error = 'Failed to update About School section.';
        }
    } else {
        // Handle regular message types (head_teacher, chairman)
        $name = trim($_POST['name'] ?? '');
        $messageText = trim($_POST['message'] ?? '');
        $photo = null;
        $max_size = 2 * 1024 * 1024; // 2MB
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                if ($_FILES['photo']['size'] > $max_size) {
                    $error = 'Photo file is too large. Maximum size is 2MB. Please upload a smaller image.';
                } else {
                    $photo = $type . '_' . time() . '_' . rand(100,999) . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], '../assets/images/' . $photo);
                }
            }
        }
        if (!$error && $msg->update($type, $name, $messageText, $photo)) {
            $success = 'Message updated successfully!';
        } elseif (!$error) {
            $error = 'Failed to update message.';
        }
    }
}
$head = $msg->get('head_teacher');
$chair = $msg->get('chairman');
$about_school = $msg->get('about_school');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages Management - Admin</title>
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
    <div class="container-fluid" style="margin-top: 0;">
      <div class="row gx-0">
        <?php include '_sidebar.php'; ?>
        <main class="col-md-9 col-lg-10 px-md-4 d-flex flex-column" style="min-height: 100vh;">
          <div class="my-5">
            <h3 class="mb-4">Messages Management</h3>
            <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
            <?php echo $success ? '<div class="alert alert-success">' . $success . '</div>' : ''; ?>
            <?php echo $error ? '<div class="alert alert-danger">' . $error . '</div>' : ''; ?>
            <div class="row g-4">
                <!-- About School Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">স্কুল সম্পর্কে</div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="about_school">
                                <div class="mb-3">
                                    <label class="form-label">Short Details</label>
                                    <textarea class="form-control" name="about_short" rows="3"><?php echo htmlspecialchars($about_school['message'] ?? ''); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Banner Image</label><br>
                                    <?php if (!empty($about_school['photo'])): ?>
                                        <img src="../assets/images/<?php echo htmlspecialchars($about_school['photo']); ?>" width="120" class="mb-2" alt="About Banner">
                                    <?php endif; ?>
                                        <div class="form-text mb-2" style="color: #0d6efd;">
                                      নির্দেশনাঃ 1200px X 700px সাইজের এবং 200Kb এর কম সাইজের ছবি ব্যাবহার করুন । প্রয়োজনে সাইজ কমাতে <a href="https://tinyjpg.com/" target="_blank" rel="noopener" style="color:#198754;">tinyjpg.com</a> ওয়েবসাইট ব্যাবহার করুন ।
                                     </div>
                                    <input type="file" class="form-control" name="about_banner" accept=".jpg,.jpeg,.png,.webp">
                                </div>
                                <button type="submit" class="btn btn-warning">Update About School</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Head Teacher Message -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">Head Teacher's Message</div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="head_teacher">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($head['name']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control" name="message" rows="4"><?php echo htmlspecialchars($head['message']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Photo</label><br>
                                    <?php if ($head['photo']): ?>
                                        <img src="../assets/images/<?php echo htmlspecialchars($head['photo']); ?>" width="80" class="mb-2 rounded" alt="Head Teacher">
                                    <?php endif; ?>
                                    <div class="form-text mb-2" style="color: #0d6efd;">
                                      নির্দেশনাঃ 300px X 300px সাইজের এবং 100Kb এর কম সাইজের ছবি ব্যাবহার করুন । প্রয়োজনে সাইজ কমাতে <a href="https://tinyjpg.com/" target="_blank" rel="noopener" style="color:#198754;">tinyjpg.com</a> ওয়েবসাইট ব্যাবহার করুন ।
                                     </div>
                                <input type="file" class="form-control" name="photo" accept=".jpg,.jpeg,.png,.webp">
                                </div>
                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Chairman Message -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">Chairman's Message</div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="chairman">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($chair['name']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control" name="message" rows="4"><?php echo htmlspecialchars($chair['message']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Photo</label><br>
                                    <?php if ($chair['photo']): ?>
                                        <img src="../assets/images/<?php echo htmlspecialchars($chair['photo']); ?>" width="80" class="mb-2 rounded" alt="Chairman">
                                    <?php endif; ?>
                                    <div class="form-text mb-2" style="color: #0d6efd;">
                                    নির্দেশনাঃ 300px X 300px সাইজের এবং 100Kb এর কম সাইজের ছবি ব্যাবহার করুন । প্রয়োজনে সাইজ কমাতে <a href="https://tinyjpg.com/" target="_blank" rel="noopener" style="color:#198754;">tinyjpg.com</a> ওয়েবসাইট ব্যাবহার করুন ।
                                    </div>
                                    <input type="file" class="form-control" name="photo" accept=".jpg,.jpeg,.png,.webp">
                                </div>
                                <button type="submit" class="btn btn-info">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>