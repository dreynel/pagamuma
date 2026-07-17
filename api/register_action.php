<?php
// c:\xampp\htdocs\pagamuma2\api\register_action.php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Quick validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all mandatory fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "This email is already registered.";
        } else {
            // Hash password and insert
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $address = trim($_POST['address'] ?? '');
            if ($address === '') {
                $address = null;
            }

            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, address) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$firstName, $lastName, $email, $hash, $address])) {
                $_SESSION['success'] = "Registration successful! You can now sign in.";
                header("Location: ../register.php");
                exit;
            } else {
                $_SESSION['error'] = "An error occurred during registration. Please try again.";
            }
        }
    }
    
    header("Location: ../register.php");
    exit;
} else {
    header("Location: ../register.php");
    exit;
}
?>
