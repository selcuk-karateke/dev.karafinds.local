<?php
require_once '../classes/SecurityMonitor.php';

$host = $_GET['host'];
$port = isset($_GET['port']) ? (int)$_GET['port'] : 22; // Standardwert 22
$username = $_GET['user'];
$password = $_GET['pass'];
$directory = $_GET['path'];

try {
    $monitor = new Karatekes\SecurityMonitor($host, $username, $password, $directory, $port);
    $result = $monitor->malwareScan();
    $jsonData = json_encode(['data' => $result]);
    echo $jsonData;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
