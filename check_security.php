<?php
require_once 'classes/SecurityMonitor.php';

$host = 'w01bcb0d.kasserver.com'; // Der entfernte WordPress-Server
$port = 22; // Standard-SSH-Port
$username = 'ssh-w01bcb0d';
$password = 'JPw9vZ6uBJBLzDMzqZRF';
$directory = '/www/htdocs/w01bcb0d/dev.karafinds.com'; // Verzeichnis der WordPress-Seite auf dem entfernten Server

try {
    $monitor = new Karatekes\SecurityMonitor($host, $port, $username, $password, $directory);
    $status = $monitor->malwareScan();
    echo nl2br($status);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
