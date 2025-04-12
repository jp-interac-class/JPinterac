<?php

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set("Asia/Tokyo");

// Get the offset from the URL
$offset = isset($_GET['week_offset']) ? intval($_GET['week_offset']) : 0;

// Base date: current week's Monday
$today = new DateTimeImmutable("today", new DateTimeZone("Asia/Tokyo"));
$baseMonday = $today->modify('monday this week');

// Apply week offset
$startOfWeek = $baseMonday->modify("+{$offset} weeks");      // Monday
$endOfWeek = $startOfWeek->modify('+5 days');                // Saturday

$weekStart = $startOfWeek->format("M j");
$weekEnd = $endOfWeek->format("M j");

$isThisWeek = $today >= $startOfWeek && $today <= $endOfWeek;

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weekly Schedule</title>
  <link rel="stylesheet" href="weeklyschedule.css" />
  <meta http-equiv="Cache-Control" content="no-store" />
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
      <div class="header-center">
        <a href="weeklyschedule.php?week_offset=<?= $offset - 1 ?>" class="arrow">&#9664;</a>
        <div class="header-text">
          <h1>Weekly Schedule</h1>
          <h2><?= $weekStart . " - " . $weekEnd ?></h2>
          <?php if (!$isThisWeek): ?>
            <div style="margin-top: 8px;">
              <a href="weeklyschedule.php" style="font-size: 13px; color: #2a6edb;">🔄 Back to current week</a>
            </div>
          <?php endif; ?>
        </div>
        <a href="weeklyschedule.php?week_offset=<?= $offset + 1 ?>" class="arrow">&#9654;</a>
      </div>

      <div class="week-schedule">
        <?php
        for ($i = 0; $i < count($days); $i++) {
          $currentDate = $startOfWeek->modify("+$i days");
          $formattedDate = $currentDate->format("M j");

          $isToday = $currentDate->format('Y-m-d') === $today->format('Y-m-d');
          $todayClass = $isToday ? ' today-column' : '';

          echo "<div class='day-column$todayClass'>";
          echo "<div class='day-header'>";
          echo "<div class='day-name'>{$days[$i]}</div>";
          echo "<div class='day-date'>{$formattedDate}</div>";
          echo "</div>";

          echo "<div class='slots'>";
          for ($j = 0; $j < 3; $j++) {
            echo "<a href='lessondetails.php?id={$days[$i]}-{$j}' class='lesson-card'>";
            echo "<div class='lesson-time'>🕘 09:30 – 09:45</div>";
            echo "<div class='lesson-location'>🏫 Ureshino City<br>Ureshino ES</div>";
            echo "</a>";
          }
          echo "</div>"; // slots
          echo "</div>"; // day-column
        }
        ?>
      </div>
    </main>
  </div>
</body>
</html>
