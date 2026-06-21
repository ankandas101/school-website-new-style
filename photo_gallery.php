<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// Fetch all active photos
$photos = $conn->query('SELECT * FROM gallery_photos WHERE status=1 ORDER BY id DESC');

$page_title = 'ফটো গ্যালারী';
$page_desc = 'স্কুলের ফটো গ্যালারী';

include_once 'includes/header.php';
?>
<style>
.gallery-section {
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

.photos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.photo-card {
  background: #fff;
  border-radius: 20px;
  padding: 1rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
  text-decoration: none;
  display: block;
}

.photo-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(17, 136, 71, 0.15);
  border-color: #15803D;
}

.photo-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 1rem;
}

.photo-caption {
  font-size: 0.95rem;
  color: #475569;
  text-align: center;
  font-weight: 500;
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
  .gallery-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>

<div class="gallery-section">
  <div class="page-header">
    <h1>ফটো গ্যালারী</h1>
    <p>স্কুলের ফটো গ্যালারী</p>
  </div>
  
  <?php if ($photos && $photos->num_rows > 0): ?>
    <div class="photos-grid">
      <?php while($photo = $photos->fetch_assoc()): ?>
        <a href="assets/images/<?php echo htmlspecialchars($photo['image']); ?>" target="_blank" class="photo-card">
          <img src="assets/images/<?php echo htmlspecialchars($photo['image']); ?>" class="photo-image" alt="<?php echo htmlspecialchars($photo['caption']); ?>">
          <?php if (!empty($photo['caption'])): ?>
            <div class="photo-caption"><?php echo htmlspecialchars($photo['caption']); ?></div>
          <?php endif; ?>
        </a>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-data">
      <div style="font-size: 2rem; margin-bottom: 1rem;">📷</div>
      <p>কোনো ছবি পাওয়া যায়নি।</p>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
