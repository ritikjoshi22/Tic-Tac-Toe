<?php

class Move
{
    private $conn;

    public function __construct()
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "test_db";

        try {
            // Create PDO connection instead of mysqli
            $this->conn = new PDO(
                "mysql:host=$host;dbname=$database",
                $username,
                $password
            );
            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Create a new game and return the game_id
    public function createNewGame()
    {
        $sql = "INSERT INTO Games (start_time) VALUES (NOW())";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Return the new game_id
        } else {
            throw new Exception("Failed to create a new game.");
        }
    }

    // Fetch all moves for a given game_id
    public function fetchMovesByGame($game_id)
    {
        $sql = "SELECT * FROM Moves WHERE game_id = :game_id ORDER BY move_number ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Fetch game details
    public function fetchGameDetails($game_id)
    {
        $sql = "SELECT * FROM Games WHERE game_id = :game_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveGame($player1_name, $player2_name, $is_computer = false)
    {
        $sql = "INSERT INTO Games (player1_name, player2_name, is_computer, start_time) 
                VALUES (:player1, :player2, :is_computer, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':player1', $player1_name, PDO::PARAM_STR);
        $stmt->bindParam(':player2', $player2_name, PDO::PARAM_STR);
        $stmt->bindParam(':is_computer', $is_computer, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            throw new Exception("Failed to save game.");
        }
    }

    public function saveMove($game_id, $position, $player)
    {
        $sql = "INSERT INTO Moves (game_id, position, player, move_time) 
                VALUES (:game_id, :position, :player, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->bindParam(':position', $position, PDO::PARAM_INT);
        $stmt->bindParam(':player', $player, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateGameWinner($game_id, $winner)
    {
        try {
            $sql = "UPDATE Games SET winner = :winner, end_time = NOW() WHERE game_id = :game_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':winner', $winner, PDO::PARAM_STR);
            $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating game winner: " . $e->getMessage());
            return false;
        }
    }
}
