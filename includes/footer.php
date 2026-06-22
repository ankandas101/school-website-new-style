<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/classes.php';
$school = new SchoolInfo($conn);
$footer = new FooterInfo($conn);
$school_info = $school->get();
$footer_info = $footer->get();
?>
    <!-- ===== SITE FOOTER ===== -->
    <footer class="site-footer" role="contentinfo">
        <div style="max-width:1280px; margin:0 auto; padding:0 1rem;">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:2.5rem; padding-bottom:2rem;">

                <!-- Col 1: About School -->
                <div>
                    <?php if (!empty($footer_info['footer_logo'])): ?>
                        <img src="assets/images/<?php echo htmlspecialchars($footer_info['footer_logo']); ?>" alt="<?php echo htmlspecialchars($school_info['school_name'] ?? ''); ?>" class="footer-logo" loading="lazy" decoding="async">
                    <?php endif; ?>
                    <div class="footer-heading"><?php echo htmlspecialchars($school_info['school_name'] ?? ''); ?></div>
                    <?php if (!empty($footer_info['footer_short'])): ?>
                    <p style="font-size:0.83rem; color:#94a3b8; line-height:1.65; margin-bottom:1rem;"><?php echo nl2br(htmlspecialchars($footer_info['footer_short'])); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($school_info['eiin'])): ?>
                    <p style="font-size:0.8rem; color:#64748b;">EIIN: <?php echo htmlspecialchars($school_info['eiin']); ?></p>
                    <?php endif; ?>
                    <!-- Social Media -->
                    <div class="footer-social">
                        <?php if (!empty($footer_info['facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($footer_info['facebook']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <?php endif; ?>
                        <a href="#" aria-label="YouTube">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#123B6A"/></svg>
                        </a>
                        <a href="#" aria-label="Twitter/X">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <div class="footer-heading">গুরুত্বপূর্ণ লিংক</div>
                    <nav aria-label="Quick Links">
                        <a href="index.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            হোম
                        </a>
                        <a href="about.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            প্রতিষ্ঠান সম্পর্কে
                        </a>
                        <a href="teachers.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            শিক্ষকবৃন্দ
                        </a>
                        <a href="notices.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            নোটিশ বোর্ড
                        </a>
                        <a href="result.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            ফলাফল
                        </a>
                        <a href="routine.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            রুটিন
                        </a>
                        <a href="photo_gallery.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            ছবি গ্যালারি
                        </a>
                        <a href="admission.php" class="footer-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            ভর্তি তথ্য
                        </a>
                    </nav>
                </div>

                <!-- Col 3: Contact -->
                <div>
                    <div class="footer-heading">যোগাযোগ</div>
                    <?php if (!empty($footer_info['address'])): ?>
                    <div class="footer-contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="2" style="flex-shrink:0; margin-top:2px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span><?php echo htmlspecialchars($footer_info['address']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($footer_info['phone'])): ?>
                    <div class="footer-contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="2" style="flex-shrink:0;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.37a16 16 0 0 0 6.72 6.72l1.83-1.83a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <a href="tel:<?php echo htmlspecialchars($footer_info['phone']); ?>" style="color:#94a3b8;"><?php echo htmlspecialchars($footer_info['phone']); ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($footer_info['email'])): ?>
                    <div class="footer-contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="2" style="flex-shrink:0;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:<?php echo htmlspecialchars($footer_info['email']); ?>" style="color:#94a3b8;"><?php echo htmlspecialchars($footer_info['email']); ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($school_info['eiin'])): ?>
                    <div class="footer-contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#118847" stroke-width="2" style="flex-shrink:0;"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        <span>EIIN: <?php echo htmlspecialchars($school_info['eiin']); ?></span>
                    </div>
                    <?php endif; ?>
                    <a href="contact.php" class="btn-primary-modern" style="margin-top:1rem; font-size:0.8rem; padding:0.5rem 1.1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        যোগাযোগ করুন
                    </a>
                </div>

                <!-- Col 4: Our Location -->
                <div>
                    <div class="footer-heading">আমাদের অবস্থান</div>
                    <?php if (!empty($footer_info['address'])): ?>
                    <div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:1rem; margin-bottom:1rem;">
                        <div style="display:flex; align-items:flex-start; gap:0.6rem;">
                            <div style="width:36px; height:36px; background:#118847; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <div style="font-size:0.78rem; color:#94a3b8; margin-bottom:0.2rem;">ঠিকানা</div>
                                <div style="font-size:0.84rem; color:#cbd5e1;"><?php echo htmlspecialchars($footer_info['address']); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($school_info['established'])): ?>
                    <div style="font-size:0.82rem; color:#64748b; margin-bottom:0.4rem;">
                        <span style="color:#118847; font-weight:600;">প্রতিষ্ঠাকাল:</span> <?php echo htmlspecialchars($school_info['established']); ?>
                    </div>
                    <?php endif; ?>
                    <a href="contact.php" style="font-size:0.8rem; color:#4ade80; display:inline-flex; align-items:center; gap:0.3rem; margin-top:0.5rem; transition:color 0.2s;" onmouseover="this.style.color='#86efac'" onmouseout="this.style.color='#4ade80'">
                        যোগাযোগের মানচিত্র দেখুন
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>

            </div><!-- /grid -->
        </div><!-- /container -->

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div style="max-width:1280px; margin:0 auto; padding:0 1rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
                <span>&copy; <span class="current-year"><?php echo date('Y'); ?></span> <?php echo htmlspecialchars($school_info['school_name'] ?? ''); ?>. সর্বস্বত্ব সংরক্ষিত।</span>
                <span>Designed &amp; Developed by <a href="https://ankandas.me" target="_blank" rel="noopener noreferrer">ANKAN</a></span>
            </div>
        </div>
    </footer>
    <!-- ===== END FOOTER ===== -->

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
    </button>

    <!-- ===== SCRIPTS ===== -->
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js?v=7" defer></script>
</body>
</html>