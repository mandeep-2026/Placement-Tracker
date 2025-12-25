<?php 
require_once '../config/db.php'; 
if (!isLoggedIn() || !isAdm1n()) exit('Unauthorized');

if ($_POST && isset($_POST['status'], $_POST['application_id'])) {
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    if ($stmt->execute([$_POST['status'], $_POST['application_id']])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
