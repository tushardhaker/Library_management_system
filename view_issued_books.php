<?php
include '../db.php';

// Query to fetch all issued books
$result = $conn->query("SELECT Issue_ID, User_ID, Book_ID, IssueDate, ReturnDate FROM IssuedBooks");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issued Book Records</title>
    <link rel="stylesheet" href="/lms/css/view_issued_books.css">
</head>
<body>

<div class="container">
    <h2>📚 Issued Book Records</h2>

    <?php
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Issue ID</th>
                <th>User ID</th>
                <th>Book ID</th>
                <th>Issue Date</th>
                <th>Return Date</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['Issue_ID']}</td>
                    <td>{$row['User_ID']}</td>
                    <td>{$row['Book_ID']}</td>
                    <td>{$row['IssueDate']}</td>
                    <td>" . ($row['ReturnDate'] ?? '<span style="color: red;">Not returned</span>') . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='empty'>No books have been issued yet.</p>";
    }
    ?>

    <form action="/lms/userauthentication/dashboard.php" method="get">
        <button type="submit" class="btn-back">🔙 Back to Dashboard</button>
    </form>
</div>

</body>
</html>
