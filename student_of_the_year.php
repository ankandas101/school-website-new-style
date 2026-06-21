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

.students-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
  max-width: 1000px;
  margin: 0 auto;
}

.student-card {
  background: #fff;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
  text-align: center;
}

.student-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(17, 136, 71, 0.15);
  border-color: #15803D;
}

.student-avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 1rem;
  border: 4px solid #ecfdf5;
  box-shadow: 0 4px 16px rgba(21, 128, 61, 0.18);
}

.student-avatar-placeholder {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  margin: 0 auto 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #e8f5ee 0%, #dbeafe 100%);
  border: 4px solid #ecfdf5;
  color: #15803D;
  font-size: 2.5rem;
}

.student-name {
  font-size: 1.1rem;
  font-weight: 800;
  color: #1E3A8A;
  margin-bottom: 0.5rem;
}

.student-details {
  font-size: 0.9rem;
  color: #475569;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.student-details span {
  font-weight: 600;
  color: #15803D;
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
  .students-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>

<div class="students-section">
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
            <div class="student-avatar-placeholder">👤</div>
          <?php endif; ?>
          <div class="student-name"><?php echo htmlspecialchars($student['name']); ?></div>
          <div class="student-details">
            <div>Class: <span><?php echo htmlspecialchars($student['class']); ?></span></div>
            <div>Year: <span><?php echo htmlspecialchars($student['year']); ?></span></div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-data">No Student of the Year found.</div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
