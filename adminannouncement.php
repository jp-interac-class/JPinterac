<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
  header("Location: login.php");
  exit;
}

include 'db_connect.php';

date_default_timezone_set('Asia/Tokyo');

$currentTime = date("H:i:s");
$today = date("j");
$month = date("n");
$year = date("Y");
$monthName = date("F");
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$startDay = date("w", $firstDayOfMonth);
$daysInMonth = date("t", $firstDayOfMonth);

// Fetch announcements
$announcements = [];
$sql = "SELECT * FROM announcements ORDER BY date DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
  }
}

// Emoji replacement
$emojiMap = [
  '(heart)' => '❤️', '(sun)' => '☀️', '(happy)' => '😊', '(cool)' => '😎',
  '(paperclip)' => '📎', '(smile)' => '😄', '(star)' => '⭐', '(thumbsup)' => '👍',
  '(fire)' => '🔥', '(warning)' => '⚠️', '(check)' => '✅', '(x)' => '❌',
  '(wave)' => '👋', '(clap)' => '👏', '(sparkles)' => '✨', '(confetti)' => '🎉',
  '(idea)' => '💡', '(book)' => '📖', '(calendar)' => '📅', '(mic)' => '🎤',
  '(video)' => '🎥', '(zoom)' => '🧿', '(google)' => '🌐', '(arrow)' => '➡️',
  '(music)' => '🎵', '(sunflower)' => '🌻', '(v)' => '✌️'
];

function convertEmojis($text, $emojiMap) {
  return str_replace(array_keys($emojiMap), array_values($emojiMap), $text);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Announcements</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="adminannouncement.css"> <!-- ✅ Correct CSS file -->
</head>

<body>

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
        <a href="adminannouncement.php" class="nav-item">📢 View Announcements</a>
      </nav>
    </div>
    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <!-- Main Content -->
  <main class="main">
    <div class="main-scroll">
      <h1>📢 Announcements</h1>

      <div class="faq-container">
        <?php foreach ($announcements as $item): ?>
          <div class="faq-item">
            <h3><?php echo date("F j, Y", strtotime($item['date'])); ?></h3>
            <p><?php echo nl2br(convertEmojis(htmlspecialchars($item['content']), $emojiMap)); ?></p>

            <?php if (!empty($item['file'])): ?>
              <?php
                $files = json_decode($item['file'], true);
                if (is_array($files)) {
                  foreach ($files as $file):
                    $file = trim($file);
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $path = "uploads/" . $file;
              ?>
                <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                  <img src="<?php echo $path; ?>" class="announcement-image" alt="attachment" />
                <?php elseif (in_array($ext, ['mp4', 'webm'])): ?>
                  <video controls class="announcement-video">
                    <source src="<?php echo $path; ?>" type="video/<?php echo $ext; ?>">
                    Your browser does not support the video tag.
                  </video>
                <?php else: ?>
                  <p><a href="<?php echo $path; ?>" target="_blank">📎 Download <?php echo basename($file); ?></a></p>
                <?php endif; ?>
              <?php endforeach; } ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
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
