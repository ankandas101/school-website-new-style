<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

$page_title = 'সভাপতির নির্দেশনা | ';
$page_desc = 'সভাপতির সঠিক নির্দেশনা , সরকারি উচ্চ বিদ্যালয়';

$msg = new Message($conn);
$chairman = $msg->get('chairman');

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
            <?php if (!empty($chairman['photo'])): ?>
              <div style="width:180px; height:180px; border-radius:50%; overflow:hidden; border:4px solid #dbeafe; box-shadow: var(--shadow-md); margin-bottom: var(--spacing-lg); flex-shrink:0;">
                <img src="assets/images/<?php echo htmlspecialchars($chairman['photo']); ?>" alt="সভাপতি" style="width:100%; height:100%; object-fit:cover; display:block;">
              </div>
            <?php else: ?>
              <div style="width:180px; height:180px; border-radius:50%; background:#eff6ff; border:4px solid #3b82f6; display:flex; align-items:center; justify-content:center; margin-bottom: var(--spacing-lg); flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              </div>
            <?php endif; ?>
            <div style="text-align:center;">
              <div class="fw-bold" style="font-size:1.15rem; color:var(--dark); line-height:1.3;"><?php echo htmlspecialchars($chairman['name'] ?? ''); ?></div>
              <?php if (!empty($chairman['title'])): ?>
              <div style="font-size:0.85rem; color:#3b82f6; font-weight:600; margin-top:2px;"><?php echo htmlspecialchars($chairman['title']); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Message Column -->
          <div class="principal-text-col">
            <div style="margin-bottom: var(--spacing-md);">
              <div style="width:40px; height:3px; background:#3b82f6; border-radius:2px; margin-bottom: var(--spacing-sm);"></div>
              <div style="font-size:0.8rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">সভাপতির বার্তা</div>
            </div>
            <div class="justified-text" style="font-size:1.05rem; line-height:1.8; color:var(--text-primary); flex:1;">
              <?php echo nl2br(htmlspecialchars($chairman['message'] ?? '')); ?>
            </div>
            
            <?php if (!empty($chairman['phone']) || !empty($chairman['email'])): ?>
            <!-- Contact Info -->
            <div style="margin-top: var(--spacing-lg); padding-top: var(--spacing-lg); border-top:1px solid var(--border);">
              <div style="font-size:0.8rem; font-weight:700; color:var(--dark); margin-bottom: var(--spacing-sm); text-transform:uppercase; letter-spacing:0.5px;">যোগাযোগ</div>
              <div style="display:flex; flex-direction:column; gap:8px;">
                <?php if (!empty($chairman['phone'])): ?>
                <a href="tel:<?php echo htmlspecialchars($chairman['phone']); ?>" style="display:inline-flex; align-items:center; gap:8px; color:var(--text-primary); text-decoration:none; font-size:0.9rem; transition: color 0.2s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
                  <span style="width:32px; height:32px; background:#eff6ff; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.59 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.37a16 16 0 0 0 6.72 6.72l1.83-1.83a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                  </span>
                  <?php echo htmlspecialchars($chairman['phone']); ?>
                </a>
                <?php endif; ?>
                <?php if (!empty($chairman['email'])): ?>
                <a href="mailto:<?php echo htmlspecialchars($chairman['email']); ?>" style="display:inline-flex; align-items:center; gap:8px; color:var(--text-primary); text-decoration:none; font-size:0.9rem; transition: color 0.2s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
                  <span style="width:32px; height:32px; background:#eff6ff; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                  </span>
                  <?php echo htmlspecialchars($chairman['email']); ?>
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
