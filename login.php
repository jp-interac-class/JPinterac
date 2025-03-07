<?php
session_start();
include 'db_connect.php'; // Ensure the database connection file exists

// Debugging: Check request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die("405 Method Not Allowed - Only POST requests are allowed.");
}

$email = $_POST["email"] ?? null;
$password = $_POST["password"] ?? null;

// Debugging: Check if form data is received
if (!$email || !$password) {
    die("Error: No email or password received.");
}

// Prepare SQL query
$stmt = $conn->prepare("SELECT name, password FROM teachers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name, $hashed_password);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    header("Location: teacherdashboard.php"); // Redirect to teacher's dashboard
    exit();
} else {
    echo "Invalid email or password!";
}

$stmt->close();
$conn->close();
?>
