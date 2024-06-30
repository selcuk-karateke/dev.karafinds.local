<?php
require_once '../classes/AvailabilityMonitor.php';

// URL aus den GET-Parametern holen
$url = isset($_GET['url']) ? $_GET['url'] : null;

if ($url) {
    // $cacertPath = __DIR__ . DIRECTORY_SEPARATOR . "auth" . DIRECTORY_SEPARATOR . "cacert.pem";
    $cacertPath = __DIR__ . '/../auth/cacert.pem';
    $monitor = new Karatekes\AvailabilityMonitor($url, $cacertPath);
    $result = $monitor->check();

    $response = [$url => $result];
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Keine URL angegeben']);
}
