<!-- ===== STICKY NAVBAR ===== -->
<header id="main-header" class="site-header" x-data="{ open: false, mobileOpenSub: null }" x-init="open = false; mobileOpenSub = null" @keydown.escape.window="open = false; mobileOpenSub = null">
  <nav style="max-width:1280px; margin:0 auto; padding:0.65rem 1rem; display:flex; align-items:center; justify-content:space-between; gap:1rem;">

    <!-- Brand / Logo -->
    <a href="index.php" style="display:flex; align-items:center; gap:10px; text-decoration:none; flex-shrink:0;">
      <?php
        require_once __DIR__ . '/../includes/db.php';
        require_once __DIR__ . '/classes.php';
        $school = new SchoolInfo($conn);
        $school_info = $school->get();
      ?>
      <?php if (!empty($school_info['logo'])): ?>
        <img src="assets/images/<?php echo htmlspecialchars($school_info['logo']); ?>" alt="<?php echo htmlspecialchars($school_info['school_name'] ?? 'Logo'); ?>" style="height:44px; width:auto; border-radius:8px; object-fit:contain;" fetchpriority="high">
      <?php endif; ?>
      <div>
        <div style="font-weight:700; font-size:0.95rem; color:#123B6A; line-height:1.25; max-width:250px;"><?php echo htmlspecialchars($school_info['school_name'] ?? 'স্কুল'); ?></div>
        <?php if (!empty($school_info['eiin'])): ?>
        <div style="font-size:0.7rem; color:#64748b;">EIIN: <?php echo htmlspecialchars($school_info['eiin']); ?></div>
        <?php endif; ?>
      </div>
    </a>

    <!-- Desktop Navigation -->
    <div class="hidden lg:flex" style="align-items:center; gap:2px; flex-wrap:nowrap;">

      <a href="index.php" class="nav-link" style="padding:8px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; white-space:nowrap;">হোম</a>
      <a href="about.php" class="nav-link" style="padding:8px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; white-space:nowrap;">প্রতিষ্ঠান সম্পর্কে</a>

      <?php if ($isSoftware): ?>
      <!-- Dropdown: Student Corner -->
      <div style="position:relative;" x-data="{d1:false}" @mouseenter="d1=true" @mouseleave="d1=false" x-cloak>
        <button @click="d1=!d1" @click.away="d1=false" style="display:flex; align-items:center; gap:4px; padding:8px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; background:none; border:none; cursor:pointer; white-space:nowrap;" class="nav-link">
          স্টুডেন্ট কর্নার
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" :style="d1 ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="d1" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="position:absolute; top:100%; left:0; margin-top:4px; min-width:200px; background:#fff; border-radius:12px; box-shadow:0 8px 30px rgba(18,59,106,0.14); border:1px solid #E8ECF3; padding:6px; z-index:200;">
          <a href="<?php echo APP_URL; ?>/authentication" class="dropdown-item">শিক্ষার্থী প্রফাইল লগইন</a>
          <a href="<?php echo APP_URL; ?>/admission" class="dropdown-item">অনলাইন ভর্তি</a>
          <a href="<?php echo APP_URL; ?>/certificates" class="dropdown-item">Certificates</a>
          <a href="<?php echo APP_URL; ?>/exam_results" class="dropdown-item">Exam Results</a>
        </div>
      </div>
    <?php endif; ?>
      <!-- Dropdown: Important Info -->
      <div style="position:relative;" x-data="{d2:false}" @mouseenter="d2=true" @mouseleave="d2=false" x-cloak>
        <button @click="d2=!d2" @click.away="d2=false" style="display:flex; align-items:center; gap:4px; padding:8px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; background:none; border:none; cursor:pointer; white-space:nowrap;" class="nav-link">
          গুরুত্বপূর্ণ তথ্য
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" :style="d2 ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="d2" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="position:absolute; top:100%; left:0; margin-top:4px; min-width:200px; background:#fff; border-radius:12px; box-shadow:0 8px 30px rgba(18,59,106,0.14); border:1px solid #E8ECF3; padding:6px; z-index:200;">
          <a href="student_info.php" class="dropdown-item">শিক্ষার্থী তথ্য</a>
          <a href="teachers.php" class="dropdown-item">শিক্ষকবৃন্দ</a>
          <a href="routine.php" class="dropdown-item">ক্লাস রুটিন</a>
          <a href="result.php" class="dropdown-item">ফলাফল</a>
          <a href="result_archives.php" class="dropdown-item">ফলাফল আর্কাইভ</a>
          <a href="management_committee.php" class="dropdown-item">ব্যবস্থাপনা কমিটি</a>
          <a href="admission.php" class="dropdown-item">ভর্তি তথ্য</a>
        </div>
      </div>

      <a href="notices.php" class="nav-link" style="padding:8px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; white-space:nowrap;">নোটিশ</a>

      <!-- Dropdown: Others -->
      <div style="position:relative;" x-data="{d3:false}" @mouseenter="d3=true" @mouseleave="d3=false" x-cloak>
        <button @click="d3=!d3" @click.away="d3=false" style="display:flex; align-items:center; gap:4px; padding:8px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; background:none; border:none; cursor:pointer; white-space:nowrap;" class="nav-link">
          অন্যান্য
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" :style="d3 ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="d3" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="position:absolute; top:100%; left:0; margin-top:4px; min-width:200px; background:#fff; border-radius:12px; box-shadow:0 8px 30px rgba(18,59,106,0.14); border:1px solid #E8ECF3; padding:6px; z-index:200;">
          <a href="forms.php" class="dropdown-item">সকল ফর্ম</a>
          <a href="student_of_the_year.php" class="dropdown-item">কৃতি শিক্ষার্থী</a>
          <a href="event.php" class="dropdown-item">ইভেন্ট</a>
          <a href="photo_gallery.php" class="dropdown-item">ছবি গ্যালারি</a>
          <a href="video_gallery.php" class="dropdown-item">ভিডিও গ্যালারি</a>
          <a href="admin/dashboard.php" class="dropdown-item">Admin Dashboard</a>
        </div>
      </div>

      <a href="contact.php" class="nav-link" style="padding:8px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; white-space:nowrap;">যোগাযোগ</a>
    </div>

    <!-- CTA + Mobile Toggle -->
    <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
      <a href="<?php echo APP_URL; ?>/admission" class="hidden sm:inline-flex btn-primary-modern" style="font-size:0.82rem; padding:0.5rem 1.1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        অনলাইন ভর্তি
      </a>
      <!-- Mobile hamburger -->
      <button @click="open = !open; if (!open) mobileOpenSub = null" class="lg:hidden" style="padding:8px; border-radius:8px; background:none; border:none; cursor:pointer; color:#374151;" aria-label="Toggle menu">
        <svg x-cloak x-show="!open" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <svg x-cloak x-show="open" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
  </nav>

  <!-- Mobile Menu -->
  <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden" style="border-top:1px solid #E8ECF3; background:#fff;" @click.away="open = false; mobileOpenSub = null">
    <div style="padding:0.75rem 1rem; max-height:80vh; overflow-y:auto;">
      <a href="index.php" style="display:block; padding:10px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151;">হোম</a>
      <a href="about.php" style="display:block; padding:10px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151;">প্রতিষ্ঠান সম্পর্কে</a>
    <?php if ($isSoftware): ?>
      <div style="margin-top:6px;">
        <button type="button" @click="mobileOpenSub = (mobileOpenSub === 'student' ? null : 'student')" style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:8px; font-size:0.88rem; font-weight:600; color:#374151; background:none; border:none; cursor:pointer; text-align:left;">
          <span>স্টুডেন্ট কর্নার</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" :style="mobileOpenSub === 'student' ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-cloak x-show="mobileOpenSub === 'student'" x-collapse style="padding:0 6px 6px 6px;">
          <a href="<?php echo APP_URL; ?>/authentication" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">শিক্ষার্থী প্রফাইল লগইন</a>
          <a href="<?php echo APP_URL; ?>/admission" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">অনলাইন ভর্তি</a>
          <a href="<?php echo APP_URL; ?>/certificates" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">Certificates</a>
          <a href="<?php echo APP_URL; ?>/exam_results" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">Exam Results</a>
        </div>
      </div>
    <?php endif; ?>
      <div style="margin-top:6px;">
        <button type="button" @click="mobileOpenSub = (mobileOpenSub === 'important' ? null : 'important')" style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:8px; font-size:0.88rem; font-weight:600; color:#374151; background:none; border:none; cursor:pointer; text-align:left;">
          <span>গুরুত্বপূর্ণ তথ্য</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" :style="mobileOpenSub === 'important' ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-cloak x-show="mobileOpenSub === 'important'" x-collapse style="padding:0 6px 6px 6px;">
          <a href="student_info.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">শিক্ষার্থী তথ্য</a>
          <a href="teachers.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">শিক্ষকবৃন্দ</a>
          <a href="routine.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ক্লাস রুটিন</a>
          <a href="result.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ফলাফল</a>
          <a href="result_archives.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ফলাফল আর্কাইভ</a>
          <a href="management_committee.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ব্যবস্থাপনা কমিটি</a>
          <a href="admission.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ভর্তি তথ্য</a>
        </div>
      </div>

      <a href="notices.php" style="display:block; padding:10px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; margin-top:4px;">নোটিশ</a>

      <div style="margin-top:6px;">
        <button type="button" @click="mobileOpenSub = (mobileOpenSub === 'other' ? null : 'other')" style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:8px; font-size:0.88rem; font-weight:600; color:#374151; background:none; border:none; cursor:pointer; text-align:left;">
          <span>অন্যান্য</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" :style="mobileOpenSub === 'other' ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-cloak x-show="mobileOpenSub === 'other'" x-collapse style="padding:0 6px 6px 6px;">
          <a href="forms.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">সকল ফর্ম</a>
          <a href="student_of_the_year.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">কৃতি শিক্ষার্থী</a>
          <a href="event.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ইভেন্ট</a>
          <a href="photo_gallery.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ছবি গ্যালারি</a>
          <a href="video_gallery.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">ভিডিও গ্যালারি</a>
          <a href="admin/dashboard.php" style="display:block; padding:8px 18px; border-radius:8px; font-size:0.85rem; color:#374151;">Admin Dashboard</a>
        </div>
      </div>

      <a href="contact.php" style="display:block; padding:10px 12px; border-radius:8px; font-size:0.88rem; font-weight:500; color:#374151; margin-top:4px;">যোগাযোগ</a>
      <div style="padding:8px 4px; margin-top:8px;">
        <a href="<?php echo APP_URL; ?>/admission" class="btn-primary-modern" style="justify-content:center; width:100%; text-align:center;">অনলাইন ভর্তি</a>
      </div>
    </div>
  </div>
</header>
<!-- AlpineJS -->
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>