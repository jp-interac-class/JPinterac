<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
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

  <!-- Main Content -->
  <main class="main">
    <div class="header-center">
      <span class="arrow">&#9664;</span>
      <div class="header-text">
        <h1>Weekly Schedule</h1>
        <h2>April 7 - 12</h2>
      </div>
      <span class="arrow">&#9654;</span>
    </div>

    <div class="week-schedule">
      <?php
      $days = [
        ['Monday', '04-07', '💖', '#A3C93A'],
        ['Tuesday', '04-08', '📱', '#A879E6'],
        ['Wednesday', '04-09', '🌸', '#F2C94C'],
        ['Thursday', '04-10', '❓', '#56CCF2'],
        ['Friday', '04-11', '🎉', '#F58BCF'],
        ['Saturday', '04-12', '🍬', '#F2994A'],
      ];

      foreach ($days as $day) {
        echo "<div class='day-column'>";
        echo "<div class='day-header' style='background: {$day[3]}'>";
        echo "<div class='day-title'>{$day[2]}<br><strong>{$day[0]}</strong><br><span>{$day[1]}</span></div>";
        echo "</div>";
        echo "<div class='slots'>";
        for ($i = 0; $i < 10; $i++) {
          // Assuming each slot corresponds to a lesson with a unique ID
          $lessonId = $i + 1; // Replace this with the actual lesson ID
          echo "<a href='lessondetails.php?id={$lessonId}' class='slot-link'>";
          echo "<div class='slot'></div>";
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
