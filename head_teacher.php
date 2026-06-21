<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// SEO meta for head teacher page
$page_title = 'প্রধান শিক্ষকের বাণী | ';
$page_desc = 'প্রধান শিক্ষকের বাণী, সরকারি বিদ্যালয়';

// Fetch head teacher message
$msg = new Message($conn);
$head = $msg->get('head_teacher');

include_once 'includes/header.php';
?>


<!-- ===== MAIN CONTENT ===== -->
<div style="background: var(--light); padding: var(--spacing-2xl) 0;">
  <div style="max-width:1280px; margin:0 auto; padding:0 1rem;">
    <div style="max-width:900px; margin:0 auto;" data-aos="fade-up" data-aos-delay="100">
      <div class="about-card" style="padding: var(--spacing-2xl);">
        <div class="principal-message-grid">
          <!-- Photo Column -->
          <div class="principal-photo-col">
            <?php if (!empty($head['photo'])): ?>
              <div style="width:180px; height:180px; border-radius:50%; overflow:hidden; border:4px solid var(--primary-light); box-shadow: var(--shadow-md); margin-bottom: var(--spacing-lg); flex-shrink:0;">
                <img src="assets/images/<?php echo htmlspecialchars($head['photo']); ?>" alt="প্রধান শিক্ষক" style="width:100%; height:100%; object-fit:cover; display:block;">
              </div>
            <?php else: ?>
              <div style="width:180px; height:180px; border-radius:50%; background:var(--primary-light); border:4px solid var(--primary); display:flex; align-items:center; justify-content:center; margin-bottom: var(--spacing-lg); flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
            <?php endif; ?>
            <div style="text-align:center;">
              <div class="fw-bold" style="font-size:1.15rem; color:var(--dark); line-height:1.3;"><?php echo htmlspecialchars($head['name'] ?? ''); ?></div>
              <div style="font-size:0.85rem; color:var(--primary); font-weight:600; margin-top:2px;">প্রধান শিক্ষক</div>
            </div>
          </div>

          <!-- Message Column -->
          <div class="principal-text-col">
            <div style="margin-bottom: var(--spacing-md);">
              <div style="width:40px; height:3px; background:var(--primary); border-radius:2px; margin-bottom: var(--spacing-sm);"></div>
              <div style="font-size:0.8rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">প্রধান শিক্ষকের বার্তা</div>
            </div>
            <div class="justified-text" style="font-size:1.05rem; line-height:1.8; color:var(--text-primary); flex:1;">
              <?php echo nl2br(htmlspecialchars($head['message'] ?? '')); ?>
            </div>
            
            <!-- Contact Info -->
            <?php if (!empty($head['phone']) || !empty($head['email'])): ?>
            <div style="margin-top: var(--spacing-lg); padding-top: var(--spacing-lg); border-top:1px solid var(--border);">
              <div style="font-size:0.8rem; font-weight:700; color:var(--dark); margin-bottom: var(--spacing-sm); text-transform:uppercase; letter-spacing:0.5px;">যোগাযোগ</div>
              <div style="display:flex; flex-direction:column; gap:8px;">
                <?php if (!empty($head['phone'])): ?>
                <a href="tel:<?php echo htmlspecialchars($head['phone']); ?>" style="display:inline-flex; align-items:center; gap:8px; color:var(--text-primary); text-decoration:none; font-size:0.9rem; transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-primary)'">
                  <span style="width:32px; height:32px; background:var(--primary-light); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.37a16 16 0 0 0 6.72 6.72l1.83-1.83a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                  </span>
                  <?php echo htmlspecialchars($head['phone']); ?>
                </a>
                <?php endif; ?>
                <?php if (!empty($head['email'])): ?>
                <a href="mailto:<?php echo htmlspecialchars($head['email']); ?>" style="display:inline-flex; align-items:center; gap:8px; color:var(--text-primary); text-decoration:none; font-size:0.9rem; transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-primary)'">
                  <span style="width:32px; height:32px; background:var(--primary-light); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                  </span>
                  <?php echo htmlspecialchars($head['email']); ?>
                </a>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Back Button -->
      <div style="margin-top: var(--spacing-xl); text-align:center;" data-aos="fade-up" data-aos-delay="200">
        <a href="index.php" class="btn-outline-modern">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          হোমে ফিরে যান
        </a>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>
