<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Tokyo');
$currentTime = date("H:i:s");
$today = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lesson Update</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="lessonupdate.css">
</head>
<body>
<div class="container">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="Logo/logo1.png" alt="Logo">
      <div class="logo-text">
        <strong>J-P Network English Corp</strong>
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

    <a href="logout.php" class="logout">🔒 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main">
    <h1>Lesson Update</h1>

    <div class="update-section">
      <form class="update-form" method="POST">
        <label for="date">Select Date:</label>
        <input type="date" name="date" value="<?= $today ?>">

        <label for="access_time">Select Access Time:</label>
        <input type="time" name="access_time" value="<?= date('H:i') ?>">

        <button type="submit">🔍 Preview Lessons</button>
      </form>
    </div>

    <!-- Result Table -->
    <div class="results-table-wrapper">
      <table class="results-table">
        <thead>
          <tr>
            <th>Teacher Email</th>
            <th>Area</th>
            <th>Meeting Group</th>
            <th>Start Time</th>
            <th>End Time</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>lisse.jpenglish68@gmail.com</td>
            <td>Nishigou mura</td>
            <td>BR</td>
            <td>09:50:00</td>
            <td>10:10:00</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Right Panel -->
  <div class="right-panel">
    <div class="clock">
      <span id="time"><?= date('H:i:s') ?></span>
      <span><?= date('l, F j, Y') ?></span>
    </div>

    <div class="calendar">
      <div class="calendar-header">
        <span class="month"><?= date("F") ?></span>
        <span class="year"><?= date("Y") ?></span>
      </div>
      <div class="calendar-grid">
        <?php
        $month = date("n");
        $year = date("Y");
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $startDay = date("w", $firstDayOfMonth);
        $daysInMonth = date("t");

        for ($i = 0; $i < $startDay; $i++) {
          echo "<div></div>";
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
          $class = ($day == date("j")) ? "today" : "";
          echo "<div class='$class'>$day</div>";
        }
        ?>
      </div>
    </div>
  </div>
</div>

<script>
// Update clock every second
setInterval(() => {
  const now = new Date();
  document.getElementById('time').textContent =
    now.toLocaleTimeString('en-US', { hour12: false });
}, 1000);
</script>
</body>
</html>
