<?php
require_once 'classes/AvailabilityMonitor.php';

$urls = [
    'https://karateke-webdesign.de',
    'https://karatekes.com/',
    'https://f35performance.com/',
    'https://fixrepairberlin.de/',
    'https://faceart-berlin.de/',
    'https://alpaysolar.de/',
    'https://alenrec-reinigung.de/',
    'http://kosmetik61.de/',
];

$cacertPath = __DIR__ . DIRECTORY_SEPARATOR . "auth" . DIRECTORY_SEPARATOR . "cacert.pem";
$results = [];

foreach ($urls as $url) {
    $monitor = new Karatekes\AvailabilityMonitor($url, $cacertPath);
    $results[$url] = $monitor->check();
}

echo json_encode($results);
