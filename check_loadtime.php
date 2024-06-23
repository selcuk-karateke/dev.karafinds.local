<?php
require_once 'classes/LoadTimeMonitor.php';

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

$loadTimes = [];
foreach ($urls as $url) {
    $monitor = new Karatekes\LoadTimeMonitor($url);
    $loadTimes[$url] = $monitor->getLoadTime();
}

echo json_encode($loadTimes);
