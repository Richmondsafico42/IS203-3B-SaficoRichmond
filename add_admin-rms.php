<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Store as plain text
    $created_at = date('Y-m-d H:i:s'); // Current date and time

    $stmt = $pdo->prepare("INSERT INTO admins (username, password, created_at) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $created_at]);
    
    header("Location: admin_dashboard-rms.php");
    exit;
}
?>
