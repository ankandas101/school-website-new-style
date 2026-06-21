<?php
// _sidebar.php
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
  /* Sidebar fixed width */
  nav#sidebarMenu {
    width: 250px;
    min-height: 100vh;
  }

  /* Nav-link full width & proper padding */
  nav#sidebarMenu .nav-link {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Icon margin */
  nav#sidebarMenu .nav-link i {
    min-width: 24px;
    margin-right: 12px;
    font-size: 1.2rem;
  }

  /* Active nav-link styling */
  nav#sidebarMenu .nav-link.active {
    background-color: #0d6efd;
    color: #fff;
    font-weight: 600;
  }

  nav#sidebarMenu .nav-link.active i {
    color: #fff;
  }

  /* Scrollbar for sidebar if content too long */
  nav#sidebarMenu {
    overflow-y: auto;
  }

  /* Make sidebar collapse on smaller devices */
  @media (max-width: 767.98px) {
    nav#sidebarMenu {
      width: 100%;
      min-height: auto;
      position: fixed;
      z-index: 1040;
    }
  }
</style>

<!-- Mobile Navbar with toggle button -->
<nav class="navbar navbar-light bg-light shadow-sm d-md-none">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
      aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand ms-2" href="#">Menu</a>
  </div>
</nav>

<!-- Sidebar -->
<nav id="sidebarMenu" class="collapse d-md-block bg-light sidebar shadow-sm rounded-3 p-3">
  <ul class="nav flex-column nav-pills gap-2">
    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL; ?>/dashboard"><i class="bi bi-speedometer"></i> Software Login</a></li>
    <li class="nav-item"><a class="nav-link" href="notices.php"><i class="bi bi-megaphone"></i> Notices</a></li>
    <li class="nav-item"><a class="nav-link" href="slider.php"><i class="bi bi-sliders"></i> Slider</a></li>
    <li class="nav-item"><a class="nav-link" href="teachers.php"><i class="bi bi-person-badge"></i> Teachers</a></li>
    <li class="nav-item"><a class="nav-link" href="management_committee_admin.php"><i class="bi bi-people-fill"></i>Committee Info</a></li>
    <li class="nav-item"><a class="nav-link" href="student_info.php"><i class="bi bi-people"></i> Student Info</a></li>
    <li class="nav-item"><a class="nav-link" href="student_of_the_year.php"><i class="bi bi-award"></i> কৃতি শিক্ষার্থী</a></li>
    <li class="nav-item"><a class="nav-link" href="routines.php"><i class="bi bi-calendar2-week"></i> Routine</a></li>
    <li class="nav-item"><a class="nav-link" href="results.php"><i class="bi bi-file-earmark-bar-graph"></i> Results</a></li>
    <li class="nav-item"><a class="nav-link" href="result_archives.php"><i class="bi bi-archive"></i> Result Archives</a></li>
    <li class="nav-item"><a class="nav-link" href="gallery_photos.php"><i class="bi bi-images"></i> Photo Gallery</a></li>
    <li class="nav-item"><a class="nav-link" href="gallery_videos.php"><i class="bi bi-camera-video"></i> Video Gallery</a></li>
    <li class="nav-item"><a class="nav-link" href="messages.php"><i class="bi bi-chat-dots"></i>Speech Messages</a></li>
    <li class="nav-item"><a class="nav-link" href="contact_messages.php"><i class="bi bi-envelope"></i> Contact Messages</a></li>
    <li class="nav-item"><a class="nav-link" href="admission_info.php"> Admission Info</a></li>
    <li class="nav-item"><a class="nav-link" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
    <li class="nav-item mt-auto"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
  </ul>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
