<?php
// DATABASE CONNECTION STRING CREDENTIALS
$dbConn = "mysql:host=127.0.0.1;dbname=bsis3b_rms";
$user = "root";
$pass = "12345"; // for mobile users password: root

// CREATE PDO DATABASE CONNECTION
try {
    $pdo = new PDO($dbConn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
