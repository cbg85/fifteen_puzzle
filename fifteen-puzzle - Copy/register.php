<!-- register.php -->
<?php
session_start();
require 'php/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);
        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Username may already be taken.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="POST">
  <input type="text" name="username" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Register</button>
</form>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<a href="login.php">Already have an account? Login</a>
</body>
</html>
