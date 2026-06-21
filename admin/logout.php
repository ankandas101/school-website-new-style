<?php
// Set secure session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // Set to your domain if needed
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
define('ADMIN_SESSION', 'admin_logged_in');
class AdminAuth {
    public function logout() {
        unset($_SESSION[ADMIN_SESSION]);
        session_destroy();
    }
}
$auth = new AdminAuth();
$auth->logout();
// Clear session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
header('Location: login.php');
exit; 