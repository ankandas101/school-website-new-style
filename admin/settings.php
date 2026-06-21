<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/csrf.php';
define('ADMIN_SESSION', 'admin_logged_in');

// Auth check
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}

//
class SchoolInfo {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function get() {
        $sql = 'SELECT * FROM school_info WHERE id=1';
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    public function update($school_name, $logo, $banner, $eiin, $about, $established, $address, $phone, $email, $mpo_code, $school_code, $google_map) {
        $fields = 'school_name=?, eiin=?, about=?, established=?, address=?, phone=?, email=?, mpo_code=?, school_code=?, google_map=?, updated_at=NOW()';
        $params = [$school_name, $eiin, $about, $established, $address, $phone, $email, $mpo_code, $school_code, $google_map];
        $types = 'ssssssssss';
        if ($logo && $banner) {
            $fields = 'school_name=?, logo=?, banner=?, eiin=?, about=?, established=?, address=?, phone=?, email=?, mpo_code=?, school_code=?, google_map=?, updated_at=NOW()';
            $params = [$school_name, $logo, $banner, $eiin, $about, $established, $address, $phone, $email, $mpo_code, $school_code, $google_map];
            $types = 'ssssssssssss';
        } elseif ($logo) {
            $fields = 'school_name=?, logo=?, eiin=?, about=?, established=?, address=?, phone=?, email=?, mpo_code=?, school_code=?, google_map=?, updated_at=NOW()';
            $params = [$school_name, $logo, $eiin, $about, $established, $address, $phone, $email, $mpo_code, $school_code, $google_map];
            $types = 'sssssssssss';
        } elseif ($banner) {
            $fields = 'school_name=?, banner=?, eiin=?, about=?, established=?, address=?, phone=?, email=?, mpo_code=?, school_code=?, google_map=?, updated_at=NOW()';
            $params = [$school_name, $banner, $eiin, $about, $established, $address, $phone, $email, $mpo_code, $school_code, $google_map];
            $types = 'sssssssssss';
        }

        $sql = "UPDATE school_info SET $fields WHERE id=1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }
    public function createIfNotExists() {
        $sql = 'SELECT id FROM school_info WHERE id=1';
        $result = $this->conn->query($sql);
        if ($result->num_rows == 0) {
            $this->conn->query("INSERT INTO school_info (id, school_name, logo, banner, eiin, about) VALUES (1, '', '', '', '', '')");
        }
    }
}
// FooterInfo class
class FooterInfo {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function get() {
        $sql = 'SELECT * FROM footer_info WHERE id=1';
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    public function update($address, $phone, $email, $facebook, $twitter, $youtube) {
        $stmt = $this->conn->prepare('UPDATE footer_info SET address=?, phone=?, email=?, facebook=?, twitter=?, youtube=?, updated_at=NOW() WHERE id=1');
        $stmt->bind_param('ssssss', $address, $phone, $email, $facebook, $twitter, $youtube);
        return $stmt->execute();
    }
    public function createIfNotExists() {
        $sql = 'SELECT id FROM footer_info WHERE id=1';
        $result = $this->conn->query($sql);
        if ($result->num_rows == 0) {
            $this->conn->query("INSERT INTO footer_info (id, address, phone, email, facebook, twitter, youtube) VALUES (1, '', '', '', '', '', '')");
        }
    }
}
// SEOSettings class declaration moved below SiteSettings class

// SiteSettings class for managing Cloudflare Turnstile keys
class SiteSettings {
    private $conn;
    
    public function __construct($conn) { 
        $this->conn = $conn; 
    }
    
    public function get($setting_name) {
        $stmt = $this->conn->prepare('SELECT setting_value FROM site_settings WHERE setting_name=?');
        $stmt->bind_param('s', $setting_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['setting_value'] : '';
    }
    
    public function getAll() {
        $settings = [];
        $sql = 'SELECT setting_name, setting_value FROM site_settings';
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_name']] = $row['setting_value'];
        }
        return $settings;
    }
    
    public function update($setting_name, $setting_value) {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as count FROM site_settings WHERE setting_name=?');
        $stmt->bind_param('s', $setting_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $stmt = $this->conn->prepare('UPDATE site_settings SET setting_value=?, updated_at=NOW() WHERE setting_name=?');
            $stmt->bind_param('ss', $setting_value, $setting_name);
        } else {
            $stmt = $this->conn->prepare('INSERT INTO site_settings (setting_name, setting_value) VALUES (?, ?)');
            $stmt->bind_param('ss', $setting_name, $setting_value);
        }
        return $stmt->execute();
    }
    
    public function createIfNotExists($setting_name, $default_value = '') {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as count FROM site_settings WHERE setting_name=?');
        $stmt->bind_param('s', $setting_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] == 0) {
            $stmt = $this->conn->prepare('INSERT INTO site_settings (setting_name, setting_value) VALUES (?, ?)');
            $stmt->bind_param('ss', $setting_name, $default_value);
            $stmt->execute();
        }
    }
}

// Continue with SEOSettings class
class SEOSettings {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function get($page) {
        $stmt = $this->conn->prepare('SELECT * FROM seo_settings WHERE page=?');
        $stmt->bind_param('s', $page);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function update($page, $meta_title, $meta_description, $meta_keywords) {
        $exists = $this->get($page);
        if ($exists) {
            $stmt = $this->conn->prepare('UPDATE seo_settings SET meta_title=?, meta_description=?, meta_keywords=?, updated_at=NOW() WHERE page=?');
            $stmt->bind_param('ssss', $meta_title, $meta_description, $meta_keywords, $page);
        } else {
            $stmt = $this->conn->prepare('INSERT INTO seo_settings (page, meta_title, meta_description, meta_keywords) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $page, $meta_title, $meta_description, $meta_keywords);
        }
        return $stmt->execute();
    }
    public function createIfNotExists($page) {
        $stmt = $this->conn->prepare('SELECT id FROM seo_settings WHERE page=?');
        $stmt->bind_param('s', $page);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $stmt2 = $this->conn->prepare("INSERT INTO seo_settings (page, meta_title, meta_description, meta_keywords) VALUES (?, ?, ?, ?)");
            $empty = '';
            $stmt2->bind_param('ssss', $page, $empty, $empty, $empty);
            $stmt2->execute();
            $stmt2->close();
        }
        $stmt->close();
    }
}

class SchoolStatistics {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }

    public function getAll() {
        $sql = 'SELECT * FROM school_statistics ORDER BY sort_order ASC, id ASC';
        return $this->conn->query($sql);
    }

    public function get($id) {
        $stmt = $this->conn->prepare('SELECT * FROM school_statistics WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function add($title, $value, $sort_order, $status) {
        $stmt = $this->conn->prepare('INSERT INTO school_statistics (title, value, sort_order, status) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $title, $value, $sort_order, $status);
        return $stmt->execute();
    }

    public function update($id, $title, $value, $sort_order, $status) {
        $stmt = $this->conn->prepare('UPDATE school_statistics SET title=?, value=?, sort_order=?, status=?, updated_at=NOW() WHERE id=?');
        $stmt->bind_param('ssiii', $title, $value, $sort_order, $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare('DELETE FROM school_statistics WHERE id=?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}

$school = new SchoolInfo($conn);
$footer = new FooterInfo($conn);
$seo = new SEOSettings($conn);
$statistics = new SchoolStatistics($conn);
$site_settings = new SiteSettings($conn);
$seo_pages = ['index'=>'Home', 'about'=>'About', 'contact'=>'Contact', 'notices'=>'Notices', 'teachers'=>'Teachers'];
$school->createIfNotExists();
$footer->createIfNotExists();
foreach (array_keys($seo_pages) as $page) {
    $seo->createIfNotExists($page);
}

$success = $error = '';
$school_info = $school->get();
$footer_info = $footer->get();
$meta_code = '';
$stat_edit = null;
$statistics_list = $statistics->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $error = 'Invalid security token. Please try again.';
} else {
// Handle school info update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_school'])) {
    $school_name = trim($_POST['school_name'] ?? '');
    $eiin = trim($_POST['eiin'] ?? '');
    $about = trim($_POST['about'] ?? '');
    $established = trim($_POST['established'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mpo_code = trim($_POST['mpo_code'] ?? '');
    $school_code = trim($_POST['school_code'] ?? '');
    $logo = $banner = null;
    $google_map = $_POST['google_map'] ?? '';
    $max_size = 2 * 1024 * 1024; // 2MB
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            if ($_FILES['logo']['size'] > $max_size) {
                $error = 'Logo file is too large. Maximum size is 2MB. Please upload a smaller image.';
            } else {
                $logo = 'logo_' . time() . '_' . rand(100,999) . '.' . $ext;
                move_uploaded_file($_FILES['logo']['tmp_name'], '../assets/images/' . $logo);
            }
        }
    }
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            if ($_FILES['banner']['size'] > $max_size) {
                $error = 'Banner file is too large. Maximum size is 2MB. Please upload a smaller image.';
            } else {
                $banner = 'banner_' . time() . '_' . rand(100,999) . '.' . $ext;
                move_uploaded_file($_FILES['banner']['tmp_name'], '../assets/images/' . $banner);
            }
        }
    }
    if ($school->update($school_name, $logo, $banner, $eiin, $about, $established, $address, $phone, $email, $mpo_code, $school_code, $google_map)) {
        $success = 'School info updated successfully!';
    } else {
        $error = 'Failed to update school info.';
    }
}
// Handle footer info update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_footer'])) {
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $facebook = trim($_POST['facebook'] ?? '');
    $twitter = trim($_POST['twitter'] ?? '');
    $youtube = trim($_POST['youtube'] ?? '');
    $footer_short = trim($_POST['footer_short'] ?? '');
    $footer_logo = null;
    $max_size = 2 * 1024 * 1024; // 2MB
    if (isset($_FILES['footer_logo']) && $_FILES['footer_logo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['footer_logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            if ($_FILES['footer_logo']['size'] > $max_size) {
                $error = 'Footer logo file is too large. Maximum size is 2MB. Please upload a smaller image.';
            } else {
                $footer_logo = 'footer_logo_' . time() . '_' . rand(100,999) . '.' . $ext;
                move_uploaded_file($_FILES['footer_logo']['tmp_name'], '../assets/images/' . $footer_logo);
            }
        }
    }
    // Update only the fields provided
    $set = [];
    $params = [];
    $types = '';
    $set[] = 'address=?'; $params[] = $address; $types .= 's';
    $set[] = 'phone=?'; $params[] = $phone; $types .= 's';
    $set[] = 'email=?'; $params[] = $email; $types .= 's';
    $set[] = 'facebook=?'; $params[] = $facebook; $types .= 's';
    $set[] = 'twitter=?'; $params[] = $twitter; $types .= 's';
    $set[] = 'youtube=?'; $params[] = $youtube; $types .= 's';
    $set[] = 'footer_short=?'; $params[] = $footer_short; $types .= 's';
    if ($footer_logo) { $set[] = 'footer_logo=?'; $params[] = $footer_logo; $types .= 's'; }
    $sql = 'UPDATE footer_info SET ' . implode(',', $set) . ', updated_at=NOW() WHERE id=1';
    $stmt = $conn->prepare($sql);
    if ($types && $params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();
    $success = 'Footer/contact info updated successfully!';
    $footer_info = $footer->get();
}
// Handle SEO update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_seo'])) {
    $page = $_POST['seo_page'];
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');
    if ($seo->update($page, $meta_title, $meta_description, $meta_keywords)) {
        $success = 'SEO info updated for ' . htmlspecialchars($seo_pages[$page]) . ' page!';
    } else {
        $error = 'Failed to update SEO info.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_google_map'])) {
    $google_map = $_POST['google_map'] ?? '';
    $school_info = $school->get();
    $school_name = $school_info['school_name'] ?? '';
    $logo = $school_info['logo'] ?? '';
    $banner = $school_info['banner'] ?? '';
    $eiin = $school_info['eiin'] ?? '';
    $about = $school_info['about'] ?? '';
    $established = $school_info['established'] ?? '';
    $address = $school_info['address'] ?? '';
    $phone = $school_info['phone'] ?? '';
    $email = $school_info['email'] ?? '';
    $mpo_code = $school_info['mpo_code'] ?? '';
    $school_code = $school_info['school_code'] ?? '';
    $school->update($school_name, $logo, $banner, $eiin, $about, $established, $address, $phone, $email, $mpo_code, $school_code, $google_map);
    $success = 'Google Map embed code updated successfully.';
    $school_info = $school->get();
}

if (isset($_GET['edit_statistics'])) {
    $edit_id = intval($_GET['edit_statistics']);
    $stat_edit = $statistics->get($edit_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_statistics'])) {
    $stat_id = intval($_POST['stat_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $value = trim($_POST['value'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 1);
    $status = intval($_POST['status'] ?? 1);

    if ($title !== '' && $value !== '') {
        if ($stat_id > 0) {
            if ($statistics->update($stat_id, $title, $value, $sort_order, $status)) {
                $success = 'Statistic updated successfully!';
            } else {
                $error = 'Failed to update statistic.';
            }
        } else {
            if ($statistics->add($title, $value, $sort_order, $status)) {
                $success = 'Statistic added successfully!';
            } else {
                $error = 'Failed to add statistic.';
            }
        }
        $statistics_list = $statistics->getAll();
        $stat_edit = null;
    } else {
        $error = 'Title and value are required.';
    }
}

if (isset($_GET['delete_statistics'])) {
    $delete_id = intval($_GET['delete_statistics']);
    if ($statistics->delete($delete_id)) {
        $success = 'Statistic deleted successfully!';
    } else {
        $error = 'Failed to delete statistic.';
    }
    header('Location: settings.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_turnstile_settings'])) {
    $site_settings->update('turnstile_site_key', trim($_POST['turnstile_site_key'] ?? ''));
    $site_settings->update('turnstile_secret_key', trim($_POST['turnstile_secret_key'] ?? ''));
    $site_settings->update('CloudflareTurnstile_Status', trim($_POST['turnstile_status'] ?? '0'));
    $success = 'Turnstile settings updated successfully.';
}

// Handle meta code update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_meta_code'])) {
    $new_meta_code = $_POST['meta_code'] ?? '';
    $stmt = $conn->prepare('UPDATE meta_code SET code=? WHERE id=1');
    $stmt->bind_param('s', $new_meta_code);
    $stmt->execute();
    $stmt->close();
    $success = 'Meta code updated successfully!';
    $meta_code = $new_meta_code;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
        }

        .settings-main {
            padding-bottom: 3rem;
        }

        .settings-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .settings-page-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .settings-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .settings-tabs {
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 1rem;
        }

        .settings-tabs .nav-link {
            color: #64748b;
            font-weight: 600;
            border: 0;
            border-radius: 10px 10px 0 0;
            padding: 0.65rem 0.9rem;
        }

        .settings-tabs .nav-link.active {
            color: #0d6efd;
            background: #fff;
            border-bottom: 2px solid #0d6efd;
        }

        .settings-card {
            border: 0;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            height: 100%;
        }

        .settings-card .card-header {
            border: 0;
            border-radius: 16px 16px 0 0;
            padding: 0.95rem 1rem;
            font-weight: 600;
            font-size: 0.98rem;
        }

        .settings-card .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .settings-card .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #334155;
        }

        .settings-card .form-control,
        .settings-card .form-select,
        .settings-card textarea {
            border-radius: 10px;
            border: 1px solid #d8dee9;
        }

        .settings-card .form-control:focus,
        .settings-card .form-select:focus,
        .settings-card textarea:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .settings-card .btn {
            border-radius: 10px;
        }

        .settings-card .table {
            margin-bottom: 0;
        }

        .settings-card .table th {
            font-size: 0.88rem;
            white-space: nowrap;
        }

        .settings-card textarea {
            min-height: 120px;
        }
    </style>
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
        <main class="col-md-9 col-lg-10 px-md-4 d-flex flex-column settings-main">
          <div class="my-4">
            <div class="settings-page-header">
                <h3>Settings</h3>
                <div class="settings-actions">
                    <a href="dashboard.php" class="btn btn-secondary">&larr; Back to Dashboard</a>
                    <a href="change_password.php" class="btn btn-primary">Change Password</a>
                    <a href="admins.php" class="btn btn-primary">Admin Users</a>
                    <a href="license.php" class="btn btn-primary">License</a>
                </div>
            </div>

            <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

            <ul class="nav nav-tabs settings-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Information</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab">Statistics</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">SEO</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#map" type="button" role="tab">Map</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="turnstile-tab" data-bs-toggle="tab" data-bs-target="#turnstile" type="button" role="tab">Turnstile</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    <div class="row g-4">
                        <!-- School Info -->
                        <div class="col-md-6">
                            <div class="card settings-card h-100">
                                <div class="card-header bg-info text-white">School Info</div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label class="form-label">Institute Name</label>
                                            <input type="text" class="form-control" name="school_name" value="<?php echo htmlspecialchars($school_info['school_name'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">EIIN</label>
                                            <input type="text" class="form-control" name="eiin" value="<?php echo htmlspecialchars($school_info['eiin'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Established</label>
                                            <input type="text" class="form-control" name="established" value="<?php echo htmlspecialchars($school_info['established'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($school_info['address'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($school_info['phone'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($school_info['email'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">About</label>
                                            <textarea class="form-control" name="about" rows="3"><?php echo htmlspecialchars($school_info['about'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Logo</label><br>
                                            <?php if (!empty($school_info['logo'])): ?>
                                                <img src="../assets/images/<?php echo htmlspecialchars($school_info['logo']); ?>" width="60" class="mb-2" alt="Logo">
                                            <?php endif; ?>
                                            <input type="file" class="form-control" name="logo" accept=".jpg,.jpeg,.png,.webp">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Banner</label><br>
                                            <?php if (!empty($school_info['banner'])): ?>
                                                <img src="../assets/images/<?php echo htmlspecialchars($school_info['banner']); ?>" width="120" class="mb-2" alt="Banner">
                                            <?php endif; ?>
                                            <input type="file" class="form-control" name="banner" accept=".jpg,.jpeg,.png,.webp">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">MPO Code</label>
                                            <input type="text" class="form-control" name="mpo_code" value="<?php echo htmlspecialchars($school_info['mpo_code'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Institute Code </label>
                                            <input type="text" class="form-control" name="school_code" value="<?php echo htmlspecialchars($school_info['school_code'] ?? ''); ?>">
                                        </div>
                                        <button type="submit" name="update_school" class="btn btn-info">Update Institute Info</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Footer/Contact Info -->
                        <div class="col-md-6">
                            <div class="card settings-card h-100">
                                <div class="card-header bg-success text-white">Footer & Contact Info</div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($footer_info['address'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($footer_info['phone'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($footer_info['email'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Facebook</label>
                                            <input type="url" class="form-control" name="facebook" value="<?php echo htmlspecialchars($footer_info['facebook'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Twitter</label>
                                            <input type="url" class="form-control" name="twitter" value="<?php echo htmlspecialchars($footer_info['twitter'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">YouTube</label>
                                            <input type="url" class="form-control" name="youtube" value="<?php echo htmlspecialchars($footer_info['youtube'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Short Details</label>
                                            <textarea class="form-control" name="footer_short" rows="2"><?php echo htmlspecialchars($footer_info['footer_short'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Footer Logo</label><br>
                                            <?php if (!empty($footer_info['footer_logo'])): ?>
                                                <img src="../assets/images/<?php echo htmlspecialchars($footer_info['footer_logo']); ?>" width="60" class="mb-2" alt="Footer Logo">
                                            <?php endif; ?>
                                            <input type="file" class="form-control" name="footer_logo" accept=".jpg,.jpeg,.png,.webp">
                                        </div>
                                        <button type="submit" name="update_footer" class="btn btn-success">Update Footer/Contact Info</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="stats" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card settings-card h-100">
                                <div class="card-header bg-dark text-white">School Statistics</div>
                                <div class="card-body">
                                    <form method="post" class="mb-4">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="stat_id" value="<?php echo intval($stat_edit['id'] ?? 0); ?>">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($stat_edit['title'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Value</label>
                                                <input type="text" class="form-control" name="value" value="<?php echo htmlspecialchars($stat_edit['value'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Sort Order</label>
                                                <input type="number" class="form-control" name="sort_order" value="<?php echo htmlspecialchars((string)($stat_edit['sort_order'] ?? 1)); ?>" min="1">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Status</label>
                                                <select class="form-select" name="status">
                                                    <option value="1" <?php echo (($stat_edit['status'] ?? 1) == 1) ? 'selected' : ''; ?>>Active</option>
                                                    <option value="0" <?php echo (($stat_edit['status'] ?? 1) == 0) ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="submit" name="save_statistics" class="btn btn-dark w-100">Save</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Value</th>
                                                    <th>Order</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($statistics_list && $statistics_list->num_rows > 0): $i=1; while ($row = $statistics_list->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['value']); ?></td>
                                                    <td><?php echo htmlspecialchars((string)$row['sort_order']); ?></td>
                                                    <td><?php echo $row['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'; ?></td>
                                                    <td>
                                                        <a href="settings.php?edit_statistics=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <a href="settings.php?delete_statistics=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this statistic?');">Delete</a>
                                                    </td>
                                                </tr>
                                                <?php endwhile; else: ?>
                                                <tr><td colspan="6" class="text-center">No statistics found.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="seo" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card settings-card h-100">
                                <div class="card-header bg-warning text-dark">SEO Settings</div>
                                <div class="card-body">
                                    <?php foreach ($seo_pages as $page => $label): $seo_data = $seo->get($page); ?>
                                    <form method="post" class="mb-4">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="seo_page" value="<?php echo $page; ?>">
                                        <h6><?php echo $label; ?> Page</h6>
                                        <div class="mb-2">
                                            <label class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" name="meta_title" value="<?php echo htmlspecialchars($seo_data['meta_title'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Meta Description</label>
                                            <textarea class="form-control" name="meta_description" rows="2"><?php echo htmlspecialchars($seo_data['meta_description'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Meta Keywords</label>
                                            <input type="text" class="form-control" name="meta_keywords" value="<?php echo htmlspecialchars($seo_data['meta_keywords'] ?? ''); ?>">
                                        </div>
                                        <button type="submit" name="update_seo" class="btn btn-warning btn-sm">Update SEO</button>
                                    </form>
                                    <hr>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-4">
                      <div class="col-12">
                        <div class="card settings-card h-100">
                          <div class="card-header bg-secondary text-white">Meta Code (for &lt;head&gt;)</div>
                          <div class="card-body">
                            <form method="post">
                              <?php echo csrf_field(); ?>
                              <div class="mb-3">
                                <label class="form-label">Meta Code (HTML, will be added before &lt;/head&gt;)</label>
                                <textarea class="form-control" name="meta_code" rows="5"><?php echo htmlspecialchars($meta_code); ?></textarea>
                              </div>
                              <button type="submit" name="update_meta_code" class="btn btn-secondary">Update Meta Code</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="map" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card settings-card h-100">
                                <div class="card-header bg-info text-white">Location Map</div>
                                <div class="card-body text-center">
                                    <form method="post">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label class="form-label">Google Map Embed Code</label>
                                            <textarea class="form-control" name="google_map" rows="4"><?php echo htmlspecialchars($school_info['google_map'] ?? ''); ?></textarea>
                                        </div>
                                        <button type="submit" name="update_google_map" class="btn btn-info">Update Map</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="turnstile" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card settings-card h-100 mb-4">
                                <div class="card-header bg-primary text-white">Cloudflare Turnstile Settings</div>
                                <div class="card-body">
                                    <form method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="update_turnstile_settings" value="1">
                                        <div class="mb-3">
                                            <label for="turnstile_site_key" class="form-label">Site Key</label>
                                            <input type="text" class="form-control" id="turnstile_site_key" name="turnstile_site_key" value="<?php echo htmlspecialchars($site_settings->get('turnstile_site_key')); ?>" required>
                                            <div class="form-text">Your Cloudflare Turnstile site key</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="turnstile_secret_key" class="form-label">Secret Key</label>
                                            <input type="text" class="form-control" id="turnstile_secret_key" name="turnstile_secret_key" value="<?php echo htmlspecialchars($site_settings->get('turnstile_secret_key')); ?>" required>
                                            <div class="form-text">Your Cloudflare Turnstile secret key</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="turnstile_status" class="form-label">Turnstile Status</label>
                                            <select class="form-select" id="turnstile_status" name="turnstile_status">
                                                <option value="1" <?php echo $site_settings->get('CloudflareTurnstile_Status') === '1' ? 'selected' : ''; ?>>Active</option>
                                                <option value="0" <?php echo $site_settings->get('CloudflareTurnstile_Status') === '0' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                            <div class="form-text">Enable or disable Cloudflare Turnstile captcha on the login page</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Turnstile Settings</button>
                                    </form>
                                </div>
                            </div>
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