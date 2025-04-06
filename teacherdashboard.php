<?php
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
<<<<<<< HEAD
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Teacher Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="teacherdashboard.css" />
  <style>
    .today {
      background-color: #4a4a2f;
      color: white;
      border-radius: 50%;
      font-weight: bold;
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
=======
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="teacherdashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({ 
            title: "Successfully Logged In!", 
            icon: "success", 
            draggable: true 
        });
    </script>
    
    <div class="sidebar">
        <div class="logo">
            <img src="Logo/Logo.png" alt="Logo">
        </div>
        <ul>
            <li class="active"><a href="#">Dashboard</a></li>
            <li><a href="#">My Lessons</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
>>>>>>> 348029ba3aeba2edaad36b635a5676567531541d
    </div>
  </div>

  <!-- Wrap nav in a bordered container -->
  <div class="nav-wrapper">
    <nav class="nav">
      <a href="#" class="nav-item">🏠 Dashboard</a>
      <a href="#" class="nav-item">📖 Schedule</a>
      <a href="#" class="nav-item">✔️ FAQ</a>
    </nav>
  </div>

  <a href="logout.php" class="logout">🔓 Logout</a>
</aside>

    <main class="main">
  <h1>Hi, Teacher Lisse!</h1>
  <h2>Upcoming Lessons</h2>

  <div class="main-content-row">
    <!-- Lesson List -->
    <div class="lessons">
      <div class="lesson">
        <div class="lesson-time">9:00 - 9:15 JST</div>
        <div class="lesson-location">Kanzaki City | Kanzaki ES</div>
      </div>

      <div class="lesson">
        <div class="lesson-time">9:30 - 9:45 JST</div>
        <div class="lesson-location">Ureshino City | Ureshino ES</div>
      </div>

      <div class="lesson">
        <div class="lesson-time">9:30 - 9:45 JST</div>
        <div class="lesson-location">Ureshino City | Ureshino ES</div>
      </div>

      <div class="lesson">
        <div class="lesson-time">9:30 - 9:45 JST</div>
        <div class="lesson-location">gbzsrheth | sgsRg</div>
      </div>
      <!-- Repeat as needed -->
    </div>


    <!-- Announcement Board -->
    <div class="announcement-board">
      <h2>📢 Announcements</h2>
      <ul>
        <li>Don't forget to submit your lesson reports by 5:00 PM.</li>
        <li>Power interruption advisory for April 7 in Davao.</li>
        <li>Zoom backgrounds must be updated before next Monday.</li>
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
