<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// Helper function to make URLs clickable
function make_links_clickable($text) {
  $pattern = '/(https?:\/\/[\w\-\.\/?&=;%#@!\+~:,]+)/i';
  return preg_replace($pattern, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>', htmlspecialchars($text));
}

// Get notice id from query string
$notice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$notice = null;
if ($notice_id > 0) {
  $stmt = $conn->prepare('SELECT * FROM notices WHERE id=?');
  $stmt->bind_param('i', $notice_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $notice = $result->fetch_assoc();
}

// SEO meta
$page_title = $notice ? htmlspecialchars($notice['title']) . ' | নোটিশ' : 'নোটিশ';
$page_desc = $notice ? mb_substr(strip_tags($notice['description']), 0, 150) : 'নোটিশ';

include_once 'includes/header.php';
?>
<style>
.notice-detail-section {
  max-width: 900px;
  margin: 0 auto;
  padding: 3rem 1rem;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: #15803D;
  font-weight: 700;
  text-decoration: none;
  font-size: 0.95rem;
  margin-bottom: 2rem;
  transition: color 0.2s ease;
}

.back-link:hover {
  color: #0d6b38;
}

.notice-detail-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  overflow: hidden;
}

.notice-detail-badge {
  background: linear-gradient(135deg, #15803D 0%, #0d6b38 100%);
  color: white;
  padding: 1rem 2rem;
  font-size: 0.95rem;
  font-weight: 700;
  text-align: center;
}

.notice-detail-content {
  padding: 2.5rem;
}

.notice-detail-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #1E3A8A;
  margin-bottom: 1rem;
}

.notice-detail-date {
  font-size: 1rem;
  color: #15803D;
  font-weight: 600;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #E8ECF3;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.notice-detail-body {
  font-size: 1rem;
  color: #475569;
  line-height: 1.8;
}

.notice-detail-body a {
  color: #15803D;
  text-decoration: underline;
}

.notice-detail-attachment {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #E8ECF3;
}

.attachment-preview {
  margin-top: 1rem;
}

.attachment-preview img {
  max-width: 100%;
  height: auto;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

.attachment-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, #15803D 0%, #0d6b38 100%);
  color: white;
  text-decoration: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.attachment-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(17, 136, 71, 0.3);
}

.no-notice {
  text-align: center;
  padding: 4rem 2rem;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
}

.no-notice h2 {
  font-size: 1.5rem;
  color: #1E3A8A;
  margin-bottom: 1rem;
}

.no-notice p {
  color: #64748b;
}

/* Responsive */
@media (max-width: 640px) {
  .notice-detail-section {
    padding: 2rem 1rem;
  }
  
  .notice-detail-content {
    padding: 1.5rem;
  }
  
  .notice-detail-title {
    font-size: 1.4rem;
  }
}
</style>

<div class="notice-detail-section">
  <?php if ($notice): ?>
    <a href="notices.php" class="back-link">
      <span>←</span>
      সব নোটিশ
    </a>
    
    <div class="notice-detail-card">
      <div class="notice-detail-badge">
        নোটিশ
      </div>
      
      <div class="notice-detail-content">
        <h1 class="notice-detail-title"><?php echo htmlspecialchars($notice['title']); ?></h1>
        
        <div class="notice-detail-date">
          <span>📅</span>
          প্রকাশের তারিখ: <?php echo htmlspecialchars($notice['notice_date']); ?>
        </div>
        
        <div class="notice-detail-body">
          <?php echo nl2br(make_links_clickable($notice['description'])); ?>
        </div>
        
        <?php if (!empty($notice['attachment'])): ?>
          <div class="notice-detail-attachment">
            <?php 
              $ext = strtolower(pathinfo($notice['attachment'], PATHINFO_EXTENSION));
              $fileUrl = 'assets/notices/' . htmlspecialchars($notice['attachment']);
            ?>
            
            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
              <div class="attachment-preview">
                <img src="<?php echo $fileUrl; ?>" alt="Attachment">
              </div>
              <a href="<?php echo $fileUrl; ?>" target="_blank" class="attachment-btn" style="margin-top: 1rem;">
                📷 View Full Image
              </a>
            <?php elseif ($ext === 'pdf'): ?>
              <a href="<?php echo $fileUrl; ?>" target="_blank" class="attachment-btn">
                📄 View PDF Attachment
              </a>
            <?php else: ?>
              <a href="<?php echo $fileUrl; ?>" target="_blank" class="attachment-btn">
                📎 Download Attachment
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <div class="no-notice">
      <h2>নোটিশ পাওয়া যায়নি।</h2>
      <p>আপনি যে নোটিশটি খুঁজছেন তা বিদ্যমান নেই।</p>
      <a href="notices.php" class="back-link" style="margin-top: 1.5rem;">
        <span>←</span>
        সব নোটিশ
      </a>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
