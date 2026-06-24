<?php
header('Content-Type: application/xml; charset=utf-8');
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/classes.php';

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';

$urls = [];
// Static pages
$staticPages = [
    'index.php', 'about.php', 'contact.php', 'admission.php', 'event.php', 'forms.php', 'head_teacher.php', 'chairman.php', 'management_committee.php', 'notice.php', 'notices.php', 'photo_gallery.php', 'video_gallery.php', 'result.php', 'routine.php', 'student_info.php', 'student_of_the_year.php','review.php','complaint.php', 'teachers.php'
];
foreach ($staticPages as $page) {
    $urls[] = $baseUrl . $page;
}
// Events
$events = $conn->query('SELECT id FROM events');
if ($events && $events->num_rows > 0) {
    while ($row = $events->fetch_assoc()) {
        $urls[] = $baseUrl . 'event_detail.php?id=' . $row['id'];
    }
}
// Notices
$notices = $conn->query('SELECT id FROM notices WHERE status=1');
if ($notices && $notices->num_rows > 0) {
    while ($row = $notices->fetch_assoc()) {
        $urls[] = $baseUrl . 'notice.php?id=' . $row['id'];
    }
}
// Teachers
$teachers = $conn->query('SELECT id FROM teachers');
if ($teachers && $teachers->num_rows > 0) {
    while ($row = $teachers->fetch_assoc()) {
        $urls[] = $baseUrl. 'teacher_detail.php?id='. $row['id'];
    }
}
// Student Info
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as $url) {
    echo "  <url>\n    <loc>" . htmlspecialchars($url, ENT_XML1, 'UTF-8') . "</loc>\n  </url>\n";
}
echo "</urlset>";