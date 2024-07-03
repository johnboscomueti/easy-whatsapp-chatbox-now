<?php
session_start();
require 'connect.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['recipient_userid'])) {
        $user_id = $_SESSION['user_id'];
        $recipient_id = $_POST['recipient_userid'];

        // Fetch messages between the two users
        $query = "SELECT c.*, u.username AS sender_username FROM chats c JOIN users u ON c.sender_id = u.id WHERE (c.sender_id = ? AND c.recipient_id = ?) OR (c.sender_id = ? AND c.recipient_id = ?) ORDER BY c.created_at ASC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiii', $user_id, $recipient_id, $recipient_id, $user_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $messages = [];

            while ($row = $result->fetch_assoc()) {
                $messages[] = [
                    'sender_username' => $row['sender_username'],
                    'message' => $row['message'],
                    'sent_at' => $row['created_at']
                ];
            }

            echo json_encode(['status' => 'success', 'messages' => $messages]);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch messages.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing recipient_userid.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}
?>
