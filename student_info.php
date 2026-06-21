<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

$infos = $conn->query('SELECT * FROM student_info ORDER BY id ASC');

$page_title = 'শিক্ষার্থী তথ্য';
$page_desc = 'শ্রেণি ও লিঙ্গভিত্তিক শিক্ষার্থী তথ্য';

include_once 'includes/header.php';
?>

<style>
.student-info-section {
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

.info-card {
  background: #fff;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  max-width: 900px;
  margin: 0 auto;
}

.info-table-wrapper {
  overflow-x: auto;
}

.info-table {
  width: 100%;
  border-collapse: collapse;
}

.info-table thead {
  background: linear-gradient(135deg, #15803D 0%, #1E3A8A 100%);
  color: white;
}

.info-table th,
.info-table td {
  padding: 1rem;
  text-align: center;
  border-bottom: 1px solid #E8ECF3;
}

.info-table th {
  font-weight: 700;
  font-size: 0.95rem;
}

.info-table td {
  color: #475569;
  font-size: 0.95rem;
}

.info-table tbody tr {
  transition: background 0.2s;
}

.info-table tbody tr:hover {
  background: #f8fafc;
}

.no-data {
  text-align: center;
  padding: 3rem;
  color: #64748b;
  font-size: 1rem;
}

/* Responsive */
@media (max-width: 640px) {
  .student-info-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .info-card {
    padding: 1.5rem;
  }
  
  .info-table th,
  .info-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.85rem;
  }
}
</style>

<div class="student-info-section">
  <div class="page-header">
    <h1>শিক্ষার্থী তথ্য</h1>
    <p>শ্রেণি ও লিঙ্গভিত্তিক শিক্ষার্থী তথ্য</p>
  </div>
  
  <div class="info-card">
    <div class="info-table-wrapper">
      <table class="info-table">
        <thead>
          <tr>
            <th>শ্রেণি</th>
            <th>মোট শিক্ষার্থী</th>
            <th>ছাত্ৰ</th>
            <th>ছাত্ৰী</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($infos && $infos->num_rows > 0): while($row = $infos->fetch_assoc()): ?>
          <tr>
            <td class="fw-semibold" style="font-weight: 600; color: #1E3A8A;"><?php echo htmlspecialchars($row['class_name']); ?></td>
            <td><?php echo $row['total_students']; ?></td>
            <td><?php echo $row['male_students']; ?></td>
            <td><?php echo $row['female_students']; ?></td>
          </tr>
          <?php endwhile; else: ?>
          <tr>
            <td colspan="4" class="no-data">কোনো তথ্য পাওয়া যায়নি।</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>
