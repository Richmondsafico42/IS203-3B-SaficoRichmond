<?php
session_start();
require 'db.php'; // Ensure this includes your PDO connection setup

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM student_acc WHERE id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch();

    if (!$student) {
        echo "Student not found.";
        exit;
    }
} else {
    echo "Invalid student ID.";
    exit;
}

// Set the uploads directory path
$uploadsDir = '../stud/uploads/'; // Adjust based on your folder structure

// Get the profile picture path
$profilePicture = !empty($student['profile_picture']) ? htmlspecialchars($uploadsDir . basename($student['profile_picture'])) : 'default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
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
        .logo {
            position: absolute;
            top: 9px; /* Adjust as needed */
            left: 20px; /* Adjust as needed */
            width: 120px; /* Increased width for a bigger logo */
            height: 120px; /* Set height to match width for circular shape */
            border-radius: 50%; /* Make the logo circular */
            object-fit: cover; /* Ensure the image covers the circle */
        }
        .navbar {
            background-color: #000;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #0f0f0f;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: left;
        }
        h2 {
            margin-bottom: 20px;
            color: #fff;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
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
        .profile-pic {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
    <img src="../stud/uploads/src.jpg" alt="Logo" class="logo">
        <h1>Student Details</h1>
    </div>

    <div class="container">
        <label for="profile_picture_input">
            <img src="<?= $profilePicture ?>" alt="Profile Picture" class="profile-pic">
        </label>

        <h2><?= htmlspecialchars($student['student_name']) ?>'s Details</h2>
        <p><strong>Student ID:</strong> <?= htmlspecialchars($student['student_id']) ?></p>
        <p><strong>Section:</strong> <?= htmlspecialchars($student['section']) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($student['age']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></p>
        <p><strong>Year:</strong> <?= htmlspecialchars($student['year']) ?></p>
        <p><strong>Course:</strong> <?= htmlspecialchars($student['course']) ?></p>
        <p><strong>Birthday:</strong> <?= htmlspecialchars($student['birthday']) ?></p>
        
        <a class="back-link" href="view_students-rms.php">Back to Students</a>
    </div>
</body>
</html>

<?php
// Close the PDO connection if needed
$pdo = null;
?>
