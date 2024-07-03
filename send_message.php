<?php
session_start();
require_once 'connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $recipient_id = $_POST['recipient_id'];
    $message = $_POST['message'];

    $insert_message_sql = "INSERT INTO chats (sender_id, recipient_id, message) 
                           VALUES ('$sender_id', '$recipient_id', '$message')";
    
    if ($conn->query($insert_message_sql) === TRUE) {
        echo "Message sent successfully.";
    } else {
        echo "Error: " . $insert_message_sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
