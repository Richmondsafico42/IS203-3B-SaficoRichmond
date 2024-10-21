<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM student_acc WHERE id = ?");
    $stmt->execute([$studentId]);
    
    header("Location: view_students-rms.php");
    exit;
}
?>
