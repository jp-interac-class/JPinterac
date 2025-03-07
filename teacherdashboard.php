<?php
include 'db_connect.php'; // Connect to MySQL
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="teacherdashboard.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h2>
        <p>You are now logged in.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
