<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $_POST['gmail'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE gmail = '$gmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: index.php');
            exit();
        } else {
            header('Location: reset_password.php?gmail=' . urlencode($gmail));
            exit();
        }
    } else {
        header('Location: register.php?error=account_not_exist');
        exit();
    }
}
?>
