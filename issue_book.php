<?php
session_start();
include '../db.php';

// Check login session
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    die("⛔ Access denied. Please log in.");
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$allowed_roles = ['USER', 'ADMIN', 'SUPER_ADMIN'];
$message = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'] ?? '';

    if (empty($book_id)) {
        $message = "⚠️ Book ID is required.";
    } else {
        // Check if book exists and has stock
        $book = $conn->query("SELECT Quantity FROM Books WHERE Book_ID = '$book_id'")->fetch_assoc();

        if (!$book) {
            $message = "❌ Book not found.";
        } elseif ($book['Quantity'] <= 0) {
            $message = "❌ Book is out of stock.";
        } else {
            // Issue the book
            $stmt = $conn->prepare("INSERT INTO IssuedBooks (User_ID, Book_ID, IssueDate) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $user_id, $book_id);
            $stmt->execute();

            // Update quantity
            $conn->query("UPDATE Books SET Quantity = Quantity - 1 WHERE Book_ID = '$book_id'");

            $message = "✅ Book Issued Successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <link rel="stylesheet" href="/lms/css/issue_book.css">
</head>
<body>
    <h2>📚 Issue a Book</h2>

    <?php if (!in_array($role, $allowed_roles)): ?>
        <p style="color: red;">⛔ You do not have permission to issue books.</p>
    <?php else: ?>
        <form method="POST" action="">
            <label for="book_id">Enter Book ID:</label><br>
            <input type="text" name="book_id" id="book_id" required>
            <br><br>
            <button type="submit">Issue Book</button>
        </form>

        <?php if ($message): ?>
            <p><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>
    <?php endif; ?>

    <form action="/lms/userauthentication/dashboard.php" method="get">
        <button type="submit">🔙 Back to Dashboard</button>
    </form>
</body>
</html>
