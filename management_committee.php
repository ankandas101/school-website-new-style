<?php
require_once 'includes/db.php';
require_once 'includes/classes.php';

$committee = new ManagementCommittee($conn);
$members = $committee->getAll();

$messages = new Message($conn);
$chairman = $messages->get('chairman');

$page_title = 'ব্যবস্থাপনা কমিটি';
$page_desc = 'প্রতিষ্ঠানের ব্যবস্থাপনা কমিটি';

include_once 'includes/header.php';
?>

<style>
.management-section {
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

.chairman-card {
  background: #fff;
  border-radius: 20px;
  padding: 2.5rem;
  text-align: center;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  margin-bottom: 3rem;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid #E8ECF3;
}

.chairman-image {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  object-fit: cover;
  margin: 0 auto 1.5rem;
  display: block;
  border: 4px solid #ecfdf5;
  box-shadow: 0 4px 16px rgba(21, 128, 61, 0.18);
}

.chairman-name {
  font-size: 1.5rem;
  font-weight: 800;
  color: #1E3A8A;
  margin-bottom: 0.25rem;
}

.chairman-designation {
  font-size: 0.95rem;
  color: #15803D;
  font-weight: 700;
  margin-bottom: 1rem;
  letter-spacing: 0.3px;
}

.members-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.member-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  transition: all 0.3s ease;
}

.member-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(18, 59, 106, 0.1);
  border-color: #15803D;
}

.member-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin: 0 auto 1rem;
  display: block;
  border: 3px solid #E8ECF3;
  background: #ecfdf5;
}

.member-name {
  font-size: 1rem;
  font-weight: 700;
  color: #1E3A8A;
  margin-bottom: 0.35rem;
}

.member-designation {
  font-size: 0.82rem;
  color: #15803D;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.member-details {
  font-size: 0.78rem;
  color: #64748b;
}

.member-details p {
  margin-bottom: 0.25rem;
}

.no-members {
  text-align: center;
  padding: 3rem;
  background: #fff;
  border-radius: 20px;
  border: 1px dashed #E8ECF3;
  color: #64748b;
}

/* Responsive */
@media (max-width: 1024px) {
  .members-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .members-grid {
    grid-template-columns: 1fr;
  }
  .management-section {
    padding: 2rem 1rem;
  }
  .chairman-card {
    padding: 1.75rem;
  }
  .chairman-image {
    width: 120px;
    height: 120px;
  }
  .page-header h1 {
    font-size: 1.75rem;
  }
}
</style>

<div class="management-section">
  <div class="page-header">
    <h1>ব্যবস্থাপনা কমিটি</h1>
    <p>প্রতিষ্ঠানের ব্যবস্থাপনা কমিটির সদস্যদের তথ্য</p>
  </div>

  <?php if ($chairman): ?>
  <div class="chairman-card">
    <?php if (!empty($chairman['photo'])): ?>
      <img src="assets/images/<?php echo htmlspecialchars($chairman['photo']); ?>" class="chairman-image" alt="Chairman">
    <?php else: ?>
      <div class="chairman-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #ecfdf5 0%, #dbeafe 100%);">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="1.5">
          <circle cx="12" cy="8" r="4"/>
          <path d="M20 21a8 8 0 1 0-16 0"/>
        </svg>
      </div>
    <?php endif; ?>
    <div class="chairman-name">
      <?php echo htmlspecialchars($chairman['name'] ?? ''); ?>
    </div>
    <div class="chairman-designation">
      সভাপতি
    </div>
  </div>
  <?php endif; ?>

  <?php if ($members && $members->num_rows > 0): ?>
    <div class="members-grid">
      <?php while($row = $members->fetch_assoc()): ?>
      <div class="member-card">
        <?php if (!empty($row['image'])): ?>
          <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" class="member-image" alt="<?php echo htmlspecialchars($row['full_name']); ?>">
        <?php else: ?>
          <div class="member-image" style="display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="1.5">
              <circle cx="12" cy="8" r="4"/>
              <path d="M20 21a8 8 0 1 0-16 0"/>
            </svg>
          </div>
        <?php endif; ?>
        <div class="member-name">
          <?php echo htmlspecialchars($row['full_name']); ?>
        </div>
        <div class="member-designation">
          <?php echo htmlspecialchars($row['title']); ?>
        </div>
        <div class="member-details">
          <?php if (!empty($row['contact_number'])): ?>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>
          <?php endif; ?>
          <?php if (!empty($row['joining_date'])): ?>
            <p><strong>Joining Date:</strong> <?php echo htmlspecialchars($row['joining_date']); ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-members">
      No committee members found.
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
