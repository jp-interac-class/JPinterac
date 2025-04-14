<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "db_connect.php";

// Get POST data
$lesson_date = $_POST['lesson_date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$current_email = $_POST['current_email'] ?? '';
$new_email = $_POST['new_email'] ?? '';
$meeting_group = $_POST['meeting_group'] ?? '';

// Validate
if (!$lesson_date || !$start_time || !$current_email || !$new_email || !$meeting_group) {
    $_SESSION['upload_message'] = "❌ All fields are required.";
    header("Location: lessonupdate.php");
    exit;
}

// Prepare SELECT to check if lesson exists
$stmt = $conn->prepare("SELECT * FROM lessons WHERE date = ? AND start_time = ? AND teacher_email = ? LIMIT 1");
$stmt->bind_param("sss", $lesson_date, $start_time, $current_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['upload_message'] = "❌ Lesson not found. Please check the details.";
    header("Location: lessonupdate.php");
    exit;
}

// Proceed to update
$update = $conn->prepare("UPDATE lessons SET teacher_email = ?, meeting_group = ? WHERE date = ? AND start_time = ? AND teacher_email = ?");
$update->bind_param("sssss", $new_email, $meeting_group, $lesson_date, $start_time, $current_email);

if ($update->execute()) {
    $_SESSION['upload_message'] = "✅ Lesson reassigned successfully.";
} else {
    $_SESSION['upload_message'] = "❌ Failed to update lesson. Please try again.";
}

header("Location: lessonupdate.php");
exit;
