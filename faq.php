<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("Location: login.php");
  exit;
}

date_default_timezone_set('Asia/Tokyo');

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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resources</title>
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="faq.css">
</head>
<body>
<div class="container">
  <aside class="sidebar">
    <div class="logo">
      <img src="Logo/logo1.png" alt="Logo">
      <div class="logo-text">
        <strong>J-P Network English Corp</strong><br>
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

  <main class="main">
    <h1>🛠️ Resources, Guidelines, & Announcements</h1>
    <div class="main-scroll">
      <div class="faq-container">

        <!-- NEWS & UPDATES -->
        <div class="faq-section">
          <h2 class="faq-section-header">📵 News & Updates</h2>
          <div class="faq-divider-short"></div>
          <div class="faq-item">
            <h3>📢 Important Announcements</h3>
            <p>This section contains updates from management, system maintenance, award notices, and new school policies.</p>
            <a href="announcement.php">Read Announcements</a>
          </div>
        </div>

        <!-- GUIDELINES & POLICIES -->
        <div class="faq-section">
          <h2 class="faq-section-header">📢 Guidelines & Policies</h2>
          <div class="faq-divider-short"></div>
          <div class="faq-item">
            <h3>⛔️ Offenses and Penalties</h3>
            <p>Be guided by our updated policy on conduct, penalties, and compliance with lesson expectations.</p>
            <a href="https://docs.google.com/spreadsheets/d/1G_kRRavs20TfnA2JYou5fLbRVeeASA6mshi3dlI0KiA/edit?gid=0#gid=0" target="_blank">View Penalty Guide</a>
          </div>
        </div>

        <!-- TRAINING MATERIALS -->
        <div class="faq-section">
          <h2 class="faq-section-header">📝 Training Materials</h2>
          <div class="faq-divider-short"></div>
          <div class="faq-item">
            <div class="tab-wrapper">
              <div class="tab-buttons">
                <button class="tab-button">General Orientation Files</button>
                <button class="tab-button">Book Training Materials</button>
              </div>

              <div class="tab-content">
                <p>Includes company rules, onboarding video guides, system usage reminders, and lesson advice references.</p>
                <details>
                  <summary><strong>📁 General Orientation List</strong></summary>
                  
                  <ul>
                    <li>📁 <strong>Orientation File</strong>
                      <ul>
                        <li><a href="https://drive.google.com/file/d/1hpaqbEBgxJudXbR5oA2Q-QVS7f_MMGo4/view" target="_blank">Orientation Discussion Reminder.docx.pdf</a></li>
                        <li><a href="https://drive.google.com/file/d/1HgzXmmSTuEdDxer5lXoTcCndDzIXQSdJ/view" target="_blank">Orientation Slide Reminder.pdf</a></li>
                      </ul>
                    </li>
                    <li>🔄 <strong>Orientation Self Review Videos</strong>
                      <ul>
                        <li><a href="https://drive.google.com/file/d/1GFXkTEppQTSlto0PYEASxdl_VgtLAr1i/view" target="_blank">🎥Part 1 : JP English Overview.mp4</a></li>
                        <li><a href="https://drive.google.com/file/d/1HUOPsNN-r4sVrzvEf2YINCxA1R3hzFEi/view" target="_blank">🎥Part 2 : Tools for Classes, Company Accounts and Register Viber.mp4</a></li>
                        <li><a href="https://drive.google.com/file/d/1E2Gm9A9QiiNIZrza4iCgNA2NFeS763Fh/view" target="_blank">🎥Part 3 : Use of Lesson Tracker and Teacher Status.mp4</a></li>
                        <li><a href="https://drive.google.com/file/d/1OkU10BQV_fSLS0SoD74aHU-CPKKSlGtJ/view" target="_blank">🎥Part 4 : Recorder Application, Video/Audio Format and Dropbox Account.mp4</a></li>
                        <li><a href="https://drive.google.com/file/d/1mPbKFqEPC3UGrp4vvaapDms1wkENaBol/view" target="_blank">🎥Part 5: Flow of Plotting.mp4</a></li>
                        <li><a href="https://drive.google.com/file/d/1TrdXECs_ea6-TcNho3NS1EEzzY-iJI8g/view" target="_blank">🎥Part 6: Teachers Status, JP Awarding and JP Web Portal.mp4</a></li>
                      </ul>
                      <li>📞 <strong>Setting Up Viber</strong>
                      <ul>
                        <li><a href="https://drive.google.com/file/d/14jLadmcv-YGR5JBTGcNgRMWLPVqf0Bf1/view" target="_blank">📑Setup Viber on Your Desktop.pdf</a></li>
                        <li><a href="https://docs.google.com/spreadsheets/d/1LGowa7uRwUFGB1fhMJD6I8rzPPd60bykf8dTvaMy7Ew/edit?gid=1485155900#gid=1485155900" target="_blank">📑Teachers Viber</a></li>
                      </ul>
                </details>
                <details>
                  <summary><strong>📁 Orientation File Part 2 – Lesson Advice</strong></summary>
                  <ul>
                    <li><a href="https://docs.google.com/presentation/d/1KiNCzmo4SUC6unHpbOeb4OW2syqB57WU/edit?slide=id.p10#slide=id.p10" target="_blank">📃 View Lesson Advice Document</a></li>
                  </ul>
                </details>
              </div>

              <div class="tab-content">
                <p>Includes KEC textbook training resources such as videos, preparation guides, and reminders, all organized by category.</p>
                <details>
                  <summary><strong>📁 KEC</strong></summary>
                  <ul>
                    <li><a href="https://drive.google.com/drive/u/0/folders/19E898NkUOp9aJEfvT9QnOozw79xOGj58" target="_blank">🎥 KEC Reminders (mp4)</a></li>
                    <li><a href="https://docs.google.com/presentation/d/18Y8ZA3naXT1_A7KYpdHcrq0ROvDQ92ScZAP3Dw7XV8Y/edit?slide=id.p#slide=id.p" target="_blank">🔍 How to look for the KEC materials</a></li>
                  </ul>
                </details>
                <details>
                  <summary><strong>📁 KEC Training Video List</strong></summary>
                  <ul>
                    <li>📘 <a href="https://drive.google.com/drive/folders/1Ra2NtIFAFqYqHlP1C7c5Y_9HTd_NzYXv" target="_blank">KEC Lesson Material</a></li>
                    <li>📝 <a href="https://drive.google.com/drive/folders/1IA7BKbHPaV7q03Ibw778nxL94Mrla223" target="_blank">KEC Lesson Preparation</a></li>
                    <li>📁 <strong>KEC Book Training</strong>
                      <ul>
                        <li><a href="https://drive.google.com/file/d/1Rqq_vLJDrgp_pSZ0VaTEOZYo8vma9_Nu/view" target="_blank">KEC Overview.mp4</a></li>
                        <li><a href="https://drive.google.com/file/d/1kzvXNLUFqwjWv5HcDUXCLuwEu64g7eH7/view" target="_blank">KEC Part 1.mp4</a></li>
                        <li><a href="https://drive.google.com/file/d/1kWu6hIUEZs7T-__x-Vb2_9POUMsjDp3I/view" target="_blank">KEC Part 2 – Back Up and Sub.mp4</a></li>
                        <li><a href="https://docs.google.com/presentation/d/1kZER6AB4EnFuv67pwA9a8eQVdS7fABYCrxBTxckWniI/edit?slide=id.g2e7c5f1df79_2_13#slide=id.g2e7c5f1df79_2_13" target="_blank">KEC Book Training Slide</a></li>
                      </ul>
                    </li>
                    <li>🔄 <strong>KEC Reminder Overview</strong>
                      <ul>
                        <li><a href="https://drive.google.com/drive/folders/1IA7BKbHPaV7q03Ibw778nxL94Mrla223" target="_blank">KEC Textbook Preparation</a></li>
                        <li><a href="https://drive.google.com/drive/folders/14dbQwE2M1XHNUuqP-wrP-1ouGp6yKJBQ" target="_blank">KEC Book Training</a></li>
                      </ul>
                    </li>
                  </ul>
                </details>
              </div>
            </div>
          </div>
        </div>

        <!-- HELP & SUPPORT -->
        <div class="faq-section">
          <h2 class="faq-section-header">❓ Help & Support</h2>
          <div class="faq-divider-short"></div>
          <div class="faq-item">
            <h3>❓ Frequently Asked Questions</h3>
            <p>Answers to most common inquiries from teachers regarding classes, conduct, and technical support.</p>
            <a href="https://drive.google.com/file/d/15_-7uhffGuM2BfuDl6R70C_z4JvCaCu-/view" target="_blank">Open FAQ Document</a>
          </div>
        </div>

        <!-- TOOLS -->
        <div class="faq-section">
          <h2 class="faq-section-header">🩰 Tools</h2>
          <div class="faq-divider-short"></div>
          <div class="faq-item">
            <h3>🎧 Recommended Recorders</h3>
            <p>Recommended tools for recording lessons, as required by our teaching standards.</p>
            <li><a href="https://drive.google.com/file/d/1UM9lrDLRaRCrKIY0T-tN0eB9OKdAMD3M/view" target="_blank">Audio and Video Recorder for JP Classes Part 1</li>
            <li><a href="https://drive.google.com/file/d/1rVDEN3HsylyDUujsfNodYjcADWZbPf2q/view" target="_blank">Audio and Video Recorder for JP Classes Part 2</a></li>
          </div>
        </div>

      </div>
    </div>
  </main>

  <section class="right-panel">
    <div class="clock" id="live-clock">
      <span id="time">--:--:--</span><br>
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
updateClock();
</script>

<script>
const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach((btn, idx) => {
  btn.addEventListener('click', () => {
    tabButtons.forEach(b => b.classList.remove('active'));
    tabContents.forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    tabContents[idx].classList.add('active');
  });
});

if (tabButtons.length > 0) {
  tabButtons[0].classList.add('active');
  tabContents[0].classList.add('active');
}
</script>
</body>
</html>
