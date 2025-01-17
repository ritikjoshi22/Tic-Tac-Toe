<!DOCTYPE html>
<html>

<head>
    <title>Tic Tac Toe Game</title>
    <style>
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            gap: 5px;
            margin: 20px auto;
        }

        .cell {
            width: 100px;
            height: 100px;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            cursor: pointer;
        }

        .container {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tic Tac Toe</h1>
        <div id="player-info">
            <p>Player 1 (X): <?php echo htmlspecialchars($_SESSION['player1']); ?></p>
            <p>Player 2 (O): <?php echo htmlspecialchars($_SESSION['player2']); ?></p>
        </div>
        <div id="current-player"></div>
        <div class="board" id="board">
            <?php for ($i = 0; $i < 9; $i++): ?>
                <div class="cell" data-index="<?php echo $i; ?>"></div>
            <?php endfor; ?>
        </div>
        <div id="game-result"></div>
        <button onclick="location.href='index.php'">New Game</button>
    </div>

    <script>
        const board = document.getElementById('board');
        const cells = document.querySelectorAll('.cell');
        let currentPlayer = 'X';
        let gameBoard = Array(9).fill('');
        const isComputer = <?php echo json_encode($_SESSION['is_computer']); ?>;

        cells.forEach(cell => {
            cell.addEventListener('click', handleClick);
        });

        function handleClick(e) {
            const cell = e.target;
            const index = cell.dataset.index;

            if (gameBoard[index] === '' && !checkWinner()) {
                makeMove(index);

                if (isComputer && !checkWinner()) {
                    setTimeout(computerMove, 500);
                }
            }
        }

        function makeMove(index) {
            gameBoard[index] = currentPlayer;
            cells[index].textContent = currentPlayer;

            // Save move to database
            fetch('index.php?action=make_move', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `position=${index}&player=${currentPlayer}`
            });

            if (checkWinner()) {
                const winner = currentPlayer === 'X' ? '<?php echo $_SESSION['player1']; ?>' : '<?php echo $_SESSION['player2']; ?>';
                document.getElementById('game-result').textContent = `${winner} wins!`;

                // Send winner information in a separate request
                fetch('index.php?action=make_move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `winner=${encodeURIComponent(winner)}`
                });
            } else if (!gameBoard.includes('')) {
                document.getElementById('game-result').textContent = "It's a draw!";

                // Send draw information
                fetch('index.php?action=make_move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'draw=true'
                });
            }

            currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
        }

        function computerMove() {
            const emptyCells = gameBoard.reduce((acc, cell, index) => {
                if (cell === '') acc.push(index);
                return acc;
            }, []);

            if (emptyCells.length > 0) {
                const randomIndex = emptyCells[Math.floor(Math.random() * emptyCells.length)];
                makeMove(randomIndex);
            }
        }

        function checkWinner() {
            const winPatterns = [
                [0, 1, 2],
                [3, 4, 5],
                [6, 7, 8], // rows
                [0, 3, 6],
                [1, 4, 7],
                [2, 5, 8], // columns
                [0, 4, 8],
                [2, 4, 6] // diagonals
            ];

            return winPatterns.some(pattern => {
                const [a, b, c] = pattern;
                return gameBoard[a] && gameBoard[a] === gameBoard[b] && gameBoard[a] === gameBoard[c];
            });
        }
    </script>
</body>

</html>