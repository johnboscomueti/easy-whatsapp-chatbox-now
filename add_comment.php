<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];
$comment = $_POST['comment'];

// Insert the comment into the comments table
$comment_sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')";
$conn->query($comment_sql);

header('Location: index.php');
?>
