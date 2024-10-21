<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $student_password = $_POST['student_password'];

    $stmt = $pdo->prepare("SELECT * FROM student_acc WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    // Compare plain text password
    if ($student && $student_password === $student['student_password']) {
        $_SESSION['student'] = $student;
        header("Location: student_dashboard-rms.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #333; /* Dark gray background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white; /* White text color */
        }
        .container {
            background-color: #0f0f0f; /* Dark background for the container */
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 90%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #fff; /* White heading color */
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #555; /* Darker border */
            border-radius: 4px;
            background-color: #222; /* Dark background for inputs */
            color: white; /* White text in inputs */
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007bff; /* Highlight border color */
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #003366; /* Dark blue background */
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #002244; /* Darker blue on hover */
        }
        p {
            color: red; /* Red error message color */
            text-align: center;
            margin-top: 10px;
        }
        @media (max-width: 500px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 1.5em;
            }
        }
        .logo {
            position: absolute;
            top: 20px; /* Adjust as needed */
            left: 20px; /* Adjust as needed */
            width: 120px; /* Increased width for a bigger logo */
            height: 120px; /* Set height to match width for circular shape */
            border-radius: 50%; /* Make the logo circular */
            object-fit: cover; /* Ensure the image covers the circle */
        }
        .content {
    position: absolute;
    top: 0;
    left: 0;
    width: 50%; /* Adjust width for the trapezium */
    height: 100%;
    clip-path: polygon(0 0, 72% 0, 31% 100%, 0% 100%);
    background: rgba(0, 0, 0, 0.7); /* Example of a light background */
 /* Background for better text visibility */
    color: white;
 }
 .content2 {
    position: absolute;
    top: 0;
    left: 0;
    width: 50%; /* Adjust width for the trapezium */
    height: 100%;
    clip-path: polygon(0 0, 5% 0, 72% 100%, 0% 100%);
   
    background: rgba(255, 255, 255, 0.7); /* Background for better text visibility */
    color: white;
 }
 .left-align {
    margin-left: 160px; 
    margin-top: 70px;/* Adjust the value as needed */
}
    </style>
</head>
<body>
<div class="content">
      
<h3 class="left-align">Santa Rita College</h3>
           </div>
           <div class="content2">
           </div>
    <img src="../stud/uploads/src.jpg" alt="Logo" class="logo">
    <div class="container">
        <h2>Student Login</h2>
        <form method="POST">
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="password" name="student_password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
