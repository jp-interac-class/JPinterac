<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("Location: login.php");
  exit;
}

date_default_timezone_set('Asia/Tokyo');

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

$lessonId = isset($_GET['id']) ? ((int)$_GET['id'] + 1) : 'Not provided';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lesson Details</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="lessondetails.css" />
  <style>
    .lesson-info-grid {
      display: grid;
      grid-template-columns: 160px 1fr;
      gap: 8px 16px;
      font-size: 13px;
      padding: 16px 24px;
      background: #f1f5ff;
      border-radius: 10px;
      border: 1px solid #cdd6f4;
    }

    .lesson-info-grid div {
      line-height: 1.5;
    }

    .lesson-info-grid a {
      color: #2a6edb;
      text-decoration: none;
    }

    .lesson-info-grid a:hover {
      text-decoration: underline;
    }

    .main {
      flex: 1;
      padding: 24px 32px;
      background: white;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      max-height: 100vh;
      overflow: hidden;
      box-sizing: border-box;
    }

    .faq-container {
      flex: 1;
      overflow-y: auto;
      margin-top: 10px;
    }

    .faq-item {
      background: #ffffff;
      border: 1px solid #ddd;
      border-left: 5px solid #4a4a2f;
      border-radius: 8px;
      padding: 16px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
    }
  </style>
</head>
<body>
<div class="container">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">
      <img src="Logo/logo1.png" alt="Logo" />
      <div class="logo-text">
        <strong>J-P Network English Corp</strong><br />
        <span>Interac Classes</span>
      </div>
    </div>
    <div class="nav-wrapper">
      <nav class="nav">
        <a href="teacherdashboard.php" class="nav-item">🏠 Dashboard</a>
        <a href="weeklyschedule.php" class="nav-item">📖 Schedule</a>
        <a href="faq.php" class="nav-item">✔️ FAQ</a>
      </nav>
    </div>
    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <!-- Main Content -->
  <main class="main">
    <h1>Lesson Details</h1>
    <div class="faq-container">
      <div class="faq-item">
        <h3>📘 Please see the details below for your reference:</h3>
        <div class="lesson-info-grid">
          <div>Access Time:</div> <div>9:20 AM JST</div>
          <div>Start Time:</div> <div>9:30 AM JST</div>
          <div>End Time:</div> <div>9:45 AM JST</div>
          <div>Lesson Type:</div> <div>1:1</div>
          <div>Area (BoE):</div> <div>Ureshino City</div>
          <div>School Name:</div> <div>Ureshino Elementary School</div>
          <div>Meeting Group:</div> <div>Tanaka</div>
          <div>Grade:</div> <div>5</div>
          <div>Class:</div> <div>A</div>
          <div>Lesson Period:</div> <div>2nd</div>
          <div>Meeting Link:</div> <div><a href="#">Enter the link</a></div>
          <div>Material:</div> <div>“Unit 3 – Greetings”</div>
          <div>Material Link:</div> <div><a href="#">View</a></div>
          <div>Feedback Form:</div> <div><a href="#">Submit your feedback here</a></div>
        </div>
      </div>
    </div>
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
