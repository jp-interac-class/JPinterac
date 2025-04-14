<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

// Fetch teachers
$teachers = [];
$result = $conn->query("SELECT name, email FROM teachers ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Calendar setup
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

  <!-- MAIN CONTENT -->
  <main class="main">
    <div class="main-scroll">
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
                  <th style="text-align: left;">Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($teachers as $teacher): ?>
                  <tr data-email="<?= htmlspecialchars($teacher['email']) ?>">
                    <td><?= htmlspecialchars($teacher['name']) ?></td>
                    <td><?= htmlspecialchars($teacher['email']) ?></td>
                    <td style="text-align: center;">
                      <button class="delete-btn" style="background: none; border: none; cursor: pointer;" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="red" viewBox="0 0 24 24">
                          <path d="M3 6h18v2H3V6zm2 3h14l-1.5 13h-11L5 9zm4 2v9h2v-9H9zm4 0v9h2v-9h-2z"/>
                        </svg>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
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
</script>

<script>
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

<script>
document.querySelectorAll(".delete-btn").forEach(button => {
  button.addEventListener("click", function () {
    const row = this.closest("tr");
    const email = row.getAttribute("data-email");

    Swal.fire({
      title: "Are you sure?",
      text: `This will delete ${email}.`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        fetch("delete_teacher.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            row.remove();
            Swal.fire("Deleted!", "The teacher has been removed.", "success");
          } else {
            Swal.fire("Error!", data.message || "Something went wrong.", "error");
          }
        });
      }
    });
  });
});
</script>
</body>
</html>
