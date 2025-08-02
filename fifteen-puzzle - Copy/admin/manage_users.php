<?php
session_start();
require '../php/db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php");
    exit;
}

// Fetch users
$result = $conn->query("SELECT id, username, is_admin, created_at FROM users");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
</head>
<body>
  <h1>Manage Users</h1>
  <a href="dashboard.php">← Back to Dashboard</a>
  <table border="1" cellpadding="5">
    <tr><th>ID</th><th>Username</th><th>Admin</th><th>Created</th><th>Action</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= $row['is_admin'] ? 'Yes' : 'No' ?></td>
      <td><?= $row['created_at'] ?></td>
      <td>
        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
