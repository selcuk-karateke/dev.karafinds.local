<?php
require_once(__DIR__ . '/../classes/UpdatesMonitor.php');
require_once(__DIR__ . '/../classes/Logger.php');
require_once(__DIR__ . '/../classes/ConfigLoader.php');

// Download the WordPress plugin into your plugins directory
// https://github.com/WP-API/Basic-Auth

$logger = new Karatekes\Logger(__DIR__ . '/../logs/custom.log');
$logger->log("Skript gestartet", 'info');

// Erkenne, ob das Skript über CLI oder HTTP aufgerufen wird
$cli = (php_sapi_name() === "cli");
// Variable zum Speichern der Parameter, die entweder von CLI oder GET/POST kommen
$params = [];
if ($cli) {
    // CLI Modus
    parse_str(implode('&', array_slice($argv, 1)), $params);
} else {
    // HTTP Modus (GET oder POST)
    $params = $_REQUEST; // $_REQUEST umfasst sowohl $_GET als auch $_POST
}

// Verbindung zur Datenbank herstellen
$pdo = new PDO('mysql:host=localhost;dbname=db_main', 'root', '');

// Konfiguration laden
try {
    $configLoader = new Karatekes\ConfigLoader($pdo);
    $websites = $configLoader->getWebsites();
} catch (Exception $e) {
    $logger->log($e->getMessage(), 'error');
    exit("Fehler beim Laden der Konfiguration: " . $e->getMessage());
}

function handleUpdateCheck($website, $logger, $configLoader)
{
    // API-Daten aus der Datenbank abrufen
    $apiAccount = $configLoader->getApiAccount($website['id']);
    $user_api = isset($apiAccount['user']) ? $apiAccount['user'] : '';
    $pass_api = isset($apiAccount['pass']) ? $apiAccount['pass'] : '';

    // Erstellt eine Instanz von UpdatesMonitor mit den erforderlichen Parametern.
    $monitor = new UpdatesMonitor($website['url'], $user_api, $pass_api);

    // Ruft Updates von der Monitor-Instanz ab.
    $updates = $monitor->getUpdates();

    // Überprüft, ob ein Fehler in den Updates vorhanden ist.
    if (isset($updates['error'])) {
        // Loggt den Fehler mit der entsprechenden URL und Fehlermeldung.
        $logger->log("Fehler bei {$website['url']}: {$updates['error']}", 'error');
        return false;  // Rückgabe von false, um die Ausführung fortzusetzen ohne zu beenden.
    }

    // Verarbeitet die Plugins, falls keine Fehler aufgetreten sind.
    processPlugins($updates['plugins'], $website, $configLoader, $logger);

    return true;  // Gibt true zurück, wenn alles erfolgreich war.
}

// Funktion zur Verarbeitung von Plugins
function processPlugins($plugins, $website, $configLoader, $logger)
{
    foreach ($plugins as $plugin) {
        // Verarbeitet jedes Plugin entsprechend
        $logger->log("Verarbeite Plugin {$plugin['name']} für {$website['url']}", 'info');
        // Hier kann zusätzliche Logik zur Plugin-Verarbeitung eingefügt werden
    }
}

foreach ($websites as $website) {
    if (!handleUpdateCheck($website, $logger, $configLoader)) {
        continue;  // Fortsetzung mit der nächsten Website im Fehlerfall
    }
}

if (!$cli && isset($_GET['type']) && $_GET['type'] == 'wordpress') {
    // HTTP-Modus: Verarbeitet die Anfrage und gibt die Ergebnisse als HTML aus
    $url = isset($_GET['url']) ? $_GET['url'] : null;
    $user_api = isset($_GET['user_api']) ? $_GET['user_api'] : null;
    $pass_api = isset($_GET['pass_api']) ? $_GET['pass_api'] : null;

    $monitor = new UpdatesMonitor($url, $user_api, $pass_api);
    $updates = $monitor->getUpdates();

    if (isset($updates['error'])) {
        echo '<p class="text-danger">Fehler beim Abrufen der Updates: ' . htmlspecialchars($updates['error']) . '</p>';
        exit;
    }

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

    $jsonData = json_encode(['data' => $output]);
    echo $jsonData;
} elseif ($cli) {
    // CLI-Modus: Verarbeitet die Konfiguration und aktualisiert config.json
    foreach ($websites as $website) {
        $monitor = new UpdatesMonitor($website['url'], $website['api'][0]['user'], $website['api'][0]['pass']);
        $updates = $monitor->getUpdates();
        $hash = md5($website['url']);
        $stmt = $pdo->prepare('UPDATE websites SET updates = :updates WHERE id = :id');
        $stmt->execute(['updates' => 0, 'id' => $website['id']]);  // Setzt den Zähler zurück

        if (isset($updates['error'])) {
            $logger->log("Fehler bei {$website['url']}: {$updates['error']}", 'error');
            continue;
        }

        // Prüfe auf verfügbare Updates bei Plugins
        if (!empty($updates['plugins'])) {
            $updatesCount = (int) $website['updates'];  // Holt den aktuellen Zählerwert aus der Datenbank
            foreach ($updates['plugins'] as $plugin) {
                if (is_array($plugin) && isset($plugin['name']) && is_string($plugin['name'])) {
                    $pluginName = htmlspecialchars($plugin['name']);
                    $currentVersion = htmlspecialchars($plugin['version']);
                    $slug = strtolower(explode('/', $plugin['plugin'])[0]);
                    $latestVersion = get_latest_version('plugin', $slug);
                    $pluginStatus = version_compare($currentVersion, $latestVersion, '<') ? 'Update verfügbar' : 'Aktuell';

                    if ($pluginStatus === 'Update verfügbar') {
                        $updatesCount++;  // Inkrementiert den Zähler
                        $stmt = $pdo->prepare('UPDATE websites SET updates = :updates WHERE id = :id');
                        $stmt->execute(['updates' => $updatesCount, 'id' => $website['id']]);  // Aktualisiert den Zählerwert in der Datenbank
                    }
                }
            }
        }
    }
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
