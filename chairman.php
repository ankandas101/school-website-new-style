<?php
require_once 'includes/db.php';
require_once 'includes/classes.php';

$msg = new Message($conn);
$chairman = $msg->get('chairman');

$page_title = 'সভাপতির বাণী';
$page_desc = 'সভাপতির বাণী';

include_once 'includes/header.php';
?>
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white text-center fs-5">সভাপতির বাণী</div>
        <div class="card-body text-center">
          <?php if (!empty($chairman['photo'])): ?>
            <img src="assets/images/<?php echo htmlspecialchars($chairman['photo']); ?>" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;" alt="সভাপতি">
          <?php endif; ?>
          <?php if (!empty($chairman['name'])): ?>
            <div class="fw-bold mb-2" style="font-size:1.2rem;">- <?php echo htmlspecialchars($chairman['name']); ?></div>
          <?php endif; ?>
          <?php if (!empty($chairman['title'])): ?>
            <div class="mb-3 text-muted">(<?php echo htmlspecialchars($chairman['title']); ?>)</div>
          <?php endif; ?>
          <div class="text-start mx-auto" style="max-width:600px;">
            <?php echo nl2br(htmlspecialchars($chairman['message'] ?? '')); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once 'includes/footer.php'; ?>