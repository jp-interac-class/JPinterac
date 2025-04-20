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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Resources</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="faq.css" />
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
        <a href="faq.php" class="nav-item">🧰 Resources</a>
      </nav>
    </div>
    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <!-- Main Content -->
  <main class="main">
    <h1>🛠️ Resources, Guidelines, & Announcements</h1>
    <div class="main-scroll">
      <div class="faq-container">

        <!-- NEWS & UPDATES -->
        <h2 class="faq-section-header">🗞️ News & Updates</h2>
        <div class="faq-divider-short"></div>
        <div class="faq-item">
          <h3>📢 Important Announcements</h3>
          <p>This section contains updates from management, system maintenance, award notices, and new school policies.</p>
          <a href="announcement.php">Read Announcements</a>
        </div>

        <!-- GUIDELINES & POLICIES -->
        <h2 class="faq-section-header">📢 Guidelines & Policies</h2>
        <div class="faq-divider-short"></div>
        <div class="faq-item">
          <h3>🚫 Offenses and Penalties</h3>
          <p>Be guided by our updated policy on conduct, penalties, and compliance with lesson expectations.</p>
          <a href="https://docs.google.com/spreadsheets/d/1G_kRRavs20TfnA2JYou5fLbRVeeASA6mshi3dlI0KiA/edit?gid=0#gid=0" target="_blank">View Penalty Guide</a>
        </div>

        <!-- TRAINING MATERIALS -->
        <h2 class="faq-section-header">📝 Training Materials</h2>
        <div class="faq-divider-short"></div>
        <div class="faq-item">
          <h3>📘 General Orientation Files</h3>
          <p>Includes company rules, login procedures, lesson structure, expectations, and other onboarding guides.</p>
          <a href="https://docs.google.com/document/d/general-orientation-file-link" target="_blank">View Orientation Guide</a>
        </div>
        <div class="faq-item">
          <h3>📚 Book Training Materials</h3>
          <p>Get to know textbook structure, question types, lesson pacing, and strategies for effective delivery.</p>
          <a href="https://docs.google.com/document/d/book-training-material-link" target="_blank">Access Book Training</a>
        </div>

        <!-- HELP & SUPPORT -->
        <h2 class="faq-section-header">❓ Help & Support</h2>
        <div class="faq-divider-short"></div>
        <div class="faq-item">
          <h3>❓ Frequently Asked Questions</h3>
          <p>Answers to most common inquiries from teachers regarding classes, conduct, and technical support.</p>
          <a href="https://docs.google.com/document/d/faq-link" target="_blank">Open FAQ Document</a>
        </div>

        <!-- TOOLS -->
        <h2 class="faq-section-header">🧰 Tools</h2>
        <div class="faq-divider-short"></div>
        <div class="faq-item">
          <h3>🎙️ Recommended Recorders</h3>
          <p>List of suggested tools for recording video/audio for demo, feedback, or troubleshooting purposes.</p>
          <a href="https://docs.google.com/document/d/recommended-recorder-link" target="_blank">See Recommended Tools</a>
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
