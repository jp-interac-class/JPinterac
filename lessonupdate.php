<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

// Calendar Setup
date_default_timezone_set('Asia/Tokyo');
$today = date("j");
$month = date("n");
$year = date("Y");
$monthName = date("F");
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$startDay = date("w", $firstDayOfMonth);
$daysInMonth = date("t", $firstDayOfMonth);

$calendar = "<div class='calendar'>
  <div class='calendar-header'>
    <span class='month'>$monthName</span>
    <span class='year'>$year</span>
  </div>
  <div class='calendar-grid'>
    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>";
for ($i = 0; $i < $startDay; $i++) {
    $calendar .= "<div></div>";
}
for ($i = 1; $i <= $daysInMonth; $i++) {
    $highlight = ($i == $today) ? "today" : "";
    $calendar .= "<div class='$highlight'>$i</div>";
}
$calendar .= "</div></div>";

$lessons = [];
$selectedDate = $_GET['date'] ?? '';
$selectedTime = $_GET['access_time'] ?? '';
$selectedTeacher = $_GET['teacher_name'] ?? '';

if ($selectedDate && $selectedTime && $selectedTeacher) {
    $stmt = $conn->prepare("SELECT lessons.teacher_email, teachers.name AS teacher_name, lessons.area, lessons.meeting_group, lessons.start_time, lessons.end_time FROM lessons LEFT JOIN teachers ON lessons.teacher_email = teachers.email WHERE lessons.date = ? AND lessons.access_time = ? AND teachers.name LIKE ?");
    $likeTeacher = "%" . $selectedTeacher . "%";
    $stmt->bind_param("sss", $selectedDate, $selectedTime, $likeTeacher);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $lessons[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lesson Update</title>
  <link rel="stylesheet" href="lessonupdate.css" />
</head>
<body>
<div class="container">
  <aside class="sidebar">
    <div class="logo">
      <img src="Logo/logo1.png" alt="Logo" />
      <div class="logo-text">
        <strong>J-P Network English Corp</strong><br />
        <span>Admin Panel</span>
      </div>
    </div>
    <div class="nav-wrapper">
      <nav class="nav">
        <a href="admin.php" class="nav-item">🏠 Dashboard</a>
        <a href="registerteacher.php" class="nav-item">🖊️ Register New Teacher</a>
        <a href="viewteachers.php" class="nav-item">👩‍🏫 View Teachers</a>
        <a href="lessonupdate.php" class="nav-item">🛠️ Lesson Update</a>
      </nav>
    </div>
    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <main class="main">
    <h1>Lesson Update</h1>
    <div class="lesson-update-wrapper">
      <form method="GET" class="lesson-update-form">
        <label>Select Date:</label>
        <input type="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>" required>

        <label>Select Access Time:</label>
        <input type="time" name="access_time" value="<?= htmlspecialchars($selectedTime) ?>" required>

        <label>Enter Teacher Name:</label>
        <input type="text" name="teacher_name" value="<?= htmlspecialchars($selectedTeacher) ?>" placeholder="e.g. Maria Santos" required>

        <button type="submit">🔍 Preview Lessons</button>
      </form>

      <?php if (!empty($lessons)): ?>
        <div class="scroll-section">
          <table class="lesson-preview-table">
            <tr>
              <th>Teacher Name</th>
              <th>Area</th>
              <th>Meeting Group</th>
              <th>Start Time</th>
              <th>End Time</th>
            </tr>
            <?php foreach ($lessons as $lesson): ?>
              <tr>
                <td><?= htmlspecialchars($lesson['teacher_name']) ?></td>
                <td><?= htmlspecialchars($lesson['area']) ?></td>
                <td><?= htmlspecialchars($lesson['meeting_group']) ?></td>
                <td><?= htmlspecialchars($lesson['start_time']) ?></td>
                <td><?= htmlspecialchars($lesson['end_time']) ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>

        <form action="processlessonupdate.php" method="POST" class="lesson-update-form">
          <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate) ?>">
          <input type="hidden" name="access_time" value="<?= htmlspecialchars($selectedTime) ?>">

          <label>Enter New Teacher Email to Assign:</label>
          <input type="text" name="new_teacher_email" placeholder="e.g. backup@jpnetwork.com" required>

          <button type="submit">✅ Update Lesson</button>
        </form>
      <?php elseif ($selectedDate && $selectedTime && $selectedTeacher): ?>
        <p style="margin-top:20px;color:darkred;">❌ No lesson found for the selected values.</p>
      <?php endif; ?>
    </div>
  </main>

  <section class="right-panel">
    <div class="clock">
      <span id="time">--:--:--</span>
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
