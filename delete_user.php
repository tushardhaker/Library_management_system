<?php
session_start();

// Check if the user is logged in
$role = isset($_SESSION['role']) ? strtoupper($_SESSION['role']) : '';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';

if ($role !== 'ADMIN' && $role !== 'SUPER_ADMIN') {
    die("Access denied!");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userEmail = $_POST['email'];

    // Fetch the role and User_ID of the user to be deleted
    $stmt = $conn->prepare("SELECT User_ID, role FROM user WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $message = "❌ User not found.";
    } else {
        $stmt->bind_result($userId, $targetRole);
        $stmt->fetch();
        $targetRole = strtoupper($targetRole);

        if ($role === "ADMIN" && $targetRole !== "USER") {
            $message = "❌ Admins can only delete USER accounts.";
        } elseif ($role === "SUPER_ADMIN" && $targetRole === "SUPER_ADMIN") {
            $message = "❌ You cannot delete another SUPER_ADMIN.";
        } else {
            // First delete issued books related to the user
            $deleteIssued = $conn->prepare("DELETE FROM issuedbooks WHERE User_ID = ?");
            $deleteIssued->bind_param("s", $userId);
            $deleteIssued->execute();
            $deleteIssued->close();

            // Then delete the user
            $deleteStmt = $conn->prepare("DELETE FROM user WHERE email = ?");
            $deleteStmt->bind_param("s", $userEmail);

            if ($deleteStmt->execute()) {
                $message = "✅ User deleted successfully.";
            } else {
                $message = "❌ Error deleting user: " . $deleteStmt->error;
            }

            $deleteStmt->close();
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <link rel="stylesheet" href="/lms/css/delete_user.css">
</head>
<body>
    <div class="container">
        <h2>Delete User</h2>
        <p class="message"><?= htmlspecialchars($message) ?></p>
        <form method="POST">
            <label for="email">Enter User Email to Delete:</label><br>
            <input type="email" name="email" required><br><br>
            <button type="submit" class="btn-delete">🗑 Delete User</button>
        </form>
        <form action="/lms/userauthentication/dashboard.php" method="get" style="margin-top: 20px;">
            <button type="submit" class="btn-back">🔙 Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
