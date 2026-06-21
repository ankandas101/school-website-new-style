<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/classes.php';
// Ensure UTF-8 content type for correct Bengali rendering
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}
$school = new SchoolInfo($conn);
$footer = new FooterInfo($conn);
$school_info = $school->get();
$footer_info = $footer->get();
// Fetch meta code from database
$meta_code = '';
$meta_code_row = $conn->query('SELECT code FROM meta_code LIMIT 1');
if ($meta_code_row && $meta_code_row->num_rows > 0) {
    $meta_code = $meta_code_row->fetch_assoc()['code'];
}
// Fetch SEO settings for current page
$page = basename($_SERVER['SCRIPT_NAME'], '.php');
$seo_data = null;
if (class_exists('SEOSettings')) {
    $seo = new SEOSettings($conn);
    $seo_data = $seo->get($page);
}
$default_desc = 'বাংলাদেশের অন্যতম শ্রেষ্ঠ স্কুল ম্যানেজমেন্ট ওয়েবসাইট সিস্টেম - সহজ ও দ্রুত ওয়েবসাইট আপনার শিক্ষাপ্রতিষ্ঠানের জন্য।';
if ($seo_data && !empty($seo_data['meta_description'])) {
    $description = $seo_data['meta_description'];
} elseif (!empty($school_info['about'])) {
    $description = mb_substr(strip_tags($school_info['about']), 0, 160);
} else {
    $description = $default_desc;
}
$default_keywords = 'ankandas.me,স্কুল ওয়েবসাইট, স্কুল ম্যানেজমেন্ট, শিক্ষাপ্রতিষ্ঠান, school website, education website,ankan das, বাংলাদেশ';
$keywords = ($seo_data && !empty($seo_data['meta_keywords'])) ? $seo_data['meta_keywords'] : $default_keywords;
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($keywords); ?>">
    <title><?php echo htmlspecialchars(($seo_data && !empty($seo_data['meta_title'])) ? $seo_data['meta_title'] : ($school_info['school_name'] ?? 'বিদ্যালয় ওয়েবসাইট')); ?></title>
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars(($seo_data && !empty($seo_data['meta_title'])) ? $seo_data['meta_title'] : ($school_info['school_name'] ?? '')); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:image" content="<?php echo (!empty($seo_data['meta_image'])) ? htmlspecialchars($seo_data['meta_image']) : 'assets/images/feature.jpg'; ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars(((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>">
    <meta property="og:type" content="website">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars(($seo_data && !empty($seo_data['meta_title'])) ? $seo_data['meta_title'] : ($school_info['school_name'] ?? '')); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="twitter:image" content="<?php echo (!empty($seo_data['meta_image'])) ? htmlspecialchars($seo_data['meta_image']) : 'assets/images/feature.jpg'; ?>">
    <!-- Resource Hints -->
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//www.youtube.com">
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico">
    <!-- Google Fonts: Hind Siliguri -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=6">
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary:        '#118847',
                    'primary-dark': '#0d6b38',
                    'primary-light':'#e8f5ee',
                    dark:           '#123B6A',
                    'dark-light':   '#1a4f8a',
                    light:          '#F7F9FC',
                    border:         '#E8ECF3',
                },
                fontFamily: {
                    siliguri: ['Hind Siliguri', 'Noto Sans Bengali', 'sans-serif'],
                },
            }
        }
    }
    </script>
    <!-- Responsive CSS (after Tailwind so mobile overrides apply) -->
    <link rel="stylesheet" href="assets/css/responsive.css?v=6">
</head>
<?php if (!empty($meta_code)) echo $meta_code; ?>
<body class="font-siliguri bg-light text-gray-800 antialiased">

<!-- ===== TOP NOTICE TICKER ===== -->
<?php
$noticeTicker = new Notice($conn);
$latest_notices = $noticeTicker->getActive(5, true);
?>
<?php if ($latest_notices && $latest_notices->num_rows > 0): ?>
<div class="ticker-bar">
    <div style="max-width:1280px; margin:0 auto; display:flex; align-items:center; gap:12px; padding:0 1rem;">
        <div style="flex-shrink:0; display:flex; align-items:center; gap:6px; font-weight:700; font-size:0.82rem; white-space:nowrap; color:#fff; background:rgba(0,0,0,0.15); padding:4px 10px; border-radius:4px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
            নোটিশ
        </div>
        <div class="ticker-wrap">
            <div class="ticker-move">
                <?php $first = true; while($row = $latest_notices->fetch_assoc()): ?>
                    <?php if (!$first): ?><span style="margin:0 12px; opacity:0.4;">|</span><?php endif; $first = false; ?>
                    <a href="notice.php?id=<?php echo (int)$row['id']; ?>" style="color:#fff; text-decoration:none; font-size:0.82rem; transition:color 0.2s;" onmouseover="this.style.color='#fde68a'" onmouseout="this.style.color='#fff'"><?php echo htmlspecialchars($row['title']); ?></a>
                <?php endwhile; ?>
            </div>
        </div>
        <div style="flex-shrink:0; display:flex; align-items:center; gap:8px;">
            <?php if (!empty($footer_info['facebook'])): ?>
            <a href="<?php echo htmlspecialchars($footer_info['facebook']); ?>" target="_blank" rel="noopener noreferrer" style="color:#fff; opacity:0.85; transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.85" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <?php endif; ?>
            <a href="#" style="color:#fff; opacity:0.85; transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.85" aria-label="YouTube">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#118847"/></svg>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ===== NAVBAR ===== -->
<?php include_once __DIR__ . '/navbar.php'; ?>
<!-- ===== MAIN CONTENT STARTS ===== -->