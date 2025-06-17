<?php
session_start();
require_once('../db.php'); // Adjust path if needed

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT USER_ID, Name, Role FROM User WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['role'] = $row['Role'];  // USER / ADMIN / SUPER_ADMIN
        $_SESSION['user_id'] = $row['USER_ID'];
        $_SESSION['name'] = $row['Name'];

        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/lms/css/login.css">
</head>
<body>
    <div class="container">
        <h2>🔐 Login</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>

        <p class="message"><?= $message ?></p>

        <form action="/lms/index.php" method="get">
            <button type="submit" class="back-btn">🔙 Back</button>
        </form>
    </div>
</body>
</html>
