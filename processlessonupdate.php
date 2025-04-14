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
    $original_teacher_email = $_POST['original_teacher_email'] ?? '';
    $new_teacher_email = $_POST['new_teacher_email'] ?? '';

    if ($date && $access_time && $original_teacher_email && $new_teacher_email) {
        // Prepare SQL to update only the matching lesson
        $stmt = $conn->prepare("
            UPDATE lessons 
            SET teacher_email = ? 
            WHERE date = ? AND access_time = ? AND teacher_email = ?
        ");

        $stmt->bind_param("ssss", $new_teacher_email, $date, $access_time, $original_teacher_email);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['upload_message'] = "✅ Lesson successfully reassigned to <strong>$new_teacher_email</strong> for <strong>$date</strong> at <strong>$access_time</strong>.";
            } else {
                $_SESSION['upload_message'] = "⚠️ No matching lesson found. Please double-check the teacher and time.";
            }
        } else {
            $_SESSION['upload_message'] = "❌ Error updating lesson: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['upload_message'] = "❌ All fields are required.";
    }
}

header("Location: lessonupdate.php");
exit;
?>
