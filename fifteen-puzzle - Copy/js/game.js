const puzzle = document.getElementById("puzzle");
const moveCounter = document.getElementById("moveCount");
const timerDisplay = document.getElementById("timer");
let moveCount = 0;
let time = 0;
let timerInterval;
let background = "background1.jpg";

// Initialize puzzle with numbers 1 to 15 + empty
let tiles = [...Array(15).keys()].map(i => i + 1);
tiles.push(null);

function createTiles() {
  puzzle.innerHTML = '';
  tiles.forEach((value, index) => {
    const tile = document.createElement("div");
    tile.classList.add("tile");
    tile.dataset.index = index;
    if (value) {
        tile.textContent = value;
        tile.style.backgroundImage = `url("images/backgrounds/${background}")`; // ✅ new

        // Calculate correct image piece based on the *value*, not index
        const originalIndex = value - 1;
        const bgRow = Math.floor(originalIndex / 4);
        const bgCol = originalIndex % 4;

        tile.style.backgroundPosition = `-${bgCol * 100}px -${bgRow * 100}px`;
}
 else {
      tile.classList.add("empty");
    }
    tile.addEventListener("click", () => moveTile(index));
    puzzle.appendChild(tile);
  });
}

function moveTile(index) {
  const emptyIndex = tiles.indexOf(null);
  const validMoves = [
    emptyIndex - 4, // up
    emptyIndex + 4, // down
    (emptyIndex % 4 !== 0) ? emptyIndex - 1 : null, // left
    (emptyIndex % 4 !== 3) ? emptyIndex + 1 : null  // right
  ];

  if (validMoves.includes(index)) {
    [tiles[emptyIndex], tiles[index]] = [tiles[index], tiles[emptyIndex]];
    moveCount++;
    moveCounter.textContent = moveCount;
    createTiles();
    if (checkWin()) alert("🎉 You solved the puzzle!");
  }
}

function shuffleTiles() {
  do {
    tiles = shuffleArray(tiles);
  } while (!isSolvable(tiles) || checkWin());

  moveCount = 0;
  time = 0;
  clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    time++;
    timerDisplay.textContent = time + "s";
  }, 1000);
  moveCounter.textContent = moveCount;
  createTiles();
}

function shuffleArray(arr) {
  let a = arr.slice();
  for (let i = a.length - 1; i > 0; i--) {
    let j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

function isSolvable(puzzleArray) {
  const flat = puzzleArray.filter(n => n !== null);
  let inversions = 0;
  for (let i = 0; i < flat.length; i++) {
    for (let j = i + 1; j < flat.length; j++) {
      if (flat[i] > flat[j]) inversions++;
    }
  }
  const emptyRow = Math.floor(puzzleArray.indexOf(null) / 4);
  return (inversions + emptyRow) % 2 === 0;
}

function checkWin() {
  for (let i = 0; i < 15; i++) {
    if (tiles[i] !== i + 1) return false;
  }

  clearInterval(timerInterval);
  saveGameStats(moveCount, time); // Send stats to PHP

  setTimeout(() => {
    alert("🎉 Puzzle completed!");
  }, 100);
  return true;
}

function changeBackground() {
  const selector = document.getElementById("backgroundSelector");
  background = selector.value;
  createTiles(); // re-render puzzle with new background
}


window.onload = () => {
  createTiles();
}

function saveGameStats(moves, time) {
  fetch("php/save_stats.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `moves=${moves}&time=${time}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      console.log("✅ Game stats saved!");
    } else {
      console.error("❌ Failed to save stats:", data.error);
    }
  })
  .catch(err => console.error("❌ Fetch error:", err));
};
