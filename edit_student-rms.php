<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM student_acc WHERE id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_name = $_POST['student_name'];
        $student_id = $_POST['student_id'];
        $section = $_POST['section'];
        $year = $_POST['year'];
        $course = $_POST['course'];

        $stmt = $pdo->prepare("UPDATE student_acc SET student_name = ?, student_id = ?, section = ?, year = ?, course = ? WHERE id = ?");
        $stmt->execute([$student_name, $student_id, $section, $year, $course, $studentId]);
        
        header("Location: view_students-rms.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Account</title>
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
        label {
            display: block;
            text-align: left;
            margin-top: 10px;
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
        input[type="number"] {
            width: calc(50% - 20px); /* Shorter width for textboxes */
        }
        button {
            padding: 12.5px 15px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    
        .back-link {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 15px;
            background-color: #003366;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background-color: #002244;
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
    <img src="../stud/uploads/src.jpg" alt="Logo" class="logo">
        <h1>Admin Dashboard</h1>
       
    </div>
    
    <div class="container">
        <h2>Edit Student Account</h2>
        <form method="POST">
            <label for="student_name">Student Name:</label>
            <input type="text" name="student_name" id="student_name" value="<?= htmlspecialchars($student['student_name']) ?>" required>
            
            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" id="student_id" value="<?= htmlspecialchars($student['student_id']) ?>" required>
            
            <label for="section">Section:</label>
            <input type="text" name="section" id="section" value="<?= htmlspecialchars($student['section']) ?>">
            
            <label for="year">Year:</label>
            <input type="number" name="year" id="year" value="<?= htmlspecialchars($student['year']) ?>">
            
            <label for="course">Course:</label>
            <input type="text" name="course" id="course" value="<?= htmlspecialchars($student['course']) ?>">
            </br></br>
            <button type="submit">Update Student</button>
            
            <a class="back-link" href="view_students-rms.php">Back to Students</a>
        </form>
    </div>
    
    <footer>
        &copy; <?= date("Y") ?> Your Organization. All rights reserved.
    </footer>
</body>
</html>
