<?php
echo "<center>";

// Board initialize (GET values ya default "")
$bh = [
    1 => $_GET['bh1'] ?? "",
    2 => $_GET['bh2'] ?? "",
    3 => $_GET['bh3'] ?? "",
    4 => $_GET['bh4'] ?? "",
    5 => $_GET['bh5'] ?? "",
    6 => $_GET['bh6'] ?? "",
    7 => $_GET['bh7'] ?? "",
    8 => $_GET['bh8'] ?? "",
    9 => $_GET['bh9'] ?? "",
];

// Buttons enable by default
$btn = array_fill(1, 9, "enabled");

$winner = "";
$gameOver = false;

// Check if player clicked any button
for ($i = 1; $i <= 9; $i++) {
    if (isset($_GET["b$i"]) && $bh[$i] == "") {
        $bh[$i] = "X"; // Player move
        break;
    }
}

// Function to check winner
function checkWinner($b) {
    $wins = [
        [1,2,3],[4,5,6],[7,8,9], // rows
        [1,4,7],[2,5,8],[3,6,9], // cols
        [1,5,9],[3,5,7]          // diagonals
    ];
    foreach ($wins as $w) {
        if ($b[$w[0]] != "" && $b[$w[0]] == $b[$w[1]] && $b[$w[1]] == $b[$w[2]]) {
            return $b[$w[0]];
        }
    }
    return "";
}

// Check winner after player move
$winner = checkWinner($bh);
if ($winner != "") {
    $gameOver = true;
}

// Computer move (only if game not over)
if (!$gameOver) {
    $emptyCells = [];
    foreach ($bh as $key => $val) {
        if ($val == "") $emptyCells[] = $key;
    }
    if (!empty($emptyCells)) {
        $compChoice = $emptyCells[array_rand($emptyCells)];
        $bh[$compChoice] = "O";
    }

    // Check winner again after computer move
    $winner = checkWinner($bh);
    if ($winner != "") {
        $gameOver = true;
    }
}

// Disable filled buttons
for ($i = 1; $i <= 9; $i++) {
    if ($bh[$i] != "") $btn[$i] = "disabled";
}

// HTML + Game Board
echo "
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Tic Tac Toe</title>
    <style>
        body { background: chocolate; text-align: center; font-family: Arial; }
        h1 { color: blue; background: white; padding: 5px; border-radius: 10px; display:inline-block; }
        .buttons {
            width:80px; height:70px; margin:5px;
            background: darkgray; color:red; font-size:40px;
            font-weight:bold; border-radius: 10px; cursor: pointer;
        }
        h2 { color:green; background:white; padding:10px; border-radius:10px; }
        .restart { background:red; color:white; padding:10px; border-radius:5px; }
    </style>
</head>
<body>
    <h1>(Player vs Computer)</h1>
    <div>
        <form method='GET' action=''>
";

// Hidden fields to store board
for ($i = 1; $i <= 9; $i++) {
    echo "<input type='hidden' name='bh$i' value='{$bh[$i]}'>";
}

// Buttons layout (3x3)
for ($i = 1; $i <= 9; $i++) {
    echo "<input type='submit' class='buttons' name='b$i' value='{$bh[$i]}' {$btn[$i]}>";
    if ($i % 3 == 0) echo "<br>";
}

echo "
        </form>
    </div>
";

// Show winner
if ($winner != "") {
    echo "<h2>🎉 $winner Wins!</h2>";
    echo "<a href='tic_tac_game.php'><button class='restart'>Restart Game</button></a>";
} elseif (!in_array("", $bh)) {
    echo "<h2>😅 It's a Draw!</h2>";
    echo "<a href='tic_tac_game.php'><button class='restart'>Restart Game</button></a>";
}

echo "</body></html>";
?>
