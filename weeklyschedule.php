<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Tokyo');
$teacherEmail = $_SESSION["teacher_email"];

// Get offset from URL
$offset = isset($_GET['week_offset']) ? intval($_GET['week_offset']) : 0;

// Get today's date and find the Monday of this week
$today = new DateTime();
$dayOfWeek = $today->format('w'); // 0 (Sun) - 6 (Sat)
$daysToSubtract = ($dayOfWeek == 0) ? 6 : $dayOfWeek - 1;
$baseMonday = $today->modify("-$daysToSubtract days");

// Apply offset (e.g., -1 week, +1 week)
$startDate = clone $baseMonday;
$startDate->modify("{$offset} weeks");
$endDate = clone $startDate;
$endDate->modify('+5 days'); // Monday to Saturday

// Format for header display
$weekStart = $startDate->format("F j");
$weekEnd = $endDate->format("F j");

// Weekday labels (Monday–Saturday only)
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

// Fetch lessons between start and end date
$startStr = $startDate->format("Y-m-d");
$endStr = $endDate->format("Y-m-d");

$stmt = $conn->prepare("
  SELECT date, start_time, end_time, area 
  FROM lessons 
  WHERE teacher_email = ? AND date BETWEEN ? AND ?
  ORDER BY date, start_time
");
$stmt->bind_param("sss", $teacherEmail, $startStr, $endStr);
$stmt->execute();
$result = $stmt->get_result();

$lessonsByDate = [];
while ($row = $result->fetch_assoc()) {
    $lessonsByDate[$row['date']][] = $row;
}
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
        <a href="faq.php" class="nav-item">🧰 Resources</a>
      </nav>
    </div>
    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <!-- Main -->
  <main class="main">
    <div class="header-bar">
      <div class="arrow-box">
        <a href="weeklyschedule.php?week_offset=<?= $offset - 1 ?>">
          <img src="Logo/arrow-left.png" class="nav-arrow" alt="Previous Week" />
        </a>
      </div>

      <div class="header-text">
        <h1>Weekly Schedule</h1>
        <h2><?= $weekStart . " - " . $weekEnd ?></h2>
        <a href="weeklyschedule.php" class="today-button">Today</a>
      </div>

      <div class="arrow-box">
        <a href="weeklyschedule.php?week_offset=<?= $offset + 1 ?>">
          <img src="Logo/arrow-right.png" class="nav-arrow" alt="Next Week" />
        </a>
      </div>
    </div>

    <div class="week-schedule">
      <?php
      for ($i = 0; $i < count($days); $i++) {
        $currentDate = clone $startDate;
        $currentDate->modify("+$i days");
        $dateKey = $currentDate->format("Y-m-d");
        $formattedDate = $currentDate->format("M j");
        $isToday = ($dateKey === (new DateTime())->format("Y-m-d"));
        $todayClass = $isToday ? " today-column" : "";

        echo "<div class='day-column$todayClass'>";
        echo "<div class='day-header'>";
        echo "<div class='day-name'>{$days[$i]}</div>";
        echo "<div class='day-date'>{$formattedDate}</div>";
        echo "</div>";

        echo "<div class='slots'>";
        if (isset($lessonsByDate[$dateKey])) {
          foreach ($lessonsByDate[$dateKey] as $lesson) {
            $start = date("H:i", strtotime($lesson['start_time']));
            $end = date("H:i", strtotime($lesson['end_time']));
            $area = htmlspecialchars($lesson['area']);
            $encodedDate = urlencode($lesson['date']);
            $encodedStart = urlencode($lesson['start_time']);

            echo "<a href='lessondetails.php?date={$encodedDate}&start_time={$encodedStart}&from=schedule' class='lesson-card'>";
            echo "<div class='lesson-time'>⏰ $start – $end</div>";
            echo "<div class='lesson-location'>📍 $area</div>";
            echo "</a>";
          }
        } else {
          echo "<div class='lesson-card no-lesson'>No lessons</div>";
        }
        echo "</div></div>";
      }
      ?>
    </div>
  </main>
</div>
</body>
</html>
