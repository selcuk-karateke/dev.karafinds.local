<?php
session_start();
require 'config.php';
$title = "Einfaches 2D-Spiel - Webdesign Karateke";
$meta_description = "Game - Willkommen bei Webdesign Karateke";
$additional_head_content = '<link rel="stylesheet" href="game.css">';
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['score'])) {
        $_SESSION['score'] = $_POST['score'];
    }
}

$mysqli = new mysqli('localhost', USERNAME, PASSWORD, DATABASE);

if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM highscores ORDER BY score DESC LIMIT 10");
$highscores = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
include 'parts/head.php';
?>

<body class="bg-dark text-white">
    <div class="container">
        <header class="my-4">
            <h1 class="text-center">Einfaches 2D-Spiel - Webdesign Karateke</h1>
            <?php include 'parts/nav.htm'; ?>
            <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1070;"></div>
        </header>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <!-- Button zum Öffnen des Modals -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#settingsModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Einstellungen öffnen">
                        <i class="fas fa-cog"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="settingsModalLabel">Einstellungen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Hier deine Einstellungsoptionen einfügen -->
                                    <form>
                                        <div class="mb-3">
                                            <label for="setting-theme" class="form-label">Themes</label>
                                            <select class="form-select" id="setting-theme" data-setting="theme">
                                                <option value="light">Light</option>
                                                <option value="dark">Dark</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="setting-notification" class="form-label">Notifications</label>
                                            <input type="checkbox" class="form-check-input" id="setting-notifications" data-setting="notifications">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                                    <!-- Button im Modal für das Speichern der Einstellungen -->
                                    <!-- <button type="button" class="btn btn-primary" data-bs-placement="top" data-bs-toggle="tooltip" data-bs-placement="top" title="Änderungen speichern"><i class="fas fa-save"></i></button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div id="level" class="hud-item"><i class="fas fa-medal"></i> <span id="level-value">1</span></div>
                        <div id="score" class="hud-item"><i class="fas fa-trophy"></i> <span id="score-value"><?php echo $_SESSION['score']; ?></span></div>
                        <div id="energy" class="hud-item"><i class="fas fa-bolt"></i> <span id="energy-value">100</span></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button id="cheat-energy" class="btn btn-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Energie auffüllen"><i class="fas fa-bolt"></i></button>
                    <button onclick="toggleDarkMode()" class="btn btn-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modus wechseln"><i class="fas fa-cloud-sun"></i>
                    </button>
                    <div id="inventory" class="hud-item">
                        Inventar: <span id="inventory-items">Keine Gegenstände</span>
                    </div>
                    <button id="use-food" class="btn btn-warning mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Nahrung verwenden"><i class="fas fa-apple-alt"></i></button>
                    <button id="use-water" class="btn btn-primary mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Wasser verwenden"><i class="fas fa-tint"></i></button>
                    <div id="highscores" class="mb-3">
                        <h3>Highscores:</h3>
                        <ul class="list-group">
                            <?php foreach ($highscores as $hs) : ?>
                                <li class="list-group-item"><?php echo $hs['score']; ?> - <?php echo $hs['created_at']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <form method="post" action="save_score.php" class="mb-3">
                            <input type="hidden" name="score" id="score-input" value="">
                            <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Score speichern"><i class="fas fa-save"></i></button>
                        </form>
                    </div>
                    <div id="quests" class="mt-3">
                        <h4>Quests</h4>
                        <ul class="list-group">
                            <li class="list-group-item">Sammle 5 Nahrung</li>
                            <li class="list-group-item">Erreiche Level 3</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="game-container" class="border border-dark">
                        <canvas id="game-canvas"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="text-center stroked-text mt-4">
        &copy; 2023 Webdesign Karateke - Alle Rechte vorbehalten.
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="game.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update the hidden score input before submitting the form
            document.querySelector('form').addEventListener('submit', function() {
                document.getElementById('score-input').value = game.player.score;
            });
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });

        function toggleDarkMode() {
            document.body.classList.toggle('bg-dark');
            document.querySelector('.modal-content').classList.toggle('bg-dark');
            document.body.classList.toggle('text-white');
            document.querySelector('.modal-content').classList.toggle('text-white');
        }
    </script>

</body>

</html>