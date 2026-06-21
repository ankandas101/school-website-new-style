<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/classes.php';

// SEO meta for head teacher page
$page_title = 'প্রধান শিক্ষকের বাণী | ';
$page_desc = 'প্রধান শিক্ষকের বাণী, হবিগঞ্জ সরকারি উচ্চ বিদ্যালয়';

// Fetch head teacher message
$msg = new Message($conn);
$head = $msg->get('head_teacher');

include_once 'includes/header.php';
?>
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header bg-success text-white text-center fs-5">প্রধান শিক্ষকের বাণী</div>
        <div class="card-body text-center">
          <?php if (!empty($head['photo'])): ?>
            <img src="assets/images/<?php echo htmlspecialchars($head['photo']); ?>" class="rounded mb-3 w-50 d-block mx-auto" alt="প্রধান শিক্ষক">
          <?php endif; ?>
          <p class="fs-5"><?php echo nl2br(htmlspecialchars($head['message'] ?? '')); ?></p>
          <div class="fw-bold mt-3">- <?php echo htmlspecialchars($head['name'] ?? ''); ?></div>
          <?php if (!empty($head['phone']) || !empty($head['email'])): ?>
          <div class="mt-3">
            <?php if (!empty($head['phone'])): ?>
              <div><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($head['phone']); ?></div>
            <?php endif; ?>
            <?php if (!empty($head['email'])): ?>
              <div><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($head['email']); ?></div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-secondary">হোমে ফিরে যান</a>
      </div>
    </div>
  </div>
</div>
<?php include_once 'includes/footer.php'; ?> 