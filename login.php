<?php
session_start();
include 'db_connect.php'; // Connects to teachers_db

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // ✅ First, check if it's an admin
    $adminStmt = $conn->prepare("SELECT password FROM admins WHERE email = ?");
    $adminStmt->bind_param("s", $email);
    $adminStmt->execute();
    $adminStmt->store_result();

    if ($adminStmt->num_rows > 0) {
        $adminStmt->bind_result($admin_password);
        $adminStmt->fetch();

        if ($password === $admin_password) {
            $_SESSION["loggedin"] = true;
            $_SESSION["admin_email"] = $email;
            $_SESSION["user_type"] = "admin";
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }

        $adminStmt->close();
    } else {
        // ✅ Then check if it's a teacher
        $stmt = $conn->prepare("SELECT name, password FROM teachers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name, $db_password);
            $stmt->fetch();

            if ($password === $db_password) {
                $_SESSION["loggedin"] = true;
                $_SESSION["teacher_name"] = $name;
                $_SESSION["teacher_email"] = $email;
                $_SESSION["user_type"] = "teacher";
                header("Location: teacherdashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }

        $stmt->close();
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <h2>Welcome Back!</h2>
            <p>Please enter your details.</p>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="login.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign in</button>
            </form>
        </div>
        <div class="right">
            <img src="Logo/Logo.png" alt="J-P NETWORK ENGLISH" class="logo">
        </div>
    </div>
</body>
</html>
