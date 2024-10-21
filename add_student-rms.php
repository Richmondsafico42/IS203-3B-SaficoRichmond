<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $student_password = $_POST['student_password'];
    $section = $_POST['section'];
    $year = $_POST['year'];
    $course = $_POST['course'];

    $stmt = $pdo->prepare("INSERT INTO student_acc (student_name, student_id, student_password, section, year, course) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$student_name, $student_id, $student_password, $section, $year, $course]);
    header("Location: view_students-rms.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #a19f9f;
            margin: 0;
            padding: 0;
            color: white;
        }
        .navbar {
            background-color: #000;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: 700;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #0f0f0f;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #fff;
        }
        input, select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 5px 0 15px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
        }
        input[type="text"],
        input[type="number"],
        input[type="password"] {
            width: calc(50% - 20px); /* Shorter width for textboxes */
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
        }
        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .logo {
            position: absolute;
            top: 9px; /* Adjust as needed */
            left: 20px; /* Adjust as needed */
            width: 120px; /* Increased width for a bigger logo */
            height: 120px; /* Set height to match width for circular shape */
            border-radius: 50%; /* Make the logo circular */
            object-fit: cover; /* Ensure the image covers the circle */
        }
    </style>
</head>
<body>
    <div class="navbar">
    <img src="../stud/uploads/src.jpg" alt="Logo" class="logo"> <!-- Logo added here -->
        <h1>Admin Dashboard</h1>
       
    </div>
    
    <div class="container">
        <h2>Add Student Account</h2>
        <form method="POST">
            <input type="text" name="student_name" placeholder="Student Name" required>
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="password" name="student_password" placeholder="Student Password" required>
            <input type="text" name="section" placeholder="Section">
            <input type="number" name="year" placeholder="Year">
            <input type="text" name="course" placeholder="Course"></br></br>
            <button type="submit">Add Student</button>
        </form>
        <a href="admin_dashboard-rms.php">Back to Dashboard</a>
    </div>
    
    <footer>
        &copy; <?= date("Y") ?> Your Organization. All rights reserved.
    </footer>
</body>
</html>
