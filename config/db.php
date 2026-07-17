<?php
// c:\xampp\htdocs\pagamuma2\config\db.php

$host = 'localhost';
$dbname = 'pagamuma_db';
$username = 'root'; // Default XAMPP username
$password = '007622'; // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch objects as associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    die("Database connection failed. Please ensure MySQL is running in XAMPP: " . $e->getMessage());
}
?>
