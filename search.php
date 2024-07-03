<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$query = $_GET['query'];

// Search for users matching the query
$search_sql = "SELECT * FROM users WHERE username LIKE '%$query%'";
$search_result = $conn->query($search_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Search Results for "<?php echo $query; ?>"</h1>
        <div class="contacts">
            <?php while($contact = $search_result->fetch_assoc()): ?>
                <div class="contact">
                    <img src="uploads/<?php echo $contact['profile_pic']; ?>" alt="Profile Picture">
                    <p><?php echo $contact['username']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
