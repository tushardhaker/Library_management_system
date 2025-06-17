<?php
include '../db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Books</title>
    <link rel="stylesheet" href="/lms/css/view_books.css">
</head>
<body>
    <div class="container">
        <h3>📚 Available Books</h3>

        <?php
        $result = $conn->query("SELECT * FROM Books");

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Topic</th>
                        <th>Quantity</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Book_ID']}</td>
                        <td>{$row['Title']}</td>
                        <td>{$row['Author']}</td>
                        <td>{$row['Topic']}</td>
                        <td>{$row['Quantity']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p class='no-books'>❌ No books available.</p>";
        }
        ?>

        <a href="/lms/userauthentication/dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
    </div>
</body>
</html>
