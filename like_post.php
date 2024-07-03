<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$post_id = $_POST['post_id'];

// Update the like count for the post
$like_sql = "UPDATE posts SET like_count = like_count + 1 WHERE id = $post_id";
$conn->query($like_sql);

header('Location: index.php');
?>
