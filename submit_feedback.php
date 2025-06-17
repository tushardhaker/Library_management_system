<?php
include '../db.php';

$name = $_POST['name'] ?? 'Anonymous';
$email = $_POST['email'] ?? 'anonymous@example.com';
$feedback = $_POST['feedback'] ?? '';
$message = "";

// ✅ Insert feedback if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($feedback)) {
    $timestamp = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO Feedback (Name, Email, Feedback, Timestamp) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $feedback, $timestamp);

    if ($stmt->execute()) {
        $message = "✅ Feedback submitted successfully!";
    } else {
        $message = "❌ Failed to submit feedback.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="/lms/css/submit_feedback.css">
</head>
<body>
    <div class="container">
        <h2>📝 Submit Feedback</h2>

        <form method="POST" class="feedback-form">
            <input type="text" name="name" placeholder="Your Name">
            <input type="email" name="email" placeholder="Your Email">
            <textarea name="feedback" rows="5" placeholder="Write your feedback here..." required></textarea>
            <button type="submit">Submit</button>
        </form>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="/lms/userauthentication/dashboard.php" method="get">
            <button type="submit" class="back-btn">🔙 Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
