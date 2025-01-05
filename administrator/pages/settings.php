<?php
include "../Dashboard/db_connection.php";  // Ensure this path is correct




if (!isset($_GET['admin_id'])) {
    echo "<script>alert('Admin ID not found!'); window.location.href = 'login.php';</script>";
    exit();
}

$admin_id = $_GET['admin_id'];  // Get admin_id from URL

// Query to fetch admin's details
$query = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
$result = $connection->query($query);

if ($result->num_rows == 0) {
    echo "<script>alert('Admin not found!'); window.location.href = 'login.php';</script>";
    exit();
}

$admin_data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update Profile
    if (isset($_POST['update_profile'])) {
        $username = $_POST['username'];

        // Update query
        $update_profile_query = "UPDATE admin SET username='$username' WHERE admin_id='$admin_id'";
        if ($connection->query($update_profile_query) === TRUE) {
            echo "<script>alert('Profile updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating profile.');</script>";
        }
    }

    // Change Password
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if the current password is correct
        $password_check_query = "SELECT password FROM admin WHERE admin_id='$admin_id'";
        $password_result = $connection->query($password_check_query);
        $stored_password = $password_result->fetch_assoc()['password'];

        if (password_verify($current_password, $stored_password)) {
            if ($new_password == $confirm_password) {
                // Hash new password
                $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update password query
                $update_password_query = "UPDATE admin SET password='$new_hashed_password' WHERE admin_id='$admin_id'";
                if ($connection->query($update_password_query) === TRUE) {
                    echo "<script>alert('Password changed successfully!');</script>";
                } else {
                    echo "<script>alert('Error changing password.');</script>";
                }
            } else {
                echo "<script>alert('New passwords do not match.');</script>";
            }
        } else {
            echo "<script>alert('Incorrect current password.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="/assets/css/settings.css">  <!-- Your custom CSS -->
</head>
<body>
    <h1>Admin Settings</h1>
    <button id="theme-toggle" onclick="toggleTheme()">Switch to Dark Mode</button>
<div class="settings">
  <div class="setting1">
    <img src="./setting.png" alt="">
  </div>
<div class="setting2">
        <h2>Update Profile</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= isset($admin_data['username']) ? htmlspecialchars($admin_data['username']) : ''; ?>" required>

            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <h2>Change Password</h2>
        <form method="POST" action="">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>
</div>
<script>
        // Toggle between light and dark mode
        function toggleTheme() {
            const body = document.body;
            const button = document.getElementById("theme-toggle");
            
            // Toggle class on body
            body.classList.toggle("dark-mode");

            // Update button text based on the current mode
            if (body.classList.contains("dark-mode")) {
                button.textContent = "Switch to Light Mode";
            } else {
                button.textContent = "Switch to Dark Mode";
            }
        }

        // Check if dark mode was previously set and apply it on page load
        window.onload = function() {
            if (localStorage.getItem("theme") === "dark") {
                document.body.classList.add("dark-mode");
                document.getElementById("theme-toggle").textContent = "Switch to Light Mode";
            }
        };
    </script>   
   

</body>
</html>
