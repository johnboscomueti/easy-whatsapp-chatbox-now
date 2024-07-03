<?php
session_start();
require 'connect.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['recipient_userid'], $_POST['message'])) {
        $sender_id = $_SESSION['user_id'];
        $recipient_id = $_POST['recipient_userid'];
        $message = $_POST['message'];

        // Insert message into database
        $query = "INSERT INTO chats (sender_id, recipient_id, message, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iis', $sender_id, $recipient_id, $message);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}
?>
