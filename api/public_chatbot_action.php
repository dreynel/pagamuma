<?php
// c:\xampp\htdocs\pagamuma\api\public_chatbot_action.php
// PAG-AMUMA Public Chatbot API - No login required (guest mode)

// --- Config ---

define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY);

// --- Headers ---
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// --- Only accept POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// --- Parse JSON input ---
$input = json_decode(file_get_contents('php://input'), true);
$user_message = trim($input['message'] ?? '');
$history = $input['history'] ?? []; // Client-side history for context

if (empty($user_message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message cannot be empty.']);
    exit;
}

// --- Build conversation contents from client-side history ---
$contents = [];
foreach ($history as $item) {
    if (!in_array($item['role'] ?? '', ['user', 'model']))
        continue;
    $contents[] = [
        'role' => $item['role'],
        'parts' => [['text' => $item['text']]]
    ];
}
// Append the current message
$contents[] = [
    'role' => 'user',
    'parts' => [['text' => $user_message]]
];

// --- System Instruction / Persona ---
$system_instruction = "You are PAG-AMUMA, a warm, caring, and knowledgeable maternal health assistant designed for Filipino mothers.
Your purpose is to provide accurate, compassionate, and practical information about pregnancy, prenatal care, fetal development, maternal nutrition, emotional wellness during pregnancy, labor, and postpartum care.

Key guidelines:
- You ONLY answer questions related to pregnancy, maternal health, prenatal/postnatal care, and related parenting topics for newborns. For any off-topic questions, kindly redirect the user back to pregnancy-related topics.
- You can respond fluently in English, Filipino (Tagalog), and Hiligaynon — match the language the user writes in.
- Always be warm, encouraging, and supportive in tone. Treat every user like a first-time expecting mother.
- For serious medical concerns (bleeding, severe pain, high fever, etc.), ALWAYS advise the user to seek immediate medical attention and contact their healthcare provider or go to the nearest hospital.
- Keep your answers concise yet complete. Use bullet points or numbered lists where helpful.
- CRITICAL: Do NOT provide diagnoses or prescribe medication under ANY circumstances. You are an informational assistant, not a doctor. ONLY the RHU Admin can give prescriptions. If they ask for a prescription or medical diagnosis, firmly state that they must consult the RHU or their doctor.
- You are speaking to a guest/public user who may not yet have an account. Occasionally and naturally, you may encourage them to register at PAG-AMUMA for a full dashboard experience with personalized tracking.";

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

// --- Call Gemini REST API via cURL ---
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

// --- Extract bot reply ---
if ($http_status === 200 && isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $bot_reply = trim($result['candidates'][0]['content']['parts'][0]['text']);
}
elseif (isset($result['error']['message'])) {
    $bot_reply = "I'm sorry, I encountered an issue: " . $result['error']['message'];
}
else {
    $bot_reply = "I'm sorry, I couldn't process that request. Please try again.";
}

echo json_encode(['reply' => $bot_reply]);
