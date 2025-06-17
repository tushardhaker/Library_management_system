<?php
session_start();
include '../db.php';

$result = $conn->query("SELECT User_ID, Name, Email, Role FROM User");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link rel="stylesheet" href="/lms/css/view_users.css">
</head>
<body>
    <div class="container">
        <h2>👥 All Registered Users</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['User_ID']) ?></td>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['Role']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>

        <form action="/lms/userauthentication/dashboard.php" method="get">
            <button type="submit" class="back-btn">🔙 Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
