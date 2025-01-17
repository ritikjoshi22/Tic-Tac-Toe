<!DOCTYPE html>
<html>

<head>
    <title>Tic Tac Toe</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            height: 50vh;
        }
        h1{
            background-color: red;
            color: white;
            text-align: center;
        }
        label{
            color: white;
        }

        form{
            background-color: black;
            padding: 20px;
            box-shadow: -10px 10px 10px gray;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"] {
            padding: 5px;
            width: 200px;
        }
        select{
            height: 30px;
            width: 215px;
            text-align: center;
            margin-left: 15px;
        }

        button {
            padding: 10px 20px;
            border:none;
            background-color: green;
            color: white;
            font-weight: bold;
            font-size: 15px;
            margin-left: 100px;
            border-radius: 50px;
        }
        button:hover{
            background-color: darkgreen;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="index.php?action=create_game" method="POST">
        <h1>Tic Tac Toe</h1>
            <div class="form-group">
                <label>Player 1 Name:</label>
                <input type="text" name="player1_name" required>
            </div>

            <div class="form-group">
                <label>Game Mode:</label>
                <select name="game_mode" id="game_mode">
                    <option value="two_player">Two Players</option>
                    <option value="vs_computer">VS Computer</option>
                </select>
            </div>

            <div class="form-group" id="player2_field">
                <label>Player 2 Name:</label>
                <input type="text" name="player2_name">
            </div>

            <button type="submit">Start Game</button>
        </form>
    </div>

    <script>
        document.getElementById('game_mode').addEventListener('change', function() {
            const player2Field = document.getElementById('player2_field');
            player2Field.style.display = this.value === 'vs_computer' ? 'none' : 'block';
        });
    </script>
</body>

</html>