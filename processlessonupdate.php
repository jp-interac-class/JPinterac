<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['date'] ?? '';
    $access_time = $_POST['access_time'] ?? '';
    $new_teacher_email = $_POST['new_teacher_email'] ?? '';

    if ($date && $access_time && $new_teacher_email) {
        $stmt = $conn->prepare("UPDATE lessons SET teacher_email = ? WHERE date = ? AND access_time = ?");
        $stmt->bind_param("sss", $new_teacher_email, $date, $access_time);

        if ($stmt->execute()) {
            $_SESSION['upload_message'] = "Lesson successfully updated to $new_teacher_email for $date at $access_time.";
        } else {
            $_SESSION['upload_message'] = "Error updating lesson: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['upload_message'] = "All fields are required.";
    }
}

header("Location: lessonupdate.php");
exit;
?>
