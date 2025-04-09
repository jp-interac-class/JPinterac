<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Tokyo');
$currentTime = date("H:i");
$currentYear = date("Y");

// Calendar logic
$today = date("j");
$month = date("n");
$year = date("Y");

$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date("t", $firstDayOfMonth);
$monthName = date("F", $firstDayOfMonth);
$startDay = date("w", $firstDayOfMonth);

$calendar = "<div class='calendar'>
  <div class='calendar-header'>
    <span class='month'>$monthName</span>
    <span class='year'>$year</span>
  </div>
  <div class='calendar-grid'>
    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>";

$day = 1;
for ($i = 0; $i < $startDay; $i++) {
  $calendar .= "<div></div>";
}

for ($i = $startDay; $i < $startDay + $daysInMonth; $i++) {
  $highlight = ($day == $today) ? "today" : "";
  $calendar .= "<div class='$highlight'>$day</div>";
  $day++;
}

$calendar .= "</div></div>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="teacherdashboard.css" />
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="Logo/logo1.png" alt="J-P Network English Corp Logo" />
        <div class="logo-text">
          <strong>J-P Network English Corp</strong><br />
          <span>Interac Classes</span>
        </div>
      </div>
      <div class="nav-wrapper">
        <nav class="nav">
          <a href="admin.php" class="nav-item">🏠 Dashboard</a>
          <a href="logout.php" class="logout">🔓 Logout</a>
        </nav>
      </div>
    </aside>

    <!-- Main Panel -->
    <main class="main">
      <h1>Hi, Admin!</h1>
      <h2>Upload Lesson File</h2>
      <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="lesson_file" required>
        <button type="submit">Upload</button>
      </form>
    </main>

    <!-- Right Panel -->
    <section class="right-panel">
      <div class="clock" id="live-clock">
        <span id="time">--:--:--</span><br/>
        <span>Japanese Standard Time</span>
      </div>
      <?php echo $calendar; ?>
    </section>
  </div>

  <script>
    function updateClock() {
      const now = new Date();
      const japanTime = new Date(now.toLocaleString("en-US", { timeZone: "Asia/Tokyo" }));
      const hours = String(japanTime.getHours()).padStart(2, '0');
      const minutes = String(japanTime.getMinutes()).padStart(2, '0');
      const seconds = String(japanTime.getSeconds()).padStart(2, '0');
      document.getElementById('time').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>
