<?php
require_once 'includes/db.php';

// Fetch all active forms
$sql = 'SELECT * FROM forms WHERE status=1 ORDER BY id DESC';
$result = $conn->query($sql);

$page_title = 'গুরুত্বপূর্ণ ডকুমেন্টস সমুহ';
$page_desc = 'স্কুলের সকল ডকুমেন্ট বা ফর্ম ডাউনলোড করুন।';
include_once 'includes/header.php';
?>
<style>
.forms-section {
  max-width: 1280px;
  margin: 0 auto;
  padding: 3rem 1rem;
}

.page-header {
  text-align: center;
  margin-bottom: 3rem;
}

.page-header h1 {
  font-size: 2.25rem;
  font-weight: 800;
  color: #1E3A8A;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #64748b;
  font-size: 1rem;
}

.forms-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
  max-width: 900px;
  margin: 0 auto;
}

.form-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.form-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(17, 136, 71, 0.15);
  border-color: #15803D;
}

.form-icon {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #e8f5ee 0%, #dbeafe 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.form-info {
  flex: 1;
  min-width: 0;
}

.form-title {
  font-size: 1rem;
  font-weight: 700;
  color: #1E3A8A;
  margin-bottom: 0.25rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.no-data {
  text-align: center;
  padding: 3rem;
  background: #fff;
  border-radius: 20px;
  border: 1px dashed #E8ECF3;
  color: #64748b;
  font-size: 1rem;
  max-width: 600px;
  margin: 0 auto;
}

/* Responsive */
@media (max-width: 640px) {
  .forms-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>

<div class="forms-section">
  <div class="page-header">
    <h1>গুরুত্বপূর্ণ ডকুমেন্টস সেন্টার</h1>
    <p>স্কুলের সকল ডকুমেন্ট বা ফর্ম ডাউনলোড করুন।</p>
  </div>
  
  <?php if ($result && $result->num_rows > 0): ?>
    <div class="forms-grid">
      <?php while($form = $result->fetch_assoc()): ?>
        <a href="assets/forms/<?php echo htmlspecialchars($form['file']); ?>" target="_blank" class="form-card">
          <div class="form-icon">📄</div>
          <div class="form-info">
            <div class="form-title"><?php echo htmlspecialchars($form['title']); ?></div>
          </div>
          <div style="color: #15803D; font-weight: 700; font-size: 0.85rem;">Download</div>
        </a>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-data">
      <div style="font-size: 2rem; margin-bottom: 1rem;">📎</div>
      <p>কোনো ডকুমেন্টস পাওয়া যায়নি।</p>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
