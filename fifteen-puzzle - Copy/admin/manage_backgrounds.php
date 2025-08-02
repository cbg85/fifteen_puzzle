<?php
session_start();
require '../php/db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit;
}

$upload_dir = '../images/backgrounds/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bg'])) {
    $file = $_FILES['bg'];
    $target = $upload_dir . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $target);
    echo "<p>✅ Upload successful!</p>";
}

// Delete image
if (isset($_GET['delete'])) {
    $file = basename($_GET['delete']);
    $target = $upload_dir . $file;
    if (file_exists($target)) {
        unlink($target);
        echo "<p>🗑️ Deleted $file</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Backgrounds</title>
</head>
<body>
  <h1>Manage Background Images</h1>
  <a href="dashboard.php">← Back to Dashboard</a>

  <form method="POST" enctype="multipart/form-data">
    <input type="file" name="bg" required>
    <button type="submit">Upload</button>
  </form>

  <h3>Available Backgrounds:</h3>
  <ul>
    <?php
    foreach (glob($upload_dir . "*.{jpg,jpeg,png}", GLOB_BRACE) as $file):
        $filename = basename($file);
    ?>
      <li>
        <?= $filename ?>
        <a href="?delete=<?= urlencode($filename) ?>" onclick="return confirm('Delete this image?')">🗑️ Delete</a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
