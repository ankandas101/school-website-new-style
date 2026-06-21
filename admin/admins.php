<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

$msg = '';
// Handle add admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($username && $password && $name && $email) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $is_superadmin = 0; // Always 0 from this page
        $stmt = $conn->prepare('INSERT INTO admins (username, password, name, email, is_superadmin) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssi', $username, $hashed_password, $name, $email, $is_superadmin);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Admin user added successfully!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Failed to add admin: ' . htmlspecialchars($stmt->error) . '</div>';
        }
        $stmt->close();
    } else {
        $msg = '<div class="alert alert-danger">All fields are required.</div>';
    }
}
// Handle delete admin
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Prevent deleting your own account and superadmins
    $stmt = $conn->prepare('SELECT is_superadmin FROM admins WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($is_superadmin);
    $stmt->fetch();
    $stmt->close();
    if ($id == $_SESSION[ADMIN_SESSION]) {
        $msg = '<div class="alert alert-danger">You cannot delete your own account.</div>';
    } else if ($is_superadmin == 1) {
        $msg = '<div class="alert alert-danger">You cannot delete a superadmin.</div>';
    } else {
        $stmt = $conn->prepare('DELETE FROM admins WHERE id = ?');
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Admin user deleted successfully!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Failed to delete admin: ' . htmlspecialchars($stmt->error) . '</div>';
        }
        $stmt->close();
    }
}
// Fetch all admins except superadmins
$admins = $conn->query('SELECT * FROM admins WHERE is_superadmin = 0 ORDER BY id ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
            <a href="../index.php" class="btn btn-outline-light btn-sm me-2" target="_blank">View Website</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Admin Users Management</h2>
        <a href="settings.php" class="btn btn-secondary mb-3">&larr; Back to Setting</a>

        <?php echo $msg; ?>
        <div class="card mb-4">
            <div class="card-header">Add New Admin</div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Existing Admins</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $admins->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <?php if ($row['id'] != $_SESSION[ADMIN_SESSION]): ?>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                                <?php else: ?>
                                <span class="text-muted">(You)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>