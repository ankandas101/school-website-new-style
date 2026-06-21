<?php
include 'includes/db.php';

// Cloudflare Turnstile configuration - Get keys from database
$turnstile_site_key = '';
$turnstile_secret_key = '';
$turnstile_status = '0'; // Default to inactive

// Get Cloudflare Turnstile keys and status from site_settings table
$stmt = $conn->prepare("SELECT setting_name, setting_value FROM site_settings WHERE setting_name IN ('turnstile_site_key', 'turnstile_secret_key', 'CloudflareTurnstile_Status')");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  if ($row['setting_name'] === 'turnstile_site_key') {
    $turnstile_site_key = $row['setting_value'];
  } else if ($row['setting_name'] === 'turnstile_secret_key') {
    $turnstile_secret_key = $row['setting_value'];
  } else if ($row['setting_name'] === 'CloudflareTurnstile_Status') {
    $turnstile_status = $row['setting_value'];
  }
}
$stmt->close();

// Form submission handling
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $conn->real_escape_string($_POST['name'] ?? '');
  $email = $conn->real_escape_string($_POST['email'] ?? '');
  $phone = $conn->real_escape_string($_POST['phone'] ?? '');
  $message = $conn->real_escape_string($_POST['message'] ?? '');
  $captcha_verified = true; // Default to true if captcha is disabled
  
  // Only verify Cloudflare Turnstile if it's active
  if ($turnstile_status === '1') {
    $turnstile_response = $_POST['cf-turnstile-response'] ?? '';
    $captcha_verified = false; // Set to false until verified
    
    // Make POST request to Cloudflare Turnstile verification endpoint
    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $data = [
      'secret' => $turnstile_secret_key,
      'response' => $turnstile_response,
      'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    
    $options = [
      'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
      ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response_data = json_decode($result, true);
    
    $captcha_verified = isset($response_data['success']) && $response_data['success'] === true;
    
    if (!$captcha_verified) {
      $error = 'Captcha verification failed. Please try again.';
    }
  }
  
  if ($name && $email && $phone && $message && $captcha_verified) {
    $sql = "INSERT INTO contact_messages (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
    if ($conn->query($sql) === TRUE) {
      $success = "আপনার বার্তা সফলভাবে পাঠানো হয়েছে। ধন্যবাদ!";
    } else {
      $error = "ত্রুটি: " . $conn->error;
    }
  } else if (!$captcha_verified) {
    // Captcha error already set
  } else {
    $error = "সমস্ত ক্ষেত্র পূরণ করুন।";
  }
}

include 'includes/header.php';
?>
<!-- Cloudflare Turnstile Script -->
<?php if ($turnstile_status === '1'): ?>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<?php endif; ?>
<style>
.contact-section {
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

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 3rem;
}

.contact-form-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
}

.contact-form-card h2 {
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

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  font-weight: 600;
  color: #1E3A8A;
  margin-bottom: 0.5rem;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #E8ECF3;
  border-radius: 10px;
  font-size: 1rem;
  font-family: inherit;
  transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #15803D;
  box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.1);
}

.form-group textarea {
  resize: vertical;
  min-height: 150px;
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

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.info-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
}

.info-card-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.info-card-icon {
  font-size: 2rem;
}

.info-card h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1E3A8A;
  margin: 0;
}

.info-card p {
  color: #475569;
  margin: 0;
  line-height: 1.6;
}

.info-card a {
  color: #15803D;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}

.info-card a:hover {
  color: #0d6b38;
  text-decoration: underline;
}

.map-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  border: 1px solid #E8ECF3;
}

.map-card h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1E3A8A;
  margin-bottom: 1.5rem;
}

.map-container {
  border-radius: 12px;
  overflow: hidden;
  height: 400px;
}

/* Responsive */
@media (max-width: 900px) {
  .contact-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .contact-section {
    padding: 2rem 1rem;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .contact-form-card,
  .info-card,
  .map-card {
    padding: 1.5rem;
  }
  
  .map-container {
    height: 300px;
  }
}
</style>

<div class="contact-section">
  <div class="page-header">
    <h1>যোগাযোগ করুন</h1>
    <p>আমাদের সাথে যোগাযোগ করতে এই ফর্মটি ব্যবহার করুন বা সরাসরি আমাদের সাথে যোগাযোগ করুন</p>
  </div>
  
  <div class="contact-grid">
    <!-- Contact Form -->
    <div class="contact-form-card">
      <h2>বার্তা পাঠান</h2>
      
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
      
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
          <label>আপনার নাম</label>
          <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
          <label>ইমেইল</label>
          <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
          <label>ফোন নম্বর</label>
          <input type="tel" name="phone" required>
        </div>
        
        <div class="form-group">
          <label>বার্তা</label>
          <textarea name="message" rows="5" required></textarea>
        </div>
        
        <?php if ($turnstile_status === '1'): ?>
        <!-- Cloudflare Turnstile Widget -->
        <div class="form-group">
          <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars($turnstile_site_key); ?>"></div>
        </div>
        <?php endif; ?>
        
        <button type="submit" class="submit-btn">বার্তা পাঠান</button>
      </form>
    </div>
    
    <!-- Contact Info -->
    <div class="contact-info">
      <!-- Info Card 1 - Address -->
      <div class="info-card">
        <div class="info-card-header">
          <div class="info-card-icon">📍</div>
          <h3>ঠিকানা</h3>
        </div>
        <p><?php echo htmlspecialchars($school_info['address'] ?? 'তথ্য উপলব্ধ নয়'); ?></p>
      </div>
      
      <!-- Info Card 2 - Phone -->
      <div class="info-card">
        <div class="info-card-header">
          <div class="info-card-icon">📞</div>
          <h3>ফোন</h3>
        </div>
        <p><a href="tel:<?php echo htmlspecialchars($school_info['phone'] ?? ''); ?>"><?php echo htmlspecialchars($school_info['phone'] ?? 'তথ্য উপলব্ধ নয়'); ?></a></p>
      </div>
      
      <!-- Info Card 3 - Email -->
      <div class="info-card">
        <div class="info-card-header">
          <div class="info-card-icon">✉️</div>
          <h3>ইমেইল</h3>
        </div>
        <p><a href="mailto:<?php echo htmlspecialchars($school_info['email'] ?? ''); ?>"><?php echo htmlspecialchars($school_info['email'] ?? 'তথ্য উপলব্ধ নয়'); ?></a></p>
      </div>
      
      <!-- Map Section -->
      <?php if (!empty($school_info['google_map'])): ?>
        <div class="map-card">
          <h2>আমাদের অবস্থান</h2>
          <div class="map-container">
            <?php echo $school_info['google_map']; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>
