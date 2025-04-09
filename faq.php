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
  <title>FAQ</title>
  <link rel="stylesheet" href="faq.css" />
  <style>
    .faq-container {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .faq-item {
      background: #ffffff;
      border: 1px solid #ddd;
      border-left: 5px solid #4a4a2f;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
    }

    .faq-item h3 {
      margin: 0 0 10px;
      font-size: 18px;
      color: #2c2c1e;
    }

    .faq-item p {
      margin: 0 0 10px;
      color: #444;
      font-size: 15px;
    }

    .faq-item a {
      color: #3367d6;
      text-decoration: underline;
    }

    .faq-item a:hover {
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="container">
  <!-- Sidebar (Copied from dashboard.php) -->
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
        <a href="teacherdashboard.php" class="nav-item">🏠 Dashboard</a>
        <a href="weeklyschedule.php" class="nav-item">📖 Schedule</a>
        <a href="faq.php" class="nav-item">✔️ FAQ</a>
      </nav>
    </div>
    <a href="logout.php" class="logout">🔓 Logout</a>
  </aside>

  <!-- Main Content -->
  <main class="main">
    <h1>FAQ & Resources</h1>
    <div class="faq-container">

    <div class="faq-item">
        <h3>🚫Offenses and Penalties </h3>
        <p>Please be guided about our updated policy.</p>
        <a href="https://docs.google.com/spreadsheets/d/1G_kRRavs20TfnA2JYou5fLbRVeeASA6mshi3dlI0KiA/edit?gid=0#gid=0" target="_blank">View Zoom Guide</a>
      </div>

      <div class="faq-item">
        <h3>📄 Zoom Troubleshooting Guide</h3>
        <p>Follow this step-by-step guide if you're having trouble connecting to Zoom before your lesson.</p>
        <a href="https://docs.google.com/document/d/your-zoom-guide-link" target="_blank">View Zoom Guide</a>
      </div>

      <div class="faq-item">
        <h3>🗂️ Student Materials (Lesson Flow)</h3>
        <p>Access all lesson materials and instructions in this Google Drive folder.</p>
        <a href="https://drive.google.com/drive/folders/your-materials-folder" target="_blank">Open Lesson Materials Folder</a>
      </div>

      <div class="faq-item">
        <h3>📊 Daily Progress Tracker (Google Sheet)</h3>
        <p>Use this sheet to input daily updates and view progress per school or student.</p>
        <a href="https://docs.google.com/spreadsheets/d/your-daily-progress-link" target="_blank">Go to Tracker Sheet</a>
      </div>

      <div class="faq-item">
        <h3>📢 Important Announcements</h3>
        <p>This document contains updates from management, system maintenance, and policy changes.</p>
        <a href="https://docs.google.com/document/d/your-announcement-link" target="_blank">Read Announcements</a>
      </div>

    </div>
  </main>
</div>
</body>
</html>
