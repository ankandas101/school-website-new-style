<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// Fetch all active videos
$videos = $conn->query('SELECT * FROM gallery_videos WHERE status=1 ORDER BY id DESC');

$page_title = 'ভিডিও গ্যালারী';
$page_desc = 'স্কুলের ভিডিও গ্যালারী';

include_once 'includes/header.php';
?>
<style>
.video-section {
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

.videos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
}

.video-card {
  background: #fff;
  border-radius: 20px;
  padding: 1rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
}

.video-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(17, 136, 71, 0.15);
  border-color: #15803D;
}

.video-wrapper {
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 1rem;
  aspect-ratio: 16/9;
}

.video-wrapper iframe,
.video-wrapper video {
  width: 100%;
  height: 100%;
  border: none;
}

.video-caption {
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
  .video-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>

<div class="video-section">
  <div class="page-header">
    <h1>ভিডিও গ্যালারী</h1>
    <p>স্কুলের ভিডিও গ্যালারী</p>
  </div>
  
  <?php if ($videos && $videos->num_rows > 0): ?>
    <div class="videos-grid">
      <?php while($video = $videos->fetch_assoc()): ?>
        <div class="video-card">
          <div class="video-wrapper">
            <?php if (strpos($video['video_url'], 'youtube.com') !== false || strpos($video['video_url'], 'youtu.be') !== false): ?>
              <?php
                // Convert YouTube URL to embed format
                $youtube_url = $video['video_url'];
                $embed_url = '';
                if (preg_match('/youtu\.be\/([\w-]+)/', $youtube_url, $matches)) {
                  $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                } elseif (preg_match('/youtube\.com\/watch\?v=([\w-]+)/', $youtube_url, $matches)) {
                  $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                } else {
                  $embed_url = $youtube_url; // fallback
                }
              ?>
              <iframe src="<?php echo htmlspecialchars($embed_url); ?>" allowfullscreen></iframe>
            <?php else: ?>
              <video src="assets/videos/<?php echo htmlspecialchars($video['video_url']); ?>" controls></video>
            <?php endif; ?>
          </div>
          <?php if (!empty($video['caption'])): ?>
            <div class="video-caption"><?php echo htmlspecialchars($video['caption']); ?></div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-data">
      <div style="font-size: 2rem; margin-bottom: 1rem;">🎥</div>
      <p>কোনো ভিডিও পাওয়া যায়নি।</p>
    </div>
  <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>
