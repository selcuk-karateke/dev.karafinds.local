<?php
require_once '../classes/DatabasePerformanceMonitor.php';

$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$monitor = new DatabasePerformanceMonitor($pdo);
$queryCount = $monitor->getQueryCount();

echo "Anzahl der Abfragen: " . $queryCount;
