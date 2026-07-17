<?php
// c:\xampp\htdocs\pagamuma\api\save_profile.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$expected_due_date = $_POST['expected_due_date'] ?? null;
$pregnancy_start_date = $_POST['pregnancy_start_date'] ?? null;
$blood_type = $_POST['blood_type'] ?? null;
$medical_history = $_POST['medical_history'] ?? null;
$address = $_POST['address'] ?? null;

if (empty($address)) {
    $address = null;
}



// If this request only updates address, allow it without requiring EDD.
$has_edd = !empty($expected_due_date);

if ($has_edd) {
    // Convert empty strings to null for date fields
    if (empty($pregnancy_start_date)) {
        $pregnancy_start_date = null;
    }
}

if (!$has_edd && (empty($address) || $address === null)) {
    echo json_encode(['success' => false, 'message' => 'No data provided to update.']);
    exit;
}


$profile_filename = null;
// Handle File Upload
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/profile_pictures/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($file_ext, $allowed_exts)) {
        $profile_filename = $user_id . '_' . time() . '.' . $file_ext;
        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $profile_filename)) {
            $profile_filename = null;
        }
    }
}

try {
    // Check if profile exists
    $stmt = $pdo->prepare("SELECT id FROM pregnancy_profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Update
        $stmt = $pdo->prepare("UPDATE pregnancy_profiles SET expected_due_date = ?, pregnancy_start_date = ?, blood_type = ?, medical_history = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
        $stmt->execute([$expected_due_date, $pregnancy_start_date, $blood_type, $medical_history, $user_id]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO pregnancy_profiles (user_id, expected_due_date, pregnancy_start_date, blood_type, medical_history) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $expected_due_date, $pregnancy_start_date, $blood_type, $medical_history]);
    }

    // Update users table fields (address)
    $stmt = $pdo->prepare("UPDATE users SET address = ? WHERE id = ?");
    $stmt->execute([$address, $user_id]);

    if ($profile_filename) {
        $user_update = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $user_update->execute([$profile_filename, $user_id]);
    }

    echo json_encode(['success' => true, 'message' => 'Pregnancy profile updated successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
