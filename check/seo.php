<?php
require_once '../classes/SEOMonitor.php';

$url = $_GET['url'];
$monitor = new SEOMonitor($url);
$metaTags = $monitor->getMetaTags();
$sitemap = $monitor->getXMLSitemap();
$robots = $monitor->getRobotsTxt();

echo json_encode([
    'title' => $metaTags['title'],
    'description' => $metaTags['description'],
    'sitemap' => $sitemap,
    'robots' => $robots
]);
