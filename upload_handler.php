<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

// DB credentials
$host = "localhost";
$user = "root";
$password = "";
$database = "teachers_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Delete existing data before inserting new
$conn->query("DELETE FROM lessons");

if (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] == 0) {
    $file = $_FILES['lesson_file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Skip header
        $rows = [];

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Clean N/A values
            $cleaned = array_map(function ($value) {
                return ($value === "N/A" || $value === "#N/A") ? '' : $value;
            }, $data);

            if (count($cleaned) < 16) continue;

            // Escape each value for SQL
            $escaped = array_map(function ($val) use ($conn) {
                return "'" . $conn->real_escape_string($val) . "'";
            }, $cleaned);

            $escaped[] = "NOW()"; // Add created_at timestamp
            $rows[] = "(" . implode(",", $escaped) . ")";
        }

        fclose($handle);

        if (!empty($rows)) {
            $sql = "
                INSERT INTO lessons (
                    teacher_email, date, access_time, start_time, end_time,
                    lesson_type, area, school, meeting_group, grade,
                    class, lesson_period, meeting_link, material,
                    material_link, feedback_form, created_at
                ) VALUES " . implode(",", $rows);

            if ($conn->query($sql)) {
                $_SESSION['upload_message'] = count($rows) . " lessons uploaded successfully!";
            } else {
                $_SESSION['upload_message'] = "Upload failed: " . $conn->error;
            }
        } else {
            $_SESSION['upload_message'] = "No valid rows found in the CSV.";
        }
    } else {
        $_SESSION['upload_message'] = "Could not read the uploaded file.";
    }
} else {
    $_SESSION['upload_message'] = "No file uploaded or there was an error.";
}

header("Location: admin.php");
exit;
?>
