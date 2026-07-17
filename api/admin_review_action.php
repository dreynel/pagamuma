<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $review_id = $_POST['review_id'] ?? 0;

    if (!$review_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid review ID.']);
        exit;
    }

    try {
        if ($action === 'approve') {
            $stmt = $pdo->prepare("UPDATE system_reviews SET status = 'approved' WHERE id = ?");
            $stmt->execute([$review_id]);
            echo json_encode(['success' => true, 'message' => 'Review approved.']);
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM system_reviews WHERE id = ?");
            $stmt->execute([$review_id]);
            echo json_encode(['success' => true, 'message' => 'Review deleted.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
