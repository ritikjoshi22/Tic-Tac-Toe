<?php
session_start();
require_once __DIR__ . '/app/models/Move.php';
require_once __DIR__ . '/app/controllers/MoveController.php';

$moveController = new MoveController();

$action = $_GET['action'] ?? 'start';

switch ($action) {
    case 'start':
        $moveController->startGame();
        break;
    case 'create_game':
        $moveController->createGame();
        break;
    case 'make_move':
        $moveController->makeMove();
        break;
    case 'play_game':
        $moveController->playGame();
        break;
    default:
        $moveController->startGame();
}
