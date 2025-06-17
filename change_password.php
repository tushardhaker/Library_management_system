<?php
session_start();
include '../db.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// ✅ Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($current) || empty($new) || empty($confirm)) {
        $message = "⚠️ All fields are required.";
    } elseif ($new !== $confirm) {
        $message = "❌ New passwords do not match.";
    } else {
        // ✅ Get existing password from DB
        $result = $conn->query("SELECT Password FROM User WHERE User_ID = '$user_id'");
        $row = $result->fetch_assoc();
        $db_password = $row['Password'];

        // ✅ Verify current password (assuming plaintext, hash recommended in production)
        if ($current !== $db_password) {
            $message = "❌ Current password is incorrect.";
        } else {
            // ✅ Update password
            $stmt = $conn->prepare("UPDATE User SET Password = ? WHERE User_ID = ?");
            $stmt->bind_param("ss", $new, $user_id);
            if ($stmt->execute()) {
                $message = "✅ Password changed successfully!";
            } else {
                $message = "❌ Failed to change password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="/lms/css/change_password.css">
</head>
<body>
    <div class="container">
        <h2>🔐 Change Password</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" class="password-form">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit">Change Password</button>
        </form>

        <form action="/lms/userauthentication/dashboard.php" method="get">
            <button type="submit" class="back-btn">🔙 Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
