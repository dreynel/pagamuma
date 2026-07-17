<?php
// Bulk update health_logs for a single mother using a tabular CSV.
// Expected CSV columns (header row required):
// Date, Weight, BP, Symptoms/Notes, Prescription
// - Must match the same order (case-insensitive) or at least contain these names.
// - Matching is done by (user_id + log_date + weight_kg + blood_pressure + symptoms) when possible.
// - If an exact match is not found, the row is skipped.

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/db.php';

$mother_id = $_POST['mother_id'] ?? null;
if (empty($mother_id)) {
    echo json_encode(['success' => false, 'message' => 'mother_id is required']);
    exit;
}

if (!isset($_FILES['csv_file']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
    echo json_encode(['success' => false, 'message' => 'csv_file is required']);
    exit;
}

$tmpPath = $_FILES['csv_file']['tmp_name'];
$originalName = $_FILES['csv_file']['name'] ?? '';

$handle = fopen($tmpPath, 'r');
if ($handle === false) {
    echo json_encode(['success' => false, 'message' => 'Unable to read uploaded CSV']);
    exit;
}

$header = fgetcsv($handle);
if ($header === false || empty($header)) {
    fclose($handle);
    echo json_encode(['success' => false, 'message' => 'CSV header row is missing']);
    exit;
}

// Map normalized header name -> index
$index = [];
foreach ($header as $i => $col) {
    $key = strtolower(trim((string)$col));
    $index[$key] = $i;
}

function pickIndex($index, array $candidates) {
    foreach ($candidates as $c) {
        $k = strtolower(trim($c));
        if (array_key_exists($k, $index)) return $index[$k];
    }
    return null;
}

$idxDate = pickIndex($index, ['date', 'log_date']);
$idxWeight = pickIndex($index, ['weight', 'weight_kg', 'weight (kg)']);
$idxBp = pickIndex($index, ['bp', 'blood pressure', 'blood_pressure']);
$idxSymptoms = pickIndex($index, ['symptoms/notes', 'symptoms', 'notes', 'symptoms_notes']);
$idxPrescription = pickIndex($index, ['prescription', 'rx', "doctor's prescription"]);

// If the CSV header uses exact names from your UI (Date, Weight, BP, Symptoms/Notes, Prescription)
// but with different casing, above logic already handles case-insensitive matching.


if ($idxDate === null || $idxWeight === null || $idxBp === null || $idxSymptoms === null || $idxPrescription === null) {
    fclose($handle);
    echo json_encode(['success' => false, 'message' => 'CSV columns must include: Date, Weight, BP, Symptoms/Notes, Prescription']);
    exit;
}

$updated = 0;
$skipped = 0;
$rowNum = 1; // includes header

while (($row = fgetcsv($handle)) !== false) {
    $rowNum++;

    // Ensure row has enough columns
    $maxNeeded = max($idxDate, $idxWeight, $idxBp, $idxSymptoms, $idxPrescription);
    if (!is_array($row) || count($row) <= $maxNeeded) {
        $skipped++;
        continue;
    }

    $dateRaw = trim((string)($row[$idxDate] ?? ''));
    $weightRaw = trim((string)($row[$idxWeight] ?? ''));
    $bpRaw = trim((string)($row[$idxBp] ?? ''));
    $symptomsRaw = trim((string)($row[$idxSymptoms] ?? ''));
    $prescriptionRaw = trim((string)($row[$idxPrescription] ?? ''));

    if ($prescriptionRaw === '') {
        // Nothing to update for this row
        $skipped++;
        continue;
    }

    // Normalize numeric + strings
    $weight = ($weightRaw === '') ? null : floatval(str_replace(',', '.', $weightRaw));
    $bp = ($bpRaw === '') ? null : $bpRaw;
    $symptoms = ($symptomsRaw === '') ? null : $symptomsRaw;

    // Try to find matching log row.
    // health_logs fields: id, user_id, log_date, weight_kg, blood_pressure, symptoms, prescription, admin_status, created_at
    // We attempt match using log_date + weight + blood_pressure + symptoms when all present.

    $sqlBase = "SELECT id FROM health_logs WHERE user_id = ? AND log_date = ?";
    $params = [$mother_id, normalize_date_for_db($dateRaw)];

    // If any of these are missing, we loosen matching to avoid skipping too much.
    $hasWeight = $weight !== null;
    $hasBp = $bp !== null;
    $hasSymptoms = $symptoms !== null;

    if ($hasWeight) { $sqlBase .= " AND weight_kg = ?"; $params[] = $weight; }
    if ($hasBp) { $sqlBase .= " AND blood_pressure = ?"; $params[] = $bp; }
    if ($hasSymptoms) { $sqlBase .= " AND symptoms = ?"; $params[] = $symptoms; }

    $stmt = $pdo->prepare($sqlBase . " LIMIT 1");
    $stmt->execute($params);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$match || empty($match['id'])) {
        $skipped++;
        continue;
    }

    $log_id = $match['id'];
    $admin_status = 'Normal'; // per your tabular: Prescription only; status can be set optionally later.

    $up = $pdo->prepare("UPDATE health_logs SET prescription = ?, admin_status = ? WHERE id = ? AND user_id = ?");
    $up->execute([$prescriptionRaw, $admin_status, $log_id, $mother_id]);

    $updated++;
}

fclose($handle);

echo json_encode([
    'success' => true,
    'message' => 'Import completed.',
    'file' => $originalName,
    'updated_rows' => $updated,
    'skipped_rows' => $skipped
]);

function normalize_date_for_db($dateRaw) {
    // Accept formats like: 2025-01-31, 31/01/2025, Jan 31, 2025, etc.
    // Fallback to raw.
    $dateRaw = trim((string)$dateRaw);
    if ($dateRaw === '') return '';

    // Try common Y-m-d
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateRaw)) return $dateRaw;

    $ts = strtotime($dateRaw);
    if ($ts === false) return $dateRaw;
    return date('Y-m-d', $ts);
}

