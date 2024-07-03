<?php
include 'connect.php';

session_start();

if (isset($_GET['gmail'])) {
    $gmail = urldecode($_GET['gmail']);
    $otp = rand(100000, 999999);
    
    // Store OTP in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_gmail'] = $gmail;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Online Kenya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Light grey */
        }
        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff; /* White */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd; /* Light grey */
            border-radius: 5px;
            box-sizing: border-box;
        }
        .submit-btn {
            background-color: #333; /* Black */
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #555; /* Darker black */
        }
        .otp-display {
            font-size: 20px;
            font-weight: bold;
            color: #007bff; /* Blue */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <p>Your OTP: <span class="otp-display"><?php echo $_SESSION['otp']; ?></span></p>
        <form action="reset_password_process.php" method="POST">
            <div class="form-group">
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn" name="submit">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>
