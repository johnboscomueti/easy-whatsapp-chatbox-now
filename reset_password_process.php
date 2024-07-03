<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    if ($entered_otp == $_SESSION['otp'] && isset($_SESSION['otp_gmail'])) {
        $gmail = $_SESSION['otp_gmail'];

        $sql = "UPDATE users SET password='$new_password' WHERE gmail='$gmail'";
        if ($conn->query($sql) === TRUE) {
            echo "Password reset successfully.";
            unset($_SESSION['otp']);
            unset($_SESSION['otp_gmail']);
            header('Location: login.php');
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // Generate a new OTP and display it
        $new_otp = rand(100000, 999999);
        $_SESSION['otp'] = $new_otp;

        echo "Invalid OTP. A new OTP has been generated. Please try again.";
        echo "<p>Your new OTP: <span class='otp-display'>$new_otp</span></p>";
        echo '<a href="reset_password.php?gmail=' . urlencode($_SESSION['otp_gmail']) . '">Go back to Reset Password page</a>';
    }
}
?>
