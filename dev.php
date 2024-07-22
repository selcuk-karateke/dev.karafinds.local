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
                        <h2>Überwachung</h2>
                    </div>
                    <div class="col-md-12">
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
                                                        <button class="btn btn-info mt-3 server-info-btn" data-url="<?php echo $website['url']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Server Info prüfen">
                                                            <i class="fas fa-info-circle"></i> Server Info prüfen
                                                        </button>
                                                        <div id="server-info-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="server-load-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="server-load-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-server-load-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-host="<?php echo $website['host']; ?>" data-port="<?php echo $website['port']; ?>" data-user="<?php echo $website['user']; ?>" data-pass="<?php echo $website['pass']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Serverauslastung prüfen (lokal)">
                                                            <i class="fas fa-server"></i> Serverauslastung prüfen
                                                        </button>
                                                        <div id="server-load-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="availability-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="availability-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-availability-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Verfügbarkeit prüfen">
                                                            <i class="fas fa-globe"></i> Verfügbarkeit prüfen
                                                        </button>
                                                    </div>
                                                    <div class="tab-pane fade" id="loadtime-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="loadtime-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-loadtime-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Ladezeiten prüfen">
                                                            <i class="fas fa-tachometer-alt"></i> Ladezeiten prüfen
                                                        </button>
                                                    </div>
                                                    <div class="tab-pane fade" id="updates-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="updates-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-updates-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-user_api="<?php echo $website['api'][0]['user']; ?>" data-pass_api="<?php echo $website['api'][0]['pass']; ?>" data-type="<?php echo $website['type']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Updates prüfen">
                                                            <i class="fas fa-sync-alt"></i> Updates prüfen
                                                        </button>
                                                        <div id="updates-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="comments-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="comments-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-comments-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-spam_api="<?php echo $website['spam_api']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Kommentare prüfen">
                                                            <i class="fas fa-comments"></i> Kommentare prüfen
                                                        </button>
                                                        <div id="comments-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="seo-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="seo-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-seo-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="SEO-Daten prüfen">
                                                            <i class="fas fa-search"></i> SEO-Daten prüfen
                                                        </button>
                                                        <div id="seo-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="log-traffic-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="log-traffic-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-log-traffic-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Log Traffic prüfen">
                                                            <i class='fas fa-traffic-light'></i> Log Traffic prüfen
                                                        </button>
                                                        <div id="log-traffic-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="google-traffic-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="google-traffic-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-google-traffic-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-prop_id="<?php echo $website['prop_id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Google Traffic prüfen">
                                                            <i class="fab fa-google"></i> Google Traffic prüfen
                                                        </button>
                                                        <div id="google-traffic-status-<?php echo $uniqueId; ?>" class="status-indicator mt-2"></div>
                                                    </div>
                                                    <div class="tab-pane fade" id="security-<?php echo $uniqueId; ?>" role="tabpanel" aria-labelledby="security-tab-<?php echo $uniqueId; ?>">
                                                        <button class="btn btn-primary mt-3 check-security-btn" data-url="<?php echo $website['url']; ?>" data-urlHash="<?php echo $uniqueId; ?>" data-host="<?php echo $website['host']; ?>" data-port="<?php echo $website['port']; ?>" data-user="<?php echo $website['user']; ?>" data-pass="<?php echo $website['pass']; ?>" data-path="<?php echo $website['path']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Sicherheitsstatus prüfen">
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
        <script src="src/classes/WebsiteMonitor.js"></script> <!-- Pfad zur JS-Datei -->
        <script>
            $(document).ready(function() {
                var websites = <?php echo json_encode($websites); ?>;
                var monitor = new WebsiteMonitor(websites);
                monitor.init();
            });
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