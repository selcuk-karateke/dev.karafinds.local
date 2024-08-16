<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
$scope = 'instagram_basic,instagram_manage_comments';
$oauthUrl = 'https://api.instagram.com/oauth/';
// URL für die Benutzerauthentifizierung bauen
$authUrl = $oauthUrl . "authorize?client_id=" . CLIENT_ID . "&redirect_uri=" . REDIRECT_URI . "&scope={$scope}&response_type=code";
// Link zur Authentifizierungsseite
$linkToAuth = "<a href='{$authUrl}'>Login with Instagram to manage comments</a>";


if (isset($_GET['code'])) {
    $code = $_GET['code'];  // Code erhalten nach Nutzer-Zustimmung
    $accessToken = getAccessToken($code, CLIENT_ID, CLIENT_SECRET, REDIRECT_URI);
    if ($accessToken) {
        $_SESSION['access_token'] = $accessToken;
        $tokenStatus = checkAccessTokenValidity($accessToken, CLIENT_ID, CLIENT_SECRET);
        if ($tokenStatus['is_valid']) {
            $message = "Access Token ist gültig.";
            // Hier könnten Sie die Funktion fetchComments aufrufen
        } else {
            $message = "Access Token ist ungültig.";
        }
    } else {
        $message = "Fehler beim Abrufen des Access Tokens.";
    }
} else {
    $message = "Kein Autorisierungscode verfügbar.";
}

$title = "Dev Instagram - Webdesign Karateke";
$meta_description = "Instagram - Willkommen bei Webdesign Karateke";
// $additional_head_content_1 = '<link rel="stylesheet" href="instagram.css">';
include $_SERVER['DOCUMENT_ROOT'] . '/parts/head.php';
?>

<body>
    <div class="container">
        <header class="my-4">
            <h1 class="text-center">Instagram - Webdesign Karateke</h1>
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/parts/nav.php'; ?>
        </header>
        <section>
            <div class="row">
                <div class="col-md-12">
                    <h2>Message</h2>
                    <?php echo $linkToAuth ?>
                    <p><?php echo $message ?></p>
                </div>
            </div>
        </section>
    </div>
    <footer class="text-center mt-4">
        &copy; 2023 Webdesign Karateke - Alle Rechte vorbehalten.
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>