<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Tokyo');

// Calendar setup
$currentTime = date("H:i");
$currentYear = date("Y");
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

// Fetch today's lessons
$teacherEmail = $_SESSION["teacher_email"];
$todayDate = date("Y-m-d");

$stmt = $conn->prepare("
  SELECT access_time, end_time, area, meeting_group 
  FROM lessons 
  WHERE teacher_email = ? AND date = ?
  ORDER BY access_time ASC
");
$stmt->bind_param("ss", $teacherEmail, $todayDate);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Teacher Dashboard</title>
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
          <a href="teacherdashboard.php" class="nav-item">🏠 Dashboard</a>
          <a href="weeklyschedule.php" class="nav-item">📖 Schedule</a>
          <a href="faq.php" class="nav-item">✔️ FAQ</a>
        </nav>
      </div>
      <a href="logout.php" class="logout">🔓 Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main">
      <h1>Hi, Teacher <?= htmlspecialchars($_SESSION["teacher_name"]); ?>!</h1>
      <h2>Upcoming Lessons (<?= date("F j, Y") ?>)</h2>

      <!-- Legend -->
      <div class="legend">
        <span class="dot ongoing"></span> Ongoing
        <span class="dot upcoming"></span> Upcoming
        <span class="dot past"></span> Past
      </div>

      <div class="main-content-row">
        <!-- Lesson Column -->
        <div class="lesson-scroll">
          <div class="lessons">
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                  $accessTimeFormatted = date("H:i", strtotime($row['access_time']));
                  $endTimeFormatted = date("H:i", strtotime($row['end_time']));

                  $todayFull = date("Y-m-d");
                  $nowRaw = strtotime("$todayFull " . date("H:i"));
                  $accessTimeRaw = strtotime("$todayFull " . $row['access_time']);
                  $endTimeRaw = strtotime("$todayFull " . $row['end_time']);

                  if ($nowRaw >= $endTimeRaw) {
                      $lessonClass = "lesson lesson-past";
                  } elseif ($nowRaw >= $accessTimeRaw && $nowRaw < $endTimeRaw) {
                      $lessonClass = "lesson lesson-ongoing";
                  } else {
                      $lessonClass = "lesson lesson-upcoming";
                  }
                ?>
                <a href="lessondetails.php?access_time=<?= urlencode($row['access_time']) ?>&meeting_group=<?= urlencode($row['meeting_group']) ?>" class="lesson-link">
                  <div class="<?= $lessonClass ?>">
                    <div class="lesson-time">🕘 <?= $accessTimeFormatted ?> – <?= $endTimeFormatted ?></div>
                    <div class="lesson-location">🏫 <?= htmlspecialchars($row['area']) ?> | <?= htmlspecialchars($row['meeting_group']) ?></div>
                  </div>
                </a>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="no-lessons">
                <img src="Images/no-lesson.jpeg" alt="No lessons" class="no-lessons-img" />
                <p class="no-lessons-msg">No lessons scheduled for today.<br>Enjoy your free time!</p>
                <a href="weeklyschedule.php" class="check-week">📅 View your weekly schedule</a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Announcement Board -->
        <div class="announcement-board">
          <h2>🔔 Reminders</h2>
          <ul>
            <li>Always SMILE.</li>
            <li>Double-check date, time, material, and meeting link.</li>
            <li>Send "I will finish" on Zoom 2 minutes before the end.</li>
            <li>Message us on Viber if Zoom channel isn’t available.</li>
            <li>Report any issues to Customer Support immediately.</li>
          </ul>
        </div>
      </div>
    </main>

    <!-- Right Panel: Clock & Calendar -->
    <section class="right-panel">
      <div class="clock" id="live-clock">
        <span id="time">--:--:--</span><br/>
        <span>Japanese Standard Time</span>
      </div>
      <?= $calendar ?>
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
