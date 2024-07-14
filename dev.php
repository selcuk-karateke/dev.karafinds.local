<?php
// phpinfo();
require_once 'bootstrap.php';
$title = "DEV";
$meta_description = "DEV Dashboard - Webdesign Karateke";
$nofollow = true ? 'rel="nofollow"' : '';
// $additional_head_content = '<link rel="stylesheet" href="index.css">';
$additional_head_content_1 = '';
$additional_head_content_2 = '<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>';
include 'parts/head.php';

$configLoader = new Karatekes\ConfigLoader('config.json');
$websites = $configLoader->getSection('websites');

if ($userLogged) {
?>

    <body>
        <div class="container">
            <header class="my-4">
                <h1 class="text-center">DEV Dashboard - Webdesign Karateke</h1>
                <?php include 'parts/nav.php'; ?>
            </header>
            <section>
                <div class="row">
                    <div class="col-md-12">
                        <h2>Dashboard</h2>
                    </div>
                    <div class="col-md-12">
                        <h3>Überwachung</h3>
                        <div class="row" id="sortable-cards">
                            <?php foreach ($websites as $website) : ?>
                                <div class="col-md-6 sortable-card">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <a href="<?php echo htmlspecialchars($website['url']); ?>" <?php echo $nofollow; ?>>
                                                <i class="fas fa-globe"></i> <?php echo htmlspecialchars($website['name']); ?>
                                                <span class="float-end" id="availability-status-header-<?php echo md5($website['url']); ?>"> </span>
                                                <span class="float-end" id="loadtime-status-header-<?php echo md5($website['url']); ?>"></span>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="myTab-<?php echo md5($website['url']); ?>" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="server-load-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#server-load-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="server-load-<?php echo md5($website['url']); ?>" aria-selected="true"><i class="fas fa-server"></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="availability-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#availability-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="availability-<?php echo md5($website['url']); ?>" aria-selected="false"><i class="fas fa-globe"></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="loadtime-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#loadtime-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="loadtime-<?php echo md5($website['url']); ?>" aria-selected="false"><i class="fas fa-tachometer-alt"></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="updates-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#updates-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="updates-<?php echo md5($website['url']); ?>" aria-selected="false"><i class="fas fa-sync-alt"></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="comments-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#comments-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="comments-<?php echo md5($website['url']); ?>" aria-selected="false"><i class="fas fa-comments"></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="seo-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#seo-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="seo-<?php echo md5($website['url']); ?>" aria-selected="false"><i class="fas fa-search"></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="log-traffic-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#log-traffic-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="log-traffic-<?php echo md5($website['url']); ?>" aria-selected="false"><i class='fas fa-traffic-light'></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="google-traffic-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#google-traffic-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="google-traffic-<?php echo md5($website['url']); ?>" aria-selected="false"><i class="fab fa-google"></i></button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="security-tab-<?php echo md5($website['url']); ?>" data-bs-toggle="tab" data-bs-target="#security-<?php echo md5($website['url']); ?>" type="button" role="tab" aria-controls="security-<?php echo md5($website['url']); ?>" aria-selected="false"><i class="fas fa-shield-alt"></i></button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="myTabContent-<?php echo md5($website['url']); ?>">
                                                <div class="tab-pane fade show active" id="server-load-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="server-load-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkServerLoad('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Serverauslastung prüfen (lokal)">
                                                        <i class="fas fa-server"></i> Serverauslastung prüfen
                                                    </button>
                                                    <div id="server-load-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                                </div>
                                                <div class="tab-pane fade" id="availability-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="availability-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkAvailability('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Verfügbarkeit prüfen">
                                                        <i class="fas fa-globe"></i> Verfügbarkeit prüfen
                                                    </button>
                                                </div>
                                                <div class="tab-pane fade" id="loadtime-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="loadtime-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkLoadTime('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Ladezeiten prüfen">
                                                        <i class="fas fa-tachometer-alt"></i> Ladezeiten prüfen
                                                    </button>
                                                </div>
                                                <div class="tab-pane fade" id="updates-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="updates-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkUpdates('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['api'][0]['user']; ?>', '<?php echo $website['api'][0]['pass']; ?>', '<?php echo $website['type']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Updates prüfen">
                                                        <i class="fas fa-sync-alt"></i> Updates prüfen
                                                    </button>
                                                    <div id="updates-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                                </div>
                                                <div class="tab-pane fade" id="comments-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="comments-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkComments('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['spam_api']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kommentare prüfen">
                                                        <i class="fas fa-comments"></i> Kommentare prüfen
                                                    </button>
                                                    <div id="comments-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                                </div>
                                                <div class="tab-pane fade" id="seo-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="seo-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkSEO('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="SEO-Daten prüfen">
                                                        <i class="fas fa-search"></i> SEO-Daten prüfen
                                                    </button>
                                                    <div id="seo-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                                </div>
                                                <div class="tab-pane fade" id="log-traffic-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="log-traffic-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkLogTraffic('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Log Traffic prüfen">
                                                        <i class='fas fa-traffic-light'></i> Log Traffic prüfen
                                                    </button>
                                                    <div id="log-traffic-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                                </div>
                                                <div class="tab-pane fade" id="google-traffic-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="google-traffic-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkGoogleTraffic('<?php echo $website['url']; ?>', '<?php echo md5($website['url']); ?>', '<?php echo $website['prop_id']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Google Traffic prüfen">
                                                        <i class="fab fa-google"></i> Google Traffic prüfen
                                                    </button>
                                                    <div id="google-traffic-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                                </div>
                                                <div class="tab-pane fade" id="security-<?php echo md5($website['url']); ?>" role="tabpanel" aria-labelledby="security-tab-<?php echo md5($website['url']); ?>">
                                                    <button class="btn btn-primary mt-3" onclick="checkSecurity('<?php echo $website['url']; ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>', '<?php echo $website['path']; ?>', '<?php echo md5($website['url']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Sicherheitsstatus prüfen">
                                                        <i class="fas fa-shield-alt"></i> Sicherheitsstatus prüfen
                                                    </button>
                                                    <div id="security-status-<?php echo md5($website['url']); ?>" class="status-indicator mt-2"></div>
                                                </div>
                                            </div>
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
                element.innerHTML = '<div class="spinner-grow spinner-grow-sm text-primary" role="status"><span class="visually-hidden">Lade Daten...</span></div>';
            }

            function hideSpinner(elementId) {
                var element = document.getElementById(elementId);
                element.innerHTML = '';
            }


            function checkGoogleTraffic(url, urlHash, propertyId) {
                var statusListId = 'google-traffic-' + urlHash;
                showSpinner(statusListId);
                console.log(statusListId)
                $.get('get/googleTraffic.php', {
                    url: url,
                    propertyId: propertyId
                }, function(response) {
                    hideSpinner(statusListId);
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response; // Parse JSON if necessary
                        if (result.data && result.data.length > 0) {
                            var output = '<table class="table"><thead><tr><th>Source/Medium</th><th>Campaign</th><th>Sessions</th></tr></thead><tbody>';
                            result.data.forEach(function(row) {
                                output += '<tr><td>' + row['dimension0'] + '</td><td>' + row['dimension1'] + '</td><td>' + row['metric0'] + '</td></tr>';
                            });
                            output += '</tbody></table>';
                            $('#' + statusListId).html(output);
                        } else if (result.error) {
                            $('#' + statusListId).html('<p>Error: ' + result.message + '</p>');
                        } else {
                            $('#' + statusListId).html('<p>No data available for the selected period.</p>');
                        }
                    } catch (e) {
                        $('#' + statusListId).html('<p>Error retrieving data.</p>');
                    }
                });
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
                console.log(statusListId)
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

            function checkLogTraffic(url, urlHash) {
                var statusListId = 'log-traffic-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check/logTraffic.php', {
                    url: url,
                }, function(data) {
                    hideSpinner(statusListId);
                    var result = JSON.parse(data);
                    $('#' + statusListId).html('Besucher von Google: ' + result.log_traffic);
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