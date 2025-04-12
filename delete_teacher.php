<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!empty($email)) {
        $stmt = $conn->prepare("DELETE FROM teachers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No record deleted.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'No email provided.']);
    }
}
?>
