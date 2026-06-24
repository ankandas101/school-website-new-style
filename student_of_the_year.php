<?php
require_once 'includes/db.php';

// Fetch students of the year (active only, latest year first)
$sql = 'SELECT * FROM student_of_the_year WHERE status=1 ORDER BY year DESC, id DESC';
$result = $conn->query($sql);

$page_title = 'Student of the Year';
$page_desc = 'List of students awarded Student of the Year.';

include_once 'includes/header.php';
?>
<style>
.students-section {
  max-width: 1280px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.page-header {
  text-align: center;
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 800;
  color: #123B6A;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #64748b;
  font-size: 0.95rem;
}

.students-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}

.student-card {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
}

.student-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(17, 136, 71, 0.12);
  border-color: #118847;
}

.student-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #ecfdf5;
  box-shadow: 0 4px 12px rgba(21, 128, 61, 0.15);
}

.student-avatar-placeholder {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #e8f5ee 0%, #dbeafe 100%);
  border: 3px solid #ecfdf5;
  color: #15803D;
  font-size: 2rem;
  font-weight: 700;
}

.student-name {
  font-size: 1rem;
  font-weight: 700;
  color: #123B6A;
  line-height: 1.3;
  margin: 0;
}

.student-details {
  font-size: 0.85rem;
  color: #475569;
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  width: 100%;
  background: #f8fafc;
  padding: 0.75rem 1rem;
  border-radius: 10px;
  border: 1px solid #f1f5f9;
}

.student-details div {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.student-details span {
  font-weight: 600;
  color: #118847;
}

.no-data {
  text-align: center;
  padding: 3rem;
  background: #fff;
  border-radius: 16px;
  border: 1px dashed #E8ECF3;
  color: #64748b;
  font-size: 0.95rem;
  max-width: 500px;
  margin: 0 auto;
}

.year-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 0.4rem 0.85rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  background: #fef3c7;
  color: #92400e;
  margin-top: 0.5rem;
}

.breadcrumb-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.85rem;
  font-weight: 600;
  color: #118847;
  text-decoration: none;
  margin-bottom: 1rem;
  transition: color 0.2s;
}

.breadcrumb-back:hover {
  color: #0d6b38;
}

/* Responsive */
@media (max-width: 1023px) {
  .students-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 639px) {
  .students-grid {
    grid-template-columns: 1fr;
    max-width: 400px;
    margin: 0 auto;
  }
  
  .page-header h1 {
    font-size: 1.5rem;
  }
}
</style>

<div class="students-section">
  <a href="index.php" class="breadcrumb-back">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
    হোম পেজে ফিরে যান
  </a>
  
  <div class="page-header">
    <h1>কৃতি শিক্ষার্থী</h1>
    <p>Student of the Year award winners</p>
  </div>
  
  <?php if ($result && $result->num_rows > 0): ?>
    <div class="students-grid">
      <?php while($student = $result->fetch_assoc()): ?>
        <div class="student-card">
          <?php if (!empty($student['photo'])): ?>
            <img src="assets/images/<?php echo htmlspecialchars($student['photo']); ?>" class="student-avatar" alt="<?php echo htmlspecialchars($student['name']); ?>">
          <?php else: ?>
            <div class="student-avatar-placeholder"><?php echo mb_substr(htmlspecialchars($student['name']), 0, 1); ?></div>
          <?php endif; ?>
          <div class="student-name"><?php echo htmlspecialchars($student['name']); ?></div>
          <div class="student-details">
            <div>শ্রেণি <span><?php echo htmlspecialchars($student['class']); ?></span></div>
            <div>বছর <span><?php echo htmlspecialchars($student['year']); ?></span></div>
          </div>
          <?php if (!empty($student['year'])): ?>
          <div class="year-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <?php echo htmlspecialchars($student['year']); ?>
          </div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-data">কোনো কৃতি শিক্ষার্থী পাওয়া যায়নি।</div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>