<?php
require_once 'classes/UpdatesMonitor.php';

$siteUrl = 'https://example.com'; // Die URL der zu überwachenden WordPress-Seite
$username = 'your-username'; // WordPress-Benutzername mit REST API-Zugriff
$password = 'your-password'; // WordPress-Passwort mit REST API-Zugriff

$monitor = new UpdatesMonitor($siteUrl, $username, $password);
$updates = $monitor->getUpdates();

$output = '<h5>Plugins:</h5>';
foreach ($updates['plugins'] as $plugin) {
    $output .= '<p>' . $plugin['name'] . ' - ' . ($plugin['update'] ? 'Update verfügbar' : 'Aktuell') . '</p>';
}

$output .= '<h5>Themes:</h5>';
foreach ($updates['themes'] as $theme) {
    $output .= '<p>' . $theme['name'] . ' - ' . ($theme['update'] ? 'Update verfügbar' : 'Aktuell') . '</p>';
}

echo $output;
