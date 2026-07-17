<?php
// c:\xampp\htdocs\pagamuma\api\delete_health_log.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$log_id = $_POST['log_id'] ?? null;

if (empty($log_id)) {
    echo json_encode(['success' => false, 'message' => 'Log ID is required.']);
    exit;
}

try {
    // Delete only if the log belongs to this user
    $stmt = $pdo->prepare("DELETE FROM health_logs WHERE id = ? AND user_id = ?");
    $stmt->execute([$log_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Health log deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Log not found or you do not have permission to delete it.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
