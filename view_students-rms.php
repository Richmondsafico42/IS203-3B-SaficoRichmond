<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}

// Initialize search variable
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Retrieve students based on the search query
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM student_acc WHERE student_name LIKE ? OR student_id LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM student_acc");
}
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input {
            padding: 10px;
            border: none;
            border-radius: 4px;
            width: calc(70% - 20px);
        }
        .search-form button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-form button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        th {
            background-color: #003366;
            color: white;
        }
        tr:hover {
            background-color: #555;
        }
        .action-links {
            display: flex;
            justify-content: space-around;
        }
        .action-links a {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .action-links a:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="navbar">
    <img src="../stud/uploads/src.jpg" alt="Logo" class="logo">
        <h1>Admin Dashboard</h1>
        
    </div>
    
    <div class="container">
        <h2>Student Accounts</h2>
        <form class="search-form" method="POST">
            <input type="text" name="search" placeholder="Search by name or ID" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['student_name']) ?></td>
                    <td><?= htmlspecialchars($student['student_id']) ?></td>
                    <td class="action-links">
    <a href="view_student_details.php?id=<?= $student['id'] ?>">View</a>
    <a href="edit_student-rms.php?id=<?= $student['id'] ?>">Edit</a>
    <a href="delete_student-rms.php?id=<?= $student['id'] ?>">Delete</a>
</td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a class="back-link" href="admin_dashboard-rms.php">Back to Dashboard</a>
    </div>
</body>
</html>
