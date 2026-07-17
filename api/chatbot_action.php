<?php
// c:\xampp\htdocs\pagamuma\api\chatbot_action.php
// PAG-AMUMA Private Chatbot API (Logged-in mothers)
session_start();

// --- Config ---


define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY);

// --- Headers ---
header('Content-Type: application/json');

// --- Require Login ---
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// --- Only accept POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// --- Parse JSON input ---
$input        = json_decode(file_get_contents('php://input'), true);
$user_message = trim($input['message'] ?? '');
$session_id   = $input['session_id'] ?? null;

if (empty($user_message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message cannot be empty.']);
    exit;
}

require_once '../config/db.php';
$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'] ?? 'Nanay';

// Validate or Create Session
if ($session_id) {
    $stmt = $pdo->prepare("SELECT id FROM chat_sessions WHERE id = ? AND user_id = ?");
    $stmt->execute([$session_id, $user_id]);
    if (!$stmt->fetchColumn()) {
        $session_id = null; // Invalid session
    }
}

if (!$session_id) {
    $stmt = $pdo->prepare("INSERT INTO chat_sessions (user_id) VALUES (?)");
    $stmt->execute([$user_id]);
    $session_id = $pdo->lastInsertId();
}

// Fetch DB History
$stmt = $pdo->prepare("SELECT sender_type, message_text FROM chat_messages WHERE session_id = ? ORDER BY sent_at ASC");
$stmt->execute([$session_id]);
$db_history = $stmt->fetchAll();

$contents = [];
foreach ($db_history as $msg) {
    if ($msg['sender_type'] === 'user') {
        $contents[] = ['role' => 'user', 'parts' => [['text' => $msg['message_text']]]];
    } else {
        $contents[] = ['role' => 'model', 'parts' => [['text' => $msg['message_text']]]];
    }
}

// Add newest message
$contents[] = [
    'role' => 'user',
    'parts' => [['text' => $user_message]]
];

$system_instruction = "You are PAG-AMUMA, a warm, caring, and knowledgeable maternal health assistant designed for Filipino mothers.
You are currently talking to a registered mother named " . $first_name . ".
Your purpose is to provide accurate, compassionate, and practical information about pregnancy, prenatal care, fetal development, maternal nutrition, emotional wellness during pregnancy, labor, and postpartum care.

Key guidelines:
- Address the user warmly as '" . $first_name . "'.
- You ONLY answer questions related to pregnancy, maternal health, prenatal/postnatal care, and related parenting topics for newborns. For any off-topic questions, gently redirect the user back to pregnancy topics.
- You can respond fluently in English, Filipino (Tagalog), and Hiligaynon — match the language the user writes in.
- For serious medical concerns, ALWAYS advise them to seek immediate medical attention.
- Keep your answers concise yet complete. Use bullet points where helpful.
- CRITICAL: Do NOT provide diagnoses or prescribe medication under ANY circumstances. You are an informational assistant only. ONLY the RHU Admin can give prescriptions. If they ask for a prescription or medical diagnosis, firmly state that they must consult the RHU or their doctor.";

// --- Build Gemini API Request ---
$payload = json_encode([
    'system_instruction' => [
        'parts' => [['text' => $system_instruction]]
    ],
    'contents' => $contents,
    'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 1024,
        'topP' => 0.9,
    ],
    'safetySettings' => [
        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
    ]
]);

// --- Call Gemini API ---
$ch = curl_init(GEMINI_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to reach AI service: ' . $curl_error]);
    exit;
}

$result = json_decode($response, true);
$bot_reply = null;

if ($http_status === 200 && isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $bot_reply = trim($result['candidates'][0]['content']['parts'][0]['text']);
} elseif (isset($result['error']['message'])) {
    $bot_reply = "I'm sorry, I encountered an issue: " . $result['error']['message'];
}

// Log both to database if no absolute catastrophe
if ($bot_reply && $http_status === 200) {
    $stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, sender_type, message_text) VALUES (?, ?, ?)");
    $stmt->execute([$session_id, 'user', $user_message]);
    $stmt->execute([$session_id, 'bot', $bot_reply]);
} elseif (!$bot_reply) {
    $bot_reply = "I'm sorry, I couldn't process that request. Please try again.";
}

echo json_encode([
    'reply' => $bot_reply,
    'session_id' => $session_id
]);
