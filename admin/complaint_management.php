<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
require_once '../includes/db.php';
require_once '../includes/classes.php';
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

$complaint = new Complaint($conn);
$msg = '';

$perPage = 10;
$page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$filterParam = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$filter = ($filterParam !== 'all') ? intval($filterParam) : null;

// Handle status update
if (isset($_GET['action']) && $_GET['action'] === 'status' && isset($_GET['id']) && isset($_GET['val'])) {
    $id = intval($_GET['id']);
    $val = intval($_GET['val']);
    if (in_array($val, [0, 1, 2, 3])) {
        $complaint->updateStatus($id, $val);
    }
    header('Location: complaint_management.php?filter=' . urlencode($filterParam) . '&page=' . $page);
    exit;
}

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $complaint->delete($id);
    header('Location: complaint_management.php?filter=' . urlencode($filterParam) . '&page=' . $page);
    exit;
}

// Fetch complaints
$complaints = $complaint->getAll($filter, $perPage, $offset);
$totalCount = $complaint->getCount($filter);
$totalPages = ceil($totalCount / $perPage);

$status_badges = [
    0 => '<span class="badge bg-warning text-dark">Pending</span>',
    1 => '<span class="badge bg-info">Reviewed</span>',
    2 => '<span class="badge bg-success">Resolved</span>',
    3 => '<span class="badge bg-danger">Rejected</span>'
];
$status_names = ['Pending','Reviewed','Resolved','Rejected'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:linear-gradient(90deg,#0f172a 0%,#1e3a8a 100%);">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="../index.php" class="btn btn-outline-light btn-sm me-2" target="_blank">View Website</a>
        <div class="ms-auto"><a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a></div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row gx-0">
        <?php include '_sidebar.php'; ?>
        <main class="col px-0" style="overflow-y:auto;">
            <div style="padding:1.5rem;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 p-3 bg-white rounded-3 shadow-sm">
                    <h4 class="m-0" style="color:#0f172a;">Complaint Management</h4>
                    <div class="text-muted small"><span id="datetime"></span></div>
                </div>

                <!-- Filter buttons -->
                <div class="mb-3 d-flex flex-wrap gap-1">
                    <a href="?filter=all" class="btn btn-sm <?php echo $filterParam === 'all' ? 'btn-secondary' : 'btn-outline-secondary'; ?>">All (<?php echo $complaint->getCount(null); ?>)</a>
                    <a href="?filter=0" class="btn btn-sm <?php echo $filterParam === '0' ? 'btn-warning text-dark' : 'btn-outline-warning'; ?>">Pending (<?php echo $complaint->getCount(0); ?>)</a>
                    <a href="?filter=1" class="btn btn-sm <?php echo $filterParam === '1' ? 'btn-info text-white' : 'btn-outline-info'; ?>">Reviewed (<?php echo $complaint->getCount(1); ?>)</a>
                    <a href="?filter=2" class="btn btn-sm <?php echo $filterParam === '2' ? 'btn-success' : 'btn-outline-success'; ?>">Resolved (<?php echo $complaint->getCount(2); ?>)</a>
                    <a href="?filter=3" class="btn btn-sm <?php echo $filterParam === '3' ? 'btn-danger' : 'btn-outline-danger'; ?>">Rejected (<?php echo $complaint->getCount(3); ?>)</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Details</th>
                                <th>File</th>
                                <th>Anon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = $offset;
                            $modal_rows = '';
                            if ($complaints && $complaints->num_rows > 0):
                                while ($row = $complaints->fetch_assoc()):
                                    $count++;
                                    $stat = (int)$row['status'];
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['complaint_id']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['student_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['complaint_type']); ?></td>
                                <td><small><?php echo !empty($row['incident_date']) ? htmlspecialchars($row['incident_date']) : 'N/A'; ?><br><span class="text-muted"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></span></small></td>
                                <td><button class="btn btn-sm btn-outline-secondary view-btn" data-id="<?php echo $row['id']; ?>">View</button></td>
                                <td>
                                    <?php if (!empty($row['attachment'])): ?>
                                        <a href="../uploads/complaints/<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank">
                                            <?php if (in_array(strtolower(pathinfo($row['attachment'], PATHINFO_EXTENSION)), ['jpg','jpeg','png'])): ?>
                                                <img src="../uploads/complaints/<?php echo htmlspecialchars($row['attachment']); ?>" style="max-width:50px;max-height:50px;border-radius:4px;">
                                            <?php else: ?>
                                                📎
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo $row['anonymous'] ? '🙈' : '—'; ?></td>
                                <td><?php echo $status_badges[$stat] ?? $status_badges[0]; ?></td>
                                <td style="min-width:130px;">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-warning dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?php echo $status_names[$stat] ?? 'Pending'; ?>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item <?php echo $stat === 0 ? 'active' : ''; ?>" href="?action=status&id=<?php echo $row['id']; ?>&val=0&filter=<?php echo $filterParam; ?>&page=<?php echo $page; ?>">Pending</a></li>
                                            <li><a class="dropdown-item <?php echo $stat === 1 ? 'active' : ''; ?>" href="?action=status&id=<?php echo $row['id']; ?>&val=1&filter=<?php echo $filterParam; ?>&page=<?php echo $page; ?>">Reviewed</a></li>
                                            <li><a class="dropdown-item <?php echo $stat === 2 ? 'active' : ''; ?>" href="?action=status&id=<?php echo $row['id']; ?>&val=2&filter=<?php echo $filterParam; ?>&page=<?php echo $page; ?>">Resolved</a></li>
                                            <li><a class="dropdown-item <?php echo $stat === 3 ? 'active' : ''; ?>" href="?action=status&id=<?php echo $row['id']; ?>&val=3&filter=<?php echo $filterParam; ?>&page=<?php echo $page; ?>">Rejected</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="?action=delete&id=<?php echo $row['id']; ?>&filter=<?php echo $filterParam; ?>&page=<?php echo $page; ?>" onclick="return confirm('Delete this complaint?');">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="12" class="text-center py-4">No complaints found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                    <div class="text-muted small">Page <?php echo $page; ?> of <?php echo $totalPages; ?> (<?php echo $totalCount; ?> total)</div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?filter=<?php echo $filterParam; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?filter=<?php echo $filterParam; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?filter=<?php echo $filterParam; ?>&page=<?php echo $page + 1; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- Detail Modals (outside table for valid HTML) -->
<?php
if ($complaints && $complaints->num_rows > 0):
    $complaints->data_seek(0);
    while ($row = $complaints->fetch_assoc()):
        $stat = (int)$row['status'];
?>
<div class="modal fade" id="modal-<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complaint: <?php echo htmlspecialchars($row['complaint_id']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Student:</strong> <?php echo htmlspecialchars($row['student_name'] ?? 'N/A'); ?></div>
                    <div class="col-md-3"><strong>Class:</strong> <?php echo htmlspecialchars($row['class_name'] ?? 'N/A'); ?></div>
                    <div class="col-md-3"><strong>Roll:</strong> <?php echo htmlspecialchars($row['roll_number'] ?? 'N/A'); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></div>
                    <div class="col-md-4"><strong>Type:</strong> <?php echo htmlspecialchars($row['complaint_type']); ?></div>
                    <div class="col-md-4"><strong>Incident:</strong> <?php echo !empty($row['incident_date']) ? htmlspecialchars($row['incident_date']) : 'N/A'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Submitted:</strong> <?php echo htmlspecialchars($row['created_at']); ?></div>
                    <div class="col-md-3"><strong>Anonymous:</strong> <?php echo $row['anonymous'] ? 'Yes' : 'No'; ?></div>
                    <div class="col-md-3"><strong>Status:</strong> <?php echo $status_badges[$stat]; ?></div>
                </div>
                <div class="mt-2"><strong>Details:</strong>
                    <p class="p-3 bg-light rounded mt-1"><?php echo nl2br(htmlspecialchars($row['complaint_details'])); ?></p>
                </div>
                <?php if (!empty($row['attachment'])): ?>
                <div class="mt-2">
                    <strong>Attachment:</strong><br>
                    <?php if (in_array(strtolower(pathinfo($row['attachment'], PATHINFO_EXTENSION)), ['jpg','jpeg','png'])): ?>
                        <img src="../uploads/complaints/<?php echo htmlspecialchars($row['attachment']); ?>" style="max-width:100%;max-height:400px;border-radius:8px;margin-top:8px;">
                    <?php else: ?>
                        <a href="../uploads/complaints/<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank" class="btn btn-outline-primary mt-2">📎 Download File</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endwhile; endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Initialize dropdowns and modals
document.addEventListener('DOMContentLoaded', function() {
    // View buttons - open modal
    document.querySelectorAll('.view-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var modal = new bootstrap.Modal(document.getElementById('modal-' + id));
            modal.show();
        });
    });
});

function updateDateTime() {
    var d = new Date();
    document.getElementById('datetime').textContent = d.toLocaleString(undefined, { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit' });
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>
</body>
</html>