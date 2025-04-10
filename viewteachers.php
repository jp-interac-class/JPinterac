<?php
session_start();
include 'db_connect.php'; // connects to teachers_db

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

// Fetch teachers from database
$teachers = [];
$result = $conn->query("SELECT name, email FROM teachers ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Calendar Setup
date_default_timezone_set('Asia/Tokyo');
$today = date("j");
$month = date("n");
$year = date("Y");
$monthName = date("F");
$startDay = date("w", mktime(0, 0, 0, $month, 1, $year));
$daysInMonth = date("t");

$calendar = "<div class='calendar'>
  <div class='calendar-header'><span class='month'>$monthName</span><span class='year'>$year</span></div>
  <div class='calendar-grid'>
    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>";

for ($i = 0; $i < $startDay; $i++) {
    $calendar .= "<div></div>";
}
for ($day = 1; $day <= $daysInMonth; $day++) {
    $highlight = ($day == $today) ? "today" : "";
    $calendar .= "<div class='$highlight'>$day</div>";
}
$calendar .= "</div></div>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>View Teachers</title>
  <link rel="stylesheet" href="viewteachers.css" />
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">

  <!-- SIDEBAR -->
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
        <a href="admin.php" class="nav-item">🏠 Dashboard</a>
        <a href="registerteacher.php" class="nav-item">🖊️ Register New Teacher</a>
        <a href="viewteachers.php" class="nav-item">👩‍🏫 View Teachers</a>
      </nav>
    </div>

    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main">
    <h1>Registered Teachers</h1>
    <div class="upload-section" style="max-width: 900px; width: 100%;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
    <h2 style="margin: 0;">List of Teachers</h2>
    <input
      type="text"
      id="searchInput"
      placeholder="Search by name or email..."
      style="padding: 8px; border-radius: 6px; border: 1px solid #ccc; width: 250px;"
    >
  </div>


      <?php if (empty($teachers)): ?>
        <p>No teachers registered yet.</p>
      <?php else: ?>
        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ccc; border-radius: 6px;">
          <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background-color: #f3f3f3;">
                <th style="text-align: left;">Name</th>
                <th style="text-align: left;">Email</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($teachers as $teacher): ?>
                <tr>
                  <td><?= htmlspecialchars($teacher['name']) ?></td>
                  <td><?= htmlspecialchars($teacher['email']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- RIGHT PANEL -->
  <section class="right-panel">
    <div class="clock" id="live-clock">
      <span id="time">--:--:--</span>
      <span>Japanese Standard Time</span>
    </div>
    <?= $calendar ?>
  </section>
</div>

<script>
function updateClock() {
  const now = new Date();
  const jst = new Date(now.toLocaleString("en-US", { timeZone: "Asia/Tokyo" }));
  const h = String(jst.getHours()).padStart(2, '0');
  const m = String(jst.getMinutes()).padStart(2, '0');
  const s = String(jst.getSeconds()).padStart(2, '0');
  document.getElementById('time').textContent = `${h}:${m}:${s}`;
}
setInterval(updateClock, 1000);
updateClock();

// ✅ SINGLE SEARCH for Name or Email
document.getElementById('searchInput').addEventListener('input', function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll("tbody tr");

  rows.forEach(row => {
    const name = row.cells[0].textContent.toLowerCase();
    const email = row.cells[1].textContent.toLowerCase();
    const match = name.includes(filter) || email.includes(filter);
    row.style.display = match ? "" : "none";
  });
});
</script>

</body>
</html>
