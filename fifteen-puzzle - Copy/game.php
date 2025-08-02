<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fifteen Puzzle</title>
  <link rel="stylesheet" href="css/game.css">
</head>
<body>
  <h1>Fifteen Puzzle</h1>
  <div id="controls">
    <button onclick="shuffleTiles()">Shuffle</button>
    <label for="backgroundSelector">Choose Background:</label>
    <select id="backgroundSelector" onchange="changeBackground()">
      <option value="background1.jpg">Background 1</option>
      <option value="background2.jpg">Background 2</option>
    </select>
    <p>Moves: <span id="moveCount">0</span></p>
    <p>Time: <span id="timer">0s</span></p>
  </div>
  
  <div id="puzzle" class="puzzle"></div>

  <script src="js/game.js">
    fetch('php/get_backgrounds.php')
    .then(res => res.json())
    .then(images => {
      const selector = document.getElementById("backgroundSelector");
      selector.innerHTML = ""; // clear existing options
      images.forEach(filename => {
        const option = document.createElement("option");
        option.value = filename;
        option.textContent = filename;
        selector.appendChild(option);
    });

    // Optionally set the first image as default
    if (images.length > 0) {
      background = images[0];
      createTiles(); // re-render tiles with new background
    }
  });

  </script>
</body>
</html>
