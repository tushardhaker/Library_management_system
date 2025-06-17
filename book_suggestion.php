<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// ✅ Connect to database
$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION["user_id"];
$recommendations = [];
$trendingBooks = [];

// ✅ Step 1: Get books issued by user
$stmt = $conn->prepare("
    SELECT b.Book_ID, b.Title
    FROM issuedbooks i 
    JOIN books b ON i.Book_ID = b.Book_ID 
    WHERE i.User_ID = ?
");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$issuedTitles = [];
$categories = [];

while ($row = $result->fetch_assoc()) {
    $issuedTitles[] = $row['Title'];
    if (!empty($row['Category'])) {
        $categories[] = $row['Category'];
    }
}
$stmt->close();
$categories = array_unique($categories);

// ✅ Step 2: Recommend books based on Category
if (!empty($categories)) {
    $categoryPlaceholders = implode(',', array_fill(0, count($categories), '?'));
    $sql = "SELECT Title, Author FROM books WHERE Category IN ($categoryPlaceholders)";

    if (!empty($issuedTitles)) {
        $titlePlaceholders = implode(',', array_fill(0, count($issuedTitles), '?'));
        $sql .= " AND Title NOT IN ($titlePlaceholders)";
    }

    $sql .= " LIMIT 5";
    $stmt = $conn->prepare($sql);

    $types = str_repeat('s', count($categories) + count($issuedTitles));
    $params = array_merge($categories, $issuedTitles);
    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recommendations[] = $row;
    }
    $stmt->close();
}

// ✅ Step 3: Trending Books
$result = $conn->query("SELECT Title, Author FROM books ORDER BY Quantity DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $trendingBooks[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Book Suggestions</title>
    <link rel="stylesheet" href="/lms/css/book_suggestion.css">
</head>
<body>
<div class="container">
    <h2>📚 AI-Powered Book Suggestions for You</h2>

    <div class="section">
        <h3>✅ Recommended Books</h3>
        <?php if (!empty($recommendations)): ?>
            <?php foreach ($recommendations as $book): ?>
                <div class="book">
                    <div class="title"><?= htmlspecialchars($book['Title']) ?></div>
                    <div>Author: <?= htmlspecialchars($book['Author']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No new recommendations found.</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h3>🔥 Trending Books</h3>
        <?php foreach ($trendingBooks as $book): ?>
            <div class="book">
                <div class="title"><?= htmlspecialchars($book['Title']) ?></div>
                <div>Author: <?= htmlspecialchars($book['Author']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <form action="/lms/userauthentication/dashboard.php" method="get">
        <button type="submit" class="back-btn">🔙 Back to Dashboard</button>
    </form>
</div>
</body>
</html>
