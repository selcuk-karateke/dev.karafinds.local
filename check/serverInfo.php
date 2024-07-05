<?php
// TODO: Muss auf den remote Server, irgendwo im root und abrufbar sein
header('Content-Type: application/json');

$load = sys_getloadavg();
$memoryUsage = memory_get_usage();

$response = [
    'load' => $load[0],
    'memory_usage' => $memoryUsage
];

echo json_encode($response);
