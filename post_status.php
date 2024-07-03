<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $status = $_POST['status'];

    // Handle file upload
    $media_path = '';
    $media_type = '';
    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $target_dir = "uploads/";
        $media_path = $target_dir . basename($_FILES['media']['name']);
        $media_type = strpos($_FILES['media']['type'], 'image') !== false ? 'image' : 'video';
        move_uploaded_file($_FILES['media']['tmp_name'], $media_path);
    }

    $sql = "INSERT INTO statuses (user_id, status, media_path, media_type) VALUES ('$user_id', '$status', '$media_path', '$media_type')";
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
