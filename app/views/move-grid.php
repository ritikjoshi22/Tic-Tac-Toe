<?php require_once "moveController.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game #<?php echo isset($gameDetails['game_id']) ? $gameDetails['game_id'] : ''; ?></title>
    <style>
        .grid { display: grid; grid-template-columns: repeat(3, 100px); gap: 5px; }
        .cell { width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; border: 1px solid black; font-size: 24px; }
        .start-game { margin: 20px; padding: 10px 20px; background-color: #28a745; color: #fff; border: none; cursor: pointer; }
        .start-game:hover { background-color: #218838; }
    </style>
</head>

<body>
    <h1>Tic-Tac-Toe Game</h1>

    <?php if (isset($gameDetails)): ?>
        <h2>Game ID: <?php echo $gameDetails['game_id']; ?></h2>
        <p>Start Time: <?php echo $gameDetails['start_time']; ?></p>
        <p>End Time: <?php echo $gameDetails['end_time'] ?? 'Ongoing'; ?></p>
        <p>Winner: <?php echo $gameDetails['winner'] ?? 'Undecided'; ?></p>

        <div class="grid">
            <?php
            $grid = array_fill(1, 9, ''); // Initialize empty grid
            foreach ($moves as $move) {
                $position = intval($move['coordinate']);
                $grid[$position] = $move['player'];
            }

            for ($i = 1; $i <= 9; $i++) {
                echo "<div class='cell'>" . ($grid[$i] ?? '') . "</div>";
            }
            ?>
        </div>
    <?php else: ?>
        <form method="POST" action="../controllers//moveController.php">
            <button type="submit" name="start_game" class="start-game">Start New Game</button>
        </form>
    <?php endif; ?>
</body>

</html>
