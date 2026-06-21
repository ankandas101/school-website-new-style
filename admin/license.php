<?php
session_start();
require_once '../includes/db.php';
define('ADMIN_SESSION', 'admin_logged_in');
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}
// Fetch license info from license_info table
$license_info = $conn->query('SELECT * FROM license_info WHERE id=1')->fetch_assoc();
$license_date = $license_info['license_date'] ?? '2025-12-31';
$license_domain =$license_info['license_domain'];
$license_type = $license_info['license_type'] ?? 'Single Domain';
$license_expiry_date = $license_info['license_expiry_date'] ?? '2030-12-31';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Information - Admin</title>
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
            <h3 class="mb-4">License Information</h3>
            <div class="row g-4">
              <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                  <div class="card-body">        
                    <h5 class="card-title mb-3">License Details</h5>
                    <p class="mb-1"><strong>License Date:</strong> <?php echo htmlspecialchars($license_date); ?></p>
                    <p class="mb-1"><strong>License Expiry Date: </strong> <?php echo htmlspecialchars($license_info['license_expiry_date']); ?></p>
                    <p class="mb-1"><strong>License Domain:</strong> <?php echo htmlspecialchars($license_domain); ?></p>
                    <p class="mb-1"><strong>License To: </strong> <?php echo htmlspecialchars($license_info['license_to']); ?></p>
                    <p class="mb-1"><strong>License Type: </strong> <?php echo htmlspecialchars($license_info['license_type']); ?></p>
                    <p class="mb-1"><strong>License Key: </strong>jbjadbja-dajdajbd-adad74-add12rt</p>
                    <hr>
                    <h5 class="card-title mb-3">Company Details</h5>
                    <p class="mb-1"><strong>Company Name:</strong><?php echo htmlspecialchars($license_info['company_name'] ?? ''); ?></p>
                    <p class="mb-1"><strong>Company Address:</strong> <?php echo htmlspecialchars($license_info['company_address'] ?? ''); ?></p>
                    <p class="mb-1"><strong>Support Line:</strong> +8801745009934</p>
                   <p class="mb-1"><strong>Developer Profile:</strong> 
                      <?php if (!empty($license_info['facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($license_info['facebook']); ?>" target="_blank"> Click Here</a>
                      <?php endif; ?>
                    </p>
                    
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                  <div class="card-body">
                    <h5 class="card-title mb-3">Update Information</h5>
                    <p class="mb-1"><strong>Current Version:</strong> V2.0</p>
                    <p class="mb-1"><strong>Release Date:</strong> 21-06-2026</p>
                    <hr>
                    <h5 class="card-title mb-3">Change Logs</h5>
                    <ul class="mb-0">
                      <li>New Homepage Design</li>
                      <li>Alpine.js Integration</li>
                      <li>Improve performance</li>
                      <li>All known bugs fixed</li>
                      <li>New sidebar added</li>
                      <li>Caches Update</li>
                    </ul>
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