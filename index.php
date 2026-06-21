<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// SEO class
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
}

$seo            = new SEOSettings($conn);
$slider         = new Slider($conn);
$msg            = new Message($conn);
$notice         = new Notice($conn);
$school         = new SchoolInfo($conn);
$footer         = new FooterInfo($conn);
$sidebarWidget  = new SidebarWidget($conn);
$sidebar_widgets = $sidebarWidget->getActive();

$schoolStats = new SchoolStatistics($conn);
$statistics = $schoolStats->getActive();

$seo_data       = $seo->get('index');
$sliders        = $slider->getActive();
$head           = $msg->get('head_teacher');
$chairman       = $msg->get('chairman');
$about_school   = $msg->get('about_school');
$notices        = $notice->getActive(5);
$school_info    = $school->get();
$footer_info    = $footer->get();

// Fetch latest 5 notices for ticker
$noticeTicker   = new Notice($conn);
$latest_notices = $noticeTicker->getActive(5, true);

// Fetch teachers
$teachers_result = $conn->query('SELECT name, photo, designation, phone, email, status FROM teachers WHERE status=1 ORDER BY (sort_order=0), sort_order ASC, id DESC LIMIT 9');

// Fetch management committee members
$committee_result = $conn->query('SELECT full_name, title, image FROM management_committee ORDER BY id DESC LIMIT 4 ');

// Fetch students of the year
$students_of_year_result = $conn->query('SELECT name, class, photo, status, year FROM student_of_the_year WHERE status=1 ORDER BY year DESC, id DESC LIMIT 6');

// Fetch latest 3 forms for sidebar
$latest_forms_result = $conn->query('SELECT title, id, file FROM forms WHERE status=1 ORDER BY id DESC LIMIT 3');

// Fetch school schedule
$schedules_result = $conn->query('SELECT title, time_value FROM schedules WHERE status = 1 ORDER BY sort_order ASC');

// Collect sliders into array
$sliders_arr = [];
if ($sliders && $sliders->num_rows > 0) {
    while ($row = $sliders->fetch_assoc()) $sliders_arr[] = $row;
}

// Collect teachers into array
$teachers_arr = [];
if ($teachers_result && $teachers_result->num_rows > 0) {
    while ($t = $teachers_result->fetch_assoc()) $teachers_arr[] = $t;
}

// Collect students into array
$students_arr = [];
if ($students_of_year_result && $students_of_year_result->num_rows > 0) {
    while ($s = $students_of_year_result->fetch_assoc()) $students_arr[] = $s;
}
?>
<?php include_once 'includes/header.php'; ?>

<!-- ===================================================
     HOMEPAGE MAIN LAYOUT
=================================================== -->
<main style="max-width:1280px; margin:0 auto; padding:1.25rem 1rem 0;">
  <!-- ===== HERO SECTION WITH NOTICE BOARD ===== -->
  <section class="hero-wrapper" data-aos="fade-up" data-aos-duration="600">
    <!-- LEFT: HERO SLIDER (74%) -->
    <div class="hero-container">
      <div class="hero-swiper swiper" id="heroSwiper">
        <div class="swiper-wrapper">
          <?php if (!empty($sliders_arr)): ?>
            <?php foreach ($sliders_arr as $i => $row): ?>
            <div class="swiper-slide" style="position:relative;">
              <img
                src="assets/images/<?php echo htmlspecialchars($row['image']); ?>"
                alt="<?php echo htmlspecialchars($row['caption_title'] ?? 'Slider'); ?>"
                style="width:100%; height:540px; object-fit:cover; object-position:center; display:block;"
                <?php echo ($i === 0) ? 'fetchpriority="high" decoding="async"' : 'loading="lazy" decoding="async"'; ?>
              >
              <!-- Gradient Overlay -->
              <div class="hero-overlay"></div>
              <!-- Hero Text -->
              <div class="hero-text">
                <?php if ($i === 0): ?>
                <h1><?php echo htmlspecialchars($school_info['school_name'] ?? ''); ?></h1>
                <p>জ্ঞান, নৈতিকতা ও উন্নতির পথে—আদর্শ মানুষ গড়ার অঙ্গীকারে</p>
                <!-- Meta Badges -->
                <div class="hero-meta">
                  <?php if (!empty($school_info['established'])): ?>
                  <div class="hero-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    প্রতিষ্ঠা: <?php echo htmlspecialchars($school_info['established']); ?>
                  </div>
                  <?php endif; ?>
                  <?php if (!empty($school_info['eiin'])): ?>
                  <div class="hero-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    EIIN: <?php echo htmlspecialchars($school_info['eiin']); ?>
                  </div>
                  <?php endif; ?>
                  <?php if (!empty($footer_info['address'])): ?>
                  <div class="hero-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo htmlspecialchars(mb_substr($footer_info['address'], 0, 40)); ?>
                  </div>
                  <?php endif; ?>
                </div>
                <!-- Buttons -->
                <div class="hero-buttons">
                  <a href="<?php echo APP_URL; ?>/admission" class="btn-hero-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    ভর্তি আবেদন করুন
                  </a>
                  <a href="about.php" class="btn-hero-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    আমাদের সম্পর্কে জানুন
                  </a>
                </div>
                <?php else: ?>
                <h1><?php echo htmlspecialchars($row['caption_title'] ?? $school_info['school_name'] ?? ''); ?></h1>
                <?php if (!empty($row['caption_text'])): ?>
                <p><?php echo htmlspecialchars($row['caption_text']); ?></p>
                <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="swiper-slide">
              <div style="width:100%; height:540px; background:linear-gradient(135deg,#123B6A,#118847); display:flex; align-items:center; justify-content:center;">
                <div style="text-align:center; color:#fff;">
                  <h1 style="font-size:2rem; font-weight:700;"><?php echo htmlspecialchars($school_info['school_name'] ?? ''); ?></h1>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>

    <!-- RIGHT: NOTICE BOARD (26%) -->
    <div class="hero-notice-board">
      <div class="notice-board-widget">
        <div class="notice-board-header">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
            নোটিশ বোর্ড
          </div>
          <a href="notices.php" style="font-size:0.72rem; color:rgba(255,255,255,0.85); font-weight:600;">সকল নটিশ →</a>
        </div>
        <div class="notice-board-body">
          <?php
          $noticesSidebar = new Notice($conn);
          $sidebar_notices = $noticesSidebar->getActive(5);
          if ($sidebar_notices && $sidebar_notices->num_rows > 0):
            $notice_colors = ['#e53e3e','#f59e0b','#3b82f6','#22c55e','#8b5cf6'];
            $ni = 0;
            while ($row = $sidebar_notices->fetch_assoc()):
              $nc = $notice_colors[$ni % count($notice_colors)];
              $nd = !empty($row['notice_date']) ? date('d M', strtotime($row['notice_date'])) : '';
          ?>
          <div class="notice-item">
            <div class="notice-badge" style="background:<?php echo $nc; ?>15; color:<?php echo $nc; ?>; border:1px solid <?php echo $nc; ?>25;">
              <?php if ($nd): ?>
              <span><?php echo $nd; ?></span>
              <?php else: ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
              <?php endif; ?>
            </div>
            <div>
              <a href="notice.php?id=<?php echo (int)$row['id']; ?>" class="notice-title"><?php echo htmlspecialchars($row['title']); ?></a>
              <?php if ($nd): ?><div class="notice-date"><?php echo $nd; ?></div><?php endif; ?>
            </div>
          </div>
          <?php $ni++; endwhile; else: ?>
          <p style="font-size:0.82rem; color:#94a3b8; padding:0.75rem 0.85rem;">কোনো নোটিশ নেই।</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <!-- ===== END HERO WITH NOTICE BOARD ===== -->

  <!-- ===== MAIN CONTENT WRAPPER ===== -->
  <div style="display:flex; gap:1.5rem; align-items:flex-start;">

    <!-- ============ LEFT: MAIN CONTENT ============ -->
    <div style="flex:1; min-width:0;">

      <!-- ===== STATISTICS ROW ===== -->
    <div class="hero-stats-wrapper">
      <section style="margin:1.75rem 0;" data-aos="fade-up" data-aos-delay="100">
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem;">
          <?php 
          $stat_colors = [
            ['bg' => '#e8f5ee', 'stroke' => '#118847'],
            ['bg' => '#eff6ff', 'stroke' => '#3b82f6'],
            ['bg' => '#fff7ed', 'stroke' => '#f59e0b'],
            ['bg' => '#f0fdf4', 'stroke' => '#22c55e'],
            ['bg' => '#faf5ff', 'stroke' => '#8b5cf6'],
            ['bg' => '#fce7f3', 'stroke' => '#ec4899'],
          ];
          $stat_index = 0;
          if ($statistics && $statistics->num_rows > 0): 
            while ($stat = $statistics->fetch_assoc()): 
              $color = $stat_colors[$stat_index % count($stat_colors)];
              $stat_index++;
              // Default icons if none provided
              $default_icons = [
                '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                '<path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
                '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
              ];
              $icon_path = !empty($stat['icon']) ? $stat['icon'] : $default_icons[$stat_index % count($default_icons)];
          ?>
          <div class="stat-card">
            <div class="stat-icon" style="background:<?php echo $color['bg']; ?>;">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="<?php echo $color['stroke']; ?>" stroke-width="2">
                <?php echo $icon_path; ?>
              </svg>
            </div>
            <div class="stat-number"><?php echo htmlspecialchars($stat['value']); ?><?php echo !empty($stat['suffix']) ? htmlspecialchars($stat['suffix']) : ''; ?></div>
            <div class="stat-label"><?php echo htmlspecialchars($stat['title']); ?></div>
          </div>
          <?php endwhile; else: ?>
          <!-- Fallback static stats if no database stats -->
          <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5ee;">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="stat-number">২,০০০+</div>
            <div class="stat-label">শিক্ষার্থী</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <div class="stat-number">৮০+</div>
            <div class="stat-label">শিক্ষক</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="stat-number">৪৫+</div>
            <div class="stat-label">বছরের ঐতিহ্য</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div class="stat-number">১০০%</div>
            <div class="stat-label">পাশের হার</div>
          </div>
          <?php endif; ?>
        </div>
      </section>
      </div>
      <!-- ===== END STATS ===== -->

      <!-- ===== ABOUT + PRINCIPAL ===== -->
      <section style="margin:1.75rem 0;" data-aos="fade-up" data-aos-delay="50">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

          <!-- Principal Message -->
          <div class="about-card">
            <div class="section-header">
              <h2 class="section-title">প্রতিষ্ঠান প্রধানের বাণী</h2>
              <a href="head_teacher.php" class="section-link">আরও <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:1.5rem;">
              <!-- Left: Photo + Info -->
              <div style="display:flex; flex-direction:column; align-items:center; text-align:center;">
                <?php if (!empty($head['photo'])): ?>
                <img src="assets/images/<?php echo htmlspecialchars($head['photo']); ?>" alt="প্রধান শিক্ষক" style="width:140px; height:140px; border-radius:50%; object-fit:cover; border:4px solid #E8ECF3; margin-bottom:1rem;" loading="lazy" decoding="async">
                <?php else: ?>
                <div style="width:140px; height:140px; border-radius:50%; background:#e8f5ee; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; border:4px solid #E8ECF3;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                </div>
                <?php endif; ?>
                <?php if (!empty($head['name'])): ?>
                <div style="font-weight:700; font-size:0.95rem; color:#123B6A; margin-bottom:0.3rem;">— <?php echo htmlspecialchars($head['name']); ?></div>
                <div style="font-size:0.75rem; color:#118847; font-weight:600; letter-spacing:0.3px;">প্রধান শিক্ষক</div>
                <?php endif; ?>
              </div>

              <!-- Right: Message -->
              <div style="display:flex; flex-direction:column;">
                <p class="justified-text" style="font-size:0.87rem; color:#475569; line-height:1.7; flex:1;">
                  <?php
                    $message = $head['message'] ?? '';
                    echo nl2br(htmlspecialchars(mb_strlen($message) > 320 ? mb_substr($message, 0, 320) . '...' : $message));
                  ?>
                </p>
                <a href="head_teacher.php" class="btn-outline-modern" style="margin-top:1.25rem; font-size:0.82rem; align-self:flex-start;">
                  বিস্তারিত পড়ুন
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
              </div>
            </div>
          </div>

          <!-- About School -->
          <div class="about-card">
            <div class="section-header">
              <h2 class="section-title">প্রতিষ্ঠানের সম্পর্কে</h2>
              <a href="about.php" class="section-link">সব দেখুন <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
            </div>
            <?php if (!empty($about_school['photo'])): ?>
            <img src="assets/images/<?php echo htmlspecialchars($about_school['photo']); ?>" alt="স্কুল" style="width:100%; height:160px; object-fit:cover; border-radius:12px; margin-bottom:1rem;" loading="lazy" decoding="async">
            <?php endif; ?>
            <p class="justified-text" style="font-size:0.88rem; color:#475569; line-height:1.7;">
              <?php
                $about_text = $about_school['message'] ?? '';
                echo nl2br(htmlspecialchars(mb_strlen($about_text) > 320 ? mb_substr($about_text, 0, 320) . '...' : $about_text));
              ?>
            </p>
            <a href="about.php" class="btn-outline-modern" style="margin-top:1.25rem; font-size:0.82rem;">
              বিস্তারিত পড়ুন
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
          </div>

        </div>
      </section>

      <!-- ===== MANAGEMENT COMMITTEE ===== -->
      <?php if (!empty($chairman['message']) || ($committee_result && $committee_result->num_rows > 0)): ?>
      <section style="margin:1.75rem 0;" data-aos="fade-up">
        <div class="management-section-card">
          <div class="section-header" style="margin-bottom: 1.5rem; padding-bottom: 0.7rem; border-bottom: 2px solid #E8ECF3;">
            <h2 class="section-title">ব্যবস্থাপনা কমিটি</h2>
          </div>
          <div class="management-grid">
            
            <!-- President Column -->
            <div class="president-column">
              <?php if (!empty($chairman['photo'])): ?>
              <img src="assets/images/<?php echo htmlspecialchars($chairman['photo']); ?>" alt="সভাপতি" class="president-avatar" loading="lazy" decoding="async">
              <?php else: ?>
              <div class="president-avatar" style="display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($chairman['name'])): ?>
              <div>
                <div class="president-name"><?php echo htmlspecialchars($chairman['name']); ?></div>
                <div class="president-designation">সভাপতি</div>
              </div>
              <?php endif; ?>
              
              <p class="president-message">
                <?php
                  $message = $chairman['message'] ?? '';
                  echo nl2br(htmlspecialchars(mb_strlen($message) > 250 ? mb_substr($message, 0, 250) . '...' : $message));
                ?>
              </p>
              
              <a href="chairman.php" class="btn-outline-modern" style="align-self: center; font-size: 0.85rem; padding-inline: 1.25rem;">
                বিস্তারিত পড়ুন →
              </a>
            </div>
            
            <!-- Committee Column -->
            <div class="committee-column">
              
              <div class="committee-members-grid">
                <?php
                $committee_result->data_seek(0);
                $display_count = 0;
                if ($committee_result && $committee_result->num_rows > 0):
                  while (($member = $committee_result->fetch_assoc()) && $display_count < 4):
                    $display_count++;
                ?>
                <a href="management_committee.php" class="committee-member-card">
                  <?php if (!empty($member['image'])): ?>
                  <img src="assets/images/<?php echo htmlspecialchars($member['image']); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>" class="member-avatar" loading="lazy" decoding="async">
                  <?php else: ?>
                  <div class="member-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                  </div>
                  <?php endif; ?>
                  <div class="member-info">
                    <div class="member-name"><?php echo htmlspecialchars($member['full_name']); ?></div>
                    <div class="member-designation"><?php echo htmlspecialchars($member['title'] ?? 'সদস্য'); ?></div>
                  </div>
                  <div class="member-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="9 18 15 12 9 6"/>
                    </svg>
                  </div>
                </a>
                <?php
                  endwhile;
                else:
                ?>
                <p style="font-size: 0.85rem; color: #94a3b8; text-align: center; padding: 1.5rem 0;">কমিটি সদস্য পাওয়া যায়নি।</p>
                <?php endif; ?>
              </div>
              
              <div class="committee-bottom-btn">
                <a href="management_committee.php" class="btn-outline-modern" style="font-size: 0.875rem; padding-inline: 1.75rem;">
                  সকল কমিটি সদস্য দেখুন →
                </a>
              </div>
            </div>
            
          </div>
        </div>
      </section>
      <?php endif; ?>

      <!-- ===== TEACHERS SECTION ===== -->
      <?php if (!empty($teachers_arr)): ?>
      <section style="margin:1.75rem 0;" data-aos="fade-up">
        <div class="about-card">
          <div class="section-header">
            <h2 class="section-title">আমাদের সম্মানিত শিক্ষকবৃন্দ</h2>
            <a href="teachers.php" class="section-link">সব দেখুন <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
          </div>
          <div style="position:relative;">
            <div class="swiper teachers-swiper" id="teachersSwiper">
              <div class="swiper-wrapper">
                <?php foreach ($teachers_arr as $teacher): ?>
                <div class="swiper-slide">
                  <div class="teacher-card">
                    <?php if (!empty($teacher['photo'])): ?>
                      <img src="assets/images/<?php echo htmlspecialchars($teacher['photo']); ?>" alt="<?php echo htmlspecialchars($teacher['name']); ?>" class="teacher-avatar" loading="lazy" decoding="async">
                    <?php else: ?>
                      <div class="teacher-avatar" style="background:#e8f5ee; display:flex; align-items:center; justify-content:center; border:3px solid #E8ECF3;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                      </div>
                    <?php endif; ?>
                    <div class="teacher-name"><?php echo htmlspecialchars($teacher['name']); ?></div>
                    <div class="teacher-designation"><?php echo htmlspecialchars($teacher['designation']); ?></div>
                    <?php if (!empty($teacher['phone'])): ?>
                    <div class="teacher-contact">
                      <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.37a16 16 0 0 0 6.72 6.72l1.83-1.83a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                      <?php echo htmlspecialchars($teacher['phone']); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($teacher['email'])): ?>
                    <div class="teacher-contact" style="margin-top:4px;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                      <?php echo htmlspecialchars($teacher['email']); ?>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <div class="swiper-button-prev teachers-prev"></div>
              <div class="swiper-button-next teachers-next"></div>
            </div>
          </div>
          <div style="text-align:center; margin-top:1.25rem;">
            <a href="teachers.php" class="btn-primary-modern">সকল শিক্ষকের তথ্য দেখুন</a>
          </div>
        </div>
      </section>
      <?php endif; ?>
      <!-- ===== END TEACHERS ===== -->

      <!-- ===== STUDENTS OF THE YEAR (ACHIEVEMENTS) ===== -->
      <?php if (!empty($students_arr)): ?>
      <section style="margin:1.75rem 0;" data-aos="fade-up">
        <div class="about-card">
          <div class="section-header">
            <h2 class="section-title">আমাদের শিক্ষার্থীদের অর্জন</h2>
            <a href="student_of_the_year.php" class="section-link">সব দেখুন <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
          </div>
          <div class="swiper teachers-swiper" id="studentsSwiper">
            <div class="swiper-wrapper">
              <?php foreach ($students_arr as $student): ?>
              <div class="swiper-slide">
                <div class="teacher-card">
                  <?php if (!empty($student['photo'])): ?>
                    <img src="assets/images/<?php echo htmlspecialchars($student['photo']); ?>" alt="<?php echo htmlspecialchars($student['name']); ?>" class="teacher-avatar" loading="lazy" decoding="async">
                  <?php else: ?>
                    <div class="teacher-avatar" style="background:#fff7ed; display:flex; align-items:center; justify-content:center; border:3px solid #E8ECF3;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </div>
                  <?php endif; ?>
                  <div class="teacher-name"><?php echo htmlspecialchars($student['name']); ?></div>
                  <div class="teacher-designation">শ্রেণি: <?php echo htmlspecialchars($student['class']); ?></div>
                  <div class="teacher-contact" style="margin-top:4px; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    বছর: <?php echo htmlspecialchars($student['year']); ?>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev students-prev"></div>
            <div class="swiper-button-next students-next"></div>
          </div>
          <div style="text-align:center; margin-top:1.25rem;">
            <a href="student_of_the_year.php" class="btn-outline-modern">আরও দেখুন</a>
          </div>
        </div>
      </section>
      <?php endif; ?>
      <!-- ===== END STUDENTS ===== -->

      <!-- ===== PHOTO GALLERY + VIDEO GALLERY (SAME ROW) ===== -->
      <section style="margin:1.75rem 0;" data-aos="fade-up">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; align-items:start;">

          <!-- LEFT: Photo Gallery -->
          <div class="about-card" style="padding:1.25rem;">
            <div class="section-header">
              <h2 class="section-title">ফটো গ্যালারি</h2>
              <a href="photo_gallery.php" class="section-link">সব দেখুন <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
            </div>
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
              <?php
              $photos_preview = $conn->query('SELECT * FROM gallery_photos WHERE status=1 ORDER BY id DESC LIMIT 6');
              if ($photos_preview && $photos_preview->num_rows > 0):
                while ($photo = $photos_preview->fetch_assoc()):
              ?>
              <div class="gallery-item" data-src="assets/images/<?php echo htmlspecialchars($photo['image']); ?>" data-alt="<?php echo htmlspecialchars($photo['caption'] ?? ''); ?>" style="aspect-ratio:4/3; border-radius:8px;">
                <img src="assets/images/<?php echo htmlspecialchars($photo['image']); ?>" alt="<?php echo htmlspecialchars($photo['caption'] ?? 'Gallery'); ?>" loading="lazy" decoding="async">
                <div class="gallery-overlay" style="border-radius:8px;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                </div>
              </div>
              <?php endwhile; else: ?>
              <div style="grid-column:1/-1; text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.83rem;">কোনো ছবি পাওয়া যায়নি।</div>
              <?php endif; ?>
            </div>
            <div style="text-align:center; margin-top:1rem;">
              <a href="photo_gallery.php" class="btn-primary-modern" style="font-size:0.82rem; padding:0.45rem 1.1rem;">সব ছবি দেখুন</a>
            </div>
          </div>

          <!-- RIGHT: Video Gallery (list format like reference) -->
          <div class="about-card" style="padding:1.25rem;">
            <div class="section-header">
              <h2 class="section-title">ভিডিও গ্যালারি</h2>
              <a href="video_gallery.php" class="section-link">সব দেখুন <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
            </div>
            <div style="display:flex; flex-direction:column; gap:10px;">
              <?php
              $videos_preview = $conn->query('SELECT * FROM gallery_videos WHERE status=1 ORDER BY id DESC LIMIT 3');
              if ($videos_preview && $videos_preview->num_rows > 0):
                while ($video = $videos_preview->fetch_assoc()):
                  $yt_embed = '';
                  $yt_id    = '';
                  if (strpos($video['video_url'], 'youtube.com') !== false || strpos($video['video_url'], 'youtu.be') !== false) {
                    if (preg_match('/youtu\.be\/([\w-]+)/', $video['video_url'], $m)) {
                      $yt_id    = $m[1];
                      $yt_embed = 'https://www.youtube.com/embed/' . $yt_id;
                    } elseif (preg_match('/youtube\.com\/watch\?v=([\w-]+)/', $video['video_url'], $m)) {
                      $yt_id    = $m[1];
                      $yt_embed = 'https://www.youtube.com/embed/' . $yt_id;
                    } else {
                      $yt_embed = $video['video_url'];
                    }
                  }
              ?>
              <div style="display:flex; gap:10px; align-items:flex-start;">
                <!-- Thumbnail -->
                <div style="position:relative; flex-shrink:0; width:130px; height:80px; border-radius:8px; overflow:hidden; background:#000;">
                  <?php if ($yt_id): ?>
                  <img src="https://i.ytimg.com/vi/<?php echo htmlspecialchars($yt_id); ?>/mqdefault.jpg"
                       alt="<?php echo htmlspecialchars($video['caption'] ?? 'Video'); ?>"
                       style="width:100%; height:100%; object-fit:cover; display:block;"
                       loading="lazy" decoding="async">
                  <?php else: ?>
                  <div style="width:100%; height:100%; background:#1e293b; display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                  </div>
                  <?php endif; ?>
                  <!-- Play button overlay -->
                  <a href="<?php echo htmlspecialchars($video['video_url']); ?>" target="_blank" rel="noopener noreferrer"
                     style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.32); border-radius:8px; text-decoration:none;">
                    <div style="width:30px; height:30px; border-radius:50%; background:rgba(255,255,255,0.9); display:flex; align-items:center; justify-content:center;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#123B6A"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                  </a>
                </div>
                <!-- Title & meta -->
                <div style="flex:1; min-width:0;">
                  <div style="font-size:0.83rem; font-weight:600; color:#123B6A; line-height:1.4; margin-bottom:4px;">
                    <?php echo htmlspecialchars($video['caption'] ?? 'ভিডিও'); ?>
                  </div>
                  <?php if ($yt_embed): ?>
                  <a href="<?php echo htmlspecialchars($video['video_url']); ?>" target="_blank" rel="noopener noreferrer"
                     style="font-size:0.72rem; color:#118847; font-weight:600; display:inline-flex; align-items:center; gap:3px; text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    YouTube-এ দেখুন
                  </a>
                  <?php endif; ?>
                </div>
              </div>
              <?php endwhile; else: ?>
              <div style="text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.83rem;">কোনো ভিডিও পাওয়া যায়নি।</div>
              <?php endif; ?>
            </div>
            <div style="text-align:center; margin-top:1rem;">
              <a href="video_gallery.php" class="btn-primary-modern" style="font-size:0.82rem; padding:0.45rem 1.1rem;">আরও ভিডিও দেখুন</a>
            </div>
          </div>

        </div>
      </section>
      <!-- ===== END PHOTO + VIDEO GALLERY ===== -->

      <!-- ===== LATEST NEWS / EVENTS ===== -->
      <section style="margin:1.75rem 0;" data-aos="fade-up">
        <div class="about-card">
          <div class="section-header">
            <h2 class="section-title">সর্বশেষ সংবাদ ও ইভেন্ট</h2>
            <a href="event.php" class="section-link">সব দেখুন <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></a>
          </div>
          <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;">
            <?php
            $events_preview = $conn->query('SELECT * FROM events ORDER BY event_date DESC, id DESC LIMIT 3');
            if ($events_preview && $events_preview->num_rows > 0):
              while ($event = $events_preview->fetch_assoc()):
                $event_date = !empty($event['event_date']) ? date('d', strtotime($event['event_date'])) : '';
                $event_month = !empty($event['event_date']) ? date('M', strtotime($event['event_date'])) : '';
                $event_year  = !empty($event['event_date']) ? date('Y', strtotime($event['event_date'])) : '';
            ?>
            <div class="news-card">
              <?php if (!empty($event['image'])): ?>
              <img src="assets/images/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" style="width:100%; height:140px; object-fit:cover;" loading="lazy" decoding="async">
              <?php endif; ?>
              <div style="padding:0.85rem;">
                <div style="display:flex; gap:0.6rem; align-items:flex-start; margin-bottom:0.6rem;">
                  <?php if ($event_date): ?>
                  <div class="news-date-badge">
                    <div class="day"><?php echo $event_date; ?></div>
                    <div class="month"><?php echo $event_month; ?></div>
                  </div>
                  <?php endif; ?>
                  <div>
                    <div style="font-weight:700; font-size:0.88rem; color:#123B6A; line-height:1.35; margin-bottom:0.3rem;"><?php echo htmlspecialchars($event['title']); ?></div>
                    <div style="font-size:0.78rem; color:#64748b;"><?php echo htmlspecialchars(mb_strimwidth(strip_tags($event['description']), 0, 80, '...')); ?></div>
                  </div>
                </div>
                <a href="event_detail.php?id=<?php echo (int)$event['id']; ?>" class="btn-outline-modern" style="font-size:0.78rem; padding:0.4rem 0.9rem;">বিস্তারিত পড়ুন</a>
              </div>
            </div>
            <?php endwhile; else: ?>
            <div style="grid-column:1/-1; text-align:center; padding:2rem; color:#94a3b8;">কোনো সংবাদ পাওয়া যায়নি।</div>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </div>
    <!-- ============ END MAIN CONTENT ============ -->

    <!-- ============ RIGHT: SIDEBAR ============ -->
    <aside style="width:300px; flex-shrink:0;" class="hidden lg:block">

      <!-- NOTICE BOARD (hidden on lg, shown on hero) -->
      <div class="sidebar-widget hidden" data-aos="fade-left">
        <div class="sidebar-widget-header" style="background:#e53e3e; color:#fff;">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
            নোটিশ বোর্ড
          </div>
          <a href="notices.php" style="font-size:0.72rem; color:rgba(255,255,255,0.85); font-weight:600;">সব দেখুন →</a>
        </div>
        <div class="sidebar-widget-body" style="padding:0.5rem 0.85rem;">
          <?php
          $noticesSidebar = new Notice($conn);
          $sidebar_notices = $noticesSidebar->getActive(5);
          if ($sidebar_notices && $sidebar_notices->num_rows > 0):
            $notice_colors = ['#e53e3e','#f59e0b','#3b82f6','#22c55e','#8b5cf6'];
            $ni = 0;
            while ($row = $sidebar_notices->fetch_assoc()):
              $nc = $notice_colors[$ni % count($notice_colors)];
              $nd = !empty($row['notice_date']) ? date('d M', strtotime($row['notice_date'])) : '';
          ?>
          <div class="notice-item">
            <div class="notice-badge" style="background:<?php echo $nc; ?>15; color:<?php echo $nc; ?>; border:1px solid <?php echo $nc; ?>25;">
              <?php if ($nd): ?>
              <span><?php echo $nd; ?></span>
              <?php else: ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
              <?php endif; ?>
            </div>
            <div>
              <a href="notice.php?id=<?php echo (int)$row['id']; ?>" class="notice-title"><?php echo htmlspecialchars($row['title']); ?></a>
              <?php if ($nd): ?><div class="notice-date"><?php echo $nd; ?></div><?php endif; ?>
            </div>
          </div>
          <?php $ni++; endwhile; else: ?>
          <p style="font-size:0.82rem; color:#94a3b8; padding:0.5rem 0;">কোনো নোটিশ নেই।</p>
          <?php endif; ?>
          <a href="notices.php" class="btn-primary-modern" style="font-size:0.78rem; margin-top:0.75rem; justify-content:center; width:100%;">সব নোটিশ দেখুন</a>
        </div>
      </div>

      <!-- SCHOOL SCHEDULE -->
      <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="50">
        <div class="sidebar-widget-header" style="background:#0f52ba; color:#fff;">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            প্রতিষ্ঠানের সময়সূচি
          </div>
        </div>
        <div class="sidebar-widget-body">
          <?php
          if ($schedules_result && $schedules_result->num_rows > 0) {
            while($row = $schedules_result->fetch_assoc()) {
              echo '<div class="school-schedule-item">';
              echo '  <div class="school-schedule-title">' . htmlspecialchars($row["title"]) . '</div>';
              echo '  <div class="school-schedule-time">' . htmlspecialchars($row["time_value"]) . '</div>';
              echo '</div>';
            }
          } else {
            echo '<p style="text-align:center; color:#999; font-size:0.85rem;">কোনো সময়সূচি পাওয়া যায়নি।</p>';
          }
          ?>
        </div>
      </div>

      <!-- QUICK LINKS -->
      <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="100">
        <div class="sidebar-widget-header" style="background:#118847; color:#fff;">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            একাডেমিক তথ্য
          </div>
        </div>
        <div class="sidebar-widget-body">
          <?php
          $academicInfoLinks = new AcademicInfoLinks($conn);
          $academicLinks = $academicInfoLinks->getActive();
          if ($academicLinks && $academicLinks->num_rows > 0):
            while ($row = $academicLinks->fetch_assoc()):
          ?>
          <a href="<?php echo htmlspecialchars($row['url']); ?>" class="quick-link-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            <?php echo htmlspecialchars($row['title']); ?>
          </a>
          <?php endwhile; else: ?>
          <p style="font-size:0.82rem; color:#94a3b8;">কোনো লিংক নেই।</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- STUDENT CORNER -->
      <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="150">
        <div class="sidebar-widget-header" style="background:#123B6A; color:#fff;">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            স্টুডেন্ট কর্ণার
          </div>
        </div>
        <div class="sidebar-widget-body">
          <a href="<?php echo APP_URL; ?>/authentication" class="student-corner-btn" style="background:#123B6A; color:#fff; border:1px solid rgba(255,255,255,0.15); display:block; padding:0.55rem 0.75rem; border-radius:10px; font-size:0.82rem; font-weight:600; text-align:center; margin-bottom:0.4rem;">শিক্ষার্থী প্রফাইল লগইন</a>
          <a href="<?php echo APP_URL; ?>/admission" class="student-corner-btn" style="background:#118847; color:#fff; border:1px solid rgba(255,255,255,0.15); display:block; padding:0.55rem 0.75rem; border-radius:10px; font-size:0.82rem; font-weight:600; text-align:center; margin-bottom:0.4rem;">অনলাইন ভর্তি</a>
          <a href="<?php echo APP_URL; ?>/certificates" class="student-corner-btn" style="background:#1e40af; color:#fff; border:1px solid rgba(255,255,255,0.15); display:block; padding:0.55rem 0.75rem; border-radius:10px; font-size:0.82rem; font-weight:600; text-align:center; margin-bottom:0.4rem;">Certificates</a>
          <a href="<?php echo APP_URL; ?>/exam_results" class="student-corner-btn" style="background:#b45309; color:#fff; border:1px solid rgba(255,255,255,0.15); display:block; padding:0.55rem 0.75rem; border-radius:10px; font-size:0.82rem; font-weight:600; text-align:center;">Exam Results</a>
        </div>
      </div>

      <!-- IMPORTANT LINKS -->
      <?php
      require_once 'includes/classes.php';
      $importantLinks = new ImportantLinks($conn);
      $links = $importantLinks->getActive();
      if ($links && $links->num_rows > 0):
      ?>
      <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="200">
        <div class="sidebar-widget-header" style="background:#e53e3e; color:#fff;">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            গুরুত্বপূর্ণ লিংক
          </div>
        </div>
        <div class="sidebar-widget-body">
          <?php while ($row = $links->fetch_assoc()): ?>
          <a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank" rel="noopener noreferrer" class="quick-link-item" style="color:#e53e3e;">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#e53e3e" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            <?php echo htmlspecialchars($row['title']); ?>
          </a>
          <?php endwhile; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- LATEST FORMS -->
      <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="250">
        <div class="sidebar-widget-header" style="background:#6366f1; color:#fff;">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            সাম্প্রতিক ফরম
          </div>
          <a href="forms.php" style="font-size:0.72rem; color:rgba(255,255,255,0.85); font-weight:600;">সব দেখুন →</a>
        </div>
        <div class="sidebar-widget-body">
          <?php if ($latest_forms_result && $latest_forms_result->num_rows > 0):
            while ($form = $latest_forms_result->fetch_assoc()): ?>
          <a href="assets/forms/<?php echo htmlspecialchars($form['file']); ?>" target="_blank" rel="noopener noreferrer" class="quick-link-item" style="color:#6366f1;">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <?php echo htmlspecialchars($form['title']); ?>
          </a>
          <?php endwhile; else: ?>
          <p style="font-size:0.82rem; color:#94a3b8;">কোনো ডকুমেন্ট নেই।</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- OUR LOCATION -->
      <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="300">
        <div class="sidebar-widget-header" style="background:#118847; color:#fff;">
          <div style="display:flex; align-items:center; gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            আমাদের অবস্থান
          </div>
        </div>
        <div class="sidebar-widget-body">
          <div style="display:flex; align-items:flex-start; gap:0.6rem;">
            <div style="width:32px; height:32px; background:#e8f5ee; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div style="font-size:0.82rem; color:#475569; line-height:1.55;"><?php echo htmlspecialchars($footer_info['address'] ?? ''); ?></div>
          </div>
        </div>
      </div>

      <!-- DYNAMIC SIDEBAR WIDGETS -->
      <?php if ($sidebar_widgets && $sidebar_widgets->num_rows > 0): while ($widget = $sidebar_widgets->fetch_assoc()): ?>
        <?php if ($widget['type'] === 'image'): ?>
        <div class="sidebar-widget" data-aos="fade-left">
          <?php if (!empty($widget['title'])): ?>
          <div class="sidebar-widget-header" style="background:#123B6A; color:#fff;">
            <span><?php echo htmlspecialchars($widget['title']); ?></span>
          </div>
          <?php endif; ?>
          <div style="padding:0.75rem;">
            <img src="assets/images/<?php echo htmlspecialchars($widget['content']); ?>" class="widget-content" alt="<?php echo htmlspecialchars($widget['title'] ?? ''); ?>" loading="lazy" style="width:100%; border-radius:8px;">
          </div>
        </div>
        <?php elseif ($widget['type'] === 'html'): ?>
        <div class="sidebar-widget" data-aos="fade-left">
          <?php if (!empty($widget['title'])): ?>
          <div class="sidebar-widget-header" style="background:#123B6A; color:#fff;">
            <span><?php echo htmlspecialchars($widget['title']); ?></span>
          </div>
          <?php endif; ?>
          <div class="sidebar-widget-body widget-content"><?php echo $widget['content']; ?></div>
        </div>
        <?php endif; ?>
      <?php endwhile; endif; ?>

    </aside>
    <!-- ============ END SIDEBAR ============ -->

  </div>
</main>
<!-- ===================================================
     END HOMEPAGE LAYOUT
=================================================== -->

<!-- Mobile Sidebar: append below on mobile -->
<style>
  @media (max-width: 1023px) {
    aside { display: block !important; width: 100% !important; }
    main > div { flex-direction: column !important; }
  }
  @media (max-width: 639px) {
    .gallery-grid { grid-template-columns: repeat(2,1fr) !important; }
    main > div > div:first-child > section > div[style*="grid-template-columns:repeat(4"] {
      grid-template-columns: repeat(2,1fr) !important;
    }
    main > div > div:first-child > section > div[style*="grid-template-columns:repeat(3"] {
      grid-template-columns: 1fr !important;
    }
    main > div > div:first-child > section > div[style*="grid-template-columns:1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<?php include_once 'includes/footer.php'; ?>
</style>

<?php include_once 'includes/footer.php'; ?>
