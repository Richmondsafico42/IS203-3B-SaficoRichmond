<?php
session_start();
require 'db.php';

if (!isset($_SESSION['student'])) {
    header("Location: student_login-rms.php");
    exit;
}

// Get the student ID from the session
$student_id = $_SESSION['student']['student_id'];

// Retrieve the student info from the database
$stmt = $pdo->prepare("SELECT * FROM student_acc WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $student_name = trim($_POST['student_name']);
    $section = trim($_POST['section']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $year = trim($_POST['year']);
    $course = trim($_POST['course']);
    $birthday = trim($_POST['birthday']);

    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE student_acc SET student_name = ?, section = ?, age = ?, gender = ?, year = ?, course = ?, birthday = ? WHERE student_id = ?");

    // Execute the statement and check for success
    if ($stmt->execute([$student_name, $section, $age, $gender, $year, $course, $birthday, $student['student_id']])) {
        if ($stmt->rowCount() > 0) {
            // Update session data
            $_SESSION['student'] = array_merge($_SESSION['student'], [
                'student_name' => $student_name,
                'section' => $section,
                'age' => $age,
                'gender' => $gender,
                'year' => $year,
                'course' => $course,
                'birthday' => $birthday
            ]);
            header("Location: student_dashboard-rms.php");
            exit;
        } else {
            $error = "No changes were made or the update failed.";
        }
    } else {
        $errorInfo = $stmt->errorInfo();
        $error = "Database error: " . $errorInfo[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Profile</title>
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
        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
        }
        input, select {
            width: calc(100% - 20px);
            max-width: 300px;
            padding: 6px;
            margin: 5px 0;
            border: none;
            border-radius: 4px;
            background-color: #333;
            color: #fff;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Edit Student Profile</h1>
        <a href="student_dashboard-rms.php">Back to Dashboard</a>
    </div>
    
    <div class="container">
        <h2>Edit Profile</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="student_name">Name:</label>
            <input type="text" name="student_name" id="student_name" value="<?= htmlspecialchars($student['student_name']) ?>" required>
            
            <label for="section">Section:</label>
            <input type="text" name="section" id="section" value="<?= htmlspecialchars($student['section']) ?>">

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?= htmlspecialchars($student['age']) ?>">

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $student['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>

            <label for="year">Year:</label>
            <input type="number" name="year" id="year" value="<?= htmlspecialchars($student['year']) ?>">

            <label for="course">Course:</label>
            <input type="text" name="course" id="course" value="<?= htmlspecialchars($student['course']) ?>">

            <label for="birthday">Birthday:</label>
            <input type="date" name="birthday" id="birthday" value="<?= htmlspecialchars($student['birthday']) ?>">
</br></br>
            <button type="submit">Update Profile</button>
        </form>
    </div>

    <footer>
        &copy; <?= date("Y") ?> SRC. All rights reserved.
    </footer>
</body>
</html>

<?php
$pdo = null; // Close the database connection
?>
