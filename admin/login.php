<?php
// Set secure session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
require_once '../includes/db.php';
require_once '../includes/csrf.php';
require_once '../includes/security.php';

// Admin Auth class
define('ADMIN_SESSION', 'admin_logged_in');
class AdminAuth {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function login($username, $password) {
        $stmt = $this->conn->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION[ADMIN_SESSION] = $row['id'];
                return true;
            }
        }
        return false;
    }
    public function isLoggedIn() {
        return isset($_SESSION[ADMIN_SESSION]);
    }
    public function logout() {
        unset($_SESSION[ADMIN_SESSION]);
        session_destroy();
    }
}

$auth = new AdminAuth($conn);

// Cloudflare Turnstile configuration from environment variables
$turnstile_site_key = env('TURNSTILE_SITE_KEY', '');
$turnstile_secret_key = env('TURNSTILE_SECRET_KEY', '');
$turnstile_status = env('TURNSTILE_STATUS', '0');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['login_error'] = 'Invalid security token. Please try again.';
        header('Location: login.php');
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $rate_limit_key = 'login:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

    if (is_rate_limited($rate_limit_key, 5, 300)) {
        $_SESSION['login_error'] = 'Too many login attempts. Please try again in 5 minutes.';
        header('Location: login.php');
        exit;
    }

    $captcha_verified = true;
    
    if ($turnstile_status === '1') {
        $turnstile_response = $_POST['cf-turnstile-response'] ?? '';
        $captcha_verified = false;
        
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
    }
    
    if (!$captcha_verified) {
        $_SESSION['login_error'] = 'Captcha verification failed. Please try again.';
        header('Location: login.php');
        exit;
    }

    if ($auth->login($username, $password)) {
        reset_rate_limit($rate_limit_key);
        session_regenerate_id(true);
        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid username or password!';
        header('Location: login.php');
        exit;
    }
}

// Show error from session if set
$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
// Show timeout notice from query parameter
$timeoutNotice = '';
if (isset($_GET['timeout']) && $_GET['timeout'] == '1') {
    $timeoutNotice = 'আপনার সেশনটি নিষ্ক্রিয় থাকার কারণে শেষ হয়েছে। দয়া করে আবার লগইন করুন।';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - School Website Content Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Cloudflare Turnstile Script -->
    <?php if ($turnstile_status === '1'): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
</head>
<body class="bg-light" style="background: url('../assets/images/login-page-background.jpg') no-repeat center center fixed; background-size: cover;">
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4 mb-3" style="min-width: 350px; max-width: 100%;">
            <?php
            // Get school logo from database
            $stmt = $conn->prepare("SELECT logo FROM school_info LIMIT 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $school_info = $result->fetch_assoc();
            $logo_path = $school_info['logo'] ?? '../assets/images/logo.png'; // Fallback to default if not found
            ?>
            <img src="../assets/images/<?php echo htmlspecialchars($logo_path); ?>" alt="School Logo" class="img-fluid mx-auto d-block mb-3" style="max-width: 100px;">
            <h4 class="mb-3 text-center">Admin Login</h4>
            <?php if ($timeoutNotice): ?>
                <div class="alert alert-warning py-2"><?php echo htmlspecialchars($timeoutNotice); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                </div>
                <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <?php if ($turnstile_status === '1'): ?>
                    <!-- Cloudflare Turnstile Widget -->
                    <div class="mb-3">
                        <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars($turnstile_site_key); ?>"></div>
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
        <a href="/" class="btn btn-secondary">Go to Homepage</a>
        <div class="text-center mt-2 small" style="max-width: 400px; color: #fff;">
            কারিগরি সহায়তায়: <a href="https://ankandas.me" target="_blank" style="color: #fff; text-decoration: underline;">Khulna Devs</a><br>
            লগইন করতে সমস্যা হলে আমাদের সাথে যোগাযোগ করুন: 
            <a href="mailto:support@khulnadevs.com" style="color: #fff; text-decoration: underline;">support@khulnadevs.com</a> |
            <a href="https://wa.me/8801745009934" target="_blank" style="color: #fff; text-decoration: underline;">WhatsApp এ মেসেজ করুন</a>
        </div>
        
    </div>
</body>
</html>