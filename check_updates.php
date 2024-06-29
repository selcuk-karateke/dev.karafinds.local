<?php
require_once 'classes/UpdatesMonitor.php';
// Download the plugin into your plugins directory
// https://github.com/WP-API/Basic-Auth

// URL aus den GET-Parametern holen
$url = isset($_GET['url']) ? $_GET['url'] : null;
$user_api = isset($_GET['user_api']) ? $_GET['user_api'] : null;
$pass_api = isset($_GET['pass_api']) ? $_GET['pass_api'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

if (isset($_GET['type']) && $_GET['type'] == 'wordpress') {
    $url = $_GET['url'];
    $cacertPath = __DIR__ . DIRECTORY_SEPARATOR . "auth" . DIRECTORY_SEPARATOR . "cacert.pem";

    $monitor = new UpdatesMonitor($url, $user_api, $pass_api);
    $updates = $monitor->getUpdates();

    if (isset($updates['error'])) {
        echo '<p class="text-danger">Fehler beim Abrufen der Updates: ' . htmlspecialchars($updates['error']) . '</p>';
        exit;
    }

    // // Debug-Ausgabe der rohen Daten
    // echo '<pre>';
    // print_r($updates);
    // echo '</pre>';

    $output = '<h5>Plugins:</h5><ul class="list-group mb-3">';
    if (!empty($updates['plugins'])) {
        foreach ($updates['plugins'] as $plugin) {
            if (is_array($plugin) && isset($plugin['name']) && is_string($plugin['name'])) {
                $pluginName = htmlspecialchars($plugin['name']);
                $currentVersion = htmlspecialchars($plugin['version']);
                $slug = strtolower(explode('/', $plugin['plugin'])[0]);
                $latestVersion = get_latest_version('plugin', $slug);
                $pluginStatus = version_compare($currentVersion, $latestVersion, '<') ? 'Update verfügbar' : 'Aktuell';
                $output .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
                $output .= "$pluginName (Aktuelle Version: $currentVersion, Neueste Version: $latestVersion) - ";
                $output .= '<span class="badge bg-' . ($pluginStatus === 'Update verfügbar' ? 'warning' : 'success') . '">' . htmlspecialchars($pluginStatus) . '</span></li>';
            } else {
                $output .= '<li class="list-group-item text-danger">Plugin-Datenformat unbekannt: ' . htmlspecialchars(print_r($plugin, true)) . '</li>';
            }
        }
    } else {
        $output .= '<li class="list-group-item">Keine Plugins gefunden.</li>';
    }
    $output .= '</ul>';

    $output .= '<h5>Themes:</h5><ul class="list-group">';
    if (!empty($updates['themes'])) {
        foreach ($updates['themes'] as $theme) {
            if (is_array($theme) && isset($theme['name']) && is_array($theme['name']) && isset($theme['name']['rendered'])) {
                $themeName = htmlspecialchars($theme['name']['rendered']);
                $currentVersion = htmlspecialchars($theme['version']);
                $slug = strtolower($theme['stylesheet']);
                $latestVersion = get_latest_version('theme', $slug);
                $themeStatus = version_compare($currentVersion, $latestVersion, '<') ? 'Update verfügbar' : 'Aktuell';
                $output .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
                $output .= "$themeName (Aktuelle Version: $currentVersion, Neueste Version: $latestVersion) - ";
                $output .= '<span class="badge bg-' . ($themeStatus === 'Update verfügbar' ? 'warning' : 'success') . '">' . htmlspecialchars($themeStatus) . '</span></li>';
            } else {
                $output .= '<li class="list-group-item text-danger">Theme-Datenformat unbekannt: ' . htmlspecialchars(print_r($theme, true)) . '</li>';
            }
        }
    } else {
        $output .= '<li class="list-group-item">Keine Themes gefunden.</li>';
    }
    $output .= '</ul>';

    echo $output;
} else {
    echo '<p class="text-danger">Typ ist kein Wordpress: ' . htmlspecialchars($updates['error']) . '</p>';
}
/**
 * Placeholder-Funktion, um die neueste Version eines Plugins oder Themes abzurufen.
 */
function get_latest_version($type, $slug)
{
    if ($type == 'plugin') {
        $url = "https://api.wordpress.org/plugins/info/1.0/$slug.json";
    } elseif ($type == 'theme') {
        $url = "https://api.wordpress.org/themes/info/1.1/?action=theme_information&request[slug]=$slug";
    }

    $response = @file_get_contents($url); // Suppress warnings with @
    if ($response) {
        $data = json_decode($response, true);
        if ($type == 'plugin' && isset($data['version'])) {
            return $data['version'];
        } elseif ($type == 'theme' && isset($data['version'])) {
            return $data['version'];
        }
    }
    return '0.0.0';
}
