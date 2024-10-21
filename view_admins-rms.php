<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login-rms.php");
    exit;
}

// Fetch all admin accounts
$admins = $pdo->query("SELECT * FROM admins")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->execute([$deleteId]);
    header("Location: view_admins-rms.php"); // Redirect to refresh the page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Admin Accounts</title>
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
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #0f0f0f;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        h2, h3 {
            margin-bottom: 20px;
            color: #fff;
        }
        input {
            padding: 10px;
            border: none;
            border-radius: 4px;
            width: calc(70% - 20px);
            margin-bottom: 10px;
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
        .logo {
            position: absolute;
            top: 9px; /* Adjust as needed */
            left: 20px; /* Adjust as needed */
            width: 120px; /* Increased width for a bigger logo */
            height: 120px; /* Set height to match width for circular shape */
            border-radius: 50%; /* Make the logo circular */
            object-fit: cover; /* Ensure the image covers the circle */
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
        

        <h3>Add Admin Account</h3>
        <form method="POST" action="add_admin-rms.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required></br>
            <button type="submit">Add Admin</button>
        </form>
        <h2>Admin Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= htmlspecialchars($admin['username']) ?></td>
                    <td><?= htmlspecialchars($admin['created_at']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $admin['id'] ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a class="back-link" href="admin_dashboard-rms.php">Back to Dashboard</a>
    </div>
</body>
</html>
