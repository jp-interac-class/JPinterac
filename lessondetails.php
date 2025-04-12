<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Tokyo');
$teacherEmail = $_SESSION["teacher_email"];
$todayDate = date("Y-m-d");

// Get URL parameters
$accessTime = $_GET['access_time'] ?? '';
$meetingGroup = $_GET['meeting_group'] ?? '';
$date = $_GET['date'] ?? '';
$startTime = $_GET['start_time'] ?? '';
$from = $_GET['from'] ?? '';

// Fetch lesson
$lesson = null;
if ($accessTime && $meetingGroup) {
    // From dashboard
    $stmt = $conn->prepare("SELECT * FROM lessons WHERE teacher_email = ? AND date = ? AND access_time = ? AND meeting_group = ? LIMIT 1");
    $stmt->bind_param("ssss", $teacherEmail, $todayDate, $accessTime, $meetingGroup);
} elseif ($date && $startTime) {
    // From weekly schedule
    $stmt = $conn->prepare("SELECT * FROM lessons WHERE teacher_email = ? AND date = ? AND start_time = ? LIMIT 1");
    $stmt->bind_param("sss", $teacherEmail, $date, $startTime);
}

if (isset($stmt)) {
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();
}

// Calendar Setup
$month = date("n");
$year = date("Y");
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$monthName = date("F", $firstDayOfMonth);
$startDay = date("w", $firstDayOfMonth);
$daysInMonth = date("t");
$today = date("j");

// Format date for display
$formattedDate = $lesson ? date("F j, Y", strtotime($lesson['date'])) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lesson Details</title>
  <link rel="stylesheet" href="lessondetails.css" />
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
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
        <?php if ($lesson): ?>
          <div class="lesson-info-grid">
            <div>Date:</div> <div><?= $formattedDate ?></div>
            <div>Access Time:</div> <div><?= date("H:i", strtotime($lesson['access_time'])) ?></div>
            <div>Start Time:</div> <div><?= date("H:i", strtotime($lesson['start_time'])) ?></div>
            <div>End Time:</div> <div><?= date("H:i", strtotime($lesson['end_time'])) ?></div>
            <div>Lesson Type:</div> <div><?= htmlspecialchars($lesson['lesson_type']) ?></div>
            <div>Area (BoE):</div> <div><?= htmlspecialchars($lesson['area']) ?></div>
            <div>School Name:</div> <div><?= htmlspecialchars($lesson['school']) ?></div>
            <div>Meeting Group:</div> <div><?= htmlspecialchars($lesson['meeting_group']) ?></div>
            <div>Grade:</div> <div><?= htmlspecialchars($lesson['grade']) ?></div>
            <div>Class:</div> <div><?= htmlspecialchars($lesson['class']) ?></div>
            <div>Lesson Period:</div> <div><?= $lesson['lesson_period'] ?: "N/A" ?></div>
            <div>Meeting Link:</div>
              <div><?= $lesson['meeting_link'] ? "<a href='{$lesson['meeting_link']}' target='_blank'>Enter the link</a>" : "N/A" ?></div>
            <div>Material:</div> <div><?= htmlspecialchars($lesson['material']) ?></div>
            <div>Material Link:</div>
              <div><?= $lesson['material_link'] ? "<a href='{$lesson['material_link']}' target='_blank'>View</a>" : "N/A" ?></div>
            <div>Feedback Form:</div>
              <div><?= $lesson['feedback_form'] ? "<a href='{$lesson['feedback_form']}' target='_blank'>Submit your feedback here</a>" : "N/A" ?></div>
          </div>
        <?php else: ?>
          <p style="color: red; font-weight: bold;">❌ No lesson found for this slot.</p>
        <?php endif; ?>
        <a href="<?= $from === 'schedule' ? 'weeklyschedule.php' : 'teacherdashboard.php' ?>" class="back-btn">
          ⬅️ Back to <?= $from === 'schedule' ? 'Weekly Schedule' : 'Dashboard' ?>
        </a>
      </div>
    </div>
  </main>

  <!-- Right Panel -->
  <section class="right-panel">
    <div class="clock" id="live-clock">
      <span id="time">--:--:--</span><br/>
      <span>Japanese Standard Time</span>
    </div>
    <div class="calendar">
      <div class="calendar-header">
        <span class="month"><?= $monthName ?></span>
        <span class="year"><?= $year ?></span>
      </div>
      <div class="calendar-grid">
        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
        <?php
        for ($i = 0; $i < $startDay; $i++) echo "<div></div>";
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $highlight = ($i == $today) ? "today" : "";
            echo "<div class='$highlight'>$i</div>";
        }
        ?>
      </div>
    </div>
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