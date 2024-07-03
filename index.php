<?php
session_start();

// Include your database connection file
require_once 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch logged-in user's information
$sql_user = "SELECT * FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

// Fetch all users except the logged-in user
$sql_all_users = "SELECT id, username, profile_pic FROM users WHERE id != ?";
$stmt_all_users = $conn->prepare($sql_all_users);
$stmt_all_users->bind_param("i", $user_id);
$stmt_all_users->execute();
$result_all_users = $stmt_all_users->get_result();

// Handle message sending
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recipient_id']) && isset($_POST['message'])) {
    $recipient_id = $_POST['recipient_id'];
    $message = htmlspecialchars($_POST['message']);

    $sql_send_message = "INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)";
    $stmt_send_message = $conn->prepare($sql_send_message);
    $stmt_send_message->bind_param("iis", $user_id, $recipient_id, $message);

    if ($stmt_send_message->execute() === TRUE) {
        header("Location: index.php?recipient_id=$recipient_id");
        exit;
    } else {
        echo "Error: " . $stmt_send_message->error;
    }
}

// Fetch chat messages with selected recipient
$recipient_id = null;
$recipient = null;
$chat_messages = [];
if (isset($_GET['recipient_id'])) {
    $recipient_id = $_GET['recipient_id'];
    
    $sql_recipient = "SELECT id, username, profile_pic FROM users WHERE id = ?";
    $stmt_recipient = $conn->prepare($sql_recipient);
    $stmt_recipient->bind_param("i", $recipient_id);
    $stmt_recipient->execute();
    $recipient = $stmt_recipient->get_result()->fetch_assoc();

    $sql_chat_messages = "SELECT m.*, u.username AS sender_username, u.profile_pic AS sender_profile_pic 
                          FROM messages m 
                          INNER JOIN users u ON m.sender_id = u.id 
                          WHERE (m.sender_id = ? AND m.recipient_id = ?) 
                             OR (m.sender_id = ? AND m.recipient_id = ?) 
                          ORDER BY m.sent_at ASC";
    $stmt_chat_messages = $conn->prepare($sql_chat_messages);
    $stmt_chat_messages->bind_param("iiii", $user_id, $recipient_id, $recipient_id, $user_id);
    $stmt_chat_messages->execute();
    $result_chat_messages = $stmt_chat_messages->get_result();
    while ($row = $result_chat_messages->fetch_assoc()) {
        $chat_messages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Clone</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
            <a href="logout.php">Logout</a>
        </div>

        <div class="sidebar">
            <h3>All Users</h3>
            <ul>
                <?php
                if ($result_all_users->num_rows > 0) {
                    while ($row = $result_all_users->fetch_assoc()) {
                        echo "<li>";
                        echo "<a href='index.php?recipient_id={$row['id']}'>";
                        echo "<img src='" . htmlspecialchars($row['profile_pic']) . "' alt='Profile Picture' class='profile-pic'> ";
                        echo htmlspecialchars($row['username']);
                        echo "</a>";
                        echo "</li>";
                    }
                } else {
                    echo "<li>No users found.</li>";
                }
                ?>
            </ul>
        </div>

        <div class="main-chat">
            <h3>Chat</h3>
            <?php if ($recipient): ?>
                <div class="recipient-profile">
                    <img src="<?php echo htmlspecialchars($recipient['profile_pic']); ?>" alt="Profile Picture">
                    <h4><?php echo htmlspecialchars($recipient['username']); ?></h4>
                </div>

                <div class="chat-messages">
                    <?php if (!empty($chat_messages)): ?>
                        <?php foreach ($chat_messages as $message): ?>
                            <div class="message <?php echo $message['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                                <img src="<?php echo htmlspecialchars($message['sender_profile_pic']); ?>" alt="Profile Picture">
                                <p><?php echo htmlspecialchars($message['message']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages found.</p>
                    <?php endif; ?>
                </div>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="message-form">
                    <input type="hidden" name="recipient_id" value="<?php echo $recipient_id; ?>">
                    <textarea name="message" rows="3" placeholder="Type your message..." required></textarea>
                    <button type="submit">Send</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
