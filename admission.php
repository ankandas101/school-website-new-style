<?php
include 'includes/db.php';

// Fetch current admission info
$requirements = '';
$banner = '';
$updated_at = '';
$result = $conn->query("SELECT * FROM admission_info LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
  $requirements = $row['requirements'];
  $banner = $row['banner'];
  $updated_at = $row['updated_at'];
}

include 'includes/header.php';
?>
<style>
.admission-section {
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

.admission-card {
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  max-width: 900px;
  margin: 0 auto;
}

.admission-banner {
  width: 100%;
  height: 300px;
  object-fit: cover;
  display: block;
}

.admission-content {
  padding: 3rem;
}

.admission-requirements {
  background: #f8fafc;
  padding: 2rem;
  border-radius: 12px;
  border: 1px solid #E8ECF3;
  margin-bottom: 2rem;
}

.admission-requirements h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1E3A8A;
  margin-bottom: 1.5rem;
}

.admission-requirements .content {
  color: #475569;
  line-height: 1.8;
  font-size: 1rem;
}

.admission-requirements .empty {
  color: #94a3b8;
}

.updated-badge {
  text-align: center;
  padding: 1rem;
  background: #e8f5ee;
  border-radius: 12px;
  color: #15803D;
  font-size: 0.9rem;
  border-left: 4px solid #15803D;
}

/* Responsive */
@media (max-width: 640px) {
  .admission-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .admission-content {
    padding: 1.5rem;
  }
  
  .admission-banner {
    height: 200px;
  }
}
</style>

<div class="admission-section">
  <div class="page-header">
    <h1>ভর্তির তথ্য</h1>
    <p>আমাদের প্রতিষ্ঠানে ভর্তির জন্য প্রয়োজনীয় তথ্য এবং নির্দেশাবলী</p>
  </div>
  
  <div class="admission-card">
    <?php if (!empty($banner)): ?>
      <img src="assets/images/<?php echo htmlspecialchars($banner); ?>" class="admission-banner" alt="ভর্তি ব্যানার">
    <?php endif; ?>
    
    <div class="admission-content">
      <div class="admission-requirements">
        <h2>ভর্তির শর্তাবলী ও প্রয়োজনীয়তা</h2>
        <div class="content">
          <?php if ($requirements): ?>
            <?php echo nl2br(htmlspecialchars($requirements)); ?>
          <?php else: ?>
            <p class="empty">ভর্তির শর্তাবলী শীঘ্রই যোগ করা হবে।</p>
          <?php endif; ?>
        </div>
      </div>
      
      <?php if ($updated_at): ?>
        <div class="updated-badge">
          ℹ️ শেষ আপডেট: <?php echo date('d F Y, g:i a', strtotime($updated_at)); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
include 'includes/footer.php';
?>
