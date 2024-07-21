<?php
// Beispielhaftes PHP-Skript, das Serverinformationen zurückgibt
header('Content-Type: application/json');

// Funktion zum Abrufen der Systeminformationen
function getSystemInfo()
{
    // Direkt abrufen, anstatt von phpinfo() zu extrahieren
    $fcgi = (strpos(php_sapi_name(), 'cgi') !== false) ? 'On' : 'Off';
    $max_execution_time = ini_get('max_execution_time');
    $php_version = phpversion();
    $db_version = defined('PDO::ATTR_CLIENT_VERSION') ? PDO::ATTR_CLIENT_VERSION : 'Unknown';
    $safe_mode = ini_get('safe_mode') ? 'On' : 'Off';
    $memory_limit = ini_get('memory_limit');
    $memory_usage = memory_get_usage(true) / 1024 / 1024 . ' MB';
    $memory_peak_usage = memory_get_peak_usage(true) / 1024 / 1024 . ' MB';
    $pdo_enabled = extension_loaded('PDO') ? 'enabled' : 'disabled';
    $curl_enabled = extension_loaded('curl') ? 'enabled' : 'disabled';
    $zlib_enabled = extension_loaded('zlib') ? 'enabled' : 'disabled';
    $is_multisite = '0'; // Da es sich nicht um eine WordPress-Umgebung handelt, setzen wir dies auf '0'
    $server_ip = gethostbyname(gethostname());

    return [
        'FCGI' => $fcgi,
        'Max Execution Time' => $max_execution_time,
        'PHP Version' => $php_version,
        'DB Version' => $db_version,
        'Safe Mode' => $safe_mode,
        'Memory Limit' => $memory_limit,
        'Memory Get Usage' => $memory_usage,
        'Memory Peak Usage' => $memory_peak_usage,
        'PDO Enabled' => $pdo_enabled,
        'Curl Enabled' => $curl_enabled,
        'Zlib Enabled' => $zlib_enabled,
        'Is Multisite' => $is_multisite,
        'Server IP' => $server_ip
    ];
}

// Funktion zum Speichern des Verzeichnisinhalts als Textdateien
function saveDirectoryAsText($sourceDir, $targetDir, &$fileStructure, $currentPath = '')
{
    $excludedDirs = ['.git', 'backup', 'node_modules', 'vendor', 'logs', 'download'];
    $excludedFiles = ['favicon.ico', '.gitignore', 'textify.php', 'config.php', 'database_credentials.php'];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($sourceDir) + 1);

        $excludeFile = false;
        foreach ($excludedDirs as $exclude) {
            if (strpos($relativePath, $exclude . DIRECTORY_SEPARATOR) === 0) {
                $excludeFile = true;
                break;
            }
        }

        if ($excludeFile || in_array(basename($filePath), $excludedFiles)) {
            continue;
        }

        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $relativePath . '.txt';
        if (!is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0777, true);
        }

        file_put_contents($targetPath, file_get_contents($filePath));

        $pathParts = explode(DIRECTORY_SEPARATOR, $relativePath);
        $lastPart = array_pop($pathParts);
        $subPath = &$fileStructure;

        foreach ($pathParts as $part) {
            if (!isset($subPath[$part])) {
                $subPath[$part] = [];
            }
            $subPath = &$subPath[$part];
        }
        $subPath[$lastPart] = 'file';
    }
}

// Beispielhafte Verwendung der Funktion getSystemInfo
$systemInfo = getSystemInfo();
echo json_encode($systemInfo);
