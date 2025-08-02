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
      const row = Math.floor(index / 4);
      const col = index % 4;
      tile.style.backgroundImage = `url("images/${background}")`;
      tile.style.backgroundPosition = `-${col * 100}px -${row * 100}px`;
    } else {
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
  return true;
}

function changeBackground() {
  background = document.getElementById("backgroundSelector").value;
  createTiles();
}

window.onload = () => {
  createTiles();
};
