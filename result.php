<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

$results = $conn->query('SELECT * FROM results ORDER BY id DESC');

$page_title = 'ফলাফল';
$page_desc = 'শ্রেণি ভিত্তিক ফলাফল (PDF/ছবি)';

include_once 'includes/header.php';
?>

<style>
.result-section {
  max-width: 1280px;
  margin: 0 auto;
  padding: 3rem 1rem;
}

.page-header {
  text-align: center;
  margin-bottom: 2rem;
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

.archive-link {
  text-align: center;
  margin-bottom: 2rem;
}

.archive-link a {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #15803D 0%, #1E3A8A 100%);
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  transition: transform 0.2s, box-shadow 0.2s;
  box-shadow: 0 4px 12px rgba(21, 128, 61, 0.2);
}

.archive-link a:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(21, 128, 61, 0.3);
}

.result-card {
  background: #fff;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  max-width: 900px;
  margin: 0 auto;
}

.result-table-wrapper {
  overflow-x: auto;
}

.result-table {
  width: 100%;
  border-collapse: collapse;
}

.result-table thead {
  background: linear-gradient(135deg, #15803D 0%, #1E3A8A 100%);
  color: white;
}

.result-table th,
.result-table td {
  padding: 1rem;
  text-align: center;
  border-bottom: 1px solid #E8ECF3;
}

.result-table th {
  font-weight: 700;
  font-size: 0.95rem;
}

.result-table td {
  color: #475569;
  font-size: 0.95rem;
}

.result-table tbody tr {
  transition: background 0.2s;
}

.result-table tbody tr:hover {
  background: #f8fafc;
}

.file-preview {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: #ecfdf5;
  border-radius: 12px;
  color: #15803D;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  transition: background 0.2s;
}

.file-preview:hover {
  background: #d1fae5;
}

.file-preview img {
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}

.no-data {
  text-align: center;
  padding: 3rem;
  color: #64748b;
  font-size: 1rem;
}

/* Responsive */
@media (max-width: 640px) {
  .result-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .result-card {
    padding: 1.5rem;
  }
  
  .result-table th,
  .result-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.85rem;
  }
}
</style>

<div class="result-section">
  <div class="page-header">
    <h1>ফলাফল</h1>
    <p>শ্রেণি ভিত্তিক ফলাফল (PDF/ছবি)</p>
  </div>
  
  <div class="archive-link">
    <a href="result_archives.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="21 15 21 21 15 21"></polyline>
      <path d="M17 10 A7 7 0 0 1 3 10"></path>
    </svg>
      বিগত বছরের পাবলিক পরিক্ষার ফলাফল দেখুন
    </a>
  </div>
  
  <div class="result-card">
    <div class="result-table-wrapper">
      <table class="result-table">
        <thead>
          <tr>
            <th>শ্রেণি</th>
            <th>ধরণ</th>
            <th>ফাইল</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($results && $results->num_rows > 0): while($row = $results->fetch_assoc()): ?>
          <tr>
            <td style="font-weight: 600; color: #1E3A8A;"><?php echo htmlspecialchars($row['class_name']); ?></td>
            <td><?php echo $row['file_type'] === 'pdf' ? 'PDF' : 'ছবি'; ?></td>
            <td>
              <?php if ($row['file_type'] === 'pdf'): ?>
                <a href="assets/results/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank" class="file-preview">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                  </svg>
                  PDF দেখুন
                </a>
              <?php else: ?>
                <a href="assets/results/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank">
                  <img src="assets/results/<?php echo htmlspecialchars($row['file_name']); ?>" alt="Result" style="height:60px;width:auto;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
                </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr>
            <td colspan="3" class="no-data">কোনো ফলাফল পাওয়া যায়নি।</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>
