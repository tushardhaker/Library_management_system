<?php
include '../db.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $book_id = trim($_POST['Book_id'] ?? '');

    if (!empty($book_id)) {
        $stmt = $conn->prepare("DELETE FROM Books WHERE Book_ID = ?");
        $stmt->bind_param("s", $book_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "✅ Book with ID '$book_id' deleted!";
        } else {
            $message = "❌ No book found with ID '$book_id'.";
        }

        $stmt->close();
    } else {
        $message = "❌ Please enter a Book ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Book</title>
    <link rel="stylesheet" href="/lms/css/delete_book.css">
</head>
<body>
<div class="container">
    <h2>🗑️ Delete Book</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="delete-form">
        <label>📖 Enter Book ID to Delete:</label>
        <input type="text" name="Book_id" required>
        <button type="submit">Delete Book</button>
    </form>

    <form action="/lms/userauthentication/dashboard.php" method="get">
        <button type="submit" class="back-btn">🔙 Back to Dashboard</button>
    </form>
</div>
</body>
</html>
