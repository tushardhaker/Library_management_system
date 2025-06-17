<?php
session_start();
include '../db.php';

$message = "";

// ✅ Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("⛔ Please log in to return a book.");
}

$user_id = $_SESSION['user_id'];

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'] ?? '';

    if (empty($book_id)) {
        $message = "⚠️ Please enter Book ID.";
    } else {
        // ✅ Check if this book was issued to this user and not yet returned
        $check = $conn->query("SELECT * FROM IssuedBooks WHERE User_ID = '$user_id' AND Book_ID = '$book_id' AND ReturnDate IS NULL");

        if ($check->num_rows === 0) {
            $message = "❌ No such book currently issued.";
        } else {
            // ✅ Update return date
            $conn->query("UPDATE IssuedBooks SET ReturnDate = NOW() WHERE User_ID = '$user_id' AND Book_ID = '$book_id' AND ReturnDate IS NULL LIMIT 1");

            // ✅ Increase quantity
            $conn->query("UPDATE Books SET Quantity = Quantity + 1 WHERE Book_ID = '$book_id'");

            $message = "✅ Book Returned Successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="/lms/css/return_book.css">
</head>
<body>
    <h2>🔄 Return a Book</h2>

    <form method="POST">
        <label for="book_id">Enter Book ID to Return:</label><br>
        <input type="text" name="book_id" id="book_id" required>
        <br><br>
        <button type="submit">Return Book</button>
    </form>

    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <form action="/lms/userauthentication/dashboard.php" method="get">
        <button type="submit">🔙 Back to Dashboard</button>
    </form>
</body>
</html>
