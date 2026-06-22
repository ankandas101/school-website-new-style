<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// Pagination setup
$per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$page = (int)$page;
$offset = ($page - 1) * $per_page;

// Fetch total count
$total_result = $conn->query('SELECT COUNT(*) as total FROM notices WHERE status=1');
$total_row = $total_result ? $total_result->fetch_assoc() : ['total' => 0];
$total_notices = (int)($total_row['total'] ?? 0);

// Fetch notices for this page
$sql = 'SELECT * FROM notices WHERE status=1 ORDER BY notice_date IS NULL, notice_date DESC, id DESC LIMIT ? OFFSET ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

$page_title = 'নোটিশ | বিদ্যালয়ের জন্য সর্বশেষ নোটিশ ও বিজ্ঞপ্তি';
$page_desc  = 'আমাদের বিদ্যালয়ের সর্বশেষ নোটিশ, বিজ্ঞপ্তি, ভর্তি সংক্রান্ত তথ্য, পরীক্ষার রুটিন, ফলাফল এবং অন্যান্য গুরুত্বপূর্ণ ঘোষণা একসাথে দেখুন।';

include_once 'includes/header.php';
?>
<style>
.notices-section {
  max-width: 900px;
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

.notices-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.notice-card {
  background: #fff;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  border-left: 4px solid #15803D;
  transition: all 0.3s ease;
}

.notice-card:hover {
  transform: translateX(8px);
  box-shadow: 0 8px 30px rgba(17, 136, 71, 0.12);
}

.notice-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1rem;
}

.notice-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1E3A8A;
  margin: 0;
}

.notice-title a {
  text-decoration: none;
  color: inherit;
}

.notice-date {
  padding: 0.5rem 1rem;
  background: #e8f5ee;
  color: #15803D;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  white-space: nowrap;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.notice-excerpt {
  color: #475569;
  line-height: 1.5;
  margin-bottom: 0.9rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.notice-footer {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
  align-items: center;
}

.notice-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, #15803D 0%, #0d6b38 100%);
  color: white;
  text-decoration: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.notice-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(17, 136, 71, 0.3);
}

.notice-attachment {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: #f0f9ff;
  color: #15803D;
  text-decoration: none;
  border: 1px solid #bfdbfe;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.95rem;
  transition: all 0.2s ease;
}

.notice-attachment:hover {
  background: #e8f5ee;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 3rem;
}

.pagination a,
.pagination span {
  padding: 0.75rem 1.5rem;
  border-radius: 10px;
  font-weight: 700;
  text-decoration: none;
}

.pagination a {
  color: #15803D;
  background: #f0f9ff;
  border: 1px solid #bfdbfe;
  transition: all 0.2s ease;
}

.pagination a:hover {
  background: linear-gradient(135deg, #15803D 0%, #0d6b38 100%);
  color: white;
  border-color: transparent;
}

.pagination span {
  color: #15803D;
  background: #e8f5ee;
}

.no-notices {
  text-align: center;
  padding: 3rem;
  background: #fff;
  border-radius: 20px;
  border: 1px dashed #E8ECF3;
  color: #64748b;
  font-size: 1rem;
}

/* Responsive */
@media (max-width: 640px) {
  .notices-section {
    padding: 1.5rem 0.75rem;
  }
  
  .page-header {
    margin-bottom: 1.5rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .notice-card {
    padding: 1rem;
    border-radius: 14px;
  }
  
  .notice-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.6rem;
    margin-bottom: 0.75rem;
  }
  
  .notice-title {
    font-size: 1rem;
    line-height: 1.4;
  }
  
  .notice-date {
    padding: 0.4rem 0.7rem;
    font-size: 0.78rem;
  }
  
  .notice-excerpt {
    font-size: 0.88rem;
    -webkit-line-clamp: 2;
    margin-bottom: 0.75rem;
  }
  
  .notice-footer {
    gap: 0.5rem;
  }
  
  .notice-link,
  .notice-attachment {
    padding: 0.6rem 0.9rem;
    font-size: 0.84rem;
  }
  
  .pagination {
    gap: 0.5rem;
    margin-top: 2rem;
  }
  
  .pagination a,
  .pagination span {
    padding: 0.55rem 0.9rem;
    font-size: 0.88rem;
  }
}
</style>

<div class="notices-section">
  <div class="page-header">
    <h1>নোটিশ বোর্ড</h1>
    <p>সর্বশেষ নোটিশ এবং ঘোষণা</p>
  </div>
  
  <?php if ($result && $result->num_rows > 0): ?>
    <div class="notices-list">
      <?php while($notice = $result->fetch_assoc()): ?>
        <div class="notice-card">
          <div class="notice-header">
            <h2 class="notice-title">
              <a href="notice.php?id=<?php echo $notice['id']; ?>"><?php echo htmlspecialchars($notice['title']); ?></a>
            </h2>
            <div class="notice-date">
              <span>📅</span>
              <?php echo htmlspecialchars($notice['notice_date']); ?>
            </div>
          </div>
          
          <p class="notice-excerpt"><?php echo htmlspecialchars(mb_substr(strip_tags($notice['description']), 0, 150)); ?><?php if (mb_strlen(strip_tags($notice['description'])) > 150) echo '...'; ?></p>
          
          <div class="notice-footer">
            <a href="notice.php?id=<?php echo (int)$notice['id']; ?>" class="section-link" style="font-size:0.78rem;">
              বিস্তারিত পড়ুন
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            
            <?php if (!empty($notice['attachment'])): ?>
              <a href="assets/notices/<?php echo htmlspecialchars($notice['attachment']); ?>" target="_blank" class="notice-attachment">
                📎 ফাইল
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    
    <?php if ($total_notices > (int)$page * (int)$per_page || (int)$page > 1): ?>
      <div class="pagination">
        <?php if ((int)$page > 1): ?>
          <a href="notices.php?page=<?php echo (int)$page - 1; ?>">← পূর্ববর্তী</a>
       
        <span>পৃষ্ঠা <?php echo (int)$page; ?></span>
        

        <?php if ((int)$total_notices > (int)$page * (int)$per_page): ?>
          <a href="notices.php?page=<?php echo (int)$page + 1; ?>">পরবর্তী →</a>
        <?php endif; ?>
       <?php endif; ?>  
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div class="no-notices">
      <div style="font-size: 2rem; margin-bottom: 1rem;">📌</div>
      <p>কোনো নোটিশ পাওয়া যায়নি।</p>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
