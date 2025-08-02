<?php
session_start();

// Restrict access
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
</head>
<body>
  <h1>Admin Dashboard</h1>

  <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</p>

  <ul>
    <li><a href="manage_users.php">Manage Users</a></li>
    <li><a href="manage_backgrounds.php">Manage Background Images</a></li>
    <li><a href="../logout.php">Logout</a></li>
  </ul>
</body>
</html>
