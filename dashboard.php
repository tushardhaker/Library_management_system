<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="/lms/css/dashboard.css">
</head>
<body>
        <h2>Welcome, <?= htmlspecialchars($name) ?> </h2>
   <div class="button-grid">
    <a href="/lms/transaction/view_books.php">📘 View Books</a>
    <a href="/lms/transaction/issue_book.php">📥 Issue Book</a>
    <a href="/lms/transaction/return_book.php">📤 Return Book</a>
    <a href="/lms/transaction/search_book.php">🔍 Search Book</a>
    <a href="/lms/transaction/submit_feedback.php">✉ Submit Feedback</a>
    <a href="/lms/userauthentication/change_password.php">🔑 Change Password</a>
    <a href="/lms/transaction/book_suggestion.php">💡 Book Suggestion</a>

    <?php if ($role === "ADMIN" || $role === "SUPER_ADMIN"): ?>
        <a href="/lms/transaction/add_book.php">➕ Add Book</a>
        <a href="/lms/transaction/delete_book.php">🗑️ Delete Book</a>
        <a href="/lms/userauthentication/view_users.php">👥 View Users</a>
        <a href="/lms/userauthentication/delete_user.php">❌ Delete User</a>
        <a href="/lms/transaction/view_issued_books.php">📚 View Issued Books</a>
        <a href="/lms/transaction/view_feedback.php">📨 View Feedback</a>
    <?php endif; ?>

    <?php if ($role === "SUPER_ADMIN"): ?>
        <a href="/lms/userauthentication/delete_admin.php">⚠️ Delete Admin</a>
    <?php endif; ?>

    <a href="/lms/logout.php">📕 Logout</a>
</div>


        <form action="/lms/index.php" method="get">
            <button class="back-btn">🔙 Back</button>
        </form>
    </div>
</body>
</html>
