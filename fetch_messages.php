<?php
session_start();
require_once 'connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_id'])) {
    $user_id = $_SESSION['user_id'];
    $recipient_id = $_POST['recipient_id'];

    $fetch_messages_sql = "SELECT * FROM chats 
                           WHERE (sender_id = '$user_id' AND recipient_id = '$recipient_id') 
                           OR (sender_id = '$recipient_id' AND recipient_id = '$user_id') 
                           ORDER BY created_at ASC";
    
    $result = $conn->query($fetch_messages_sql);

    $messages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }

    echo json_encode($messages);
} else {
    echo "Invalid request.";
}
?>
