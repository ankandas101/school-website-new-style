<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// Fetch all events
$events = $conn->query('SELECT * FROM events ORDER BY event_date DESC, id DESC');

$page_title = 'ইভেন্ট/ব্লগ';
$page_desc = 'স্কুলের সকল ইভেন্ট ও ব্লগ';

include_once 'includes/header.php';
?>
<style>
.events-section {
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

.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.event-card {
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
  text-decoration: none;
  display: block;
}

.event-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(17, 136, 71, 0.15);
  border-color: #15803D;
}

.event-image {
  height: 200px;
  overflow: hidden;
}

.event-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.event-card:hover .event-image img {
  transform: scale(1.05);
}

.event-image-placeholder {
  height: 200px;
  background: linear-gradient(135deg, #f0f9ff 0%, #e8f5ee 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #15803D;
  font-size: 3rem;
}

.event-content {
  padding: 1.5rem;
}

.event-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1E3A8A;
  margin-bottom: 0.5rem;
}

.event-date {
  font-size: 0.85rem;
  color: #15803D;
  font-weight: 600;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.event-excerpt {
  color: #475569;
  font-size: 0.95rem;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.event-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: #15803D;
  font-weight: 700;
  font-size: 0.95rem;
  text-decoration: none;
  transition: color 0.2s ease;
}

.event-link:hover {
  color: #0d6b38;
}

.no-data {
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
  .events-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>

<div class="events-section">
  <div class="page-header">
    <h1>ইভেন্ট/ব্লগ</h1>
    <p>স্কুলের সর্বশেষ ইভেন্ট এবং আপডেট</p>
  </div>
  
  <?php if ($events && $events->num_rows > 0): ?>
    <div class="events-grid">
      <?php while($event = $events->fetch_assoc()): ?>
        <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="event-card">
          <?php if (!empty($event['image'])): ?>
            <div class="event-image">
              <img src="assets/images/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
            </div>
          <?php else: ?>
            <div class="event-image-placeholder">🎉</div>
          <?php endif; ?>
          <div class="event-content">
            <h2 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h2>
            <div class="event-date">
              <span>📅</span>
              <?php echo htmlspecialchars($event['event_date']); ?>
            </div>
            <p class="event-excerpt"><?php echo htmlspecialchars(mb_strimwidth(strip_tags($event['description']), 0, 100, '...')); ?></p>
            <div class="event-link">
              আরও পড়ুন →
            </div>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-data">
      <div style="font-size: 2rem; margin-bottom: 1rem;">📰</div>
      <p>কোনো ইভেন্ট পাওয়া যায়নি।</p>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
