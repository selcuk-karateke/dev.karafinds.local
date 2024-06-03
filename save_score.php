<?php
session_start();
$score = $_POST['score'];

$mysqli = new mysqli('localhost', 'root', '', 'game_db');

if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_error);
}

$stmt = $mysqli->prepare("INSERT INTO highscores (score) VALUES (?)");
$stmt->bind_param('i', $score);
$stmt->execute();
$stmt->close();
$mysqli->close();

$_SESSION['score'] = 0;
header('Location: game.php');
?>
