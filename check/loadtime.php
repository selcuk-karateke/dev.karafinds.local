<?php
require_once '../classes/LoadTimeMonitor.php';

// Prüfen, ob die URL als Parameter übergeben wurde
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $monitor = new Karatekes\LoadTimeMonitor($url);
    $loadTime = $monitor->getLoadTime();

    // Ergebnis als Array formatieren und in JSON umwandeln
    $response = [];
    $response[$url] = $loadTime;

    echo json_encode($response);
} else {
    // Fehlerfall, wenn keine URL übergeben wurde
    echo json_encode(['error' => 'No URL provided']);
}
