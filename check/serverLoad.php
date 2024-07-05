<?php
require_once '../classes/ServerLoadMonitor.php';

$url = $_GET['url'];
$host = $_GET['host'];
$port = isset($_GET['port']) ? (int)$_GET['port'] : 22; // Standardwert 22
$user = $_GET['user'];
$pass = $_GET['pass'];

$monitor = new ServerLoadMonitor($url, $host, $port, $user, $pass);
// $load = $monitor->getLoad();
$load = $monitor->getLoadLocal();
// $loadAPI = $monitor->getLoadOverAPI(); // TODO: check\serverLoad.php muss auf dem remote Rechner, wo die Webseite ist ins root, muss aufrufbar sein

echo json_encode(["load" => $load, "api" => $loadAPI]);
