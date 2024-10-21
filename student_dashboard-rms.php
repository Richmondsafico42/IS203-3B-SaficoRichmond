<?php
session_start();

// Database connection string credentials
$dbConn = "mysql:host=127.0.0.1;dbname=bsis3b_rms";
$user = "root";
$pass = "12345"; // For mobile users password: root

// Create PDO database connection
try {
    $pdo = new PDO($dbConn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

if (!isset($_SESSION['student'])) {
    header("Location: student_login-rms.php");
    exit;
}

// Get the student ID from the session
$student_id = $_SESSION['student']['student_id'];

// Retrieve the updated student info from the database
$stmt = $pdo->prepare("SELECT * FROM student_acc WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student not found.";
    exit;
}

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // File size limit
    if ($_FILES["profile_picture"]["size"] > 5000000) {
        die("Sorry, your file is too large.");
    }

    // Allow certain file formats
    $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("UPDATE student_acc SET profile_picture = ? WHERE student_id = ?");
        if ($stmt->execute([$target_file, $student_id])) {
            $_SESSION['student']['profile_picture'] = $target_file;
            $student['profile_picture'] = $target_file; // Update the current student profile picture for display
        } else {
            echo "Error updating record.";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Change password logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['message'] = "New passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $_SESSION['message'] = "New password must be at least 6 characters long.";
    } else {
        $stmt = $pdo->prepare("SELECT student_password FROM student_acc WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $current_password === $row['student_password']) { // Compare plain text
            // Update the password
            $stmt = $pdo->prepare("UPDATE student_acc SET student_password = ? WHERE student_id = ?");
            if ($stmt->execute([$new_password, $student_id])) {
                $_SESSION['message'] = "Password changed successfully.";
            } else {
                $_SESSION['message'] = "Error updating password.";
            }
        } else {
            $_SESSION['message'] = "Current password is incorrect.";
        }
    }
}

if (empty($student['profile_picture'])) {
    $student['profile_picture'] = 'default-profile.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
        h2, h3 {
            margin: 0;
            color: #fff;
        }
        .profile-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .profile-pic {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }
        .info {
            margin: 15px 0;
            padding: 15px;
            border-left: 4px solid #007bff;
            background-color: #555; 
        }
        .info p {
            margin: 5px 0;
            color: #fff;
        }
        .edit-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #003366;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .edit-link:hover {
            background-color: #002244; 
        }
        .change-password {
            margin-left: auto; 
            color: white; 
            text-decoration: none; 
            font-weight: bold; 
            position: relative; 
            top: -50px; 
            font-size: 0.8em; 
        }
        .change-password:hover {
            text-decoration: underline; 
        }
        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.7); 
        }
        .modal-content {
            background-color: #0f0f0f;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 400px; 
            max-width: 25%; 
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .modal-content input[type="password"] {
            width: 100%; 
            max-width: 300px; 
            margin: 10px 0; 
        }
        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #f1f1f1;
            text-decoration: none;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Student Dashboard</h1>
        <a href="student_login-rms.php">Logout</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div id="message" style="background-color: #007BFF; color: white; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="container">
        <div class="profile-container">
            <label for="profile_picture_input">
                <img src="<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile Picture" class="profile-pic">
            </label>
            <form action="" method="POST" enctype="multipart/form-data" style="display: inline;">
                <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*" style="display: none;" onchange="this.form.submit();">
            </form>
            <script>
                document.querySelector('.profile-pic').parentNode.onclick = function() {
                    document.getElementById('profile_picture_input').click();
                };
            </script>
            <h2>Welcome, <?= htmlspecialchars($student['student_name']) ?></h2>
            <a class="change-password" href="#" id="changePasswordLink">Change Password</a>
        </div>
        
        <div class="info">
            <p><strong>Student ID:</strong> <?= htmlspecialchars($student['student_id']) ?></p>
            <p><strong>Section:</strong> <?= htmlspecialchars($student['section']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($student['age']) ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($student['gender']) ?></p>
            <p><strong>Year:</strong> <?= htmlspecialchars($student['year']) ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($student['course']) ?></p>
            <p><strong>Birthday:</strong> <?= htmlspecialchars($student['birthday']) ?></p>
        </div>
        <a class="edit-link" href="edit_student_profile-rms.php">Edit Profile</a>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3>Change Password</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password:</label></br>
                    <input type="password" name="current_password" id="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label></br>
                    <input type="password" name="new_password" id="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label></br>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="submit-button">Change Password</button>
            </form>
        </div>
    </div>

    <footer>
        &copy; <?= date("Y") ?> SRC. All rights reserved.
    </footer>

    <script>
        setTimeout(function() {
            var message = document.getElementById('message');
            if (message) {
                message.style.display = 'none';
            }
        }, 5000); // 5000 milliseconds = 5 seconds

        var modal = document.getElementById("changePasswordModal");
        var link = document.getElementById("changePasswordLink");
        var span = document.getElementById("closeModal");

        link.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php
$pdo = null;
?>
