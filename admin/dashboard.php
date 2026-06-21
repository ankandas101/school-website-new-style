<?php
// Set secure session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // প্রোডাকশনে আপনার ডোমেইন দিন, যেমন: 'example.com'
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

require_once '../includes/db.php'; // একবারেই যথেষ্ট
define('ADMIN_SESSION', 'admin_logged_in');
require_once __DIR__ . '/session_guard.php';

// Count classes
class DashboardStats {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function count($table) {
        // নিশ্চিত করুন $table কে ডাইনামিক না করেন, নাহলে SQL Injection ঝুঁকি আছে
        $allowedTables = [
            'notices', 'teachers', 'videos', 'important_links', 
            'contact_messages', 'student_of_the_year', 'forms'
        ];
        if (!in_array($table, $allowedTables)) {
            throw new Exception("Invalid table name.");
        }
        $result = $this->conn->query("SELECT COUNT(*) as total FROM $table");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}

$stats = new DashboardStats($conn);

$notices = $stats->count('notices');
$teachers = $stats->count('teachers');
$videos = $stats->count('videos');
$important_links = $stats->count('important_links');

$active_sliders_result = $conn->query('SELECT COUNT(*) as total FROM sliders WHERE status=1');
$active_sliders = ($active_sliders_result && $row = $active_sliders_result->fetch_assoc()) ? $row['total'] : 0;

$active_photos_result = $conn->query('SELECT COUNT(*) as total FROM gallery_photos WHERE status=1');
$active_photos = ($active_photos_result && $row = $active_photos_result->fetch_assoc()) ? $row['total'] : 0;

$active_videos_result = $conn->query('SELECT COUNT(*) as total FROM gallery_videos WHERE status=1');
$active_videos = ($active_videos_result && $row = $active_videos_result->fetch_assoc()) ? $row['total'] : 0;

$events_count_result = $conn->query('SELECT COUNT(*) as total FROM events');
$events_count = ($events_count_result && $row = $events_count_result->fetch_assoc()) ? $row['total'] : 0;

$contact_messages = $stats->count('contact_messages');
$students_of_year = $stats->count('student_of_the_year');
$forms_count = $stats->count('forms');



//Calculate student totals
$total_students = $total_male = $total_female = 0;
$student_result = $conn->query('SELECT total_students, male_students, female_students FROM student_info');
if ($student_result) {
    while ($row = $student_result->fetch_assoc()) {
        $total_students += (int)$row['total_students'];
        $total_male += (int)$row['male_students'];
        $total_female += (int)$row['female_students'];
    }
}
$students_result = $conn->query('SELECT SUM(total_students) as total_students, SUM(male_students) as total_male, SUM(female_students) as total_female FROM student_info');
$students_row = $students_result ? $students_result->fetch_assoc() : [];
$total_students = $students_row['total_students'] ?? 0;
$total_male = $students_row['total_male'] ?? 0;
$total_female = $students_row['total_female'] ?? 0;

class SchoolInfo {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function get() {
        $sql = 'SELECT * FROM school_info WHERE id=1';
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
$schoolInfoObj = new SchoolInfo($conn);
$school_info = $schoolInfoObj->get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - School CMS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <a href="../index.php" class="btn btn-outline-light btn-sm me-2" target="_blank">View Website</a>
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
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2" style="font-size:1.1rem;">
                        <div>
                            <strong>School Name:</strong> <?= htmlspecialchars($school_info['school_name'] ?? '') ?> &nbsp;|
                            <strong>EIIN:</strong> <?= htmlspecialchars($school_info['eiin'] ?? '') ?>
                            <strong>Date/Time:</strong> <span id="datetime"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Students</h5>
                            <p class="display-6 fw-bold text-info">
                                <?= $total_students ?>
                                <span style="font-size:1rem; color:#333; display:block;">Male: <?= $total_male ?> | Female: <?= $total_female ?></span>
                            </p>
                            <a href="student_info.php" class="btn btn-info w-100">Manage Students</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Teachers</h5>
                            <p class="display-6 fw-bold text-success"><?= $teachers ?></p>
                            <a href="teachers.php" class="btn btn-info w-100">Manage Teachers</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Slider</h5>
                            <p class="display-6 fw-bold text-info"><?= $active_sliders ?></p>
                            <a href="slider.php" class="btn btn-info w-100">Manage Slider</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Photo Gallery</h5>
                            <p class="display-6 fw-bold text-secondary"><?= $active_photos ?></p>
                            <a href="gallery_photos.php" class="btn btn-secondary w-100">Manage Photos</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Video Gallery</h5>
                            <p class="display-6 fw-bold text-dark"><?= $active_videos ?></p>
                            <a href="gallery_videos.php" class="btn btn-dark w-100">Manage Videos</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Important Links</h5>
                            <p class="display-6 fw-bold text-danger"><?= $important_links ?></p>
                            <a href="sidebar.php#important-links" class="btn btn-danger w-100">Manage Links</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Events/Blog</h5>
                            <p class="display-6 fw-bold text-primary"><?= $events_count ?></p>
                            <a href="events.php" class="btn btn-primary w-100">Manage Events</a>
                        </div>
                    </div>
                </div>
<!-- 
                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Contact Messages</h5>
                            <p class="display-6 fw-bold text-primary"><?= $contact_messages ?></p>
                            <a href="contact_messages.php" class="btn btn-info w-100">View Messages</a>
                        </div>
                    </div>
                </div>
-->
                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">কৃতি শিক্ষার্থী</h5>
                            <p class="display-6 fw-bold text-warning"><?= $students_of_year ?></p>
                            <a href="student_of_the_year.php" class="btn btn-warning w-100">Manage Students of the Year</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Downloads Management</h5>
                            <p class="display-6 fw-bold text-primary"><?= $forms_count ?></p>
                            <a href="forms.php" class="btn btn-primary w-100">Manage Downloads </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links Row (optional) -->
            <div class="row g-3 mt-4">
                <div class="col-md-2 col-6">
                    <a href="slider.php" class="btn btn-outline-primary w-100 py-3">Slider</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="messages.php" class="btn btn-outline-success w-100 py-3">Messages</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="notices.php" class="btn btn-outline-warning w-100 py-3">Notices</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="teachers.php" class="btn btn-outline-info w-100 py-3">Teachers</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="management_committee_admin.php" class="btn btn-outline-primary w-100 py-3">Management Committee</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="student_info.php" class="btn btn-outline-info w-100 py-3">Student Info</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="routines.php" class="btn btn-outline-warning w-100 py-3">Routine</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="results.php" class="btn btn-outline-primary w-100 py-3">Results</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="gallery_photos.php" class="btn btn-outline-secondary w-100 py-3">Photo Gallery</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="gallery_videos.php" class="btn btn-outline-dark w-100 py-3">Video Gallery</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="settings.php" class="btn btn-outline-dark w-100 py-3">Settings</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="student_of_the_year.php" class="btn btn-outline-warning w-100 py-3">কৃতি শিক্ষার্থী</a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="forms.php" class="btn btn-outline-primary w-100 py-3">Forms</a>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Add "Back to Dashboard" button to all admin pages except dashboard.php
document.addEventListener('DOMContentLoaded', function() {
    if (!window.location.pathname.endsWith('dashboard.php')) {
        var btn = document.createElement('a');
        btn.href = 'dashboard.php';
        btn.className = 'btn btn-secondary mb-3';
        btn.innerHTML = '&larr; Back to Dashboard';
        var main = document.querySelector('main') || document.querySelector('.container, .container-fluid');
        if (main) main.insertBefore(btn, main.firstChild);
    }
});

// Date/time display
function updateDateTime() {
    var now = new Date();
    var options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    document.getElementById('datetime').textContent = now.toLocaleString(undefined, options);
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
@media (min-width: 768px) {
    .sidebar {
        min-height: 100vh;
        position: sticky;
        top: 0;
    }
}
.sidebar .nav-link {
    font-size: 1.08rem;
    color: #333;
    border-radius: 0.5rem;
    transition: background 0.2s;
}
.sidebar .nav-link.active, .sidebar .nav-link:hover {
    background: #e9ecef;
    color: #0d6efd;
}
</style>

</body>
</html>

