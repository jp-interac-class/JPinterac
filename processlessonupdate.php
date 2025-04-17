<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lessonId = $_POST['lesson_id'];
    $newTeacherName = trim($_POST['new_teacher_name']);

    if (!empty($lessonId) && !empty($newTeacherName)) {
        $stmt = $conn->prepare("SELECT email FROM teachers WHERE LOWER(name) = LOWER(?)");
        $stmt->bind_param("s", $newTeacherName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $newEmail = $row['email'];

            $update = $conn->prepare("UPDATE lessons SET teacher_email = ? WHERE id = ?");
            $update->bind_param("si", $newEmail, $lessonId);

            if ($update->execute()) {
                header("Location: lessonupdate.php?success=1");
                exit;
            } else {
                header("Location: lessonupdate.php?success=0");
                exit;
            }
        } else {
            header("Location: lessonupdate.php?success=0");
            exit;
        }
    } else {
        header("Location: lessonupdate.php?success=0");
        exit;
    }
}
?>
