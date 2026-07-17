<?php
// c:\xampp\htdocs\pagamuma\api\get_chatbot_history.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../config/db.php';
$user_id = $_SESSION['user_id'];

// Get the latest session for the user
$stmt = $pdo->prepare("SELECT id FROM chat_sessions WHERE user_id = ? ORDER BY started_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$session_id = $stmt->fetchColumn();

if (!$session_id) {
    echo json_encode(['session_id' => null, 'messages' => []]);
    exit;
}

// Fetch messages
$stmt = $pdo->prepare("SELECT sender_type as sender, message_text as text, sent_at FROM chat_messages WHERE session_id = ? ORDER BY sent_at ASC");
$stmt->execute([$session_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'session_id' => $session_id,
    'messages' => $messages
]);
