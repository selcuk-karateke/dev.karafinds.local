<?php
require_once 'bootstrap.php';
$title = "Homepage";
$meta_description = "Willkommen bei Webdesign Karateke";
// $additional_head_content_1 = '<link rel="stylesheet" href="index.css">';
include 'parts/head.php';

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
                            <div class="col-md-4">
                                <div class="card" id="card-availability">
                                    <div class="card-header">
                                        <i class="fas fa-globe"></i> Verfügbarkeit
                                    </div>
                                    <div class="card-body">
                                        <ul id="availability-status">
                                            <li>
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Lade Verfügbarkeitsdaten...</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card" id="card-updates">
                                    <div class="card-header">Plugin- und Theme-Updates</div>
                                    <div class="card-body">
                                        <p id="updates-status">Lade Updates...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card" id="card-security">
                                    <div class="card-header">Sicherheitsstatus</div>
                                    <div class="card-body">
                                        <p id="security-status">Lade Sicherheitsdaten...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card" id="card-loadtime">
                                    <div class="card-header">Ladezeiten</div>
                                    <div class="card-body">
                                        <ul id="loadtime-status">
                                            <li>
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Lade Ladezeitdaten...</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
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
                $.get('/check/availability.php', function(data) {
                    var results = JSON.parse(data);
                    var statusList = $('#availability-status');
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
                $.get('check/security.php', function(data) {
                    $('#security-status').html(data);
                });
                $.get('check/loadtime.php', function(data) {
                    var results = JSON.parse(data);
                    var statusList = $('#loadtime-status');
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
                $.get('check/updates.php', function(data) {
                    $('#updates-status').html(data);
                });
            });
        </script>
    <?php
} else {
    include('auth/login.php'); // Anmeldeseite
}
    ?>
    </body>

    </html>