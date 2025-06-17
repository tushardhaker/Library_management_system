<?php
include '../db.php';
session_start();

$message = "";

// Check if user is logged in and is SUPER_ADMIN
if (!isset($_SESSION['role']) || strtoupper($_SESSION['role']) !== 'SUPER_ADMIN') {
    die("Access denied!");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $admin_id = trim($_POST['admin_id'] ?? '');

    if (!empty($admin_id)) {
        $check = $conn->prepare("SELECT Role FROM User WHERE User_ID = ?");
        $check->bind_param("s", $admin_id);
        $check->execute();
        $result = $check->get_result();

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();

            if (strtoupper($data['Role']) === 'ADMIN') {
                $delete = $conn->prepare("DELETE FROM User WHERE User_ID = ?");
                $delete->bind_param("s", $admin_id);
                if ($delete->execute()) {
                    $message = "✅ Admin with ID '$admin_id' deleted!";
                } else {
                    $message = "❌ Error deleting admin: " . $delete->error;
                }
                $delete->close();
            } else {
                $message = "❌ Only ADMIN accounts can be deleted.";
            }
        } else {
            $message = "❌ User ID not found.";
        }

        $check->close();
    } else {
        $message = "❌ Please enter a valid Admin ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Admin</title>
    <link rel="stylesheet" href="/lms/css/delete_admin.css">
</head>
<body>
    <div class="container">
        <h2>🗑️ Delete Admin</h2>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Enter Admin User ID:</label><br>
            <input type="text" name="admin_id" required><br><br>
            <button type="submit">Delete Admin</button>
        </form>

        <form action="/lms/userauthentication/dashboard.php" method="get">
            <button type="submit" class="btn-back">🔙 Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
