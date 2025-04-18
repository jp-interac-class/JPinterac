<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $adminStmt = $conn->prepare("SELECT password FROM admins WHERE email = ?");
    $adminStmt->bind_param("s", $email);
    $adminStmt->execute();
    $adminStmt->store_result();

    if ($adminStmt->num_rows > 0) {
        $adminStmt->bind_result($admin_password);
        $adminStmt->fetch();

        if (password_verify($password, $admin_password)) {
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
        $stmt = $conn->prepare("SELECT name, password FROM teachers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name, $db_password);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Page</title>
  <link rel="stylesheet" href="login.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    .password-container {
      position: relative;
    }

    .password-container input {
      width: 100%;
      padding-right: 40px;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #666;
    }

    .error {
      color: red;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Welcome Back!</h2>
      <p>Please enter your details.</p>
      <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
      <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>

        <div class="password-container">
          <input type="password" name="password" id="password" placeholder="Password" required>
          <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <button type="submit">Sign in</button>
      </form>
    </div>
    <div class="right">
      <img src="Logo/Logo.png" alt="J-P NETWORK ENGLISH" class="logo">
    </div>
  </div>

  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordInput = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
      const isPassword = passwordInput.type === "password";
      passwordInput.type = isPassword ? "text" : "password";
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  </script>
</body>
</html>
