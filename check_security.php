<?php
require_once 'classes/SecurityMonitor.php';

$url = $_GET['url'] ?? null;
$path = $_GET['path'] ?? null;
$host = $_GET['host'] ?? null;
$port = $_GET['port'] ?? null;
$user = $_GET['user'] ?? null;
$pass = $_GET['pass'] ?? null;

if (!$path) {
    echo "Invalid Path";
    exit;
}

try {
    $monitor = new Karatekes\SecurityMonitor($host, $port, $user, $pass, $path);
    $status = $monitor->malwareScan();
    // echo nl2br($status);
    echo $status;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
