<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

$archives = $conn->query('SELECT * FROM result_archives ORDER BY exam_year DESC, id DESC');

$page_title = 'ফলাফল আর্কাইভ';
$page_desc = 'পূর্ববর্তী বছরের পাবলিক পরিক্ষার ফলাফল আর্কাইভ';

include_once 'includes/header.php';
?>

<style>
.archive-section {
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

.archive-card {
  background: #fff;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  max-width: 1000px;
  margin: 0 auto;
}

.archive-table-wrapper {
  overflow-x: auto;
}

.archive-table {
  width: 100%;
  border-collapse: collapse;
}

.archive-table thead {
  background: linear-gradient(135deg, #15803D 0%, #1E3A8A 100%);
  color: white;
}

.archive-table th,
.archive-table td {
  padding: 1rem;
  text-align: center;
  border-bottom: 1px solid #E8ECF3;
}

.archive-table th {
  font-weight: 700;
  font-size: 0.95rem;
}

.archive-table td {
  color: #475569;
  font-size: 0.95rem;
}

.archive-table tbody tr {
  transition: background 0.2s;
}

.archive-table tbody tr:hover {
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
  .archive-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .archive-card {
    padding: 1.5rem;
  }
  
  .archive-table th,
  .archive-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.85rem;
  }
}
</style>

<div class="archive-section">
  <div class="page-header">
    <h1>বিগত বছরের পাবলিক পরিক্ষার ফলাফল</h1>
    <p>পূর্ববর্তী বছরের পাবলিক পরিক্ষার ফলাফল আর্কাইভ</p>
  </div>
  
  <div class="archive-card">
    <div class="archive-table-wrapper">
      <table class="archive-table">
        <thead>
          <tr>
            <th>পরীক্ষার নাম</th>
            <th>বছর</th>
            <th>মোট শিক্ষার্থী</th>
            <th>মোট পাস</th>
            <th>পাস রেট (%)</th>
            <th>জিপিএ ৫</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($archives && $archives->num_rows > 0): while($row = $archives->fetch_assoc()): ?>
          <tr>
            <td style="font-weight: 600; color: #1E3A8A;"><?php echo htmlspecialchars($row['exam_name']); ?></td>
            <td><?php echo htmlspecialchars($row['exam_year']); ?></td>
            <td><?php echo htmlspecialchars($row['total_students']); ?></td>
            <td><?php echo htmlspecialchars($row['total_pass']); ?></td>
            <td><?php echo number_format($row['pass_rate'], 2); ?>%</td>
            <td><?php echo isset($row['total_gpa5']) && $row['total_gpa5'] !== null ? htmlspecialchars($row['total_gpa5']) : '-'; ?></td>
          </tr>
          <?php endwhile; else: ?>
          <tr>
            <td colspan="6" class="no-data">কোনো ফলাফল আর্কাইভ পাওয়া যায়নি।</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>
