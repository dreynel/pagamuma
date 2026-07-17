<?php
// c:\xampp\htdocs\pagamuma\api\save_prescription.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/db.php';

$log_id = $_POST['log_id'] ?? null;
$prescription = $_POST['prescription'] ?? null;
$admin_status = $_POST['admin_status'] ?? 'Normal';

if (empty($log_id) || empty($prescription)) {
    echo json_encode(['success' => false, 'message' => 'Log ID and Prescription text are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE health_logs SET prescription = ?, admin_status = ? WHERE id = ?");
    $stmt->execute([$prescription, $admin_status, $log_id]);

    echo json_encode(['success' => true, 'message' => 'Prescription saved successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
