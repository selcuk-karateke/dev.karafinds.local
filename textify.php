<?php
require_once 'bootstrap.php';
$title = "Textify - Webdesign Karateke";
$meta_description = "Textify - Willkommen bei Webdesign Karateke";
// $additional_head_content = '<link rel="stylesheet" href="game.css">';
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['backup'])) {
        // Pfad zum Quell- und Zielverzeichnis
        $sourceDirectory = __DIR__;
        $targetDirectory = __DIR__ . '/backup';
        $fileStructure = [];

        // Funktion ausführen
        saveDirectoryAsText($sourceDirectory, $targetDirectory, $fileStructure);
        // Speichern der Dateistruktur als JSON
        file_put_contents($targetDirectory . '/file_structure.json', json_encode($fileStructure, JSON_PRETTY_PRINT));
        $message = "Backup wurde erfolgreich erstellt!";
    }
}

include 'parts/head.php';

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

?>

<body class="bg-dark text-white">
    <div class="container">
        <header class="my-4">
            <h1 class="text-center"><?= $title ?></h1>
            <?php include 'parts/nav.php'; ?>
        </header>
        <section>
            <div class="row">
                <div class="col-md-4">
                    <form method="post">
                        <button class="btn btn-primary" type="submit" name="backup">Backup erstellen</button>
                    </form>
                </div>
                <div class="col-md-8">
                    <?= $message ?>
                </div>
            </div>
        </section>
    </div>
    <footer class="text-center stroked-text mt-4">
        &copy; 2023 Webdesign Karateke - Alle Rechte vorbehalten.
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>