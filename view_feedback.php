<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow only admin or super_admin
if (!isset($_SESSION["role"]) || !in_array(strtolower($_SESSION["role"]), ["admin", "super_admin"])) {
    echo "Access denied.";
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch feedback data
$sql = "
    SELECT f.name, f.email, f.Feedback, f.Timestamp
    FROM feedback f
    JOIN user u ON f.email = u.email
    ORDER BY f.Timestamp DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Feedback</title>
    <link rel="stylesheet" href="/lms/css/view_feedback.css">
</head>
<body>
<div class="container">
    <h2>📬 All User Feedback</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="feedback">
                <div class="meta">
                    <span><strong>Name:</strong> <?= htmlspecialchars($row["name"]) ?></span>
                    <span><strong>Email:</strong> <?= htmlspecialchars($row["email"]) ?></span>
                    <span><strong>Time:</strong> <?= htmlspecialchars($row["Timestamp"]) ?></span>
                </div>
                <div class="feedback-text">
                    <strong>Feedback:</strong> <?= nl2br(htmlspecialchars($row["Feedback"])) ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="empty">No feedback found.</p>
    <?php endif; ?>
</div>

<form action="/lms/userauthentication/dashboard.php" method="get">
    <button type="submit" class="btn-back">🔙 Back to Dashboard</button>
</form>
</body>
</html>
