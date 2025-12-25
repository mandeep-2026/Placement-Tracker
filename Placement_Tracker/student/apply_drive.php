<?php
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_POST && isset($_POST['drive_id'])) {
    $drive_id = (int)$_POST['drive_id'];
    $user_id = $_SESSION['user_id'];
    
    // Check if already applied
    $check = $pdo->prepare("SELECT id FROM applications WHERE user_id = ? AND drive_id = ?");
    $check->execute([$user_id, $drive_id]);
    
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Already applied!']);
    } else {
        // Check if drive is open
        $drive = $pdo->prepare("SELECT status FROM drives WHERE id = ?");
        $drive->execute([$drive_id]);
        $drive = $drive->fetch();
        
        if ($drive && $drive['status'] === 'open') {
            $stmt = $pdo->prepare("INSERT INTO applications (user_id, drive_id) VALUES (?, ?)");
            if ($stmt->execute([$user_id, $drive_id])) {
                echo json_encode(['success' => true, 'message' => 'Applied successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Application failed!']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Drive is not open!']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
