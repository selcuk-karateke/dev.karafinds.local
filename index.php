<?php
require_once 'bootstrap.php';
$title = "Homepage";
$meta_description = "Willkommen bei Webdesign Karateke";
// $additional_head_content = '<link rel="stylesheet" href="index.css">';
$additional_head_content = '<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>';
include 'parts/head.php';

// Beispiel-Webseiten
$websites = [
    [
        'url' => 'https://karateke-webdesign.de',
        'host' => 'w015a999.kasserver.com',
        'port' => '22',
        'user' => 'ssh-w01e9880',
        'pass' => PASSWORD_SERVER_1,
        'path' => '/www/htdocs/w015a999/karateke-webdesign.de',
        'name' => 'Karateke Webdesign',
        'user_api' => 'AdminKarateke',
        'pass_api' => 'WebKara24',
        'type' => 'wordpress'
    ],
    [
        'url' => 'https://dev.karafinds.com',
        'host' => 'w01bcb0d.kasserver.com',
        'port' => '22',
        'user' => 'ssh-w01bcb0d',
        'pass' => PASSWORD_SERVER_2,
        'path' => '/www/htdocs/w01bcb0d/dev.karafinds.com',
        'name' => 'DEV',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'self'
    ],
    [
        'url' => 'https://karatekes.com',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w01bcb0d/karatekes.com',
        'name' => 'Karatekes Shop',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'shopify'
    ],
    [
        'url' => 'https://f35performance.com/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/f35performance.com',
        'name' => 'F35 Performance',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'url' => 'https://fixrepairberlin.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/fixrepairberlin.de',
        'name' => 'FixRepair Berlin',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'url' => 'https://faceart-berlin.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/faceart-berlin.de',
        'name' => 'Faceart Berlin',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'url' => 'https://alpaysolar.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/alpaysolar.de',
        'name' => 'Alpay Solaranlagen',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'url' => 'https://alenrec-reinigung.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '/www/htdocs/w015a999/alenrec-reinigung.de',
        'name' => 'Alenrec Reinigung',
        'user_api' => '',
        'pass_api' => '',
        'type' => 'wordpress'
    ],
    [
        'url' => 'http://kosmetik61.de/',
        'host' => '',
        'port' => '',
        'user' => '',
        'pass' => '',
        'path' => '',
        'name' => 'Kosmetikstudio 61',
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
                        <p></p>
                    </div>
                    <div class="col-md-12">
                        <h3>Überwachung</h3>
                        <p></p>

                        <div class="row">
                            <?php foreach ($websites as $website) : ?>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <i class="fas fa-globe"></i> <?php echo htmlspecialchars($website['name']); ?>
                                        </div>
                                        <div class="card-body">
                                            <h4>Verfügbarkeit</h4>
                                            <button class="btn btn-primary" onclick="checkAvailability('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')">Verfügbarkeit prüfen</button>
                                            <ul id="availability-status-<?php echo md5($website['url']); ?>">
                                                <li>
                                                    <div class="spinner-border text-primary d-none" role="status">
                                                        <span class="visually-hidden">Lade Verfügbarkeitsdaten...</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <h4>Ladezeiten</h4>
                                            <button class="btn btn-primary" onclick="checkLoadTime('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')">Ladezeiten prüfen</button>
                                            <ul id="loadtime-status-<?php echo md5($website['url']); ?>">
                                                <li>
                                                    <div class="spinner-border text-primary d-none" role="status">
                                                        <span class="visually-hidden">Lade Ladezeitdaten...</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <h4>Plugin- und Theme-Updates</h4>
                                            <button class="btn btn-primary" onclick="checkUpdates('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['user_api']; ?>', '<?php echo $website['pass_api']; ?>', '<?php echo $website['type']; ?>')">Updates prüfen</button>
                                            <p id="updates-status-<?php echo md5($website['url']); ?>">Lade Updates...</p>
                                            <h4>Sicherheitsstatus</h4>
                                            <button class="btn btn-primary" onclick="checkSecurity('<?php echo $website['url']; ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>', '<?php echo $website['path']; ?>', '<?php echo md5($website['url']); ?>')">Sicherheitsstatus prüfen</button>
                                            <p id="security-status-<?php echo md5($website['url']); ?>">Lade Sicherheitsdaten...</p>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function() {
                var websites = <?php echo json_encode($websites); ?>;
                websites.forEach(function(website) {
                    var urlHash = CryptoJS.MD5(website.url).toString()
                    checkAvailability(website.url, urlHash);
                    checkLoadTime(website.url, urlHash);
                });
            });

            function checkAvailability(url, urlHash) {
                $.get('check_availability.php', {
                    url: url
                }, function(data) {
                    var results = JSON.parse(data);
                    var statusList = $('#availability-status-' + urlHash);
                    statusList.empty();
                    $.each(results, function(url, status) {
                        var icon;
                        if (status.includes("Seite ist erreichbar")) {
                            icon = '<i class="fas fa-check-circle text-success"></i>';
                        } else if (status.includes("403")) {
                            icon = '<i class="fas fa-times-circle text-warning"></i>'; // Gelb für Forbidden
                        } else {
                            icon = '<i class="fas fa-times-circle text-danger"></i>'; // Rot für andere Fehler
                        }
                        statusList.append('<li>' + url + ': ' + icon + ' ' + status + '</li>');
                    });
                });
            }


            function checkLoadTime(url, urlHash) {
                $.get('check_loadtime.php', {
                    url: url
                }, function(data) {
                    var results = JSON.parse(data);
                    var statusList = $('#loadtime-status-' + urlHash);
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
                        statusList.append('<li>' + url + ': ' + icon + ' ' + time.toFixed(2) + ' Sekunden</li>');
                    });
                });
            }

            function checkSecurity(url, host, port, user, pass, path, urlHash) {
                $.get('check_security.php', {
                    url: url,
                    host: host,
                    port: port,
                    user: user,
                    pass: pass,
                    path: path
                }, function(data) {
                    $('#security-status-' + urlHash).html(data);
                });
            }

            function checkUpdates(url, urlHash, user, pass, type) {
                $.get('check_updates.php', {
                    url: url,
                    user: user,
                    pass: pass,
                    type: type
                }, function(data) {
                    $('#updates-status-' + urlHash).html(data);
                });
            }
        </script>
    <?php
} else {
    include('auth/login.php'); // Anmeldeseite
}
    ?>
    </body>

    </html>