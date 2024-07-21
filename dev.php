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

// $logger->log("Dies ist eine Info-Nachricht", 'info');
// $logger->log("Dies ist eine Warnung", 'warning', true);
// $logger->log("Dies ist ein Fehler", 'error', true);

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
                                <?php
                                $uniqueId = md5($website['url']); // Eindeutige ID für jedes Website-Element
                                $filename = 'C:\xampp\htdocs\dev.karafinds.local\check\updates-' . $uniqueId . '.json';
                                $updateData = file_exists($filename) ? json_decode(file_get_contents($filename), true) : null;
                                $hasUpdates = isset($website['updates']) && $website['updates'] > 0;
                                $parsedUrl = parse_url($website['url'], PHP_URL_HOST);
                                $host = $parsedUrl ? $parsedUrl : $website['url'];
                                $ipAddress = filter_var($host, FILTER_VALIDATE_IP) ? $host : gethostbyname($host);
                                ?>
                                <div class="col-md-6 sortable-card">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <a href="<?php echo htmlspecialchars($website['url']); ?>" <?php echo $nofollow; ?> target="_blank">
                                                <i class="fas fa-globe"></i> <?php echo htmlspecialchars($website['name']); ?>
                                            </a>
                                            <?php if ($hasUpdates) : ?>
                                                <span class="badge bg-danger">!</span>
                                            <?php endif; ?>
                                            <span class="float-end" id="availability-status-header-<?php echo $uniqueId; ?>"> </span>
                                            <span class="float-end" id="loadtime-status-header-<?php echo $uniqueId; ?>"></span>
                                            <button class="btn toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#cardContent-<?php echo $uniqueId; ?>" aria-expanded="false" aria-controls="cardContent-<?php echo $uniqueId; ?>">
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                        </div>
                                        <div id="cardContent-<?php echo $uniqueId; ?>" class="collapse">
                                            <div class="card-body">
                                                <p><?php echo $host; ?> (<?php echo $ipAddress; ?>)</p>
                                                <ul class="nav nav-tabs" id="myTab-<?php echo $uniqueId; ?>" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="server-info-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#server-info-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="server-info-<?php echo $uniqueId; ?>" aria-selected="true"><i class="fas fa-info-circle"></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="server-load-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#server-load-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="server-load-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fas fa-server"></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="availability-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#availability-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="availability-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fas fa-globe"></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="loadtime-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#loadtime-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="loadtime-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fas fa-tachometer-alt"></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="updates-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#updates-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="updates-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fas fa-sync-alt"></i>
                                                            <?php if ($hasUpdates) : ?>
                                                                <span class="badge bg-danger"><?php echo $website['updates']; ?></span>
                                                            <?php endif; ?>
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="comments-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#comments-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="comments-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fas fa-comments"></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="seo-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#seo-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="seo-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fas fa-search"></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="log-traffic-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#log-traffic-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="log-traffic-<?php echo $uniqueId; ?>" aria-selected="false"><i class='fas fa-traffic-light'></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="google-traffic-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#google-traffic-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="google-traffic-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fab fa-google"></i></button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="security-tab-<?php echo $uniqueId; ?>" data-bs-toggle="tab" data-bs-target="#security-<?php echo $uniqueId; ?>" type="button" role="tab" aria-controls="security-<?php echo $uniqueId; ?>" aria-selected="false"><i class="fas fa-shield-alt"></i></button>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="myTabContent-<?php echo $uniqueId; ?>">
                                                    <div class="tab-pane fade show active" id="server-info-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="server-info-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-info mt-3" onclick="showServerInfo('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Server Info prüfen">
                                                            <i class="fas fa-info-circle"></i> Server Info prüfen
                                                        </button>
                                                        <div id="server-info-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="server-load-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="server-load-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkServerLoad('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Serverauslastung prüfen (lokal)">
                                                            <i class="fas fa-server"></i> Serverauslastung prüfen
                                                        </button>
                                                        <div id="server-load-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="availability-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="availability-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkAvailability('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Verfügbarkeit prüfen">
                                                            <i class="fas fa-globe"></i> Verfügbarkeit prüfen
                                                        </button>
                                                    </div>
                                                    <div class="tab-pane fade" id="loadtime-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="loadtime-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkLoadTime('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Ladezeiten prüfen">
                                                            <i class="fas fa-tachometer-alt"></i> Ladezeiten prüfen
                                                        </button>
                                                    </div>
                                                    <div class="tab-pane fade" id="updates-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="updates-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkUpdates('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>', '<?php echo $website['api'][0]['user']; ?>', '<?php echo $website['api'][0]['pass']; ?>', '<?php echo $website['type']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Updates prüfen">
                                                            <i class="fas fa-sync-alt"></i> Updates prüfen
                                                        </button>
                                                        <div id="updates-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="comments-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="comments-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkComments('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>', '<?php echo $website['spam_api']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kommentare prüfen">
                                                            <i class="fas fa-comments"></i> Kommentare prüfen
                                                        </button>
                                                        <div id="comments-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="seo-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="seo-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkSEO('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="SEO-Daten prüfen">
                                                            <i class="fas fa-search"></i> SEO-Daten prüfen
                                                        </button>
                                                        <div id="seo-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="log-traffic-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="log-traffic-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkLogTraffic('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Log Traffic prüfen">
                                                            <i class='fas fa-traffic-light'></i> Log Traffic prüfen
                                                        </button>
                                                        <div id="log-traffic-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="google-traffic-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="google-traffic-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkGoogleTraffic('<?php echo $website['url']; ?>', '<?php echo $uniqueId; ?>', '<?php echo $website['prop_id']; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Google Traffic prüfen">
                                                            <i class="fab fa-google"></i> Google Traffic prüfen
                                                        </button>
                                                        <div id="google-traffic-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="security-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="security-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3" onclick="checkSecurity('<?php echo $website['url']; ?>', '<?php echo $website['host']; ?>', '<?php echo $website['port']; ?>', '<?php echo $website['user']; ?>', '<?php echo $website['pass']; ?>', '<?php echo $website['path']; ?>', '<?php echo $uniqueId; ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Sicherheitsstatus prüfen">
                                                            <i class="fas fa-shield-alt"></i> Sicherheitsstatus prüfen
                                                        </button>
                                                        <div id="security-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
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
        <!-- Modal HTML -->
        <div class="modal fade" id="serverInfoModal" tabindex="-1" aria-labelledby="serverInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="serverInfoModalLabel">Server Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Server Information Content Here -->
                        <div id="server-info-content"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

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
                var toggleButtons = document.querySelectorAll('.toggle-btn');
                toggleButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var icon = button.querySelector('i');
                        if (button.getAttribute('aria-expanded') === 'true') {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        } else {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        }
                    });
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

            function showServerInfo(url, urlHash) {
                var modal = new bootstrap.Modal(document.getElementById('serverInfoModal'));
                var content = document.getElementById('server-info-content');
                content.innerHTML = ''; // Inhalt löschen
                showSpinner('server-info-content'); // Spinner anzeigen

                $.get('fetch/serverInfo.php', {
                    url: url
                }, function(response) {
                    hideSpinner('server-info-content');
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response;
                        content.innerHTML = `
                    <p><strong>IP Address:</strong> ${result['Server IP']}</p>
                    <p><strong>FCGI:</strong> ${result['FCGI']}</p>
                    <p><strong>Max Execution Time:</strong> ${result['Max Execution Time']}</p>
                    <p><strong>PHP Version:</strong> ${result['PHP Version']}</p>
                    <p><strong>DB Version:</strong> ${result['DB Version']}</p>
                    <p><strong>Safe Mode:</strong> ${result['Safe Mode']}</p>
                    <p><strong>Memory Limit:</strong> ${result['Memory Limit']}</p>
                    <p><strong>Memory Get Usage:</strong> ${result['Memory Get Usage']}</p>
                    <p><strong>Memory Peak Usage:</strong> ${result['Memory Peak Usage']}</p>
                    <p><strong>PDO Enabled:</strong> ${result['PDO Enabled']}</p>
                    <p><strong>Curl Enabled:</strong> ${result['Curl Enabled']}</p>
                    <p><strong>Zlib Enabled:</strong> ${result['Zlib Enabled']}</p>
                    <p><strong>Is Multisite:</strong> ${result['Is Multisite']}</p>
                `;
                    } catch (e) {
                        content.innerHTML = '<p>Error retrieving data.</p>';
                    }
                    modal.show(); // Modal anzeigen
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
                }, function(response) {
                    hideSpinner(statusListId);
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response; // Parse JSON if necessary
                        $('#' + statusListId).html(result.data);
                    } catch (e) {
                        $('#' + statusListId).html('<p>Error retrieving data.</p>');
                    }
                });
            }

            function checkGoogleTraffic(url, urlHash, propertyId) {
                var statusListId = 'google-traffic-status-' + urlHash;
                showSpinner(statusListId);
                console.log(statusListId)
                $.get('check/googleTraffic.php', {
                    url: url,
                    propertyId: propertyId
                }, function(response) {
                    hideSpinner(statusListId);
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response;
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
                }, function(response) {
                    // hideSpinner(statusListId);
                    hideSpinner(statusHeaderId);
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response;
                        // var statusList = $('#' + statusListId);
                        var statusHeader = $('#' + statusHeaderId);
                        // statusList.empty();
                        statusHeader.empty();
                        $.each(result, function(url, status) {
                            var icon;
                            var headerText;
                            if (status.includes("UP")) {
                                icon = '<i class="fas fa-check-circle text-success"></i>';
                                headerText = icon + ' UP';
                            } else if (status.includes("403")) {
                                icon = '<i class="fas fa-times-circle text-warning"></i>'; // Gelb für Forbidden
                                headerText = icon + ' 403';
                            } else {
                                icon = '<i class="fas fa-times-circle text-danger"></i>'; // Rot für andere Fehler
                                headerText = icon + ' DOWN';
                            }
                            // statusList.append('');
                            statusHeader.html('&nbsp' + icon + ' ' + status);
                        });
                    } catch (e) {
                        $('#' + statusHeaderId).html('<p>Error retrieving data.</p>');
                    }
                });
            }

            function checkLoadTime(url, urlHash) {
                // var statusListId = 'loadtime-status-' + urlHash;
                var statusHeaderId = 'loadtime-status-header-' + urlHash;
                // showSpinner(statusListId);
                showSpinner(statusHeaderId);
                $.get('check/loadtime.php', {
                    url: url
                }, function(response) {
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response;
                        // hideSpinner(statusListId);
                        hideSpinner(statusHeaderId);
                        // var statusList = $('#' + statusHeaderId);
                        var statusHeader = $('#' + statusHeaderId);
                        // statusList.empty();
                        statusHeader.empty();
                        $.each(result, function(url, time) {
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
                    } catch (e) {
                        $('#' + statusHeaderId).html('<p>Error retrieving data.</p>');
                    }
                });
            }

            function checkComments(url, urlHash, spamApi) {
                var statusListId = 'comments-status-' + urlHash;
                showSpinner(statusListId);
                $.get('fetch/comments.php', {
                    url: url
                }, function(response) {
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response;
                        hideSpinner(statusListId);
                        var statusList = $('#' + statusListId);
                        statusList.empty();
                        $.each(result, function(index, comment) {
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
                    } catch (e) {
                        $('#' + statusListId).html('<p>Error retrieving data.</p>');
                    }
                });
            }

            function checkSEO(url, urlHash) {
                var statusListId = 'seo-status-' + urlHash;
                showSpinner(statusListId);
                $.get('check/seo.php', {
                    url: url
                }, function(response) {
                    var result = typeof response === 'string' ? JSON.parse(response) : response;
                    hideSpinner(statusListId);
                    var statusList = $('#' + statusListId);
                    statusList.empty();
                    statusList.append('<div><strong>Titel:</strong> ' + result.title + '</div>');
                    statusList.append('<div><strong>Beschreibung:</strong> ' + result.description + '</div>');
                    statusList.append('<div><strong>Sitemap:</strong> <a href="' + result.sitemap + '">' + result.sitemap + '</a></div>');
                    statusList.append('<div><strong>Robots.txt:</strong> <pre>' + result.robots + '</pre></div>');
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
                }, function(response) {
                    var result = typeof response === 'string' ? JSON.parse(response) : response;
                    hideSpinner(statusListId);
                    $('#' + statusListId).html(result.data);
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