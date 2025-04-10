<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
  date_default_timezone_set('Asia/Tokyo');
  $currentTime = date("H:i");
  $currentYear = date("Y");

  // Dynamic calendar logic
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Teacher Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="teacherdashboard.css" />
  <style>
    .today {
      background-color: #4a4a2f;
      color: white;
      border-radius: 50%;
      font-weight: bold;
    }

    .main-content-row {
      display: flex;
      gap: 30px;
      align-items: flex-start;
    }

    .lesson-scroll {
      max-height: 500px;
      overflow-y: auto;
      flex: 1;
      padding-right: 10px;
    }

    .lesson-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .lesson-scroll::-webkit-scrollbar-thumb {
      background-color: #ccc;
      border-radius: 4px;
    }

    .announcement-board {
      width: 320px;
      background-color: #fffbe8;
      padding: 20px;
      border-radius: 10px;
      border: 1px solid #eee;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      flex-shrink: 0;
    }
  </style>
</head>
<body>
  <div class="container">
  <aside class="sidebar">
  <div class="logo">
    <img src="Logo/logo1.png" alt="J-P Network English Corp Logo" />
    <div class="logo-text">
      <strong>J-P Network English Corp</strong><br />
      <span>Interac Classes</span>
    </div>
  </div>

  <!-- Wrap nav in a bordered container -->
  <div class="nav-wrapper">
    <nav class="nav">
      <a href="teacherdashboard.php" class="nav-item">🏠 Dashboard</a>
      <a href="weeklyschedule.php" class="nav-item">📖 Schedule</a>
      <a href="faq.php" class="nav-item">✔️ FAQ</a>
    </nav>
  </div>

  <a href="logout.php" class="logout">🔓 Logout</a>
</aside>

    <main class="main">
    <h1>Hi, Teacher <?= htmlspecialchars($_SESSION["teacher_name"]); ?>!</h1>
  <h2>Upcoming Lessons</h2>

  <div class="main-content-row">
    <!-- Lesson List -->
    <div class="lesson-scroll">
      <div class="lessons">
      <?php for ($i = 0; $i < 13; $i++): ?>
        <a href="lessondetails.php?id=<?= $i ?>" class="lesson-link">
          <div class="lesson">
            <div class="lesson-time">🕘 9:30 – 9:45 JST</div>
            <div class="lesson-location">🏫 Ureshino City | Ureshino ES</div>
          </div>
        </a>
        <?php endfor; ?>

        <?php for ($i = 0; $i < 13; $i++): ?>
        <a href="lessondetails.php?id=<?= $i ?>" class="lesson-link">
          <div class="lesson">
            <div class="lesson-time">🕘 9:30 – 9:45 JST</div>
            <div class="lesson-location">🏫 Ureshino City | Ureshino ES</div>
          </div>
        </a>
        <?php endfor; ?>

        <?php for ($i = 0; $i < 13; $i++): ?>
        <a href="lessondetails.php?id=<?= $i ?>" class="lesson-link">
          <div class="lesson">
            <div class="lesson-time">🕘 9:30 – 9:45 JST</div>
            <div class="lesson-location">🏫 Ureshino City | Ureshino ES</div>
          </div>
        </a>
        <?php endfor; ?>
      </div>
    </div>

    <!-- Announcement Board -->
    <div class="announcement-board">
      <h2>🔔 Reminders</h2>
      <ul>
        <li>Always SMILE.</li>
        <li>Double-check date, time, material, and meeting link.</li>
        <li>Send "I will finish" on Zoom 2 minutes before the end.<br> If not on the channel, message us on Viber.</li>
        <li>Report any lesson issues to Customer Support for immediate help.</li>
      </ul>
    </div>
  </div>
</main>

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
updateClock(); // Initial call
</script>


</body>
</html>