<?php
session_start();
include 'db_connect.php';

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_email'])) {
    header("Location: login.php");
    exit();
}

$teacher_name = $_SESSION['teacher_name'];
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
    <div class="sidebar">
        <div class="logo">
            <img src="Logo/Logo.png" alt="Logo">
        </div>
        <ul>
            <li class="active"><a href="#">Dashboard</a></li>
            <li><a href="#">My Lessons</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($teacher_name); ?>!</h1>
        </header>
        <section class="cards">
            <div class="card">
                <h3>Upcoming Lessons</h3>
                <p>You have 3 lessons scheduled today.</p>
            </div>
        </section>
    </div>
</body>
</html>
