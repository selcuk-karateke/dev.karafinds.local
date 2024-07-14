<?php

require_once '../classes/LogTrafficMonitor.php';

// Prüfen, ob die URL als Parameter übergeben wurde
if (isset($_GET['url'])) {
    // $logFile = '/var/log/nginx/access.log'; 
    // $logFile = 'C:\wamp\logs\access.log';
    $logFile = 'C:\xampp\apache\logs\access.log'; // Pfad zur Server-Log-Datei
    $monitor = new LogTrafficMonitor($logFile);
    $logTraffic = $monitor->checkLogTraffic();

    echo json_encode(["log_traffic" => $logTraffic]);
} else {
    // Fehlerfall, wenn keine URL übergeben wurde
    echo json_encode(['error' => 'No URL provided']);
}
