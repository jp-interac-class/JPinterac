<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Tokyo');
$currentTime = date("H:i:s");
$today = date("j");
$month = date("n");
$year = date("Y");
$monthName = date("F");
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$startDay = date("w", $firstDayOfMonth);
$daysInMonth = date("t", $firstDayOfMonth);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<!-- SweetAlert2 Upload Notification -->
<?php if (isset($_SESSION['upload_message'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Upload Complete',
      text: '<?php echo $_SESSION['upload_message']; ?>',
      confirmButtonColor: '#3085d6'
    });
  </script>
  <?php unset($_SESSION['upload_message']); ?>
<?php endif; ?>

<div class="container">
  <!-- Sidebar -->
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

  <!-- Main Content -->
  <main class="main">
    <div class="main-scroll">
      <h1>Hi, Admin!</h1>

      <div class="upload-section">
        <h2>Upload Lesson File</h2>
        <form action="upload_handler.php" method="POST" enctype="multipart/form-data">
          <input type="file" name="lesson_file" required>
          <button type="submit">Upload</button>
        </form>
      </div>
    </div>
  </main>

  <!-- Right Panel -->
  <section class="right-panel">
    <div class="clock">
      <span id="time">--:--:--</span>
      <span>Japanese Standard Time</span>
    </div>
    <div class="calendar">
      <div class="calendar-header">
        <span class="month"><?php echo $monthName; ?></span>
        <span class="year"><?php echo $year; ?></span>
      </div>
      <div class="calendar-grid">
        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
        <?php
        for ($i = 0; $i < $startDay; $i++) echo "<div></div>";
        for ($day = 1; $day <= $daysInMonth; $day++) {
          $highlight = ($day == $today) ? "today" : "";
          echo "<div class='$highlight'>$day</div>";
        }
        ?>
      </div>
    </div>
  </section>
</div>

<!-- Live Clock Script -->
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
