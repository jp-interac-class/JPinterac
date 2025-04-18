<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $check = $conn->prepare("SELECT id FROM teachers WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "A teacher with this email already exists.";
        } else {
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $conn->prepare("INSERT INTO teachers (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
          $stmt->bind_param("sss", $name, $email, $hashedPassword);
          
            if ($stmt->execute()) {
                $success = "Teacher successfully registered.";
            } else {
                $error = "Something went wrong. Try again.";
            }

            $stmt->close();
        }

        $check->close();
    }

    $conn->close();
}

// Calendar
date_default_timezone_set('Asia/Tokyo');
$today = date("j");
$month = date("n");
$year = date("Y");
$monthName = date("F");
$startDay = date("w", mktime(0, 0, 0, $month, 1, $year));
$daysInMonth = date("t");

$calendar = "<div class='calendar'>
  <div class='calendar-header'><span class='month'>$monthName</span><span class='year'>$year</span></div>
  <div class='calendar-grid'>
    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>";

for ($i = 0; $i < $startDay; $i++) {
    $calendar .= "<div></div>";
}
for ($day = 1; $day <= $daysInMonth; $day++) {
    $highlight = ($day == $today) ? "today" : "";
    $calendar .= "<div class='$highlight'>$day</div>";
}
$calendar .= "</div></div>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Teacher</title>
  <link rel="stylesheet" href="registerteacher.css" />
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">
      <img src="Logo/logo1.png" alt="Logo" />
      <div class="logo-text">
        <strong>J-P Network English Corp</strong><br />
        <span>Admin Panel</span>
      </div>
    </div>
    <div class="nav-wrapper">
      <nav class="nav">
        <a href="admin.php" class="nav-item">🏠 Dashboard</a>
        <a href="registerteacher.php" class="nav-item">🖊️ Register New Teacher</a>
        <a href="viewteachers.php" class="nav-item">👩‍🏫 View Teachers</a>
        <a href="lessonupdate.php" class="nav-item">🛠️ Lesson Update</a>
      </nav>
    </div>
    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main">
    <div class="main-scroll">
      <h1>Register New Teacher</h1>
      <div class="upload-section">
        <h2>Registration</h2>
        <?php if ($success): ?>
          <script>
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: '<?= $success ?>',
              confirmButtonColor: '#4a4a2f'
            });
          </script>
        <?php elseif ($error): ?>
          <script>
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: '<?= $error ?>',
              confirmButtonColor: '#d33'
            });
          </script>
        <?php endif; ?>

        <form action="registerteacher.php" method="POST">
          <label for="name">Teacher's Name:</label>
          <input type="text" id="name" name="name" required>

          <label for="email">Teacher's Email:</label>
          <input type="email" id="email" name="email" required>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>

          <button type="submit">Register</button>
        </form>
      </div>
    </div>
  </main>

  <!-- RIGHT PANEL -->
  <section class="right-panel">
    <div class="clock" id="live-clock">
      <span id="time">--:--:--</span>
      <span>Japanese Standard Time</span>
    </div>
    <?= $calendar ?>
  </section>
</div>

<script>
function updateClock() {
  const now = new Date();
  const jst = new Date(now.toLocaleString("en-US", { timeZone: "Asia/Tokyo" }));
  const h = String(jst.getHours()).padStart(2, '0');
  const m = String(jst.getMinutes()).padStart(2, '0');
  const s = String(jst.getSeconds()).padStart(2, '0');
  document.getElementById('time').textContent = `${h}:${m}:${s}`;
}
setInterval(updateClock, 1000);
updateClock();
</script>
</body>
</html>
