<?php
// Admin session guard: require login and enforce inactivity timeout
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!defined('ADMIN_SESSION')) {
    define('ADMIN_SESSION', 'admin_logged_in');
}

// Require login
if (!isset($_SESSION[ADMIN_SESSION])) {
    header('Location: login.php');
    exit;
}

// Inactivity timeout in seconds (30 minutes)
$adminInactivityTimeoutSeconds = 30 * 60;
$now = time();

if (isset($_SESSION['last_activity']) && ($now - (int)$_SESSION['last_activity'] > $adminInactivityTimeoutSeconds)) {
    // Session expired due to inactivity: destroy session and clear cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    $_SESSION = [];
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}

// Update last activity timestamp
$_SESSION['last_activity'] = $now;