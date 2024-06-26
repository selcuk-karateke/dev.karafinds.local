<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['score'])) {
        $score = intval($_POST['score']);
        $_SESSION['score'] = $score;

$mysqli = new mysqli('localhost', USERNAME, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_error);
}

$stmt = $mysqli->prepare("INSERT INTO highscores (score) VALUES (?)");
$stmt->bind_param('i', $score);
$stmt->execute();
$stmt->close();
$mysqli->close();
    }
}

header('Location: game.php');
exit();
?>
