<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            padding: 15px;
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
            max-width: 800px;
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
        .button-container {
    display: flex;
    justify-content: center; /* Center the buttons */
    gap: 10px; /* Space between buttons */
}

.link {
    padding: 10px 15px;
    background-color: #003366;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
    flex: 1; /* Make buttons fill available space */
    text-align: center; /* Center text in buttons */
}

.link:hover {
    background-color: #002244;
}

        footer {
            text-align: center;
            margin-top: 40px;
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
        <a href="login-rms.php">Logout</a>
    </div>
    
    <div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['admin']['username']) ?></h2>
    
    <div class="button-container">
        <a class="link" href="add_student-rms.php">Add Student Account</a>
        <a class="link" href="view_students-rms.php">View Students</a>
    </div>

    <h3>View Admin Accounts</h3>
    <a class="link" href="view_admins-rms.php">View All Admins</a>
</div>


    <footer>
        &copy; <?= date("Y") ?> SRC. All rights reserved.
    </footer>
</body>
</html>
