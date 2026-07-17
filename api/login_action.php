<?php
// c:\xampp\htdocs\pagamuma2\api\login_action.php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
        header("Location: ../login.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, password_hash, first_name, last_name, role, profile_picture FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Successful login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['profile_picture'] = $user['profile_picture'];
        
        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: ../admin/index.php");
            exit;
        } else {
            header("Location: ../mothers/index.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
