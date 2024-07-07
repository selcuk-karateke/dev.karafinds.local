<?php
// phpinfo();
require_once 'bootstrap.php';
$title = "Homepage";
$meta_description = "Willkommen bei Webdesign Karateke";
$nofollow = false ? 'rel="nofollow"' : '';
// $additional_head_content = '<link rel="stylesheet" href="index.css">';
$additional_head_content_1 = '';
$additional_head_content_2 = '<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>';
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
    </body>

    </html>
<?php
} else {
    include('auth/login.php'); // Anmeldeseite
}
?>
</body>

</html>