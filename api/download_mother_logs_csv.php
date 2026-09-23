<?php
// c:\xampp\htdocs\pagamuma\api\download_mother_logs_csv.php
// Clean, direct CSV download endpoint for health vitals logs (for admin and mothers)

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../config/db.php';

$user_role = $_SESSION['role'] ?? 'mother';
$user_id = $_SESSION['user_id'];

// Determine target mother ID
if ($user_role === 'admin') {
    $target_mother_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
} else {
    // Mothers can only download their own logs
    $target_mother_id = $user_id;
}

// Fetch mother's name for clean filename
$mother_name = "Mother";
if ($target_mother_id) {
    $u_stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
    $u_stmt->execute([$target_mother_id]);
    $u = $u_stmt->fetch(PDO::FETCH_ASSOC);
    if ($u) {
        $mother_name = trim($u['first_name'] . '_' . $u['last_name']);
        $mother_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $mother_name);
    }
}

// Fetch logs
if ($target_mother_id) {
    $stmt = $pdo->prepare("SELECT * FROM health_logs WHERE user_id = ? ORDER BY log_date DESC, created_at DESC");
    $stmt->execute([$target_mother_id]);
    $filename = "health_logs_{$mother_name}_" . date('Y-m-d') . ".csv";
} else {
    // Admin downloading all logs
    $stmt = $pdo->query("
        SELECT l.*, u.first_name, u.last_name, u.email 
        FROM health_logs l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.log_date DESC, l.created_at DESC
    ");
    $filename = "all_mothers_health_logs_" . date('Y-m-d') . ".csv";
}

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Clear any output buffer to ensure pure CSV stream
while (ob_get_level() > 0) {
    ob_end_clean();
}

// Send standard CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$out = fopen('php://output', 'w');

// UTF-8 BOM so Excel on Windows properly displays accents and characters
fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

// Header row compatible with UI and bulk CSV import
// Date, Weight, BP, Symptoms/Notes, Prescription
fputcsv($out, ['Date', 'Weight', 'BP', 'Symptoms/Notes', 'Prescription']);

foreach ($logs as $log) {
    $date = $log['log_date'] ?? '';
    $weight = $log['weight_kg'] ?? '';
    $bp = $log['blood_pressure'] ?? '';

    $symptoms = $log['symptoms'] ?? '';
    $symptoms = strip_tags((string)$symptoms);
    $symptoms = str_replace(["\r\n", "\r", "\n"], ' ', $symptoms);

    $prescription = $log['prescription'] ?? '';
    $prescription = strip_tags((string)$prescription);
    $prescription = str_replace(["\r\n", "\r", "\n"], ' ', $prescription);

    fputcsv($out, [$date, $weight, $bp, $symptoms, $prescription]);
}

fclose($out);
exit;
