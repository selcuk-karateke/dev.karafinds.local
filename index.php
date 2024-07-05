<?php
// phpinfo();
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
        'type' => 'wordpress',
        'spam_api' => 'd9dde23d1c26'
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
        'type' => 'self',
        'spam_api' => ''
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
        'type' => 'shopify',
        'spam_api' => ''
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
        'type' => 'wordpress',
        'spam_api' => ''
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
        'type' => 'wordpress',
        'spam_api' => ''
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
        'type' => 'wordpress',
        'spam_api' => ''
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
        'type' => 'wordpress',
        'spam_api' => ''
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
        'type' => 'wordpress',
        'spam_api' => ''
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
        'type' => '',
        'spam_api' => ''
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
                                            <a href="<?php echo htmlspecialchars($website['url']); ?>">
                                                <i class="fas fa-globe"></i> <?php echo htmlspecialchars($website['name']); ?>
                                                <span class="float-end" id="availability-status-header-<?php echo md5($website['url']); ?>"> </span>
                                                <span class="float-end" id="loadtime-status-header-<?php echo md5($website['url']); ?>"></span>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <button class="btn btn-primary mt-3" onclick="checkServerLoad('<?php echo $website['url']; ?>', '<?php echo md5($website['url']) ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Serverauslastung prüfen (lokal)">
                                                <i class="fas fa-server"></i>
                                            </button>
                                            <button class="btn btn-primary mt-3" onclick="checkAvailability('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Verfügbarkeit prüfen">
                                                <i class="fas fa-globe"></i>
                                            </button>
                                            <button class="btn btn-primary mt-3" onclick="checkLoadTime('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Ladezeiten prüfen">
                                                <i class="fas fa-tachometer-alt"></i>
                                            </button>
                                            <button class="btn btn-primary mt-3" onclick="checkUpdates('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['user_api']; ?>', '<?php echo $website['pass_api']; ?>', '<?php echo $website['type']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Updates prüfen">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                            <button class="btn btn-primary mt-3" onclick="checkComments('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['spam_api']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kommentare prüfen">
                                                <i class="fas fa-comments"></i>
                                            </button>
                                            <button class="btn btn-primary mt-3" onclick="checkSEO('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="SEO-Daten prüfen">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <button class="btn btn-primary mt-3" onclick="checkGoogleTraffic('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Google Traffic prüfen">
                                                <i class="fab fa-google"></i>
                                            </button>
                                            <button class="btn btn-primary mt-3" onclick="checkSecurity('<?php echo $website['url']; ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>', '<?php echo $website['path']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Sicherheitsstatus prüfen">
                                                <i class="fas fa-shield-alt"></i>
                                            </button>
                                            <br />
                                            <br />
                                            <span id="server-load-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span>
                                            <!-- <span id="availability-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span> -->
                                            <!-- <span id="loadtime-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span> -->
                                            <span id="updates-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span>
                                            <span id="comments-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span>
                                            <span id="seo-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span>
                                            <span id="google-traffic-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span>
                                            <span id="security-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></span>
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
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
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
                // var statusListId = 'availability-status-' + urlHash;
                var statusHeaderId = 'availability-status-header-' + urlHash;
                // showSpinner(statusListId);
                showSpinner(statusHeaderId);
                $.get('check/availability.php', {
                    url: url
                }, function(data) {
                    // hideSpinner(statusListId);
                    hideSpinner(statusHeaderId);
                    var results = JSON.parse(data);
                    // var statusList = $('#' + statusListId);
                    var statusHeader = $('#' + statusHeaderId);
                    // statusList.empty();
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
                        // statusList.append('');
                        statusHeader.html('&nbsp' + icon + ' ' + status);
                    });
                });
            }

            function checkLoadTime(url, urlHash) {
                // var statusListId = 'loadtime-status-' + urlHash;
                var statusHeaderId = 'loadtime-status-header-' + urlHash;
                // showSpinner(statusListId);
                showSpinner(statusHeaderId);
                $.get('check/loadtime.php', {
                    url: url
                }, function(data) {
                    // hideSpinner(statusListId);
                    hideSpinner(statusHeaderId);
                    var results = JSON.parse(data);
                    // var statusList = $('#' + statusListId);
                    var statusHeader = $('#' + statusHeaderId);
                    // statusList.empty();
                    statusHeader.empty();
                    $.each(results, function(url, time) {
                        var icon;
                        if (time < 2) {
                            icon = '<i class="fas fa-check-circle text-success"></i>'; // Grün für gute Ladezeit
                        } else if (time >= 2 && time <= 4) {
                            icon = '<i class="fas fa-exclamation-circle text-warning"></i>'; // Gelb für akzeptable Ladezeit
                        } else {
                            icon = '<i class="fas fa-times-circle text-danger"></i>'; // Rot für schlechte Ladezeit
                        }
                        // statusList.append('');
                        statusHeader.html('&nbsp' + icon + ' ' + time.toFixed(2) + 's ');
                    });
                });
            }

            function checkUpdates(url, urlHash, user_api, pass_api, type) {
                var statusListId = 'updates-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check/updates.php', {
                    url: url,
                    user_api: user_api,
                    pass_api: pass_api,
                    type: type
                }, function(data) {
                    hideSpinner(statusListId);
                    $('#' + statusListId).html(data);
                });
            }

            function checkComments(url, urlHash, spamApi) {
                var statusListId = 'comments-status-' + urlHash;
                showSpinner(statusListId);
                $.get('fetch/comments.php', {
                    url: url
                }, function(comments) {
                    hideSpinner(statusListId);
                    var statusList = $('#' + statusListId);
                    statusList.empty();
                    $.each(JSON.parse(comments), function(index, comment) {
                        var commentData = {
                            permalink: comment.link,
                            comment_type: 'comment',
                            comment_author: comment.author_name,
                            comment_author_email: comment.author_email,
                            comment_author_url: comment.author_url,
                            comment_content: comment.content.rendered
                        };
                        $.post('check/comment.php', {
                            spam_api: spamApi,
                            blog_url: url,
                            comment: commentData
                        }, function(data) {
                            var icon;
                            if (data == 'true') {
                                icon = '<i class="fas fa-times-circle text-danger"></i> Spam';
                            } else {
                                icon = '<i class="fas fa-check-circle text-success"></i> Kein Spam';
                            }
                            statusList.append('<div>' + commentData.comment_author + ': ' + icon + '</div>');
                        });
                    });
                });
            }

            function checkSEO(url, urlHash) {
                var statusListId = 'seo-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check/seo.php', {
                    url: url
                }, function(data) {
                    hideSpinner(statusListId);
                    var results = JSON.parse(data);
                    var statusList = $('#' + statusListId);
                    statusList.empty();
                    statusList.append('<div><strong>Titel:</strong> ' + results.title + '</div>');
                    statusList.append('<div><strong>Beschreibung:</strong> ' + results.description + '</div>');
                    statusList.append('<div><strong>Sitemap:</strong> <a href="' + results.sitemap + '">' + results.sitemap + '</a></div>');
                    statusList.append('<div><strong>Robots.txt:</strong> <pre>' + results.robots + '</pre></div>');
                });
            }

            function checkGoogleTraffic(url, urlHash) {
                var statusListId = 'google-traffic-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check/google_traffic.php', {
                    url: url,
                }, function(data) {
                    hideSpinner(statusListId);
                    var result = JSON.parse(data);
                    $('#' + statusListId).html('Besucher von Google: ' + result.google_traffic);
                });
            }

            function checkSecurity(url, host, port, user, pass, path, urlHash) {
                var statusListId = 'security-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check/security.php', {
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

            function checkServerLoad(url, urlHash, host, port, user, pass) {
                var statusListId = 'server-load-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check/serverLoad.php', {
                    url: url,
                    host: host,
                    port: port,
                    user: user,
                    pass: pass,
                }, function(data) {
                    hideSpinner(statusListId);
                    var result = JSON.parse(data);
                    $('#' + statusListId).html('Serverauslastung: ' + result.load + '%');
                });
            }

            function checkDbPerformance(url, host, port, user, pass, path, urlHash) {
                $.get('check/dbPerformance.php', {
                    url: url,
                    host: host,
                    port: port,
                    user: user,
                    pass: pass,
                    path: path
                }, function(data) {
                    $('#db-performance-status').text(data);
                });
            }

            function checkGoogleTraffic(url, host, port, user, pass, path, urlHash) {
                $.get('check/googleTraffic.php', {
                    url: url,
                    host: host,
                    port: port,
                    user: user,
                    pass: pass,
                    path: path
                }, function(data) {
                    $('#google-traffic-status').text(data);
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