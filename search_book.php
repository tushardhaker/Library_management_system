<?php
include '../db.php';

// Get search term from URL
$search = $_GET['search'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Books</title>
    <link rel="stylesheet" href="/lms/css/search_book.css">
</head>
<body>
    <div class="container">
        <h2>🔍 Search Books</h2>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Enter Book ID, Title, Author, or Topic" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>

        <div class="results">
            <?php
            if (!empty($search)) {
                $sql = "SELECT * FROM Books 
                        WHERE Book_ID LIKE '%$search%' 
                        OR Title LIKE '%$search%' 
                        OR Author LIKE '%$search%' 
                        OR Topic LIKE '%$search%'";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='book'>
                            <p>📘 <strong>{$row['Title']}</strong> by {$row['Author']}</p>
                            <p>🔖 Topic: {$row['Topic']}</p>
                            <p>🆔 Book ID: {$row['Book_ID']}</p>
                            <p>📦 Quantity: {$row['Quantity']}</p>
                        </div>";
                    }
                } else {
                    echo "<p class='info'>❌ No books found matching '<strong>$search</strong>'.</p>";
                }
            } else {
                echo "<p class='info'>ℹ️ Enter something to search.</p>";
            }
            ?>
        </div>

        <form action="/lms/userauthentication/dashboard.php" method="get" class="back-form">
            <button type="submit">🔙 Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
