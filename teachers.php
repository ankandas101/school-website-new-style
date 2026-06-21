<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// Fetch only active teachers ordered by sort_order
$sql = 'SELECT * FROM teachers WHERE status=1 ORDER BY (sort_order=0), sort_order ASC, id DESC';
$result = $conn->query($sql);

$page_title = 'শিক্ষকবৃন্দ';
$page_desc = 'স্কুলের শিক্ষকবৃন্দের তালিকা';

include_once 'includes/header.php';
?>

<style>
.teachers-section {
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

.teachers-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.teacher-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
  text-decoration: none;
  display: block;
}

.teacher-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(18, 59, 106, 0.1);
  border-color: #15803D;
}

.teacher-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin: 0 auto 1rem;
  display: block;
  border: 3px solid #E8ECF3;
  background: #ecfdf5;
}

.teacher-name {
  font-size: 1rem;
  font-weight: 700;
  color: #1E3A8A;
  margin-bottom: 0.35rem;
}

.teacher-designation {
  font-size: 0.82rem;
  color: #15803D;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.teacher-details {
  font-size: 0.78rem;
  color: #64748b;
}

.teacher-details p {
  margin-bottom: 0.25rem;
}

.teacher-details a {
  color: #15803D;
  text-decoration: none;
  transition: color 0.2s;
}

.teacher-details a:hover {
  color: #0d6b38;
}

.no-teachers {
  text-align: center;
  padding: 3rem;
  background: #fff;
  border-radius: 20px;
  border: 1px dashed #E8ECF3;
  color: #64748b;
}

/* Responsive */
@media (max-width: 1024px) {
  .teachers-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .teachers-grid {
    grid-template-columns: 1fr;
  }
  .teachers-section {
    padding: 2rem 1rem;
  }
  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>

<div class="teachers-section">
  <div class="page-header">
    <h1>শিক্ষকবৃন্দ</h1>
    <p>আমাদের অভিজ্ঞ এবং নিবেদিত শিক্ষকদের দল</p>
  </div>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="teachers-grid">
      <?php while($teacher = $result->fetch_assoc()): ?>
      <a href="teacher_detail.php?id=<?php echo $teacher['id']; ?>" class="teacher-card">
        <?php if (!empty($teacher['photo'])): ?>
          <img src="assets/images/<?php echo htmlspecialchars($teacher['photo']); ?>" class="teacher-image" alt="<?php echo htmlspecialchars($teacher['name']); ?>">
        <?php else: ?>
          <div class="teacher-image" style="display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="1.5">
              <circle cx="12" cy="8" r="4"/>
              <path d="M20 21a8 8 0 1 0-16 0"/>
            </svg>
          </div>
        <?php endif; ?>
        <div class="teacher-name">
          <?php echo htmlspecialchars($teacher['name']); ?>
        </div>
        <div class="teacher-designation">
          <?php echo htmlspecialchars($teacher['designation']); ?>
        </div>
        <div class="teacher-details">
          <?php if (!empty($teacher['phone'])): ?>
            <p><span>📞</span> <?php echo htmlspecialchars($teacher['phone']); ?></p>
          <?php endif; ?>
          <?php if (!empty($teacher['email'])): ?>
            <p><span>✉️</span> <?php echo htmlspecialchars($teacher['email']); ?></p>
          <?php endif; ?>
        </div>
      </a>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-teachers">
      <div style="font-size: 2rem; margin-bottom: 1rem;">📚</div>
      <p>কোনো শিক্ষক পাওয়া যায়নি।</p>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
