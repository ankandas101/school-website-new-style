<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$event = null;
if ($id > 0) {
  $result = $conn->prepare('SELECT * FROM events WHERE id=?');
  $result->bind_param('i', $id);
  $result->execute();
  $res = $result->get_result();
  $event = $res->fetch_assoc();
  $result->close();
}

$page_title = $event ? htmlspecialchars($event['title']) : 'ইভেন্ট';
$page_desc = $event ? mb_substr(strip_tags($event['description']), 0, 150) : 'ইভেন্ট বিস্তারিত';

include_once 'includes/header.php';
?>
<style>
.event-detail-section {
  max-width: 900px;
  margin: 0 auto;
  padding: 3rem 1rem;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: #15803D;
  font-weight: 700;
  text-decoration: none;
  font-size: 0.95rem;
  margin-bottom: 2rem;
  transition: color 0.2s ease;
}

.back-link:hover {
  color: #0d6b38;
}

.event-detail-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  overflow: hidden;
}

.event-detail-image {
  width: 100%;
  height: 320px;
  object-fit: cover;
}

.event-detail-placeholder {
  width: 100%;
  height: 320px;
  background: linear-gradient(135deg, #f0f9ff 0%, #e8f5ee 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #15803D;
  font-size: 4rem;
}

.event-detail-content {
  padding: 2.5rem;
}

.event-detail-header {
  margin-bottom: 2rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #E8ECF3;
}

.event-detail-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #1E3A8A;
  margin-bottom: 1rem;
}

.event-detail-date {
  font-size: 1rem;
  color: #15803D;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.event-detail-body {
  font-size: 1rem;
  color: #475569;
  line-height: 1.8;
}

.no-event {
  text-align: center;
  padding: 4rem 2rem;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
}

.no-event h2 {
  font-size: 1.5rem;
  color: #1E3A8A;
  margin-bottom: 1rem;
}

.no-event p {
  color: #64748b;
}

/* Responsive */
@media (max-width: 640px) {
  .event-detail-section {
    padding: 2rem 1rem;
  }
  
  .event-detail-content {
    padding: 1.5rem;
  }
  
  .event-detail-title {
    font-size: 1.4rem;
  }
  
  .event-detail-image,
  .event-detail-placeholder {
    height: 240px;
  }
}
</style>

<div class="event-detail-section">
  <?php if ($event): ?>
    <a href="event.php" class="back-link">
      <span>←</span>
      Back to Events
    </a>
    
    <div class="event-detail-card">
      <?php if (!empty($event['image'])): ?>
        <img src="assets/images/<?php echo htmlspecialchars($event['image']); ?>" class="event-detail-image" alt="<?php echo htmlspecialchars($event['title']); ?>">
      <?php else: ?>
        <div class="event-detail-placeholder">🎉</div>
      <?php endif; ?>
      
      <div class="event-detail-content">
        <div class="event-detail-header">
          <h1 class="event-detail-title"><?php echo htmlspecialchars($event['title']); ?></h1>
          <div class="event-detail-date">
            <span>📅</span>
            <?php echo htmlspecialchars($event['event_date']); ?>
          </div>
        </div>
        
        <div class="event-detail-body">
          <?php echo nl2br(htmlspecialchars($event['description'])); ?>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="no-event">
      <h2>Event Not Found</h2>
      <p>The event you're looking for doesn't exist.</p>
      <a href="event.php" class="back-link" style="margin-top: 1.5rem;">
        <span>←</span>
        Back to Events
      </a>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
