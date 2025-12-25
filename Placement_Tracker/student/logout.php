<?php
// Full path: placement_tracker/student/logout.php
require_once '../config/db.php';

// Clear all session data completely
session_unset();
session_destroy();

// Clear remember me cookie if exists
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', true, true);
}

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Redirect to root index.php
header("Location: ../index.php?logged_out=1");
exit();
?>
