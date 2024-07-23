<?php
header('Content-Type: application/json');

require '../vendor/autoload.php';

use phpseclib3\Net\SSH2;

function getSystemInfo($host, $port, $user, $pass)
{
    $ssh = new SSH2($host, $port);
    if (!$ssh->login($user, $pass)) {
        return ['error' => 'Login failed'];
    }

    $fcgi = $ssh->exec("php -r 'echo (strpos(php_sapi_name(), \"cgi\") !== false) ? \"On\" : \"Off\";'");
    $max_execution_time = $ssh->exec("php -r 'echo ini_get(\"max_execution_time\");'");
    $php_version = $ssh->exec("php -r 'echo phpversion();'");
    $db_version = $ssh->exec("php -r 'echo defined(\"PDO::ATTR_CLIENT_VERSION\") ? PDO::ATTR_CLIENT_VERSION : \"Unknown\";'");
    $safe_mode = $ssh->exec("php -r 'echo ini_get(\"safe_mode\") ? \"On\" : \"Off\";'");
    $memory_limit = $ssh->exec("php -r 'echo ini_get(\"memory_limit\");'");
    $memory_usage = $ssh->exec("php -r 'echo memory_get_usage(true) / 1024 / 1024 . \" MB\";'");
    $memory_peak_usage = $ssh->exec("php -r 'echo memory_get_peak_usage(true) / 1024 / 1024 . \" MB\";'");
    $pdo_enabled = $ssh->exec("php -r 'echo extension_loaded(\"PDO\") ? \"enabled\" : \"disabled\";'");
    $curl_enabled = $ssh->exec("php -r 'echo extension_loaded(\"curl\") ? \"enabled\" : \"disabled\";'");
    $zlib_enabled = $ssh->exec("php -r 'echo extension_loaded(\"zlib\") ? \"enabled\" : \"disabled\";'");
    $is_multisite = '0';
    $server_ip = $host;

    return [
        'FCGI' => trim($fcgi),
        'Max Execution Time' => trim($max_execution_time),
        'PHP Version' => trim($php_version),
        'DB Version' => trim($db_version),
        'Safe Mode' => trim($safe_mode),
        'Memory Limit' => trim($memory_limit),
        'Memory Get Usage' => trim($memory_usage),
        'Memory Peak Usage' => trim($memory_peak_usage),
        'PDO Enabled' => trim($pdo_enabled),
        'Curl Enabled' => trim($curl_enabled),
        'Zlib Enabled' => trim($zlib_enabled),
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

if (isset($_GET['host']) && isset($_GET['port']) && isset($_GET['user']) && isset($_GET['pass'])) {
    $host = $_GET['host'];
    $port = $_GET['port'];
    $user = $_GET['user'];
    $pass = $_GET['pass'];
    $systemInfo = getSystemInfo($host, $port, $user, $pass);
    echo json_encode($systemInfo);
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
