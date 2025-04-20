<?php
session_start();
include 'db_connect.php'; // make sure this connects to teachers_db

function convertEmojis($text) {
    $emojiMap = [
        '(heart)' => '❤️',
        '(sun)' => '☀️',
        '(happy)' => '😊',
        '(cool)' => '😎',
        '(paperclip)' => '📎',
        '(smile)' => '😄',
        '(star)' => '⭐',
        '(thumbsup)' => '👍',
        '(fire)' => '🔥',
        '(warning)' => '⚠️',
        '(check)' => '✅',
        '(x)' => '❌',
        '(wave)' => '👋',
        '(clap)' => '👏',
        '(sparkles)' => '✨',
        '(confetti)' => '🎉',
        '(idea)' => '💡',
        '(book)' => '📖',
        '(calendar)' => '📅',
        '(mic)' => '🎤',
        '(video)' => '🎥',
        '(zoom)' => '🧿',
        '(google)' => '🌐',
        '(arrow)' => '➡️',
        '(music)' => '🎵',
        '(v)' => '✌️'
    ];
    return str_replace(array_keys($emojiMap), array_values($emojiMap), $text);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = trim($_POST["announcement"] ?? '');
    $date = $_POST["announcement_date"] ?? date("Y-m-d");

    if (empty($content)) {
        $_SESSION['upload_message'] = "Content cannot be empty.";
        header("Location: admin.php");
        exit;
    }

    $uploadDir = "uploads/";
    $fileNames = [];

    // Create upload directory if not exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Convert emojis in content
    $contentWithEmoji = convertEmojis($content);

    // Handle multiple file uploads
    if (!empty($_FILES['announcement_file']['name'][0])) {
        foreach ($_FILES['announcement_file']['tmp_name'] as $key => $tmpName) {
            if (!empty($tmpName)) {
                $originalName = basename($_FILES['announcement_file']['name'][$key]);
                $uniqueName = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $originalName);
                $targetPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $fileNames[] = $uniqueName;
                }
            }
        }
    }

    // Convert array to comma-separated string
    $fileList = implode(",", $fileNames);

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO announcements (date, content, file) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $date, $contentWithEmoji, $fileList);
    $stmt->execute();
    $stmt->close();

    $_SESSION['upload_message'] = "Announcement posted successfully!";
    header("Location: admin.php");
    exit;
}
?>
