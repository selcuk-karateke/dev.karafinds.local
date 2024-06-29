<?php
require_once 'bootstrap.php';
$title = "Homepage";
$meta_description = "Willkommen bei Webdesign Karateke";
// $additional_head_content = '<link rel="stylesheet" href="index.css">';
$additional_head_content_1 = '';
$additional_head_content_2 = '<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>';
include 'parts/head.php';

// Webseiten
$websites = [
    [
        'name' => 'Karateke Webdesign',
        'url' => 'https://karateke-webdesign.de',
        'host' => 'w015a999.kasserver.com',
        'port' => '22',
        'user' => 'ssh-w01e9880',
        'pass' => PASSWORD_SERVER_1,
        'path' => '/www/htdocs/w015a999/karateke-webdesign.de',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => 'AdminKarateke',
        'pass_api' => 'WebKara24',
        'type' => 'wordpress'
    ],
    [
        'name' => 'DEV',
        'url' => 'https://dev.karafinds.com',
        'host' => 'w01bcb0d.kasserver.com',
        'port' => '22',
        'user' => 'ssh-w01bcb0d',
        'pass' => PASSWORD_SERVER_2,
        'path' => '/www/htdocs/w01bcb0d/dev.karafinds.com',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'self'
    ],
    [
        'name' => 'Karatekes Shop',
        'url' => 'https://karatekes.com',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w01bcb0d/karatekes.com',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'shopify'
    ],
    [
        'name' => 'F35 Performance',
        'url' => 'https://f35performance.com/',
        'host' => 'w01f2ac6.kasserver.com',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/f35performance.com',
        'user_ftp' => 'w01f2ac6',
        'pass_ftp' => 'tqDH5RxooE7nGc68zCUQ',
        'user_api' => 'AdminSinan',
        'pass_api' => 'QDBdJ4vWjfRertTGhQbR',
        'type' => 'wordpress'
    ],
    [
        'name' => 'FixRepair Berlin',
        'url' => 'https://fixrepairberlin.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/fixrepairberlin.de',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'name' => 'Faceart Berlin',
        'url' => 'https://faceart-berlin.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/faceart-berlin.de',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'name' => 'Alpay Solaranlagen',
        'url' => 'https://alpaysolar.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/alpaysolar.de',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'name' => 'Alenrec Reinigung',
        'url' => 'https://alenrec-reinigung.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/alenrec-reinigung.de',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'name' => 'Kosmetikstudio 61',
        'url' => 'http://kosmetik61.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '',
        'user_ftp' => '',
        'pass_ftp' => '',
        'user_api' => '',
        'pass_api' => '',
        'type' => ''
    ],
    // Füge weitere Webseiten hier hinzu
];

if ($userLogged) {
?>

    <body>
        <div class="container">
            <header class="my-4">
                <h1 class="text-center">Home - Webdesign Karateke</h1>
                <?php include 'parts/nav.php'; ?>
            </header>
            <section>
                <div class="row">
                    <div class="col-md-12">
                        <h2>Willkommen bei Webdesign Karateke</h2>
                    </div>
                    <div class="col-md-12">
                        <h3>Überwachung</h3>
                        <div class="row" id="sortable-cards">
                            <?php foreach ($websites as $website) : ?>
                                <div class="col-md-6 sortable-card">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <i class="fas fa-globe"></i> <?php echo htmlspecialchars($website['name']); ?>
                                            <span class="float-end" id="availability-status-header-<?php echo md5($website['url']); ?>"></span>
                                        </div>
                                        <div class="card-body">
                                            <button class="btn btn-primary" onclick="checkAvailability('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')">
                                                <i class="fas fa-globe"></i> Verfügbarkeit prüfen
                                            </button>
                                            <div id="availability-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                            <button class="btn btn-primary mt-3" onclick="checkLoadTime('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')">
                                                <i class="fas fa-tachometer-alt"></i> Ladezeiten prüfen
                                            </button>
                                            <div id="loadtime-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                            <button class="btn btn-primary mt-3" onclick="checkUpdates('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['user_api']; ?>', '<?php echo $website['pass_api']; ?>', '<?php echo $website['type']; ?>')">
                                                <i class="fas fa-sync-alt"></i> Updates prüfen
                                            </button>
                                            <div id="updates-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                            <button class="btn btn-primary mt-3" onclick="checkSecurity('<?php echo $website['url']; ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>', '<?php echo $website['path']; ?>', '<?php echo md5($website['url']); ?>')">
                                                <i class="fas fa-shield-alt"></i> Sicherheitsstatus prüfen
                                            </button>
                                            <div id="security-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="text-center mt-4">
            &copy; 2023 Webdesign Karateke - Alle Rechte vorbehalten.
        </footer>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function() {
                $("#sortable-cards").sortable({
                    handle: ".card-header",
                    placeholder: "sortable-placeholder"
                });

                var websites = <?php echo json_encode($websites); ?>;
                websites.forEach(function(website) {
                    var urlHash = CryptoJS.MD5(website.url).toString();
                    checkAvailability(website.url, urlHash);
                    checkLoadTime(website.url, urlHash);
                });
            });

            function showSpinner(elementId) {
                var element = document.getElementById(elementId);
                element.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Lade Daten...</span></div>';
            }

            function hideSpinner(elementId) {
                var element = document.getElementById(elementId);
                element.innerHTML = '';
            }

            function checkAvailability(url, urlHash) {
                var statusListId = 'availability-status-' + urlHash;
                var statusHeaderId = 'availability-status-header-' + urlHash;
                showSpinner(statusListId);
                showSpinner(statusHeaderId);
                $.get('check_availability.php', {
                    url: url
                }, function(data) {
                    hideSpinner(statusListId);
                    hideSpinner(statusHeaderId);
                    var results = JSON.parse(data);
                    var statusList = $('#' + statusListId);
                    var statusHeader = $('#' + statusHeaderId);
                    statusList.empty();
                    statusHeader.empty();
                    $.each(results, function(url, status) {
                        var icon;
                        var headerText;
                        if (status.includes("Seite ist erreichbar")) {
                            icon = '<i class="fas fa-check-circle text-success"></i>';
                            headerText = icon + ' Erreichbar';
                        } else if (status.includes("403")) {
                            icon = '<i class="fas fa-times-circle text-warning"></i>'; // Gelb für Forbidden
                            headerText = icon + ' Forbidden';
                        } else {
                            icon = '<i class="fas fa-times-circle text-danger"></i>'; // Rot für andere Fehler
                            headerText = icon + ' Nicht erreichbar';
                        }
                        statusList.append('<div>' + url + ': ' + icon + ' ' + status + '</div>');
                        statusHeader.append(headerText);
                    });
                });
            }

            function checkLoadTime(url, urlHash) {
                var statusListId = 'loadtime-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check_loadtime.php', {
                    url: url
                }, function(data) {
                    hideSpinner(statusListId);
                    var results = JSON.parse(data);
                    var statusList = $('#' + statusListId);
                    statusList.empty();
                    $.each(results, function(url, time) {
                        var icon;
                        if (time < 2) {
                            icon = '<i class="fas fa-check-circle text-success"></i>'; // Grün für gute Ladezeit
                        } else if (time >= 2 && time <= 4) {
                            icon = '<i class="fas fa-exclamation-circle text-warning"></i>'; // Gelb für akzeptable Ladezeit
                        } else {
                            icon = '<i class="fas fa-times-circle text-danger"></i>'; // Rot für schlechte Ladezeit
                        }
                        statusList.append('<div>' + url + ': ' + icon + ' ' + time.toFixed(2) + ' Sekunden</div>');
                    });
                });
            }

            function checkSecurity(url, host, port, user, pass, path, urlHash) {
                var statusListId = 'security-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check_security.php', {
                    url: url,
                    host: host,
                    port: port,
                    user: user,
                    pass: pass,
                    path: path
                }, function(data) {
                    hideSpinner(statusListId);
                    $('#' + statusListId).html(data);
                });
            }

            function checkUpdates(url, urlHash, user_api, pass_api, type) {
                var statusListId = 'updates-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check_updates.php', {
                    url: url,
                    user_api: user_api,
                    pass_api: pass_api,
                    type: type
                }, function(data) {
                    hideSpinner(statusListId);
                    $('#' + statusListId).html(data);
                });
            }
        </script>
        <style>
            .sortable-placeholder {
                background-color: #f0f0f0;
                border: 1px dashed #ccc;
                height: 100px;
                margin-bottom: 20px;
                visibility: visible !important;
            }
        </style>
    </body>

    </html>
<?php
} else {
    include('auth/login.php'); // Anmeldeseite
}
?>
</body>

</html>