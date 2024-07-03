<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $_POST['group_name'];
    $creator_id = $_SESSION['user_id'];

    $sql = "INSERT INTO groups (name, creator_id) VALUES ('$group_name', '$creator_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Group created successfully. <a href='index.php'>Go back</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
