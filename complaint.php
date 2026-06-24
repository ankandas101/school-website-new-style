<?php
include 'includes/db.php';
include 'includes/classes.php';

// Load Cloudflare Turnstile configuration from .env
$turnstile = Complaint::getTurnstileConfig();
$turnstile_site_key = $turnstile['site_key'];
$turnstile_secret_key = $turnstile['secret_key'];
$turnstile_status = $turnstile['status'];

// Form submission handling
$success = '';
$error = '';
$complaint_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captcha_verified = true;

    // Only verify Cloudflare Turnstile if it's active
    if ($turnstile_status === '1') {
        $turnstile_response = $_POST['cf-turnstile-response'] ?? '';
        $captcha_verified = Complaint::verifyTurnstile($turnstile_response, $turnstile_secret_key);

        if (!$captcha_verified) {
            $error = 'Captcha verification failed. Please try again.';
        }
    }

    if ($captcha_verified) {
        $complaint = new Complaint($conn);
        $attachment = '';

        // Handle file upload
        if (isset($_FILES['attachment'])) {
            try {
                $attachment = $complaint->uploadAttachment($_FILES['attachment']);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        if (empty($error)) {
            $data = [
                'student_name'     => $conn->real_escape_string($_POST['student_name'] ?? ''),
                'class_name'       => $conn->real_escape_string($_POST['class_name'] ?? ''),
                'roll_number'      => $conn->real_escape_string($_POST['roll_number'] ?? ''),
                'phone'            => $conn->real_escape_string($_POST['phone'] ?? ''),
                'complaint_type'   => $conn->real_escape_string($_POST['complaint_type'] ?? ''),
                'incident_date'    => $conn->real_escape_string($_POST['incident_date'] ?? ''),
                'complaint_details'=> $conn->real_escape_string($_POST['complaint_details'] ?? ''),
                'attachment'       => $attachment,
                'anonymous'        => isset($_POST['anonymous']) ? 1 : 0
            ];

            $result_id = $complaint->insert($data);
            if ($result_id) {
                $success = "অভিযোগ সফলভাবে জমা হয়েছে। আপনার Complaint ID: " . $result_id;
            } else {
                $error = "অভিযোগ জমা দিতে ব্যর্থ হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।";
            }
        }
    }
}

include 'includes/header.php';
?>
<!-- Cloudflare Turnstile Script -->
<?php if ($turnstile_status === '1'): ?>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<?php endif; ?>
<style>
.complaint-section {
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
  line-height: 1.6;
}

.complaint-grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 2rem;
  margin-bottom: 3rem;
}

.form-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
}

.form-card h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1E3A8A;
  margin-bottom: 1.5rem;
}

.success-message {
  padding: 1rem;
  background: #dcfce7;
  border: 1px solid #bbf7d0;
  border-radius: 12px;
  color: #166534;
  margin-bottom: 1.5rem;
}

.error-message {
  padding: 1rem;
  background: #fee2e2;
  border: 1px solid #fecaca;
  border-radius: 12px;
  color: #991b1b;
  margin-bottom: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  display: block;
  font-weight: 600;
  color: #1E3A8A;
  margin-bottom: 0.5rem;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #E8ECF3;
  border-radius: 10px;
  font-size: 1rem;
  font-family: inherit;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #15803D;
  box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.1);
}

.form-group textarea {
  resize: vertical;
  min-height: 150px;
}

.form-group input[type="file"] {
  padding: 0.5rem;
  border: 1px dashed #E8ECF3;
  background: #f8fafc;
  cursor: pointer;
}

.form-group input[type="checkbox"] {
  width: auto;
  margin-right: 0.5rem;
  accent-color: #15803D;
}

.checkbox-label {
  display: flex;
  align-items: center;
  font-weight: 500;
  color: #475569;
  cursor: pointer;
}

.submit-btn {
  width: 100%;
  padding: 0.875rem 1.5rem;
  background: linear-gradient(135deg, #15803D 0%, #0d6b38 100%);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(17, 136, 71, 0.3);
}

.side-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
  margin-bottom: 1.5rem;
}

.side-card:last-child {
  margin-bottom: 0;
}

.side-card h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1E3A8A;
  margin: 0 0 1rem 0;
}

.side-card .contact-person {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #E8ECF3;
}

.side-card .contact-person:last-child {
  margin-bottom: 0;
  padding-bottom: 0;
  border-bottom: none;
}

.contact-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: linear-gradient(135deg, #1E3A8A 0%, #3b5cb8 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.contact-details p {
  margin: 0;
  color: #475569;
  line-height: 1.5;
}

.contact-details .name {
  font-weight: 700;
  color: #1E3A8A;
}

.contact-details .phone {
  color: #15803D;
  font-weight: 600;
  text-decoration: none;
}

.contact-details .phone:hover {
  text-decoration: underline;
}

.privacy-note {
  color: #64748b;
  font-size: 0.9rem;
  line-height: 1.7;
}

.privacy-note strong {
  color: #1E3A8A;
}

/* Responsive */
@media (max-width: 900px) {
  .complaint-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .complaint-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
  }
  
  .form-card,
  .side-card {
    padding: 1.5rem;
  }
}
</style>

<div class="complaint-section">
  <div class="page-header">
    <h1>অভিযোগ ও সহায়তা কেন্দ্র</h1>
    <p>বুলিং, হয়রানি বা যেকোনো সমস্যার অভিযোগ জমা দিন। আপনার অভিযোগ সম্পূর্ণ গোপনীয়ভাবে সংরক্ষণ করা হবে এবং শুধুমাত্র অনুমোদিত কর্তৃপক্ষ এটি পর্যালোচনা করবেন।</p>
  </div>
  
  <div class="complaint-grid">
    <!-- Complaint Form -->
    <div class="form-card">
      <h2>অভিযোগ জমা দিন</h2>
      
      <?php if ($success): ?>
        <div class="success-message">
          ✓ <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="error-message">
          ✗ <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>
      
      <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-row">
          <div class="form-group">
            <label>শিক্ষার্থীর নাম *</label>
            <input type="text" name="student_name" required>
          </div>
          
          <div class="form-group">
            <label>শ্রেণি *</label>
            <input type="text" name="class_name" required>
          </div>
          
          <div class="form-group">
            <label>রোল নম্বর</label>
            <input type="text" name="roll_number">
          </div>
          
          <div class="form-group">
            <label>মোবাইল নম্বর *</label>
            <input type="tel" name="phone" placeholder="01xxxxxxxx" required>
          </div>
          
          <div class="form-group">
            <label>অভিযোগের ধরন *</label>
            <select name="complaint_type" required>
              <option value="">নির্বাচন করুন</option>
              <option>সাইবার বুলিং</option>
              <option>ইভটিজিং</option>
              <option>হয়রানি</option>
              <option>শারীরিক নির্যাতন</option>
              <option>মানসিক নির্যাতন</option>
              <option>শিক্ষক সংক্রান্ত</option>
              <option>অন্যান্য</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>ঘটনার তারিখ</label>
            <input type="date" name="incident_date">
          </div>
          
          <div class="form-group full-width">
            <label>প্রমাণ (ছবি বা PDF)</label>
            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
          </div>
        </div>
        
        <div class="form-group full-width">
          <label>অভিযোগের বিস্তারিত *</label>
          <textarea name="complaint_details" required></textarea>
        </div>
        
        <div class="form-group">
          <label class="checkbox-label">
            <input type="checkbox" name="anonymous" value="1">
            আমার পরিচয় গোপন রাখা হোক
          </label>
        </div>
        
        <?php if ($turnstile_status === '1'): ?>
        <!-- Cloudflare Turnstile Widget -->
        <div class="form-group">
          <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars($turnstile_site_key); ?>"></div>
        </div>
        <?php endif; ?>
        
        <button type="submit" class="submit-btn">অভিযোগ জমা দিন</button>
      </form>
    </div>
    
    <!-- Sidebar Info -->
    <div>
      <div class="side-card">
        <h3>অভিযোগ গ্রহণ কর্মকর্তা</h3>
        <div class="contact-person">
          <div class="contact-avatar">আ</div>
          <div class="contact-details">
            <p class="name">মোঃ আব্দুল করিম</p>
            <p>অভিযোগ গ্রহণ কর্মকর্তা</p>
            <a href="tel:017XXXXXXXX" class="phone">📞 017XXXXXXXX</a>
          </div>
        </div>
      </div>
      
      <div class="side-card">
        <h3>শিক্ষক প্রতিনিধি</h3>
        <div class="contact-person">
          <div class="contact-avatar">ফ</div>
          <div class="contact-details">
            <p class="name">ফাতেমা আক্তার</p>
            <p>শিক্ষক প্রতিনিধি</p>
            <a href="tel:018XXXXXXXX" class="phone">📞 018XXXXXXXX</a>
          </div>
        </div>
      </div>
      
      <div class="side-card">
        <h3>গোপনীয়তা নীতি</h3>
        <p class="privacy-note">
          <strong>আমার পরিচয় গোপন রাখা হোক</strong> নির্বাচন করলেও নাম ডাটাবেসে সংরক্ষিত থাকবে, তবে গোপন রাখা হবে।<br><br>
          আপনার অভিভোগ সম্পূর্ণ গোপনীয়ভাবে সংরক্ষণ করা হবে। শুধুমাত্র অনুমোদিত কর্তৃপক্ষ অভিযোগটি পর্যালোচনা করবেন।
        </p>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>