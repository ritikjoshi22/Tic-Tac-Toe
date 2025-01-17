<?php

class MoveController
{
    private $moveModel;

    public function __construct()
    {
        $this->moveModel = new Move();
    }

    public function startGame()
    {
        include __DIR__ . '/../views/game_form.php';
    }

    public function createGame()
    {
        $player1 = $_POST['player1_name'];
        $player2 = $_POST['player2_name'];
        $is_computer = $_POST['game_mode'] === 'vs_computer';

        if ($is_computer) {
            $player2 = 'Computer';
        }

        $game_id = $this->moveModel->saveGame($player1, $player2, $is_computer);
        $_SESSION['game_id'] = $game_id;
        $_SESSION['player1'] = $player1;
        $_SESSION['player2'] = $player2;
        $_SESSION['is_computer'] = $is_computer;

        header('Location: index.php?action=play_game');
        exit();
    }

    public function makeMove()
    {
        $position = $_POST['position'];
        $player = $_POST['player'];
        $game_id = $_SESSION['game_id'];

        $this->moveModel->saveMove($game_id, $position, $player);

        // Check if winner or draw is sent
        if (isset($_POST['winner'])) {
            $winner = $_POST['winner'];
            $this->moveModel->updateGameWinner($game_id, $winner);
        } else if (isset($_POST['draw']) && $_POST['draw'] === 'true') {
            $this->moveModel->updateGameWinner($game_id, 'Draw');
        }

        echo json_encode(['success' => true]);
    }

    public function playGame()
    {
        include __DIR__ . '/../views/game.php';
    }
}
