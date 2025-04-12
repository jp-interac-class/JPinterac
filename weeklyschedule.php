<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

// Get week offset from URL (default is 0)
$offset = isset($_GET['week_offset']) ? intval($_GET['week_offset']) : 0;

// Set base week start date (adjust as needed)
$baseDate = new DateTime("2024-04-07"); // This should be a Sunday or start of your calendar logic
$startDate = clone $baseDate;
$startDate->modify("+{$offset} weeks");

// Calculate end of the week
$endDate = clone $startDate;
$endDate->modify('+5 days');

// Format for display
$weekStart = $startDate->format("F j");
$weekEnd = $endDate->format("F j");

// Weekday names (6 days only)
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weekly Schedule</title>
  <link rel="stylesheet" href="weeklyschedule.css" />
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

    <!-- Main Panel -->
    <main class="main">
      <div class="header-center">
        <a href="weeklyschedule.php?week_offset=<?= $offset - 1 ?>" class="arrow">&#9664;</a>
        <div class="header-text">
          <h1>Weekly Schedule</h1>
          <h2><?= $weekStart . " - " . $weekEnd ?></h2>
        </div>
        <a href="weeklyschedule.php?week_offset=<?= $offset + 1 ?>" class="arrow">&#9654;</a>
      </div>

      <div class="week-schedule">
        <?php
        for ($i = 0; $i < count($days); $i++) {
          $currentDate = clone $startDate;
          $currentDate->modify("+$i days");
          $formattedDate = $currentDate->format("M j");

          echo "<div class='day-column'>";
          echo "<div class='day-header'>";
          echo "<div class='day-name'>{$days[$i]}</div>";
          echo "<div class='day-date'>{$formattedDate}</div>";
          echo "</div>";

          echo "<div class='slots'>";
          for ($j = 0; $j < 3; $j++) {
            echo "<a href='lessondetails.php?id={$days[$i]}-{$j}' class='lesson-card'>";
            echo "<div class='lesson-time'>🕘 9:30 – 9:45 JST</div>";
            echo "<div class='lesson-location'>🏫 Ureshino City<br>Ureshino ES</div>";
            echo "</a>";
          }
          echo "</div>";
          echo "</div>";
        }
        ?>
      </div>
    </main>
  </div>
</body>
</html>
