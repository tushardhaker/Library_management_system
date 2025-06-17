<?php
include '../db.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Check if admin is logged in (optional, based on your role system)
// if ($_SESSION['role'] !== 'admin') {
//     header("Location: ../unauthorized.php");
//     exit();
// }

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = trim($_POST['Book_ID'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $topic = trim($_POST['topic'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if ($book_id && $title && $author && $topic && $quantity) {
        $check = $conn->prepare("SELECT * FROM Books WHERE Book_ID = ?");
        $check->bind_param("s", $book_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $update = $conn->prepare("UPDATE Books SET Quantity = Quantity + ? WHERE Book_ID = ?");
            $update->bind_param("is", $quantity, $book_id);
            $update->execute();
            $message = "✅ Book quantity updated!";
        } else {
            $stmt = $conn->prepare("INSERT INTO Books (Book_ID, Title, Author, Topic, Quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $book_id, $title, $author, $topic, $quantity);
            $stmt->execute();
            $message = "✅ New book added!";
        }
    } else {
        $message = "❌ All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="/lms/css/add_book.css">
</head>
<body>
<div class="container">
    <h2>📘 Add or Update Book</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="book-form">
        <label>📖 Book ID:</label>
        <input type="text" name="Book_ID" required>

        <label>📝 Title:</label>
        <input type="text" name="title" required>

        <label>👨‍💼 Author:</label>
        <input type="text" name="author" required>

        <label>🏷️ Topic:</label>
        <input type="text" name="topic" required>

        <label>🔢 Quantity:</label>
        <input type="number" name="quantity" required min="1">

        <button type="submit">➕ Add Book</button>
    </form>

    <form action="/lms/userauthentication/dashboard.php" method="get">
        <button type="submit" class="back-btn">🔙 Back to Dashboard</button>
    </form>
</div>
</body>
</html>
