<?php
session_start();
require_once('../db.php'); // Adjust if needed

$SUPER_ADMIN_EMAIL = "tushar@gmail.com";
$ADMIN_SECURITY_KEY = "ADMIN2025";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = strtoupper($_POST['role']);
    $security_key = $_POST['security_key'];

    function isValidPassword($password) {
        return strlen($password) >= 8 &&
               preg_match('/[A-Za-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[^A-Za-z0-9]/', $password);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email.";
    } elseif (!isValidPassword($password)) {
        $message = "Weak password. Must be 8+ characters with letters, numbers, and special characters.";
    } elseif ($role == "SUPER_ADMIN" && $email != $SUPER_ADMIN_EMAIL) {
        $message = "Only $SUPER_ADMIN_EMAIL can register as SUPER_ADMIN.";
    } elseif ($role == "ADMIN" && $security_key !== $ADMIN_SECURITY_KEY) {
        $message = "Invalid security key for Admin.";
    } else {
        $stmt = $conn->prepare("INSERT INTO User (Name, Email, Password, Role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;

            echo "<script>
                alert('Registered Successfully!');
                window.location.href = '../index.php';
            </script>";
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="/lms/css/register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <select name="role">
                <option value="USER">User</option>
                <option value="ADMIN">Admin</option>
                <option value="SUPER_ADMIN">Super_Admin</option>
            </select><br>
            <input type="text" name="security_key" placeholder="Security Key (Admin/Super Admin)"><br>
            <input type="submit" value="Register">
        </form>

        <p class="message"><?= $message ?></p>

        <div class="back-btn">
            <form action="/lms/index.php" method="get">
                <button type="submit">🔙 Back</button>
            </form>
        </div>
    </div>
</body>
</html>
