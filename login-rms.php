<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && $password === $admin['password']) {
        $_SESSION['admin'] = $admin;
        header("Location: admin_dashboard-rms.php");
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
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            position: relative; /* Position relative for logo positioning */
        }
        .container {
            background-color: #0f0f0f;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 90%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #fff;
        }
        h3 {
            
            color: black;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #555;
            border-radius: 4px;
            background-color: #222;
            color: white;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #003366;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #002244;
        }
        p {
            color: red;
            text-align: center;
            margin-top: 10px;
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
        @media (max-width: 500px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 1.5em;
            }
            .logo {
                width: 100px; /* Adjust width for smaller screens */
                height: 100px; /* Set height to match width for smaller screens */
            }
        }
        .content {
    position: absolute;
    top: 0;
    left: 0;
    width: 50%; /* Adjust width for the trapezium */
    height: 100%;
    clip-path: polygon(0 0, 72% 0, 31% 100%, 0% 100%);
    background: rgba(255, 255, 255, 0.7); /* Example of a light background */
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
    background: rgba(0, 0, 0, 0.9); /* Background for better text visibility */
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
    <img src="../stud/uploads/src.jpg" alt="Logo" class="logo"> <!-- Logo added here -->
    <div class="container">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
        
    </div>
</body>
</html>
