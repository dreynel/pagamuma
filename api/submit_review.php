<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'] ?? 0;
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid rating (1-5).']);
        exit;
    }
    if (empty($comment)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a comment.']);
        exit;
    }

    try {
        // Check if user already submitted a review
        $stmtCheck = $pdo->prepare("SELECT id FROM system_reviews WHERE user_id = ?");
        $stmtCheck->execute([$user_id]);
        $existing = $stmtCheck->fetchColumn();

        if ($existing) {
            // Update existing review and set back to pending
            $stmt = $pdo->prepare("UPDATE system_reviews SET rating = ?, comment = ?, status = 'pending', created_at = CURRENT_TIMESTAMP WHERE user_id = ?");
            $stmt->execute([$rating, $comment, $user_id]);
            echo json_encode(['success' => true, 'message' => 'Review updated successfully. Pending admin approval.']);
        } else {
            // Insert new review
            $stmt = $pdo->prepare("INSERT INTO system_reviews (user_id, rating, comment, status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $rating, $comment]);
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully. Pending admin approval.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
