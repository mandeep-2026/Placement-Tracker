<?php
// CONFIGURATION
$host = 'localhost';
$dbname = 'placement_tracker';
$db_user = 'root';
$db_pass = ''; // Change in production!

// START SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SECURITY HEADERS
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// DATABASE CONNECTION
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log("DB Connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// CORE FUNCTIONS
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdm1n() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}

function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function redirect($url, $status = 302) {
    header("Location: $url", true, $status);
    exit();
}

// REMEMBER ME SUPPORT
function checkRememberMe($pdo) {
    if (isset($_COOKIE['remember_token']) && !isLoggedIn()) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ?");
        $stmt->execute([$_COOKIE['remember_token']]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

// SECURITY FUNCTION
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// DEBUG MODE (REMOVE IN PRODUCTION)
if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "Session: " . print_r($_SESSION, true) . "\n";
    echo "isLoggedIn: " . (isLoggedIn() ? 'YES' : 'NO') . "\n";
    echo "isAdm1n: " . (isAdm1n() ? 'YES' : 'NO') . "\n";
    echo "</pre>";
    exit;
}
?>
