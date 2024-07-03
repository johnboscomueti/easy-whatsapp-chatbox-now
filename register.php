<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gmail = $_POST['gmail'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gender = $_POST['gender'];

    // File upload logic
    $profile_pic = $_FILES['profile_pic']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_pic);
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);

    // Check if the username already exists
    $check_username_sql = "SELECT * FROM users WHERE username = '$username'";
    $check_username_result = $conn->query($check_username_sql);

    if ($check_username_result->num_rows > 0) {
        echo "Error: Username already exists. Please choose another username.";
    } else {
        $sql = "INSERT INTO users (gmail, username, password, profile_pic, gender) VALUES ('$gmail', '$username', '$password', '$profile_pic', '$gender')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user_id'] = $conn->insert_id;
            header('Location: login.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <label for="gmail">Gmail:</label>
            <input type="email" name="gmail" id="gmail" required>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <label for="profile_pic">Profile Picture:</label>
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
