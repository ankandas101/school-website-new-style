<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/classes.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: 404.php');
    exit;
}

$teacher_id = intval($_GET['id']);

// ডাটাবেজ থেকে ডেটা আনা
$stmt = $conn->prepare("SELECT name, designation, photo, phone, email, bio FROM teachers WHERE id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: 404.php');
    exit;
}

$teacher = $result->fetch_assoc();

$page_title = htmlspecialchars($teacher['name']) . ' - শিক্ষক বিস্তারিত';
$page_desc = htmlspecialchars($teacher['designation']);

include_once 'includes/header.php';
?>

<style>
.teacher-detail-section {
  max-width: 1280px;
  margin: 0 auto;
  padding: 3rem 1rem;
}

.page-header {
  text-align: center;
  margin-bottom: 3rem;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: #15803D;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
  margin-bottom: 2rem;
  transition: color 0.2s;
}

.back-btn:hover {
  color: #0d6b38;
}

.teacher-detail-card {
  background: #fff;
  border-radius: 20px;
  padding: 2.5rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  max-width: 900px;
  margin: 0 auto;
}

.teacher-detail-content {
  display: grid;
  grid-template-columns: 250px 1fr;
  gap: 2.5rem;
}

.teacher-detail-image {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #ecfdf5;
  box-shadow: 0 4px 16px rgba(21, 128, 61, 0.18);
  display: block;
  margin: 0 auto;
}

.teacher-detail-info h1 {
  font-size: 1.75rem;
  font-weight: 800;
  color: #1E3A8A;
  margin-bottom: 0.5rem;
}

.teacher-detail-designation {
  font-size: 1rem;
  color: #15803D;
  font-weight: 700;
  margin-bottom: 1.5rem;
  display: block;
}

.teacher-detail-contact {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #E8ECF3;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.95rem;
  color: #475569;
}

.contact-item span {
  color: #15803D;
}

.contact-item a {
  color: #15803D;
  text-decoration: none;
  transition: color 0.2s;
}

.contact-item a:hover {
  color: #0d6b38;
}

.teacher-detail-bio {
  font-size: 1rem;
  color: #475569;
  line-height: 1.75;
}

/* Responsive */
@media (max-width: 768px) {
  .teacher-detail-content {
    grid-template-columns: 1fr;
    text-align: center;
  }
  
  .teacher-detail-contact {
    justify-content: center;
  }
  
  .teacher-detail-card {
    padding: 1.75rem;
  }
  
  .teacher-detail-image {
    width: 160px;
    height: 160px;
  }
}

@media (max-width: 640px) {
  .teacher-detail-section {
    padding: 2rem 1rem;
  }
}
</style>

<div class="teacher-detail-section">
  <a href="teachers.php" class="back-btn">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
    পেছনে যান
  </a>

  <div class="teacher-detail-card">
    <div class="teacher-detail-content">
      <div class="teacher-detail-image-wrapper">
        <?php if (!empty($teacher['photo'])): ?>
          <img src="assets/images/<?php echo htmlspecialchars($teacher['photo']); ?>" 
               class="teacher-detail-image" 
               alt="<?php echo htmlspecialchars($teacher['name']); ?>">
        <?php else: ?>
          <div class="teacher-detail-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #ecfdf5 0%, #dbeafe 100%);">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="1.5">
              <circle cx="12" cy="8" r="4"/>
              <path d="M20 21a8 8 0 1 0-16 0"/>
            </svg>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="teacher-detail-info">
        <h1><?php echo htmlspecialchars($teacher['name']); ?></h1>
        <span class="teacher-detail-designation"><?php echo htmlspecialchars($teacher['designation']); ?></span>
        
        <div class="teacher-detail-contact">
          <?php if (!empty($teacher['phone'])): ?>
            <div class="contact-item">
              <span>📞</span>
              <a href="tel:<?php echo htmlspecialchars($teacher['phone']); ?>"><?php echo htmlspecialchars($teacher['phone']); ?></a>
            </div>
          <?php endif; ?>
          
          <?php if (!empty($teacher['email'])): ?>
            <div class="contact-item">
              <span>✉️</span>
              <a href="mailto:<?php echo htmlspecialchars($teacher['email']); ?>"><?php echo htmlspecialchars($teacher['email']); ?></a>
            </div>
          <?php endif; ?>
        </div>
        
        <?php if (!empty($teacher['bio'])): ?>
          <div class="teacher-detail-bio">
            <?php echo nl2br(htmlspecialchars($teacher['bio'])); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>
<?php $conn->close(); ?>
