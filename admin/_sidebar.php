<?php
// _sidebar.php
$currentPage = basename($_SERVER['PHP_SELF']);
$showSoftwareLogin = strtolower((string)env('SYSTEM_SOFTWARE', 'false')) === 'true';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
  :root {
    --admin-bg: #f5f7fb;
    --admin-surface: #ffffff;
    --admin-sidebar-bg: #0f172a;
    --admin-sidebar-hover: #142140;
    --admin-sidebar-active: #1d4ed8;
    --admin-border: #e5e7eb;
    --admin-text: #0f172a;
    --admin-muted: #64748b;
    --sidebar-width: 260px;
    --sidebar-width-tablet: 220px;
    --top-nav-height: 64px;
    --radius: 14px;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 0.875rem;
    --spacing-lg: 1rem;
    --spacing-xl: 1.5rem;
  }

  html, body {
    height: 100%;
  }

  body {
    background: var(--admin-bg);
    margin: 0;
    overflow-x: hidden;
  }

  nav.navbar {
    position: sticky;
    top: 0;
    z-index: 1040;
    min-height: var(--top-nav-height);
  }

  body > .container-fluid {
    padding: 0;
    overflow: hidden;
  }

  body > .container-fluid > .row.gx-0 {
    display: grid;
    grid-template-columns: var(--sidebar-width) minmax(0, 1fr);
    height: calc(100vh - var(--top-nav-height));
    min-height: calc(100vh - var(--top-nav-height));
  }

  nav#sidebarMenu {
    position: sticky;
    top: 0;
    height: calc(100vh - var(--top-nav-height));
    width: var(--sidebar-width);
    flex: 0 0 var(--sidebar-width);
    background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
    color: #e5eefc;
    overflow: hidden;
    border-right: 1px solid rgba(255, 255, 255, 0.06);
    z-index: 1030;
  }

  nav#sidebarMenu .sidebar-scroll {
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 1rem 0.75rem 1rem;
  }

  nav#sidebarMenu .sidebar-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1rem 0.9rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    margin-bottom: 0.75rem;
  }

  nav#sidebarMenu .sidebar-brand-icon {
    width: 44px;
    height: 44px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
    color: #fff;
    border-radius: 12px;
    font-size: 1rem;
  }

  nav#sidebarMenu .sidebar-brand-text {
    font-size: 0.82rem;
    line-height: 1.2;
    color: #cbd5e1;
  }

  nav#sidebarMenu .nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.78rem 0.9rem;
    color: #d7e2ff;
    border-radius: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
  }

  nav#sidebarMenu .nav-link:hover {
    background: var(--admin-sidebar-hover);
    color: #fff;
    transform: translateX(2px);
  }

  nav#sidebarMenu .nav-link i {
    min-width: 18px;
    font-size: 1rem;
  }

  nav#sidebarMenu .nav-link.active {
    background: linear-gradient(90deg, #1d4ed8, #2563eb);
    color: #fff;
    font-weight: 600;
  }

  nav#sidebarMenu .nav-link.text-danger {
    color: #fda4af !important;
  }

  nav#sidebarMenu .nav-link.text-danger:hover {
    background: rgba(220, 38, 38, 0.15);
  }

  nav#sidebarMenu .sidebar-section-title {
    font-size: 0.72rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #64748b;
    padding: 0.8rem 0.9rem 0.35rem;
    margin: 0;
  }

  nav#sidebarMenu::-webkit-scrollbar,
  nav#sidebarMenu .sidebar-scroll::-webkit-scrollbar {
    width: 8px;
  }

  nav#sidebarMenu::-webkit-scrollbar-thumb,
  nav#sidebarMenu .sidebar-scroll::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.3);
    border-radius: 999px;
  }

  body > .container-fluid > .row.gx-0 > main {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0;
    overflow-y: auto;
    overflow-x: hidden;
    background: var(--admin-bg);
  }

  @media (max-width: 1024px) {
    :root {
      --sidebar-width: var(--sidebar-width-tablet);
    }
  }

  @media (max-width: 767.98px) {
    body > .container-fluid > .row.gx-0 {
      display: block;
    }

    nav#sidebarMenu {
      position: fixed;
      top: var(--top-nav-height);
      left: 0;
      bottom: 0;
      width: var(--sidebar-width);
      height: calc(100vh - var(--top-nav-height));
      transform: translateX(-100%);
      transition: transform 0.25s ease;
      box-shadow: 0 20px 50px rgba(15, 23, 42, 0.35);
    }

    nav#sidebarMenu.show {
      transform: translateX(0);
    }

    body > .container-fluid > .row.gx-0 > main {
      height: auto;
      min-height: calc(100vh - var(--top-nav-height));
      overflow-y: visible;
    }
  }
</style>

<!-- Mobile menu toggle -->
<button class="btn btn-light d-md-none" id="sidebarToggle" type="button" aria-label="Toggle sidebar" style="position:fixed; top:14px; left:14px; z-index:1100; width:48px; height:48px; border-radius:12px; box-shadow:0 10px 20px rgba(15,23,42,0.18);">
  <i class="bi bi-list"></i>
</button>

<!-- Sidebar -->
<nav id="sidebarMenu" class="sidebar">
  <div class="sidebar-scroll">
    <div class="sidebar-brand">
      <div class="sidebar-brand-icon"><i class="bi bi-columns-gap"></i></div>
      <div class="sidebar-brand-text">
        <div style="font-weight:600; color:#f8fafc;">Admin Panel</div>
        <div>Management</div>
      </div>
    </div>

    <ul class="nav flex-column nav-pills gap-1">
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <?php if ($showSoftwareLogin): ?>
      <li class="nav-item"><a class="nav-link <?= $currentPage === '#' ? 'active' : '' ?>" href="<?php echo APP_URL; ?>/authentication"><i class="bi bi-speedometer"></i> Software Login</a></li>
      <?php endif; ?>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'notices.php' ? 'active' : '' ?>" href="notices.php"><i class="bi bi-megaphone"></i> Notices</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'slider.php' ? 'active' : '' ?>" href="slider.php"><i class="bi bi-sliders"></i> Slider</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'complaint_management.php' ? 'active' : '' ?>" href="complaint_management.php"><i class="bi bi-exclamation-triangle"></i> Complaint Manage</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'teachers.php' ? 'active' : '' ?>" href="teachers.php"><i class="bi bi-person-badge"></i> Teachers</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'management_committee_admin.php' ? 'active' : '' ?>" href="management_committee_admin.php"><i class="bi bi-people-fill"></i> Committee Info</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'student_info.php' ? 'active' : '' ?>" href="student_info.php"><i class="bi bi-people"></i> Student Info</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'student_of_the_year.php' ? 'active' : '' ?>" href="student_of_the_year.php"><i class="bi bi-award"></i> Students of Year</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'routines.php' ? 'active' : '' ?>" href="routines.php"><i class="bi bi-calendar2-week"></i> Routine</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'results.php' ? 'active' : '' ?>" href="results.php"><i class="bi bi-file-earmark-bar-graph"></i> Results</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'result_archives.php' ? 'active' : '' ?>" href="result_archives.php"><i class="bi bi-archive"></i> Result Archives</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'gallery_photos.php' ? 'active' : '' ?>" href="gallery_photos.php"><i class="bi bi-images"></i> Photo Gallery</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'gallery_videos.php' ? 'active' : '' ?>" href="gallery_videos.php"><i class="bi bi-camera-video"></i> Video Gallery</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'messages.php' ? 'active' : '' ?>" href="messages.php"><i class="bi bi-chat-dots"></i> Speech Messages</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'contact_messages.php' ? 'active' : '' ?>" href="contact_messages.php"><i class="bi bi-envelope"></i> Contact Messages</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'admission_info.php' ? 'active' : '' ?>" href="admission_info.php"><i class="bi bi-file-earmark-text"></i> Admission Info</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === 'settings.php' ? 'active' : '' ?>" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
      <li class="nav-item"><a class="nav-link <?= $currentPage === '#' ? 'active' : '' ?>" href="https://www.youtube.com/watch?v=I5FAWMry5pE"><i class="bi bi-youtube"></i> How to Use</a></li>
      <li class="nav-item mt-3"><a class="nav-link <?= $currentPage === 'logout.php' ? 'active' : '' ?> text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebarMenu');
    if (!toggle || !sidebar) return;

    toggle.addEventListener('click', function () {
      sidebar.classList.toggle('show');
    });

    document.addEventListener('click', function (event) {
      if (window.innerWidth > 767) return;
      if (!sidebar.contains(event.target) && !toggle.contains(event.target) && sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
      }
    });
  });
</script>
