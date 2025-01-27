<?php
// mark_as_seen.php

require_once 'db_connection.php';
session_start();

// Check if notification ID is passed and valid
if (isset($_POST['notification_id']) && is_numeric($_POST['notification_id'])) {
    $notificationId = $_POST['notification_id'];

    // Update notification's is_seen status to 'yes'
    $stmt = $pdo->prepare("UPDATE notification SET is_seen = 'yes', status = 'read' WHERE notification_id = ?");
    $stmt->execute([$notificationId]);

    echo "Notification marked as seen";
} else {
    echo "Invalid notification ID";
}
?>
