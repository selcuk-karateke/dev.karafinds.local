<?php
session_start();
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['score'])) {
        $_SESSION['score'] = $_POST['score'];
    }
}

$mysqli = new mysqli('localhost', 'root', '', 'game_db');

if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM highscores ORDER BY score DESC LIMIT 10");
$highscores = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
$title = "Einfaches 2D-Spiel - Webdesign Karateke";
$meta_description = "Game - Willkommen bei Webdesign Karateke";
$additional_head_content = '<link rel="stylesheet" href="game.css">';
include 'parts/head.php';
?>

<body class="bg-dark text-white">
    <div class="container mt-3">
        <header class="my-4">
            <h1 class="text-center">Einfaches 2D-Spiel - Webdesign Karateke</h1>
            <?php include 'parts/nav.htm'; ?>
        </header>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div id="energy" class="hud-item alert alert-info">Energie: <span id="energy-value">100%</span></div>
                        <div id="level" class="hud-item alert alert-success">Level: <span id="level-value">1</span></div>
                        <div id="score" class="hud-item alert alert-warning">Score: <span id="score-value"><?php echo $_SESSION['score']; ?></span></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button id="cheat-energy" class="btn btn-warning mb-2">Energie auffüllen</button>
                    <button onclick="toggleDarkMode()" class="btn btn-warning mb-2">Modus wechseln</button>
                    <div id="inventory" class="hud-item alert alert-dark">
                        Inventar: <span id="inventory-items">Keine Gegenstände</span>
                    </div>
                    <button id="use-food" class="btn btn-warning mb-2">Nahrung verwenden</button>
                    <button id="use-water" class="btn btn-primary mb-2">Wasser verwenden</button>
                    <form method="post" action="save_score.php" class="mb-3">
                        <input type="hidden" name="score" id="score-input" value="">
                        <button type="submit" class="btn btn-primary">Score speichern</button>
                    </form>
                    <div id="highscores" class="mb-3">
                        <h3>Highscores:</h3>
                        <ul class="list-group">
                            <?php foreach ($highscores as $hs) : ?>
                                <li class="list-group-item"><?php echo $hs['score']; ?> - <?php echo $hs['created_at']; ?></li>
                            <?php endforeach; ?>
                        </ul>
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
    <footer class="text-center mt-4">
        &copy; 2023 Webdesign Karateke - Alle Rechte vorbehalten.
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="game.js"></script>
    <script>
        // Update the hidden score input before submitting the form
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('score-input').value = game.player.score;
        });

        function toggleDarkMode() {
            document.body.classList.toggle('bg-dark');
            document.body.classList.toggle('text-white');
        }
    </script>
</body>

</html>