<?php
// c:\xampp\htdocs\pagamuma\api\save_health_log.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$log_date = $_POST['log_date'] ?? date('Y-m-d');
$weight_kg = $_POST['weight_kg'] ?? null;
$blood_pressure = $_POST['blood_pressure'] ?? null;
$symptoms = $_POST['symptoms'] ?? null;

if (empty($log_date)) {
    echo json_encode(['success' => false, 'message' => 'Date is required.']);
    exit;
}

if ($weight_kg === '') $weight_kg = null;
if ($blood_pressure === '') $blood_pressure = null;
if ($symptoms === '') $symptoms = null;

try {
    $stmt = $pdo->prepare("INSERT INTO health_logs (user_id, log_date, weight_kg, blood_pressure, symptoms) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $log_date, $weight_kg, $blood_pressure, $symptoms]);

    echo json_encode(['success' => true, 'message' => 'Health log safely saved!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
